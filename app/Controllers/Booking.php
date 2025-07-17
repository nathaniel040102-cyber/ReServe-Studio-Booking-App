<?php namespace App\Controllers;

use App\Models\StudioModel;
use App\Models\BookingModel;
use App\Models\ReviewModel;
use App\Models\PromoModel; // Pastikan ini ada

use CodeIgniter\Controller;
use chillerlan\QRCode\{QRCode, QROptions}; // Untuk QR Code

class Booking extends BaseController
{
    protected $studioModel;
    protected $bookingModel;
    protected $reviewModel;
    protected $promoModel; // Deklarasi properti untuk PromoModel

    public function __construct()
    {
        $this->studioModel = new StudioModel();
        $this->bookingModel = new BookingModel();
        $this->reviewModel = new ReviewModel();
        $this->promoModel = new PromoModel(); // Inisialisasi PromoModel

        // Memuat helper form dan url
        helper(['form', 'url', 'text']);

        // Filter autentikasi untuk semua aksi booking, kecuali jika nanti ada public booking
        if (! session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'))->with('error', 'Anda harus login untuk melakukan pemesanan.');
        }
    }

    public function create($studioId = null)
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'))->with('error', 'Anda harus login untuk melakukan pemesanan.');
        }

        $data['studio'] = $this->studioModel->find($studioId);

        if (empty($data['studio'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Studio tidak ditemukan untuk pemesanan.');
        }

        echo view('layout/header');
        echo view('booking/create', $data);
        echo view('layout/footer');
    }

    public function store()
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'))->with('error', 'Anda harus login untuk melakukan pemesanan.');
        }

        $rules = [
            'studio_id'       => 'required|numeric',
            'tanggal_booking' => 'required|valid_date[Y-m-d]',
            'jam_mulai'       => 'required|valid_date[H:i]',
            'durasi_jam'      => 'required|numeric|greater_than[0]|less_than_equal_to[24]', // Max 24 jam
            'kode_promo'      => 'permit_empty|alpha_numeric_punct|max_length[50]', // Aturan untuk kode promo
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        // --- Pengecekan manual untuk tanggal booking (harus hari ini atau setelahnya) ---
        $tanggalBooking = $this->request->getPost('tanggal_booking');
        if (strtotime($tanggalBooking) < strtotime(date('Y-m-d'))) {
            session()->setFlashdata('error', 'Tanggal booking tidak boleh di masa lalu.');
            return redirect()->back()->withInput();
        }
        // --- Akhir Pengecekan manual ---
        $studioId = $this->request->getPost('studio_id');
        $studio = $this->studioModel->find($studioId);

        if (empty($studio)) {
            session()->setFlashdata('error', 'Studio tidak ditemukan.');
            return redirect()->back()->withInput();
        }

        $tanggalBooking = $this->request->getPost('tanggal_booking');
        $jamMulai = $this->request->getPost('jam_mulai');
        $durasiJam = (float) $this->request->getPost('durasi_jam');

        // Hitung jam selesai
        $timestampMulai = strtotime($tanggalBooking . ' ' . $jamMulai);
        $timestampSelesai = $timestampMulai + ($durasiJam * 3600); // Durasi dalam detik
        $jamSelesai = date('H:i', $timestampSelesai);

        // --- Logika Cek Ketersediaan ---
        $existingBookings = $this->bookingModel
                                    ->where('studio_id', $studioId)
                                    ->where('tanggal_booking', $tanggalBooking)
                                    ->groupStart() // Mulai grup kondisi OR
                                        ->where("'$jamMulai' < jam_selesai") // Booking baru dimulai sebelum booking lama selesai
                                        ->where("'$jamSelesai' > jam_mulai")   // Booking baru selesai setelah booking lama dimulai
                                    ->groupEnd()
                                    ->whereIn('status_pembayaran', ['pending', 'lunas']) // Hanya cek booking yang aktif
                                    ->findAll();

        if (!empty($existingBookings)) {
            session()->setFlashdata('error', 'Studio sudah dipesan pada jam tersebut. Silakan pilih waktu lain.');
            return redirect()->back()->withInput();
        }
        // --- Akhir Logika Cek Ketersediaan ---

        $totalHarga = $studio['harga_per_jam'] * $durasiJam;
        $promoId = null;
        $kodePromo = $this->request->getPost('kode_promo');

        // --- Logika Penerapan Promo ---
        if (!empty($kodePromo)) {
            $promo = $this->promoModel
                            ->where('kode_promo', $kodePromo)
                            ->where('status_aktif', 'aktif')
                            ->where('tanggal_mulai <=', date('Y-m-d H:i:s'))
                            ->where('tanggal_berakhir >=', date('Y-m-d H:i:s'))
                            ->first();

            if ($promo) {
                $promoId = $promo['id'];
                if ($promo['tipe_diskon'] == 'persen') {
                    $diskonNominal = $totalHarga * ($promo['nilai_diskon'] / 100);
                    $totalHarga -= $diskonNominal;
                } else { // nominal
                    $totalHarga -= $promo['nilai_diskon'];
                }
                // Pastikan harga tidak negatif
                if ($totalHarga < 0) {
                    $totalHarga = 0;
                }
                session()->setFlashdata('promo_applied', 'Kode promo "' . esc($kodePromo) . '" berhasil diterapkan!');
            } else {
                session()->setFlashdata('error', 'Kode promo tidak valid atau sudah tidak berlaku.');
                return redirect()->back()->withInput();
            }
        }
        // --- Akhir Logika Penerapan Promo ---

        $dataBooking = [
            'user_id'           => session()->get('user_id'),
            'studio_id'         => $studioId,
            'promo_id'          => $promoId, // Simpan ID promo jika ada
            'tanggal_booking'   => $tanggalBooking,
            'jam_mulai'         => $jamMulai,
            'jam_selesai'       => $jamSelesai,
            'durasi_jam'        => $durasiJam,
            'total_harga'       => $totalHarga, // Harga setelah diskon
            'status_pembayaran' => 'pending',
        ];

        $bookingId = $this->bookingModel->insert($dataBooking);

        if ($bookingId) {
            // --- Generate QR Code ---
            $qrContent = json_encode([
                'booking_id' => $bookingId,
                'user_id' => session()->get('user_id'),
                'studio_id' => $studioId,
                'tanggal' => $tanggalBooking,
                'jam_mulai' => $jamMulai,
                'jam_selesai' => $jamSelesai,
                'total_harga' => $totalHarga,
                'promo_id' => $promoId, // Tambahkan promo_id ke QR content
            ]);

            $options = new QROptions([
                'outputType' => QRCode::OUTPUT_IMAGE_PNG,
                'eccLevel'   => QRCode::ECC_L,
                'scale'      => 5,
            ]);
            $qrcode = new QRCode($options);

            $qrDir = FCPATH . 'qrcodes';
            if (!is_dir($qrDir)) {
                mkdir($qrDir, 0777, true);
            }

            $qrFileName = 'booking_' . $bookingId . '.png';
            $qrFilePath = $qrDir . DIRECTORY_SEPARATOR . $qrFileName;
            $qrcode->render($qrContent, $qrFilePath);

            $this->bookingModel->update($bookingId, ['qr_code_path' => $qrFileName]);
            // --- Akhir Generate QR Code ---

            // --- Kirim Email Konfirmasi (dengan gambar QR di-embed via CID) ---
            $email = \Config\Services::email();
            $email->setTo(session()->get('user_email'));
            $email->setFrom('ReServe Booking', 'noreply@reserve.com'); // <--- PASTIKAN INI ADALAH ALAMAT DARI EMAIL.PHP ANDA (misal rsrvstdios@gmail.com)
            $email->setSubject('Konfirmasi Pemesanan Studio ' . $studio['nama_studio']);
            $email->setMailType('html');

            $email->attach($qrFilePath, 'inline', $qrFileName, 'image/png');
            $cid = $email->setAttachmentCID($qrFileName);

            $message = 'Halo ' . session()->get('user_nama') . ',<br><br>'
                    . 'Pemesanan studio ' . $studio['nama_studio'] . ' Anda telah berhasil.<br>'
                    . 'Tanggal: ' . $tanggalBooking . '<br>'
                    . 'Waktu: ' . $jamMulai . ' - ' . $jamSelesai . '<br>'
                    . 'Total Harga: Rp' . number_format($totalHarga, 0, ',', '.') . '<br>';
            if (!empty($kodePromo) && $promo) { // Tambahkan info promo di email
                $message .= 'Promo Digunakan: ' . esc($kodePromo) . ' (Diskon: ';
                if ($promo['tipe_diskon'] == 'persen') {
                    $message .= esc($promo['nilai_diskon']) . '%)';
                } else {
                    $message .= 'Rp' . number_format(esc($promo['nilai_diskon']), 0, ',', '.') . ')';
                }
                $message .= '<br>';
            }
            $message .= '<br>Silakan gunakan QR Code berikut saat check-in:<br>'
                    . '<img src="cid:' . $cid . '" alt="QR Code Booking" style="max-width: 200px; height: auto; border: 1px solid #ddd; padding: 5px;"><br><br>' // Gunakan cid: di sini
                    . 'Terima kasih telah menggunakan ReServe!';

            $email->setMessage($message);

            if ($email->send()) {
                session()->setFlashdata('success', 'Pemesanan studio berhasil! Detail booking Anda sudah dikirim.');
            } else {
                // Log error email lebih detail jika pengiriman gagal
                log_message('error', $email->printDebugger(['headers', 'subject', 'body']));
                session()->setFlashdata('error', 'Pemesanan berhasil, tetapi gagal mengirim email konfirmasi.');
            }
            // --- Akhir Kirim Email ---

            return redirect()->to(base_url('booking/confirm/' . $bookingId));
        } else {
            session()->setFlashdata('error', 'Gagal membuat pemesanan. Silakan coba lagi.');
            return redirect()->back()->withInput();
        }
    }

    public function confirm($bookingId = null)
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'))->with('error', 'Anda harus login untuk melihat konfirmasi.');
        }

        $booking = $this->bookingModel->find($bookingId);

        if (empty($booking) || $booking['user_id'] != session()->get('user_id')) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Booking tidak ditemukan atau Anda tidak memiliki akses.');
        }

        $data['booking'] = $booking;
        $data['studio'] = $this->studioModel->find($booking['studio_id']);

        // Ambil detail promo jika ada
        $data['promo'] = null;
        if (!empty($booking['promo_id'])) {
            $data['promo'] = $this->promoModel->find($booking['promo_id']);
        }

        echo view('layout/header');
        echo view('booking/confirm', $data);
        echo view('layout/footer');
    }

    // Metode ini bisa digunakan untuk menampilkan QR Code secara terpisah jika diperlukan
    public function showQr($qrFileName = null)
    {
        if (empty($qrFileName)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('QR Code tidak ditemukan.');
        }

        $filePath = FCPATH . 'qrcodes' . DIRECTORY_SEPARATOR . $qrFileName;

        if (!file_exists($filePath)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('QR Code tidak ditemukan.');
        }

        $file = new \CodeIgniter\Files\File($filePath);
        $response = $this->response
                         ->setHeader('Content-Type', $file->getMimeType())
                         ->setBody(file_get_contents($filePath));

        return $response;
    }

    // --- Metode untuk Riwayat Pemesanan Pengguna ---
    public function myBookings()
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'))->with('error', 'Anda harus login untuk melihat riwayat pemesanan.');
        }

        $userId = session()->get('user_id');
        $data['bookings'] = $this->bookingModel->where('user_id', $userId)->orderBy('tanggal_booking', 'DESC')->orderBy('jam_mulai', 'DESC')->findAll();

        // Untuk menampilkan nama studio dan status review
        foreach ($data['bookings'] as &$booking) {
            $studio = $this->studioModel->find($booking['studio_id']);
            $booking['studio_nama'] = $studio['nama_studio'] ?? 'Studio Tidak Ditemukan';

            // Cek apakah booking ini sudah diulas
            $review = $this->reviewModel->where('booking_id', $booking['id'])->first();
            $booking['has_reviewed'] = !empty($review);
            $booking['review_status'] = $review['status_moderasi'] ?? null;
        }

        echo view('layout/header');
        echo view('booking/my_bookings', $data); // Tampilan riwayat pemesanan
        echo view('layout/footer');
    }
}
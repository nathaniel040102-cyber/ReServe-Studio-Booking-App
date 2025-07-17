<?php namespace App\Controllers;

use App\Models\StudioModel;
use App\Models\PromoModel;
use App\Models\UserModel;
use App\Models\BookingModel;
use App\Models\ReviewModel;

use CodeIgniter\Controller;

class Admin extends BaseController
{
    protected $studioModel;
    protected $promoModel;
    protected $userModel;
    protected $bookingModel;
    protected $reviewModel;

    public function __construct()
    {
        $this->studioModel = new StudioModel();
        $this->promoModel = new PromoModel();
        $this->userModel = new UserModel();
        $this->bookingModel = new BookingModel();
        $this->reviewModel = new ReviewModel();

        // Memuat helper form dan text untuk word_limiter di review list
        helper(['form', 'text']);

        // Anda mungkin ingin menambahkan filter otorisasi di sini nanti
        // Untuk memastikan hanya admin yang bisa mengakses controller ini.
        // Contoh:
        // if (!session()->get('isLoggedIn') || session()->get('user_role') != 1) {
        //     session()->setFlashdata('error', 'Akses ditolak. Anda tidak memiliki izin Admin.');
        //     return redirect()->to(base_url('login'));
        // }
    }

    public function index()
    {
        // Dashboard admin sederhana
        $data['total_studios'] = $this->studioModel->countAllResults();
        $data['total_users'] = $this->userModel->countAllResults();
        $data['total_promos'] = $this->promoModel->countAllResults();
        $data['total_bookings'] = $this->bookingModel->countAllResults();
        $data['total_reviews'] = $this->reviewModel->countAllResults();

        echo view('layout/header');
        echo view('admin/dashboard', $data);
        echo view('layout/footer');
    }

    // --- Metode untuk Pengelolaan Studio ---

    public function studios()
    {
        // Menampilkan daftar studio untuk admin
        $data['studios'] = $this->studioModel->findAll();

        echo view('layout/header');
        echo view('admin/studio/list', $data);
        echo view('layout/footer');
    }

    public function createStudio()
    {
        // Menampilkan form tambah studio
        echo view('layout/header');
        echo view('admin/studio/create');
        echo view('layout/footer');
    }

    public function storeStudio()
    {
        $rules = [
            'nama_studio'   => 'required|max_length[255]',
            'alamat'        => 'required|max_length[255]',
            'harga_per_jam' => 'required|numeric|greater_than[0]',
            'deskripsi'     => 'permit_empty',
            'foto'          => 'uploaded[foto]|max_size[foto,1024]|is_image[foto]',
            'status_aktif'  => 'required|in_list[aktif,nonaktif]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $fileFoto = $this->request->getFile('foto');
        $fileName = $fileFoto->getRandomName();
        $fileFoto->move('uploads/studio_images', $fileName);

        $data = [
            'nama_studio'   => $this->request->getPost('nama_studio'),
            'deskripsi'     => $this->request->getPost('deskripsi'),
            'alamat'        => $this->request->getPost('alamat'),
            'harga_per_jam' => $this->request->getPost('harga_per_jam'),
            'foto'          => $fileName,
            'status_aktif'  => $this->request->getPost('status_aktif'),
        ];

        if ($this->studioModel->save($data)) {
            session()->setFlashdata('success', 'Studio berhasil ditambahkan!');
        } else {
            log_message('error', 'Failed to save studio to database: ' . json_encode($this->studioModel->errors()));
            session()->setFlashdata('error', 'Gagal menambahkan studio. Silakan coba lagi.');
        }
        return redirect()->to(base_url('admin/studios'));
    }

    public function editStudio($id = null)
    {
        // Menampilkan form edit studio
        $data['studio'] = $this->studioModel->find($id);

        if (empty($data['studio'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Studio tidak ditemukan: ' . $id);
        }

        echo view('layout/header');
        echo view('admin/studio/edit', $data);
        echo view('layout/footer');
    }

    public function updateStudio($id = null)
    {
        $rules = [
            'nama_studio'   => 'required|max_length[255]',
            'alamat'        => 'required|max_length[255]',
            'harga_per_jam' => 'required|numeric|greater_than[0]',
            'deskripsi'     => 'permit_empty',
            'status_aktif'  => 'required|in_list[aktif,nonaktif]',
        ];

        if ($this->request->getFile('foto')->isValid() && ! $this->request->getFile('foto')->hasMoved()) {
            $rules['foto'] = 'uploaded[foto]|max_size[foto,1024]|is_image[foto]';
        }

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'id'            => $id,
            'nama_studio'   => $this->request->getPost('nama_studio'),
            'deskripsi'     => $this->request->getPost('deskripsi'),
            'alamat'        => $this->request->getPost('alamat'),
            'harga_per_jam' => $this->request->getPost('harga_per_jam'),
            'status_aktif'  => $this->request->getPost('status_aktif'),
        ];

        $fileFoto = $this->request->getFile('foto');
        if ($fileFoto->isValid() && ! $fileFoto->hasMoved()) {
            $oldFoto = $this->studioModel->find($id)['foto'];
            if ($oldFoto && file_exists('uploads/studio_images/' . $oldFoto)) {
                unlink('uploads/studio_images/' . $oldFoto);
            }
            $fileName = $fileFoto->getRandomName();
            $fileFoto->move('uploads/studio_images', $fileName);
            $data['foto'] = $fileName;
        }

        if ($this->studioModel->save($data)) {
            session()->setFlashdata('success', 'Studio berhasil diperbarui!');
        } else {
            log_message('error', 'Failed to update studio in database: ' . json_encode($this->studioModel->errors()));
            session()->setFlashdata('error', 'Gagal memperbarui studio. Silakan coba lagi.');
        }
        return redirect()->to(base_url('admin/studios'));
    }

    public function deleteStudio($id = null)
    {
        $studio = $this->studioModel->find($id);

        if (empty($studio)) {
            session()->setFlashdata('error', 'Studio tidak ditemukan!');
            return redirect()->to(base_url('admin/studios'));
        }

        if ($studio['foto'] && file_exists('uploads/studio_images/' . $studio['foto'])) {
            unlink('uploads/studio_images/' . $studio['foto']);
        }

        if ($this->studioModel->delete($id)) {
            session()->setFlashdata('success', 'Studio berhasil dihapus!');
        } else {
            log_message('error', 'Failed to delete studio from database: ' . json_encode($this->studioModel->errors()));
            session()->setFlashdata('error', 'Gagal menghapus studio. Silakan coba lagi.');
        }
        return redirect()->to(base_url('admin/studios'));
    }


    // --- Metode untuk Pengelolaan Promo ---

    public function promos()
    {
        $data['promos'] = $this->promoModel->findAll();

        echo view('layout/header');
        echo view('admin/promo/list', $data);
        echo view('layout/footer');
    }

    public function createPromo()
    {
        echo view('layout/header');
        echo view('admin/promo/create');
        echo view('layout/footer');
    }

    public function storePromo()
    {
        $rules = [
            'kode_promo'       => 'required|alpha_numeric_punct|min_length[3]|max_length[50]|is_unique[promos.kode_promo]',
            'deskripsi'        => 'permit_empty',
            'tipe_diskon'      => 'required|in_list[persen,nominal]',
            'nilai_diskon'     => 'required|numeric|greater_than_equal_to[0]',
            'tanggal_mulai'    => 'required|valid_date[Y-m-d\TH:i]',
            'tanggal_berakhir' => 'required|valid_date[Y-m-d\TH:i]', // Hapus after_or_equal dari rules
            'status_aktif'     => 'required|in_list[aktif,nonaktif]',
        ];

        if (! $this->validate($rules)) {
            log_message('error', 'Promo validation failed: ' . json_encode($this->validator->getErrors()));
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // --- Pengecekan manual untuk tanggal_berakhir setelah tanggal_mulai ---
        $tanggalMulaiInput = $this->request->getPost('tanggal_mulai');
        $tanggalBerakhirInput = $this->request->getPost('tanggal_berakhir');

        // Konversi ke timestamp untuk perbandingan yang akurat
        $timestampMulai = strtotime($tanggalMulaiInput);
        $timestampBerakhir = strtotime($tanggalBerakhirInput);

        if ($timestampBerakhir < $timestampMulai) {
            session()->setFlashdata('errors', ['tanggal_berakhir' => 'Tanggal Berakhir harus setelah atau sama dengan Tanggal Mulai.']);
            return redirect()->back()->withInput();
        }
        // --- Akhir pengecekan manual ---


        $data = [
            'kode_promo'       => $this->request->getPost('kode_promo'),
            'deskripsi'        => $this->request->getPost('deskripsi'),
            'tipe_diskon'      => $this->request->getPost('tipe_diskon'),
            'nilai_diskon'     => $this->request->getPost('nilai_diskon'),
            // Format ulang tanggal dari YYYY-MM-DDTHH:MM menjadi YYYY-MM-DD HH:MM:SS untuk database
            'tanggal_mulai'    => str_replace('T', ' ', $this->request->getPost('tanggal_mulai')) . ':00',
            'tanggal_berakhir' => str_replace('T', ' ', $this->request->getPost('tanggal_berakhir')) . ':00',
            'status_aktif'     => $this->request->getPost('status_aktif'),
        ];

        if ($this->promoModel->save($data)) {
            session()->setFlashdata('success', 'Promo berhasil ditambahkan!');
        } else {
            log_message('error', 'Failed to save promo to database: ' . json_encode($this->promoModel->errors()));
            session()->setFlashdata('error', 'Gagal menambahkan promo. Silakan coba lagi.');
        }
        return redirect()->to(base_url('admin/promos'));
    }

    public function editPromo($id = null)
    {
        $data['promo'] = $this->promoModel->find($id);

        if (empty($data['promo'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Promo tidak ditemukan: ' . $id);
        }

        // Format tanggal untuk input datetime-local
        $data['promo']['tanggal_mulai_html'] = date('Y-m-d\TH:i', strtotime($data['promo']['tanggal_mulai']));
        $data['promo']['tanggal_berakhir_html'] = date('Y-m-d\TH:i', strtotime($data['promo']['tanggal_berakhir']));


        echo view('layout/header');
        echo view('admin/promo/edit', $data);
        echo view('layout/footer');
    }

    public function updatePromo($id = null)
    {
        $rules = [
            'kode_promo'       => 'required|alpha_numeric_punct|min_length[3]|max_length[50]|is_unique[promos.kode_promo,id,{id}]',
            'deskripsi'        => 'permit_empty',
            'tipe_diskon'      => 'required|in_list[persen,nominal]',
            'nilai_diskon'     => 'required|numeric|greater_than_equal_to[0]',
            'tanggal_mulai'    => 'required|valid_date[Y-m-d\TH:i]',
            'tanggal_berakhir' => 'required|valid_date[Y-m-d\TH:i]', // Hapus after_or_equal dari rules
            'status_aktif'     => 'required|in_list[aktif,nonaktif]',
        ];

        if (! $this->validate($rules)) {
            log_message('error', 'Promo update validation failed: ' . json_encode($this->validator->getErrors()));
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // --- Pengecekan manual untuk tanggal_berakhir setelah tanggal_mulai ---
        $tanggalMulaiInput = $this->request->getPost('tanggal_mulai');
        $tanggalBerakhirInput = $this->request->getPost('tanggal_berakhir');

        $timestampMulai = strtotime($tanggalMulaiInput);
        $timestampBerakhir = strtotime($tanggalBerakhirInput);

        if ($timestampBerakhir < $timestampMulai) {
            session()->setFlashdata('errors', ['tanggal_berakhir' => 'Tanggal Berakhir harus setelah atau sama dengan Tanggal Mulai.']);
            return redirect()->back()->withInput();
        }
        // --- Akhir pengecekan manual ---

        $data = [
            'id'               => $id,
            'kode_promo'       => $this->request->getPost('kode_promo'),
            'deskripsi'        => $this->request->getPost('deskripsi'),
            'tipe_diskon'      => $this->request->getPost('tipe_diskon'),
            'nilai_diskon'     => $this->request->getPost('nilai_diskon'),
            // Format ulang tanggal dari YYYY-MM-DDTHH:MM menjadi YYYY-MM-DD HH:MM:SS untuk database
            'tanggal_mulai'    => str_replace('T', ' ', $this->request->getPost('tanggal_mulai')) . ':00',
            'tanggal_berakhir' => str_replace('T', ' ', $this->request->getPost('tanggal_berakhir')) . ':00',
            'status_aktif'     => $this->request->getPost('status_aktif'),
        ];

        if ($this->promoModel->save($data)) {
            session()->setFlashdata('success', 'Promo berhasil diperbarui!');
        } else {
            log_message('error', 'Failed to update promo in database: ' . json_encode($this->promoModel->errors()));
            session()->setFlashdata('error', 'Gagal memperbarui promo. Silakan coba lagi.');
        }
        return redirect()->to(base_url('admin/promos'));
    }

    public function deletePromo($id = null)
    {
        $promo = $this->promoModel->find($id);

        if (empty($promo)) {
            session()->setFlashdata('error', 'Promo tidak ditemukan!');
            return redirect()->to(base_url('admin/promos'));
        }

        if ($this->promoModel->delete($id)) {
            session()->setFlashdata('success', 'Promo berhasil dihapus!');
        } else {
            log_message('error', 'Failed to delete promo from database: ' . json_encode($this->promoModel->errors()));
            session()->setFlashdata('error', 'Gagal menghapus promo. Silakan coba lagi.');
        }
        return redirect()->to(base_url('admin/promos'));
    }

    // --- Metode untuk Pengelolaan Pengguna ---

    public function users()
    {
        $data['users'] = $this->userModel->findAll();

        echo view('layout/header');
        echo view('admin/user/list', $data);
        echo view('layout/footer');
    }

    public function editUser($id = null)
    {
        $data['user'] = $this->userModel->find($id);

        if (empty($data['user'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Pengguna tidak ditemukan: ' . $id);
        }

        echo view('layout/header');
        echo view('admin/user/edit', $data);
        echo view('layout/footer');
    }

    public function updateUser($id = null)
    {
        $rules = [
            'nama'  => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|is_unique[users.email,id,' . $id . ']',
            'role_id' => 'required|in_list[1,2]',
        ];

        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $rules['password'] = 'min_length[6]';
            $rules['confirm_password'] = 'required_with[password]|matches[password]';
        }

        if (! $this->validate($rules)) {
            log_message('error', 'User update validation failed: ' . json_encode($this->validator->getErrors()));
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'id'       => $id,
            'nama'     => $this->request->getPost('nama'),
            'email'    => $this->request->getPost('email'),
            'role_id'  => $this->request->getPost('role_id'),
        ];

        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if ($this->userModel->save($data)) {
            session()->setFlashdata('success', 'Pengguna berhasil diperbarui!');
        } else {
            log_message('error', 'Failed to update user in database: ' . json_encode($this->userModel->errors()));
            session()->setFlashdata('error', 'Gagal memperbarui pengguna. Silakan coba lagi.');
        }
        return redirect()->to(base_url('admin/users'));
    }

    public function deleteUser($id = null)
    {
        $user = $this->userModel->find($id);

        if (empty($user)) {
            session()->setFlashdata('error', 'Pengguna tidak ditemukan!');
            return redirect()->to(base_url('admin/users'));
        }

        // Mencegah admin menghapus dirinya sendiri jika sudah menerapkan filter login
        // if ($user['id'] == session()->get('user_id')) {
        //     session()->setFlashdata('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        //     return redirect()->to(base_url('admin/users'));
        // }

        if ($this->userModel->delete($id)) {
            session()->setFlashdata('success', 'Pengguna berhasil dihapus!');
        } else {
            log_message('error', 'Failed to delete user from database: ' . json_encode($this->userModel->errors()));
            session()->setFlashdata('error', 'Gagal menghapus pengguna. Silakan coba lagi.');
        }
        return redirect()->to(base_url('admin/users'));
    }

    // --- Metode untuk Pengelolaan Booking ---

    public function bookings()
    {
        $data['bookings'] = $this->bookingModel->findAll();

        foreach ($data['bookings'] as &$booking) {
            $user = $this->userModel->find($booking['user_id']);
            $studio = $this->studioModel->find($booking['studio_id']);
            $booking['user_nama'] = $user['nama'] ?? 'User Tidak Ditemukan';
            $booking['studio_nama'] = $studio['nama_studio'] ?? 'Studio Tidak Ditemukan';
        }

        echo view('layout/header');
        echo view('admin/booking/list', $data);
        echo view('layout/footer');
    }

    public function viewBooking($id = null)
    {
        $data['booking'] = $this->bookingModel->find($id);

        if (empty($data['booking'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Booking tidak ditemukan: ' . $id);
        }

        $data['user'] = $this->userModel->find($data['booking']['user_id']);
        $data['studio'] = $this->studioModel->find($data['booking']['studio_id']);

        if (!empty($data['booking']['promo_id'])) {
            $data['promo'] = $this->promoModel->find($data['booking']['promo_id']);
        } else {
            $data['promo'] = null;
        }

        echo view('layout/header');
        echo view('admin/booking/detail', $data);
        echo view('layout/footer');
    }

    public function updateBookingStatus($id = null)
    {
        $booking = $this->bookingModel->find($id);

        if (empty($booking)) {
            session()->setFlashdata('error', 'Booking tidak ditemukan!');
            return redirect()->to(base_url('admin/bookings'));
        }

        $newStatus = $this->request->getPost('status_pembayaran');

        $rules = [
            'status_pembayaran' => 'required|in_list[pending,lunas,batal]',
        ];

        if (! $this->validate($rules)) {
            session()->setFlashdata('errors', $this->validator->getErrors());
            return redirect()->back();
        }

        if ($this->bookingModel->update($id, ['status_pembayaran' => $newStatus])) {
            session()->setFlashdata('success', 'Status booking berhasil diperbarui!');
        } else {
            log_message('error', 'Failed to update booking status in database: ' . json_encode($this->bookingModel->errors()));
            session()->setFlashdata('error', 'Gagal memperbarui status booking. Silakan coba lagi.');
        }
        return redirect()->to(base_url('admin/bookings/view/' . $id));
    }

    public function validateQrCode()
    {
        if ($this->request->getMethod() === 'post') {
            $qrContent = $this->request->getPost('qr_content');
            $bookingId = null;

            $decodedQr = json_decode($qrContent, true);
            if (json_last_error() === JSON_ERROR_NONE && isset($decodedQr['booking_id'])) {
                $bookingId = $decodedQr['booking_id'];
            } else {
                $bookingId = (int) $qrContent;
            }

            $booking = $this->bookingModel->find($bookingId);

            if (empty($booking)) {
                session()->setFlashdata('error', 'QR Code tidak valid atau booking tidak ditemukan.');
                return redirect()->back();
            }

            if ($booking['status_pembayaran'] == 'pending') {
                if ($this->bookingModel->update($bookingId, ['status_pembayaran' => 'lunas'])) {
                    session()->setFlashdata('success', 'Booking berhasil divalidasi dan status diubah menjadi Lunas!');
                } else {
                    log_message('error', 'Failed to update booking status during QR validation: ' . json_encode($this->bookingModel->errors()));
                    session()->setFlashdata('error', 'Gagal memperbarui status booking saat validasi QR.');
                }
            } elseif ($booking['status_pembayaran'] == 'lunas') {
                session()->setFlashdata('info', 'Booking ini sudah Lunas dan siap digunakan.');
            } else {
                session()->setFlashdata('warning', 'Booking ini memiliki status: ' . ucfirst($booking['status_pembayaran']) . '. Tidak dapat divalidasi.');
            }
            return redirect()->to(base_url('admin/bookings/view/' . $bookingId));
        }

        echo view('layout/header');
        echo view('admin/booking/validate_qr');
        echo view('layout/footer');
    }

    // --- Metode untuk Pengelolaan Ulasan ---

    public function reviews()
    {
        $data['reviews'] = $this->reviewModel->findAll();

        foreach ($data['reviews'] as &$review) {
            $user = $this->userModel->find($review['user_id']);
            $studio = $this->studioModel->find($review['studio_id']);
            $booking = $this->bookingModel->find($review['booking_id']);
            $review['user_nama'] = $user['nama'] ?? 'User Tidak Ditemukan';
            $review['studio_nama'] = $studio['nama_studio'] ?? 'Studio Tidak Ditemukan';
            $review['booking_id_display'] = $booking['id'] ?? 'N/A';
        }

        echo view('layout/header');
        echo view('admin/review/list', $data);
        echo view('layout/footer');
    }

    public function moderateReview($id = null)
    {
        $review = $this->reviewModel->find($id);

        if (empty($review)) {
            session()->setFlashdata('error', 'Ulasan tidak ditemukan!');
            return redirect()->to(base_url('admin/reviews'));
        }

        $newStatus = $this->request->getPost('status_moderasi');

        $rules = [
            'status_moderasi' => 'required|in_list[pending,disetujui,ditolak]',
        ];

        if (! $this->validate($rules)) {
            session()->setFlashdata('errors', $this->validator->getErrors());
            return redirect()->back();
        }

        if ($this->reviewModel->update($id, ['status_moderasi' => $newStatus])) {
            session()->setFlashdata('success', 'Status ulasan berhasil diperbarui!');
        } else {
            log_message('error', 'Failed to update review status in database: ' . json_encode($this->reviewModel->errors()));
            session()->setFlashdata('error', 'Gagal memperbarui status ulasan. Silakan coba lagi.');
        }
        return redirect()->to(base_url('admin/reviews'));
    }
}
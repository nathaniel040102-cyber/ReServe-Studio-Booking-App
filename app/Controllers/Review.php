<?php namespace App\Controllers;

use App\Models\BookingModel;
use App\Models\StudioModel;
use App\Models\ReviewModel;
use CodeIgniter\Controller;

class Review extends BaseController
{
    protected $bookingModel;
    protected $studioModel;
    protected $reviewModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->studioModel = new StudioModel();
        $this->reviewModel = new ReviewModel();

        helper(['form']);

        // Pastikan user sudah login
        if (! session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'))->with('error', 'Anda harus login untuk memberikan ulasan.');
        }
    }

    public function create($bookingId = null)
    {
        $booking = $this->bookingModel->find($bookingId);

        // Pastikan booking ada, milik user yang login, dan sudah lunas
        if (empty($booking) || $booking['user_id'] != session()->get('user_id') || $booking['status_pembayaran'] != 'lunas') {
            session()->setFlashdata('error', 'Pemesanan tidak valid untuk diulas.');
            return redirect()->to(base_url('my-bookings'));
        }

        // Cek apakah sudah diulas
        $existingReview = $this->reviewModel->where('booking_id', $bookingId)->first();
        if (!empty($existingReview)) {
            session()->setFlashdata('info', 'Anda sudah memberikan ulasan untuk pemesanan ini.');
            return redirect()->to(base_url('my-bookings'));
        }

        // Cek apakah waktu booking sudah lewat
        $currentDateTime = strtotime(date('Y-m-d H:i:s'));
        $bookingEndDateTime = strtotime($booking['tanggal_booking'] . ' ' . $booking['jam_selesai']);
        if ($currentDateTime < $bookingEndDateTime) {
            session()->setFlashdata('error', 'Ulasan dapat diberikan setelah sesi studio selesai.');
            return redirect()->to(base_url('my-bookings'));
        }

        $data['booking'] = $booking;
        $data['studio'] = $this->studioModel->find($booking['studio_id']);

        echo view('layout/header');
        echo view('review/create', $data);
        echo view('layout/footer');
    }

    public function store()
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'))->with('error', 'Anda harus login untuk memberikan ulasan.');
        }

        $rules = [
            'booking_id' => 'required|numeric',
            'rating'     => 'required|numeric|greater_than_equal_to[1]|less_than_equal_to[5]',
            'ulasan'     => 'permit_empty|max_length[500]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $bookingId = $this->request->getPost('booking_id');
        $booking = $this->bookingModel->find($bookingId);

        // Validasi ulang booking
        if (empty($booking) || $booking['user_id'] != session()->get('user_id') || $booking['status_pembayaran'] != 'lunas') {
            session()->setFlashdata('error', 'Pemesanan tidak valid untuk diulas.');
            return redirect()->to(base_url('my-bookings'));
        }

        // Cek apakah sudah diulas
        $existingReview = $this->reviewModel->where('booking_id', $bookingId)->first();
        if (!empty($existingReview)) {
            session()->setFlashdata('info', 'Anda sudah memberikan ulasan untuk pemesanan ini.');
            return redirect()->to(base_url('my-bookings'));
        }
        // Cek apakah waktu booking sudah lewat
        $currentDateTime = strtotime(date('Y-m-d H:i:s'));
        $bookingEndDateTime = strtotime($booking['tanggal_booking'] . ' ' . $booking['jam_selesai']);
        if ($currentDateTime < $bookingEndDateTime) {
            session()->setFlashdata('error', 'Ulasan dapat diberikan setelah sesi studio selesai.');
            return redirect()->to(base_url('my-bookings'));
        }


        $dataReview = [
            'user_id'    => session()->get('user_id'),
            'studio_id'  => $booking['studio_id'],
            'booking_id' => $bookingId,
            'rating'     => $this->request->getPost('rating'),
            'ulasan'     => $this->request->getPost('ulasan'),
            'status_moderasi' => 'pending', // Default pending, perlu dimoderasi admin
        ];

        $this->reviewModel->save($dataReview);

        session()->setFlashdata('success', 'Ulasan Anda berhasil dikirim dan akan dimoderasi oleh Admin.');
        return redirect()->to(base_url('my-bookings'));
    }
}
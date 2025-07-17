<?php namespace App\Controllers;

use App\Models\StudioModel;
use App\Models\ReviewModel; // Pastikan ini ada
use App\Models\UserModel;   // Pastikan ini ada

use CodeIgniter\Controller;

class Studio extends BaseController
{
    protected $studioModel;
    protected $reviewModel; // Deklarasi properti untuk ReviewModel
    protected $userModel;   // Deklarasi properti untuk UserModel (untuk mengambil nama user di review)

    public function __construct()
    {
        $this->studioModel = new StudioModel();
        $this->reviewModel = new ReviewModel(); // Inisialisasi ReviewModel
        $this->userModel = new UserModel();   // Inisialisasi UserModel
        helper(['text']); // Memuat helper 'text' untuk word_limiter
    }

    public function index()
    {
        $model = new StudioModel();
        // Ambil semua studio yang aktif
        $data['studios'] = $model->where('status_aktif', 'aktif')->findAll();

        echo view('layout/header');
        echo view('studio/list', $data); // Tampilan daftar studio
        echo view('layout/footer');
    }

    public function show($id = null)
    {
        $data['studio'] = $this->studioModel->find($id);

        if (empty($data['studio'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Studio tidak ditemukan: ' . $id);
        }

        // Ambil ulasan yang disetujui untuk studio ini
        $data['reviews'] = $this->reviewModel
                                ->where('studio_id', $id)
                                ->where('status_moderasi', 'disetujui')
                                ->findAll();

        // Untuk menampilkan nama user yang mengulas
        foreach ($data['reviews'] as &$review) {
            $user = $this->userModel->find($review['user_id']); // Menggunakan UserModel yang sudah diinisialisasi
            $review['user_nama'] = $user['nama'] ?? 'Pengguna Anonim';
        }

        echo view('layout/header');
        echo view('studio/detail', $data); // Tampilan detail studio
        echo view('layout/footer');
    }
}
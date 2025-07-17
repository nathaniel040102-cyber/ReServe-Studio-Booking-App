<?php namespace App\Controllers;

use CodeIgniter\Controller;

class Pages extends BaseController
{
    public function about()
    {
        // Menampilkan halaman About Us
        echo view('layout/header');
        echo view('pages/about');
        echo view('layout/footer');
    }

    // Anda bisa menambahkan metode lain di sini untuk halaman statis lainnya,
    // contoh: public function contact() { ... }
}
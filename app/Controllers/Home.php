<?php namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        // Memuat layout header, view home/index, dan layout footer
        echo view('layout/header');
        echo view('home/index'); // <--- Ubah ini
        echo view('layout/footer');
    }
}
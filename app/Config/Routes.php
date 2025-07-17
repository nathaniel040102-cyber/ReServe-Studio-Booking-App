<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->GET('/', 'Home::index'); // Halaman utama aplikasi, bisa diubah nanti

// Rute untuk Modul Autentikasi (Register, Login, Logout)
$routes->GET('/login', 'Auth::login'); // Menampilkan form login
$routes->POST('/attemptLogin', 'Auth::attemptLogin'); // Memproses data login (menggunakan POST)
$routes->GET('/register', 'Auth::register'); // Menampilkan form register
$routes->POST('/attemptRegister', 'Auth::attemptRegister'); // Memproses data register (menggunakan POST)
$routes->GET('/logout', 'Auth::logout'); // Proses logout

// Rute untuk Modul Studio (Daftar Studio, Detail Studio)
$routes->GET('/studios', 'Studio::index'); // Menampilkan daftar semua studio
$routes->GET('/studios/(:num)', 'Studio::show/$1'); // Menampilkan detail studio berdasarkan ID

// Rute untuk Modul Booking
$routes->GET('/booking/create/(:num)', 'Booking::create/$1'); // Form booking untuk studio berdasarkan ID studio
$routes->POST('/booking/store', 'Booking::store'); // Memproses data booking
$routes->GET('/booking/confirm/(:num)', 'Booking::confirm/$1'); // Halaman konfirmasi booking
$routes->GET('/qrcodes/(:any)', 'Booking::showQr/$1'); // Rute untuk menampilkan QR Code

// Rute untuk Riwayat Pemesanan Pengguna
$routes->GET('/my-bookings', 'Booking::myBookings'); // Menampilkan riwayat pemesanan pengguna

// Rute untuk Pemberian Ulasan Pengguna
$routes->GET('/reviews/create/(:num)', 'Review::create/$1'); // Form ulasan untuk booking tertentu
$routes->POST('/reviews/store', 'Review::store'); // Memproses ulasan

// Rute untuk Halaman Statis (seperti About Us)
$routes->GET('/about', 'Pages::about'); // Halaman Tentang Kami
// $routes->GET('/contact', 'Pages::contact'); // Contoh untuk halaman kontak

// Rute untuk Modul Admin (Dashboard, Pengelolaan Studio, Promo, Pengguna, dan Booking)
// Terapkan AdminFilter di sini
$routes->group('admin', ['filter' => 'adminFilter'], function ($routes) { // Filter 'adminFilter' diterapkan di sini
    $routes->GET('/', 'Admin::index'); // Dashboard Admin

    // Rute untuk pengelolaan Studio oleh Admin
    $routes->GET('studios', 'Admin::studios'); // Daftar studio di admin
    $routes->GET('studios/new', 'Admin::createStudio'); // Form tambah studio
    $routes->POST('studios/store', 'Admin::storeStudio'); // Proses simpan studio baru
    $routes->GET('studios/edit/(:num)', 'Admin::editStudio/$1'); // Form edit studio
    $routes->POST('studios/update/(:num)', 'Admin::updateStudio/$1'); // Proses update studio (menggunakan POST dengan method spoofing PUT)
    $routes->DELETE('studios/delete/(:num)', 'Admin::deleteStudio/$1'); // Proses hapus studio (menggunakan DELETE dengan method spoofing DELETE)

    // Rute untuk pengelolaan Promo oleh Admin
    $routes->GET('promos', 'Admin::promos'); // Daftar promo di admin
    $routes->GET('promos/new', 'Admin::createPromo'); // Form tambah promo
    $routes->POST('promos/store', 'Admin::storePromo'); // Proses simpan promo baru
    $routes->GET('promos/edit/(:num)', 'Admin::editPromo/$1'); // Form edit promo
    $routes->POST('promos/update/(:num)', 'Admin::updatePromo/$1'); // Proses update promo
    $routes->DELETE('promos/delete/(:num)', 'Admin::deletePromo/$1'); // Proses hapus promo

    // Rute untuk pengelolaan Pengguna oleh Admin
    $routes->GET('users', 'Admin::users'); // Daftar pengguna di admin
    // $routes->GET('users/new', 'Admin::createUser'); // Jika ingin admin bisa tambah user manual
    // $routes->POST('users/store', 'Admin::storeUser');
    $routes->GET('users/edit/(:num)', 'Admin::editUser/$1'); // Form edit pengguna
    $routes->POST('users/update/(:num)', 'Admin::updateUser/$1'); // Proses update pengguna
    $routes->DELETE('users/delete/(:num)', 'Admin::deleteUser/$1'); // Proses hapus pengguna

    // Rute untuk pengelolaan Booking oleh Admin
    $routes->GET('bookings', 'Admin::bookings'); // Daftar booking di admin
    $routes->GET('bookings/view/(:num)', 'Admin::viewBooking/$1'); // Detail booking
    $routes->match(['POST', 'PUT'], 'bookings/update-status/(:num)', 'Admin::updateBookingStatus/$1'); // Update status booking
    $routes->match(['GET', 'POST'], 'bookings/validate', 'Admin::validateQrCode'); // Form dan proses validasi QR

    // Rute untuk pengelolaan Ulasan oleh Admin
    $routes->GET('reviews', 'Admin::reviews'); // Daftar ulasan di admin
    $routes->POST('reviews/moderate/(:num)', 'Admin::moderateReview/$1'); // Moderasi ulasan (POST dengan method spoofing PUT)
    // Jika ingin admin bisa hapus ulasan:
    // $routes->DELETE('reviews/delete/(:num)', 'Admin::deleteReview/$1');
});
# ReServe: Platform Reservasi Studio Musik Online

ReServe adalah aplikasi web yang dirancang untuk memudahkan proses reservasi studio musik secara online. Platform ini memungkinkan pengguna untuk dengan cepat dan mudah menemukan, memesan, dan mengelola sesi studio musik favorit mereka, kapan saja dan di mana saja, tanpa kerumitan reservasi manual.

Proyek ini dibangun menggunakan **CodeIgniter 4** sebagai *framework* PHP, dengan **XAMPP** sebagai lingkungan pengembangan lokal, dan **Composer** untuk manajemen dependensi.

## Fitur Utama

Berdasarkan presentasi proyek, ReServe mengimplementasikan fitur-fitur inti berikut:

* **Sistem Autentikasi Pengguna:**
    * Pendaftaran (Register) akun baru.
    * Login dan Logout pengguna dengan manajemen sesi.
    * Otorisasi berbasis peran (Admin dan Pengguna Biasa) untuk akses panel admin.
* **Manajemen Studio:**
    * **Pengguna:** Menampilkan daftar studio yang tersedia dan detail lengkap setiap studio.
    * **Admin:** Fitur CRUD (Create, Read, Update, Delete) untuk mengelola data studio (nama, deskripsi, alamat, harga, foto, status aktif).
* **Manajemen Promo dan Diskon:**
    * **Admin:** Fitur CRUD untuk mengelola kode promo (tipe diskon, nilai, tanggal berlaku, status aktif).
    * **Pengguna:** Penerapan diskon otomatis saat pemesanan studio menggunakan kode promo yang valid.
* **Manajemen Pengguna (Admin):**
    * Admin dapat melihat daftar pengguna, mengedit detail (termasuk peran), dan menghapus akun pengguna.
* **Sistem Pemesanan (Booking) Studio:**
    * Formulir pemesanan yang intuitif dengan validasi input.
    * Logika pengecekan ketersediaan studio untuk mencegah bentrok waktu.
    * Pembuatan dan penyimpanan QR Code unik untuk setiap pemesanan.
    * Halaman konfirmasi pemesanan yang menampilkan detail booking dan QR Code.
    * Riwayat pemesanan pengguna yang menampilkan semua booking, termasuk QR Code.
* **Sistem Rating dan Ulasan:**
    * **Pengguna:** Dapat memberikan rating (1-5 bintang) dan ulasan untuk pemesanan yang sudah lunas dan selesai.
    * **Admin:** Panel moderasi untuk menyetujui atau menolak ulasan pengguna.
    * Ulasan yang disetujui ditampilkan di halaman detail studio.
* **Panel Admin Terintegrasi:**
    * Dashboard informatif dengan ringkasan statistik (total studio, pengguna, promo, pemesanan, ulasan).
    * Navigasi mudah ke semua modul manajemen (Studio, Promo, Pengguna, Pemesanan, Ulasan).
    * Fitur validasi QR Code untuk petugas/admin.
* **Antarmuka Pengguna (UI):**
    * Desain responsif dan modern menggunakan **Bootstrap 5**.
    * Halaman utama (landing page) yang menarik dengan *background* gradasi dan gambar pahlawan.
    * Penggunaan *custom* CSS untuk konsistensi dan estetika yang lebih baik.

## Teknologi yang Digunakan

* **Backend:** PHP 8.x
* **Framework:** CodeIgniter 4.x
* **Database:** MySQL (melalui phpMyAdmin)
* **Server Lokal:** XAMPP
* **Manajemen Dependensi:** Composer
* **Frontend:** HTML5, CSS3 (Custom CSS), JavaScript
* **Framework CSS:** Bootstrap 5
* **Library QR Code:** chillerlan/php-qrcode
* **Pengiriman Email:** PHPMailer (untuk konfirmasi booking)

## Instalasi dan Setup Lokal

Ikuti langkah-langkah berikut untuk menjalankan proyek ini di lingkungan lokal Anda:

1.  **Clone Repositori:**
    ```bash
    git clone [https://github.com/nathaniel040102-cyber/ReServe-Studio-Booking-App.git](https://github.com/nathaniel040102-cyber/ReServe-Studio-Booking-App.git)
    cd ReServe-Studio-Booking-App
    ```
    *(Ganti `your-username` dengan username GitHub Anda)*

2.  **Siapkan XAMPP:**
    * Pastikan Anda telah menginstal [XAMPP](https://www.apachefriends.org/download.html) dan modul Apache serta MySQL sedang berjalan.
    * Pindahkan folder proyek `ReServe-Studio-Booking-App` ke dalam direktori `htdocs` XAMPP Anda (misalnya `C:\xampp\htdocs\reserve_ci4`).

3.  **Instal Dependensi Composer:**
    * Buka Command Prompt/Terminal di *root* proyek Anda (`C:\xampp\htdocs\reserve_ci4`).
    * Jalankan: `composer install`

4.  **Konfigurasi Lingkungan:**
    * Salin `.env.example` menjadi `.env`: `copy .env.example .env` (Windows) atau `cp .env.example .env` (Linux/macOS).
    * Buka *file* `.env` dan ubah `CI_ENVIRONMENT = production` menjadi `CI_ENVIRONMENT = development`.
    * (Opsional) Pastikan `app.baseURL` di `.env` kosong atau sesuai dengan URL server pengembangan Anda (misalnya `http://localhost:8080/`).

5.  **Setup Database:**
    * Buka [phpMyAdmin](http://localhost/phpmyadmin) melalui XAMPP Control Panel.
    * Buat *database* baru bernama `db_reserve`.
    * Buka *file* `app/Config/Database.php` dan pastikan konfigurasi `default` sesuai dengan pengaturan MySQL XAMPP Anda (username `root`, password kosong, database `db_reserve`).
    * Jalankan migrasi untuk membuat tabel:
        ```bash
        php spark migrate
        ```
    * Jalankan *seeder* untuk mengisi data dummy (studio, dll.):
        ```bash
        php spark db:seed DatabaseSeeder
        ```
    * **Catatan:** Pastikan folder `public/uploads/studio_images/` dan `public/qrcodes/` ada di proyek Anda, dan tempatkan gambar *dummy* studio (sesuai nama di `StudioSeeder.php`) di `public/uploads/studio_images/`.

6.  **Konfigurasi Email (Opsional, untuk Pengiriman Email Konfirmasi):**
    * Buka `app/Config/Email.php`.
    * Isi detail SMTP Anda (misalnya untuk Gmail, gunakan App Password jika Verifikasi 2 Langkah aktif).
        ```php
        public string $fromEmail    = 'alamat_email_anda@gmail.com';
        public string $SMTPUser     = 'alamat_email_anda@gmail.com';
        public string $SMTPPass     = 'APP_PASSWORD_16_KARAKTER'; // Tanpa spasi
        public int    $SMTPPort     = 587;
        public string $SMTPCrypto   = 'tls';
        ```
    * **Penting:** Pengiriman email dari `localhost` sering diblokir oleh *firewall* atau penyedia email. Untuk *debugging* yang lebih andal, disarankan menggunakan layanan seperti [Mailtrap.io](https://mailtrap.io/).

7.  **Jalankan Aplikasi:**
    * Di Command Prompt/Terminal yang sama, jalankan server pengembangan CodeIgniter:
        ```bash
        php spark serve
        ```
    * Buka *browser* Anda dan akses URL yang ditampilkan (biasanya `http://localhost:8080`).

## Kontribusi

Kontribusi dalam bentuk *issue*, *bug report*, atau *pull request* sangat kami hargai.

---

Terima kasih!
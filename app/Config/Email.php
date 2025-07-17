<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public string $fromName     = 'ReServe Booking';
    public string $fromEmail    = 'rsrvstdios@gmail.com'; // <--- GANTI dengan ALAMAT GMAIL PENGIRIM ANDA
    public string $recipients   = '';
    public string $userAgent    = 'CodeIgniter';
    public string $protocol     = 'smtp';
    public string $mailPath     = '/usr/sbin/sendmail';

    // Kredensial untuk Gmail SMTP
    public string $SMTPHost     = 'smtp.gmail.com'; // Host SMTP Gmail
    public string $SMTPUser     = 'rsrvstdios@gmail.com'; // <--- GANTI dengan ALAMAT GMAIL PENGIRIM ANDA
    public string $SMTPPass     = 'rqwd npxs deow ihna'; // <--- GANTI dengan APP PASSWORD GMAIL ANDA (16 KARAKTER TANPA SPASI)
    public int    $SMTPPort     = 587; // Port standar untuk TLS
    public string $SMTPCrypto   = 'tls'; // Enkripsi TLS untuk port 587

    public $SMTPTimeout  = 60;       // Timeout, bisa dipertahankan tinggi
    public bool   $SMTPKeepAlive = false;
    public string $SMTPDebug    = '2'; // Tetap aktifkan debug untuk melihat log jika ada masalah
    public int    $wordWrap     = 75;
    public bool   $wrapChars    = true;
    public string $mailType     = 'html'; // Tetap HTML agar gambar muncul
    public string $charset      = 'UTF-8';
    public bool   $validate     = false;
    public bool   $priority     = false;
    public string $CRLF         = "\r\n";
    public string $newline      = "\r\n";
    public bool   $BCCBatchMode = false;
    public int    $BCCBatchSize = 200;
    public bool   $DSN          = false;
}
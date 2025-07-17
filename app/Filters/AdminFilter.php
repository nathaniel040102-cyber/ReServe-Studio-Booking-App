<?php namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Cek apakah pengguna sudah login
        if (!session()->get('isLoggedIn')) {
            session()->setFlashdata('error', 'Anda harus login untuk mengakses halaman ini.');
            return redirect()->to(base_url('login'));
        }

        // Cek apakah pengguna adalah Admin (role_id = 1)
        if (session()->get('user_role') != 1) {
            session()->setFlashdata('error', 'Akses ditolak. Anda tidak memiliki izin Admin.');
            return redirect()->to(base_url('/')); // Redirect ke halaman utama atau halaman lain yang sesuai
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here or not
    }
}
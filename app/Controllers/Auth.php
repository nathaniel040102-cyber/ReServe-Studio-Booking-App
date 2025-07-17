<?php namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    public function login()
    {
        // Menampilkan form login
        helper(['form']); // Memuat helper form
        echo view('auth/login');
    }

    public function attemptLogin()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new UserModel();
        $user = $model->where('email', $this->request->getPost('email'))->first();

        if (! $user || ! password_verify($this->request->getPost('password'), $user['password'])) {
            session()->setFlashdata('error', 'Email atau password salah.');
            return redirect()->back()->withInput();
        }

        // Login berhasil
        $sessionData = [
            'user_id' => $user['id'],
            'user_nama' => $user['nama'],
            'user_email' => $user['email'],
            'user_role' => $user['role_id'], // Simpan role untuk otorisasi
            'isLoggedIn' => true,
        ];
        session()->set($sessionData);

        if ($user['role_id'] == 1) { // Asumsi role_id 1 untuk Admin
            return redirect()->to(base_url('admin'));
        } else {
            return redirect()->to(base_url('studios')); // Redirect ke halaman utama studio untuk user biasa
        }
    }

    public function register()
    {
        // Menampilkan form register
        helper(['form']);
        echo view('auth/register');
    }

    public function attemptRegister()
    {
        $rules = [
            'nama' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required_with[password]|matches[password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new UserModel();
        $data = [
            'nama' => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role_id' => 2, // Default role_id 2 untuk user biasa
        ];

        $model->save($data);

        session()->setFlashdata('success', 'Pendaftaran berhasil! Silakan login.');
        return redirect()->to(base_url('login'));
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'));
    }
}
<?php namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call('StudioSeeder'); // Memanggil StudioSeeder
        // Jika nanti ada seeder lain (misal UserSeeder untuk admin pertama), panggil di sini juga
        // $this->call('UserSeeder');
    }
}
<?php namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StudioSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Studio yang sudah ada
            [
                'nama_studio'   => 'Studio Harmoni',
                'deskripsi'     => 'Studio musik lengkap dengan peralatan band premium dan akustik yang nyaman. Ideal untuk rekaman dan latihan.',
                'alamat'        => 'Jl. Raya Musik No. 10, Jakarta Pusat',
                'harga_per_jam' => 150000.00,
                'foto'          => 'studio_harmoni.jpg',
                'status_aktif'  => 'aktif',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'nama_studio'   => 'Groove Lab Studio',
                'deskripsi'     => 'Studio modern dengan sistem suara terkini, cocok untuk DJ, produser, dan latihan band pop/rock.',
                'alamat'        => 'Jl. Beat No. 5, Bandung',
                'harga_per_jam' => 120000.00,
                'foto'          => 'groove_lab.jpeg',
                'status_aktif'  => 'aktif',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'nama_studio'   => 'Akustika Room',
                'deskripsi'     => 'Ruangan studio dengan desain akustik khusus untuk vokal, instrumen akustik, atau sesi solo.',
                'alamat'        => 'Jl. Nada Indah Kav. 3, Yogyakarta',
                'harga_per_jam' => 90000.00,
                'foto'          => 'akustika_room.jpeg',
                'status_aktif'  => 'aktif',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],

            // Studio baru yang ditambahkan
            [
                'nama_studio'   => 'Rhythm House',
                'deskripsi'     => 'Studio rekaman profesional dengan isolasi suara superior, ideal untuk produksi album dan mixing.',
                'alamat'        => 'Jl. Rekaman Emas No. 22, Surabaya',
                'harga_per_jam' => 250000.00,
                'foto'          => 'rhythm_house.gif',
                'status_aktif'  => 'aktif',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'nama_studio'   => 'Vocal Booth Pro',
                'deskripsi'     => 'Booth vokal kedap suara dengan mikrofon kondensor kelas atas, sempurna untuk voice-over dan demo vokal.',
                'alamat'        => 'Jl. Suara Jernih No. 7, Jakarta Selatan',
                'harga_per_jam' => 80000.00,
                'foto'          => 'vocal_booth_pro.jpg',
                'status_aktif'  => 'aktif',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'nama_studio'   => 'Band Practice Zone',
                'deskripsi'     => 'Studio latihan band yang luas dengan drum set, amplifier gitar/bass, dan PA system. Harga terjangkau.',
                'alamat'        => 'Jl. Latihan Keras No. 15, Bekasi',
                'harga_per_jam' => 100000.00,
                'foto'          => 'band_practice_zone.webp',
                'status_aktif'  => 'aktif',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'nama_studio'   => 'Digital Beat Lab',
                'deskripsi'     => 'Studio khusus produksi musik digital dengan workstation, MIDI controller, dan software lengkap.',
                'alamat'        => 'Jl. Kode Musik No. 1, Tangerang',
                'harga_per_jam' => 180000.00,
                'foto'          => 'digital_beat_lab.jpg',
                'status_aktif'  => 'aktif',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'nama_studio'   => 'Classic Jam Studio',
                'deskripsi'     => 'Studio dengan nuansa vintage dan amplifier tabung, cocok untuk genre blues dan rock klasik.',
                'alamat'        => 'Jl. Rock n Roll No. 70, Semarang',
                'harga_per_jam' => 160000.00,
                'foto'          => 'classic_jam_studio.jpg',
                'status_aktif'  => 'aktif',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'nama_studio'   => 'Podcast & Dubbing Room',
                'deskripsi'     => 'Ruangan multifungsi untuk rekaman podcast, dubbing, atau audiobook dengan kualitas suara prima.',
                'alamat'        => 'Jl. Suara Cerita No. 45, Depok',
                'harga_per_jam' => 110000.00,
                'foto'          => 'podcast_dubbing_room.jpeg',
                'status_aktif'  => 'aktif',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'nama_studio'   => 'Silent Practice Booth',
                'deskripsi'     => 'Booth kecil dan kedap suara untuk latihan instrumen solo atau vokal tanpa gangguan.',
                'alamat'        => 'Jl. Sunyi Senyap No. 3, Bogor',
                'harga_per_jam' => 75000.00,
                'foto'          => 'silent_practice_booth.jpg',
                'status_aktif'  => 'aktif',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
        ];

        // Menggunakan Query Builder untuk insert data
        $this->db->table('studios')->insertBatch($data);
    }
}
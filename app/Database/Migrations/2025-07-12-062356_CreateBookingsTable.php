<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBookingsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id'           => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true],
            'studio_id'         => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true],
            'promo_id'          => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'null' => true], // Foreign key ke tabel promos
            'tanggal_booking'   => ['type' => 'DATE'],
            'jam_mulai'         => ['type' => 'TIME'],
            'jam_selesai'       => ['type' => 'TIME'],
            'durasi_jam'        => ['type' => 'DECIMAL', 'constraint' => '4,2'], // Durasi dalam jam, bisa 0.5 jam, 1 jam, dst.
            'total_harga'       => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'status_pembayaran' => ['type' => 'ENUM', 'constraint' => ['pending', 'lunas', 'batal'], 'default' => 'pending'],
            'qr_code_path'      => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true], // Path ke gambar QR Code
            'created_at'        => ['type' => 'DATETIME', 'null' => true],
            'updated_at'        => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        // Menambahkan Foreign Key
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('studio_id', 'studios', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('promo_id', 'promos', 'id', 'SET NULL', 'CASCADE'); // SET NULL jika promo dihapus

        $this->forge->createTable('bookings');
    }

    public function down()
    {
        $this->forge->dropTable('bookings');
    }
}
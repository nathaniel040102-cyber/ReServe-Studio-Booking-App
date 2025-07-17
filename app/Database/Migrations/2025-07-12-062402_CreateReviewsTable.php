<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReviewsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id'           => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true],
            'studio_id'         => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true],
            'booking_id'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'unique' => true], // Satu review per booking
            'rating'            => ['type' => 'INT', 'constraint' => 1, 'unsigned' => true], // Skala 1-5
            'ulasan'            => ['type' => 'TEXT', 'null' => true],
            'status_moderasi'   => ['type' => 'ENUM', 'constraint' => ['pending', 'disetujui', 'ditolak'], 'default' => 'pending'],
            'created_at'        => ['type' => 'DATETIME', 'null' => true],
            'updated_at'        => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        // Menambahkan Foreign Key
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('studio_id', 'studios', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('booking_id', 'bookings', 'id', 'CASCADE', 'CASCADE'); // Pastikan 'id' di tabel 'bookings' memiliki tipe data yang sama
        $this->forge->createTable('reviews');
    }

    public function down()
    {
        $this->forge->dropTable('reviews');
    }
}
<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStudiosTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true],
            'nama_studio'   => ['type' => 'VARCHAR', 'constraint' => '255'],
            'deskripsi'     => ['type' => 'TEXT', 'null' => true],
            'alamat'        => ['type' => 'VARCHAR', 'constraint' => '255'],
            'harga_per_jam' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'foto'          => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true], // Path ke gambar
            'status_aktif'  => ['type' => 'ENUM', 'constraint' => ['aktif', 'nonaktif'], 'default' => 'aktif'],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('studios');
    }

    public function down()
    {
        $this->forge->dropTable('studios');
    }
}
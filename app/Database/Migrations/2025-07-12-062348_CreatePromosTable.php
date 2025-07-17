<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePromosTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true],
            'kode_promo'        => ['type' => 'VARCHAR', 'constraint' => '50', 'unique' => true],
            'deskripsi'         => ['type' => 'TEXT', 'null' => true],
            'tipe_diskon'       => ['type' => 'ENUM', 'constraint' => ['persen', 'nominal']], // persentase atau potongan nominal
            'nilai_diskon'      => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'tanggal_mulai'     => ['type' => 'DATETIME'],
            'tanggal_berakhir'  => ['type' => 'DATETIME'],
            'status_aktif'      => ['type' => 'ENUM', 'constraint' => ['aktif', 'nonaktif'], 'default' => 'aktif'],
            'created_at'        => ['type' => 'DATETIME', 'null' => true],
            'updated_at'        => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('promos');
    }

    public function down()
    {
        $this->forge->dropTable('promos');
    }
}
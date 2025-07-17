<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true],
            'nama'          => ['type' => 'VARCHAR', 'constraint' => '100'],
            'email'         => ['type' => 'VARCHAR', 'constraint' => '100', 'unique' => true],
            'password'      => ['type' => 'VARCHAR', 'constraint' => '255'], // Disimpan dalam bentuk hash
            'role_id'       => ['type' => 'INT', 'constraint' => 5, 'default' => 2], // 1: Admin, 2: User Biasa
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
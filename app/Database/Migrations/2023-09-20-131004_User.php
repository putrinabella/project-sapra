<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class User extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idUser' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'unique' => true,
                'collate' => 'utf8mb4_bin',
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 70,
                'collate' => 'utf8mb4_bin',
            ],
            'nama' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'role' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
        ]);

        $this->forge->addKey('idUser', true);
        $this->forge->createTable('tbluser', true);
    }

    public function down()
    {
        $this->forge->dropTable('tbluser');
    }
}

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class User extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                // 'unique' => true,
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 70,
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

        $this->forge->addKey('username', true);
        $this->forge->createTable('tbluser');
    }

    public function down()
    {
        $this->forge->dropTable('tbluser');
    }
}

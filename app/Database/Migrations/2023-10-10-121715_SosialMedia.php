<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SosialMedia extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idSosialMedia' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'namaSosialMedia' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'usernameSosialMedia' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'linkSosialMedia' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('idSosialMedia', true);
        $this->forge->createTable('tblSosialMedia');
    }

    public function down()
    {
        $this->forge->dropTable('tblSosialMedia');
    }
}

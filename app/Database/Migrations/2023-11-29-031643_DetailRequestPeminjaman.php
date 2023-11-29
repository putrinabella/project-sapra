<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DetailRequestPeminjaman extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idDetailRequestPeminjaman' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'idRequestPeminjaman' => [
                'type' => 'INT',
                'constraint' => 3,
            ],
            'idRincianLabAset' => [
                'type' => 'INT',
                'constraint' => 10,
            ],
            'requestItemStatus' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
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

        $this->forge->addKey('idDetailRequestPeminjaman', true);
        $this->forge->createTable('tblDetailRequestPeminjaman');
    }

    public function down()
    {
        $this->forge->dropTable('tblDetailRequestPeminjaman');
    }
}

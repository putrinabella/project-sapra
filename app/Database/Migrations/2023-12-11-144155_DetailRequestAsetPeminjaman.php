<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DetailRequestAsetPeminjaman extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idDetailRequestAsetPeminjaman' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'idRequestAsetPeminjaman' => [
                'type' => 'INT',
                'constraint' => 3,
            ],
            'idRincianAset' => [
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

        $this->forge->addKey('idDetailRequestAsetPeminjaman', true);
        $this->forge->createTable('tblDetailRequestAsetPeminjaman');
    }

    public function down()
    {
        $this->forge->dropTable('tblDetailRequestAsetPeminjaman');
    }
}

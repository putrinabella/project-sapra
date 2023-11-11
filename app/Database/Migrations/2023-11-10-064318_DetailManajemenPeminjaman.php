<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DetailManajemenPeminjaman extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idDetailManajemenPeminjaman' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'idManajemenPeminjaman' => [
                'type' => 'INT',
                'constraint' => 3,
            ],
            'idRincianLabAset' => [
                'type' => 'INT',
                'constraint' => 10,
            ],
            'statusSetelahPengembalian' => [
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

        $this->forge->addKey('idDetailManajemenPeminjaman', true);
        $this->forge->createTable('tblDetailManajemenPeminjaman');
    }

    public function down()
    {
        $this->forge->dropTable('tblDetailDetailManajemenPeminjaman');
    }
}

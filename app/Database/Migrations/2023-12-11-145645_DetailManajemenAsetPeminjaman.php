<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DetailManajemenAsetPeminjaman extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idDetailManajemenAsetPeminjaman' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'idManajemenAsetPeminjaman' => [
                'type' => 'INT',
                'constraint' => 3,
            ],
            'idRincianAset' => [
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

        $this->forge->addKey('idDetailManajemenAsetPeminjaman', true);
        $this->forge->createTable('tblDetailManajemenAsetPeminjaman');
    }

    public function down()
    {
        $this->forge->dropTable('tblDetailDetailManajemenAsetPeminjaman');
    }
}

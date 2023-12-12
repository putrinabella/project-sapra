<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ManajemenPeminjaman extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idManajemenPeminjaman' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'kodePeminjaman' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'tanggal' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'asalPeminjam' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'keperluanAlat' => [
                'type' => 'TEXT',
            ],
            'lamaPinjam' => [
                'type' => 'INT',
                'constraint' => 10,
            ],
            'loanStatus' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'tanggalPengembalian' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'namaPenerima' => [
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

        $this->forge->addKey('idManajemenPeminjaman', true);
        $this->forge->createTable('tblManajemenPeminjaman');
    }

    public function down()
    {
        $this->forge->dropTable('tblManajemenPeminjaman');
    }
}

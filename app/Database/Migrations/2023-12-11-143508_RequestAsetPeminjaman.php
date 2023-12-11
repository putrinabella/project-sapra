<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RequestAsetPeminjaman extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idRequestAsetPeminjaman' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'tanggal' => [
                'type' => 'DATE', 
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

        $this->forge->addKey('idRequestAsetPeminjaman', true);
        $this->forge->createTable('tblRequestAsetPeminjaman');
    }

    public function down()
    {
        $this->forge->dropTable('tblRequestAsetPeminjaman');
    }
}

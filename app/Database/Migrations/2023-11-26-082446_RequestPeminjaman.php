<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RequestPeminjaman extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idLoanRequest' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'tanggal' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'asalPeminjam' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'kategoriPeminjam' => [
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

        $this->forge->addKey('idLoanRequest', true);
        $this->forge->createTable('tblLoanRequests');
    }

    public function down()
    {
        $this->forge->dropTable('tblLoanRequests');
    }
}

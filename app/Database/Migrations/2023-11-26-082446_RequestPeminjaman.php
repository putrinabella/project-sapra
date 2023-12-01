<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RequestPeminjaman extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idRequestPeminjaman' => [
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

        $this->forge->addKey('idRequestPeminjaman', true);
        $this->forge->createTable('tblRequestPeminjaman');
    }

    public function down()
    {
        $this->forge->dropTable('tblRequestPeminjaman');
    }
}

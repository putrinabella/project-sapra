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
            'tanggal' => [ 
                'type' => 'DATE', 
                'null' => true,
            ],
            'namaPeminjam' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'asalPeminjam' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'idIdentitasSarana' => [
                'type' => 'INT',
                'constraint' => 3,
            ],
            'kodeLab' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'jumlah' => [
                'type' => 'INT',
                'constraint' => 4,
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

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DataPeminjaman extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idPeminjaman' => [
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
            'tanggalPengembalian' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'idIdentitasSarana' => [
                'type' => 'INT',
                'constraint' => 3,
            ],
            'idIdentitasPrasarana' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'jumlah' => [
                'type' => 'INT',
                'constraint' => 4,
            ],
            'jumlahBarangDikembalikan' => [
                'type' => 'INT',
                'constraint' => 4,
            ],
            'jumlahBarangRusak' => [
                'type' => 'INT',
                'constraint' => 4,
            ],
            'jumlahBarangHilang' => [
                'type' => 'INT',
                'constraint' => 4,
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

        $this->forge->addKey('idPeminjaman', true);
        $this->forge->createTable('tblPeminjaman');
    }

    public function down()
    {
        $this->forge->dropTable('tblPeminjaman');
    }
}
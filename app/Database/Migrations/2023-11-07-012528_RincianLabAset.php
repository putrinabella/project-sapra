<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RincianLabAset extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idRincianLabAset' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'kodeRincianLabAset' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'idManajemenPeminjaman' => [
                'type' => 'INT',
                'constraint' => 3,
            ],
            'idIdentitasSarana' => [
                'type' => 'INT',
                'constraint' => 3,
            ],
            'idSumberDana' => [
                'type' => 'INT',
                'constraint' => 2,
            ],
            'idKategoriManajemen' => [
                'type' => 'INT',
                'constraint' => 3,
            ],
            'idIdentitasLab' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'tahunPengadaan' => [
                'type' => 'INT',
                'constraint' => 4,
            ],
            'noSeri' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'merk' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'warna' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'nomorBarang' => [
                'type' => 'INT',
                'constraint' => 4,
            ],
            'spesifikasi' => [
                'type' => 'MEDIUMTEXT',
            ],
            'bukti' => [
                'type' => 'TEXT',
            ],
            'hargaBeli' => [
                'type' => 'INT',
            ],
            'status' => [
                'type' => 'ENUM("Bagus", "Rusak", "Hilang")',
                'default' => 'Bagus',
            ],
            'sectionAset' => [
                'type' => 'ENUM("Dipinjam", "Dimusnahkan", "None")',
                'default' => 'None',
            ],
            'tanggalPemusnahan' => [
                'type' => 'DATE',
            ],
            'namaAkun' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'kodeAkun' => [
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

        $this->forge->addKey('idRincianLabAset', true);
        $this->forge->createTable('tblRincianLabAset');
    }

    public function down()
    {
        $this->forge->dropTable('tblRincianLabAset');
    }
}

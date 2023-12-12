<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RincianAset extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idRincianAset' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'kodeRincianAset' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'idManajemenAsetPeminjaman' => [
                'type' => 'INT',
                'constraint' => 3,
                'null' => true,
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
            'idIdentitasPrasarana' => [
                'type' => 'INT',
                'constraint' => 3,
            ],
            'tahunPengadaan' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
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

        $this->forge->addKey('idRincianAset', true);
        $this->forge->createTable('tblRincianAset');
    }

    public function down()
    {
        $this->forge->dropTable('tblRincianAset');
    }
}

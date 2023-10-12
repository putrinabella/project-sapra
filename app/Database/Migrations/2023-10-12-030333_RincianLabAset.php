<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RincianLabAsetLab extends Migration
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
            'kodeLab' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'tahunPengadaan' => [
                'type' => 'INT',
                'constraint' => 4,
            ],
            'saranaLayak' => [
                'type' => 'INT',
                'constraint' => 4,
            ],
            'saranaRusak' => [
                'type' => 'INT',
                'constraint' => 4,
            ],
            'spesifikasi' => [
                'type' => 'MEDIUMTEXT',
            ],
            'totalSarana' => [
                'type' => 'INT',
                'constraint' => 4,
            ],
            'bukti' => [
                'type' => 'TEXT',
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

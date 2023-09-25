<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RincianAset extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idDataSarana' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'idIdentitasSarana' => [
                'type' => 'INT',
                'constraint' => 3,
            ],
            'idDataPrasarana' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'idSumberDana' => [
                'type' => 'INT',
                'constraint' => 2,
            ],
            'idKategoriManajemen' => [
                'type' => 'INT',
                'constraint' => 3,
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
            'link' => [
                'type' => 'TEXT',
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

        $this->forge->addKey('idDataSarana', true);
        $this->forge->createTable('tblRincianAset');
    }

    public function down()
    {
        $this->forge->dropTable('tblRincianAset');
    }
}

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
            'kodePrasarana' => [
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

        $this->forge->addKey('idRincianAset', true);
        $this->forge->createTable('tblRincianAset');
    }

    public function down()
    {
        $this->forge->dropTable('tblRincianAset');
    }
}
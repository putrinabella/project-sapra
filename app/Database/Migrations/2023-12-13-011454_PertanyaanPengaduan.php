<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PertanyaanPengaduan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idPertanyaanPengaduan' => [
                'type' => 'INT',
                'constraint' => 3,
            ],
            'pertanyaanPengaduan' => [
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

        $this->forge->addKey('idPertanyaanPengaduan', true);
        $this->forge->createTable('tblPertanyaanPengaduan');
    }

    public function down()
    {
        $this->forge->dropTable('tblPertanyaanPengaduan');
    }
}

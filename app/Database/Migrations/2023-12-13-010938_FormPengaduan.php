<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FormPengaduan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idFormPengaduan' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'idDataSiswa' => [
                'type' => 'INT',
                'constraint' => 3,
            ],
            'idPertanyaanPengaduan' => [
                'type' => 'INT',
                'constraint' => 3,
            ],
            'tanggal' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'sp' => [
                'type' => 'INT',
                'constraint' => 3,
            ],
            'p' => [
                'type' => 'INT',
                'constraint' => 3,
            ],
            'n' => [
                'type' => 'INT',
                'constraint' => 3,
            ],
            'tp' => [
                'type' => 'INT',
                'constraint' => 3,
            ],
            'st' => [
                'type' => 'INT',
                'constraint' => 3,
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

        $this->forge->addKey('idFormPengaduan', true);
        $this->forge->createTable('tblFormPengaduan');
    }

    public function down()
    {
        $this->forge->dropTable('tblFormPengaduan');
    }
}

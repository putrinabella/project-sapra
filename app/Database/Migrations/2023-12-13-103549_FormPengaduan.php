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
            'kodeFormPengaduan' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'idDataSiswa' => [
                'type' => 'INT',
                'constraint' => 3,
            ],
            'statusPengaduan' => [
                'type' => 'ENUM',
                'constraint' => ['request', 'process', 'needFeedback', 'done'],
                'null' => true,
            ],
            'tanggal' => [
                'type' => 'DATE',
                'null' => true,
            ],           
            'tanggalSelesai' => [
                'type' => 'DATE',
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

        $this->forge->addKey('idFormPengaduan', true);
        $this->forge->createTable('tblFormPengaduan');
    }

    public function down()
    {
        $this->forge->dropTable('tblFormPengaduan');
    }
}

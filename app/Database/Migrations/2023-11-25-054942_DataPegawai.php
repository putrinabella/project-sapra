<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DataPegawai extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idDataPegawai' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'namaPegawai' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'nip' => [
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

        $this->forge->addKey('idDataPegawai', true);
        $this->forge->createTable('tblDataPegawai');
    }

    public function down()
    {
        $this->forge->dropTable('tblDataPegawai');
    }
}

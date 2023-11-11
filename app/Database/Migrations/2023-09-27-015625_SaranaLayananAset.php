<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SaranaLayananAset extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idSaranaLayananAset' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'tanggal' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'idRincianAset' => [
                'type' => 'INT',
                'constraint' => 3,
            ],
            'idSumberDana' => [
                'type' => 'INT',
                'constraint' => 2,
            ],
            'idStatusLayanan' => [
                'type' => 'INT',
                'constraint' => 3,
            ],
            'biaya' => [
                'type' => 'INT',
            ],
            'bukti' => [
                'type' => 'TEXT',
            ],
            'keterangan' => [
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

        $this->forge->addKey('idSaranaLayananAset', true);
        $this->forge->createTable('tblSaranaLayananAset');
    }

    public function down()
    {
        $this->forge->dropTable('tblSaranaLayananAset');
    }
}

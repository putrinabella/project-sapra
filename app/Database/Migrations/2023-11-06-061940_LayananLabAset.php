<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class LayananLabAset extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idLayananLabAset' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'tanggal' => [
                'type' => 'DATE', 
            ],
            'idRincianLabAset' => [
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

        $this->forge->addKey('idLayananLabAset', true);
        $this->forge->createTable('tbllayananLabAset');
    }

    public function down()
    {
        $this->forge->dropTable('tbllayananLabAset');
    }
}

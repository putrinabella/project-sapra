<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TagihanInternet extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idTagihanInternet' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'pemakaianInternet' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'bulanPemakaianInternet' => [
                'type' => 'INT',
                'constraint' => 2,
            ],
            'tahunPemakaianInternet' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'biaya' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
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

        $this->forge->addKey('idTagihanInternet', true);
        $this->forge->createTable('tblTagihanInternet');
    }

    public function down()
    {
        $this->forge->dropTable('tblTagihanInternet');
    }
}

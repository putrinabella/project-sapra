<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TagihanListrik extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idTagihanListrik' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'pemakaianListrik' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'bulanPemakaianListrik' => [
                'type' => 'INT',
                'constraint' => 2,
            ],
            'tahunPemakaianListrik' => [
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

        $this->forge->addKey('idTagihanListrik', true);
        $this->forge->createTable('tblTagihanListrik');
    }

    public function down()
    {
        $this->forge->dropTable('tblTagihanListrik');
    }
}

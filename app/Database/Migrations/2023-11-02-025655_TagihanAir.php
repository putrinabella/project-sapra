<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TagihanAir extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idTagihanAir' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'pemakaianAir' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'bulanPemakaianAir' => [
                'type' => 'ENUM("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember")',
            ],
            'tahunPemakaianAir' => [
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

        $this->forge->addKey('idTagihanAir', true);
        $this->forge->createTable('tblTagihanAir');
    }

    public function down()
    {
        $this->forge->dropTable('tblTagihanAir');
    }
}

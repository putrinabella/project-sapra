<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class IdentitasGedung extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idIdentitasGedung' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'namaGedung' => [
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

        $this->forge->addKey('idIdentitasGedung', true);
        $this->forge->createTable('tblIdentitasGedung');
    }

    public function down()
    {
        $this->forge->dropTable('tblIdentitasGedung');
    }
}

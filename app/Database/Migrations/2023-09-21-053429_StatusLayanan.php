<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class StatusLayanan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idStatusLayanan' => [
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

        $this->forge->addKey('idStatusLayanan', true);
        $this->forge->createTable('tblStatusLayanan');
    }

    public function down()
    {
        $this->forge->dropTable('tblStatusLayanan');
    }
}

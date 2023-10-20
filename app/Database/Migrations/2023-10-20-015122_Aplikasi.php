<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Aplikasi extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idAplikasi' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'namaAplikasi' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'picAplikasi' => [
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

        $this->forge->addKey('idAplikasi', true);
        $this->forge->createTable('tblAplikasi');
    }

    public function down()
    {
        $this->forge->dropTable('tblAplikasi');
    }
}

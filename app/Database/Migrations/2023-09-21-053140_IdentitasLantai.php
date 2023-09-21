<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class IdentitasLantai extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idIdentitasLantai' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'namaLantai' => [
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

        $this->forge->addKey('idIdentitasLantai', true);
        $this->forge->createTable('tblIdentitasLantai');
    }

    public function down()
    {
        $this->forge->dropTable('tblIdentitasLantai');
    }
}

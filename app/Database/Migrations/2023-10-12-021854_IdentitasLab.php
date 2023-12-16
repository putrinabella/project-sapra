<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class IdentitasLab extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idIdentitasLab' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'namaLab' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'luas' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'idIdentitasGedung' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
            ],
            'idIdentitasLantai' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
            ],
            'kodeLab' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'picturePath' => [
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

        $this->forge->addKey('idIdentitasLab', true);
        $this->forge->createTable('tblIdentitasLab');
    }

    public function down()
    {
        $this->forge->dropTable('tblIdentitasLab');
    }
}

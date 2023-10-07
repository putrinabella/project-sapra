<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class IdentitasPrasarana extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idIdentitasPrasarana' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'namaPrasarana' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'tipe' => [
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
            'kodePrasarana' => [
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

        $this->forge->addKey('idIdentitasPrasarana', true);
        $this->forge->createTable('tblIdentitasPrasarana');
    }

    public function down()
    {
        $this->forge->dropTable('tblIdentitasPrasarana');
    }
}

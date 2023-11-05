<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class KategoriMep extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idKategoriMep' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'namaKategoriMep' => [
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

        $this->forge->addKey('idKategoriMep', true);
        $this->forge->createTable('tblKategoriMep');
    }

    public function down()
    {
        $this->forge->dropTable('tblKategoriMep');
    }
}

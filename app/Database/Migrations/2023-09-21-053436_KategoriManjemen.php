<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class KategoriManjemen extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idKategoriManajemen' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'namaKategoriManajemen' => [
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

        $this->forge->addKey('idKategoriManajemen', true);
        $this->forge->createTable('tblKategoriManajemen');
    }

    public function down()
    {
        $this->forge->dropTable('tblKategoriManajemen');
    }
}

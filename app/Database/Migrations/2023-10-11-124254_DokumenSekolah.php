<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DokumenSekolah extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idDokumenSekolah' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'namaDokumenSekolah' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'linkDokumenSekolah' => [
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

        $this->forge->addKey('idDokumenSekolah', true);
        $this->forge->createTable('tblDokumenSekolah');
    }

    public function down()
    {
        $this->forge->dropTable('tblDokumenSekolah');
    }
}

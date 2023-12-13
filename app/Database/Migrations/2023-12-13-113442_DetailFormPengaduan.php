<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DetailFormPengaduan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idDetailFormPengaduan' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'idFormPengaduan' => [
                'type' => 'INT',
                'constraint' => 3,
            ],
            'idPertanyaanPengaduan' => [
                'type' => 'INT',
                'constraint' => 3,
            ],
            'isiPengaduan' => [
                'type' => 'MEDIUMTEXT',
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

        $this->forge->addKey('idDetailFormPengaduan', true);
        $this->forge->createTable('tblDetailFormPengaduan');
    }

    public function down()
    {
        $this->forge->dropTable('tblDetailFormPengaduan');
    }
}

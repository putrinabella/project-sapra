<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class LayananLabNonAset extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idLayananLabNonAset' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'tanggal' => [ 
                'type' => 'DATE', 
                'null' => true,
            ],
            'idIdentitasLab' => [
                'type' => 'INT',
                'constraint' => 3,
            ],
            'idStatusLayanan' => [
                'type' => 'INT',
                'constraint' => 3,
            ],
            'idSumberDana' => [
                'type' => 'INT',
                'constraint' => 2,
            ],
            'idKategoriManajemen' => [
                'type' => 'INT',
                'constraint' => 3,
            ],
            'biaya' => [
                'type' => 'INT',
            ],
            'bukti' => [
                'type' => 'TEXT',
            ],
            'spesifikasi' => [
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

        $this->forge->addKey('idLayananLabNonAset', true);
        $this->forge->createTable('tblLayananLabNonAset');
    }

    public function down()
    {
        $this->forge->dropTable('tblLayananLabNonAset');
    }
}

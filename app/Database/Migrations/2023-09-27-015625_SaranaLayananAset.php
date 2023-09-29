<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SaranaLayananAset extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idSaranaLayananAset' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'idIdentitasSarana' => [
                'type' => 'INT',
                'constraint' => 3,
            ],
            'idIdentitasPrasarana' => [
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
            'tanggal' => [ 
                'type' => 'DATE', 
                'null' => true,
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

        $this->forge->addKey('idSaranaLayananAset', true);
        $this->forge->createTable('tblSaranaLayananAset');
    }

    public function down()
    {
        $this->forge->dropTable('tblSaranaLayananAset');
    }
}

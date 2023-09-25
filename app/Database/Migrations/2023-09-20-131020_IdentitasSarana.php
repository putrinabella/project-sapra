<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class IdentitasSarana extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idIdentitasSarana' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'namaSarana' => [
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

        $this->forge->addKey('idIdentitasSarana', true);
        $this->forge->createTable('tblIdentitasSarana');
    }

    public function down()
    {
        $this->forge->dropTable('tblIdentitasSarana');
    }
}

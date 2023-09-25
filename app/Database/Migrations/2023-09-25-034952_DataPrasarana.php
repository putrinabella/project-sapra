<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DataPrasarana extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idDataPrasarana' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'idIdentitasPrasarana' => [
                'type' => 'INT',
                'constraint' => 3,
            ],
            'idDataSarana' => [
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

        $this->forge->addPrimaryKey('idDataPrasarana');
        $this->forge->createTable('tblDataPrasarana');
    }

    public function down()
    {
        $this->forge->dropTable('tblDataPrasarana');
    }
}

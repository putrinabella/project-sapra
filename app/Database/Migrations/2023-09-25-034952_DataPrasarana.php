<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DataPrasarana extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idDataPrasarana' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'kodePrasarana' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
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

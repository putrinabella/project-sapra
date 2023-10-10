<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Website extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idWebsite' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'namaWebsite' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'fungsiWebsite' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'linkWebsite' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'picWebsite' => [
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

        $this->forge->addKey('idWebsite', true);
        $this->forge->createTable('tblWebsite');
    }

    public function down()
    {
        $this->forge->dropTable('tblWebsite');
    }
}

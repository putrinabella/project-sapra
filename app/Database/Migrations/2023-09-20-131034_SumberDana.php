<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SumberDana extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idSumberDana' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'kodeSumberDana' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'namaSumberDana' => [
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

        $this->forge->addKey('idSumberDana', true);
        $this->forge->createTable('tblSumberDana');
    }

    public function down()
    {
        $this->forge->dropTable('tblSumberDana');
    }
}

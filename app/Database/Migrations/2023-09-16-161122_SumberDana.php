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
            'namaSumberDana' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
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

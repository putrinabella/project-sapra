<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PertanyaanFeedback extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idPertanyaanFeedback' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'pertanyaanFeedback' => [
                'type' => 'TEXT',
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

        $this->forge->addKey('idPertanyaanFeedback', true);
        $this->forge->createTable('tblPertanyaanFeedback');
    }

    public function down()
    {
        $this->forge->dropTable('tblPertanyaanFeedback');
    }
}

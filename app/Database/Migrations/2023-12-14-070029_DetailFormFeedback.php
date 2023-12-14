<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DetailFormFeedback extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idDetailFormFeedback' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'idFormFeedback' => [
                'type' => 'INT',
                'constraint' => 3,
            ],
            'idPertanyaanFeedback' => [
                'type' => 'INT',
                'constraint' => 3,
            ],
            'isiFeedback' => [
                'type' => 'INT',
                'constraint' => 3,
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

        $this->forge->addKey('idDetailFormFeedback', true);
        $this->forge->createTable('tblDetailFormFeedback');
    }

    public function down()
    {
        $this->forge->dropTable('tblDetailFormFeedback');
    }
}

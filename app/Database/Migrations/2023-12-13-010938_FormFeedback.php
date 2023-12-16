<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FormFeedback extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idFormFeedback' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'idFormPengaduan' => [
                'type' => 'INT',
                'constraint' => 3,
            ],
            'idDataSiswa' => [
                'type' => 'INT',
                'constraint' => 3,
            ],
            'statusFeedback' => [
                'type' => 'ENUM',
                'constraint' => ['empty', 'done'],
                'null' => true,
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

        $this->forge->addKey('idFormFeedback', true);
        $this->forge->createTable('tblFormFeedback');
    }

    public function down()
    {
        $this->forge->dropTable('tblFormFeedback');
    }
}

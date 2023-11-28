<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserActionLogs extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type' => 'INT', 
                'constraint' => 5, 
                'unsigned' => true, 
                'auto_increment' => true
            ],
            'user_id'     => [
                'type' => 'INT', 
                'constraint' => 11
            ],
            'actionTime'  => [
                'type' => 'DATETIME'
            ],
            'actionType' => [
                'type' => 'VARCHAR', 
                'constraint' => 20
            ],
            'actionDetails' => [
                'type' => 'TEXT'
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('tblUserActionsLogs');
    }

    public function down()
    {
        $this->forge->dropTable('tblUserActionsLogs');
    }
}
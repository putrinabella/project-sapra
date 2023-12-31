<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserLoginLogs extends Migration
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
            'loginTime'  => [
                'type' => 'DATETIME'
            ],
            'actionType' => [
                'type' => 'VARCHAR', 
                'constraint' => 10
            ],
            'ipAddress'  => [
                'type' => 'VARCHAR', 
                'constraint' => 45
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('tblUserLogs');
    }

    public function down()
    {
        $this->forge->dropTable('tblUserLogs');
    }
}

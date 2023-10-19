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
            'login_time'  => [
                'type' => 'DATETIME'
            ],
            'ip_address'  => [
                'type' => 'VARCHAR', 
                'constraint' => 45
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('user_login_logs');
    }

    public function down()
    {
        $this->forge->dropTable('user_login_logs');
    }
}

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DataSiswa extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idDataSiswa' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'namaSiswa' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'nis' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'idIdentitasKelas' => [
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

        $this->forge->addKey('idDataSiswa', true);
        $this->forge->createTable('tblDataSiswa');
    }

    public function down()
    {
        $this->forge->dropTable('tblDataSiswa');
    }
}

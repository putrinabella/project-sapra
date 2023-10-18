<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class IdentitasKelas extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idIdentitasKelas' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'namaKelas' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'jumlahSiswa' => [
                'type' => 'INT',
                'constraint' => 4,
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
    
            $this->forge->addKey('idIdentitasKelas', true);
            $this->forge->createTable('tblIdentitasKelas');
        }
    
        public function down()
        {
            $this->forge->dropTable('tblIdentitasKelas');
        }
    }
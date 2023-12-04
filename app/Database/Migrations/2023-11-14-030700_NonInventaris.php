<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class NonInventaris extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idNonInventaris' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nama' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'satuan' => [
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
    
            $this->forge->addKey('idNonInventaris', true);
            $this->forge->createTable('tblNonInventaris');
        }
    
        public function down()
        {
            $this->forge->dropTable('tblNonInventaris');
        }
    }
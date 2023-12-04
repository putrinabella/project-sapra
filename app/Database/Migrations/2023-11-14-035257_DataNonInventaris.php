<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DataNonInventaris extends Migration
{

    public function up()
    {
        $this->forge->addField([
            'idDataNonInventaris' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'idNonInventaris' => [
                'type' => 'INT',
                'constraint' => 3,
            ],
            'jumlah' => [
                'type' => 'INT',
                'constraint' => 10,
            ],
            'tanggal' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'tipe' => [
                'type' => 'ENUM',
                'constraint' => ['Pemasukan', 'Pengeluaran'],
                'default' => 'Pemasukan',
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
    
            $this->forge->addKey('idDataNonInventaris', true);
            $this->forge->createTable('tblDataNonInventaris');
        }
    
        public function down()
        {
            $this->forge->dropTable('tblDataNonInventaris');
        }
    }
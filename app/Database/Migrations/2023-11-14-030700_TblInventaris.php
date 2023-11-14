<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TblInventaris extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idInventaris' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'namaInventaris' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'satuan' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'tanggalInventaris' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'tipeInventaris' => [
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
    
            $this->forge->addKey('idInventaris', true);
            $this->forge->createTable('tblInventaris');
        }
    
        public function down()
        {
            $this->forge->dropTable('tblInventaris');
        }
    }
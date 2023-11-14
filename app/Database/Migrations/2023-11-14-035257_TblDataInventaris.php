<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TblDataInventaris extends Migration
{

    public function up()
    {
        $this->forge->addField([
            'idDataInventaris' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'idInventaris' => [
                'type' => 'INT',
                'constraint' => 3,
            ],
            'jumlahDataInventaris' => [
                'type' => 'INT',
                'constraint' => 10,
            ],
            'tanggalDataInventaris' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'tipeDataInventaris' => [
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
    
            $this->forge->addKey('idDataInventaris', true);
            $this->forge->createTable('tblDataInventaris');
        }
    
        public function down()
        {
            $this->forge->dropTable('tblDataInventaris');
        }
    }
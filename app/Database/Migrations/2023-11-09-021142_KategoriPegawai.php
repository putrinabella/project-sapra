<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class KategoriPegawai extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idKategoriPegawai' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'namaKategoriPegawai' => [
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
    
            $this->forge->addKey('idKategoriPegawai', true);
            $this->forge->createTable('tblKategoriPegawai');
        }
    
        public function down()
        {
            $this->forge->dropTable('tblKategoriPegawai');
        }
    }
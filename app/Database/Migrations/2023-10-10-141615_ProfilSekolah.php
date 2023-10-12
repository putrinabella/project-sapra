<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ProfilSekolah extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'idProfilSekolah' => [
                'type' => 'INT',
                'constraint' => 3,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'kepsek' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'operator' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'akreditasi' => [
                'type' => 'CHAR',
                'constraint' => 5,
            ],
            'kurikulum' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'npsn' => [
                'type' => 'VARCHAR',
                'constraint' => 18,
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'bentukPendidikan' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'statusKepemilikan' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'skPendirian' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'tanggalSkPendirian' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'skIzinOperasional' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'tanggalSkIzinOperasional' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'statusBos' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'waktuPenyelenggaraan' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'sertifikasiIso' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'sumberListrik' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'kecepatanInternet' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'siswaKebutuhanKhusus' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'namaBank' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'cabangKcp' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'atasNamaRekening' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
        ]);

        $this->forge->addKey('idProfilSekolah', true);
        $this->forge->createTable('tblProfilSekolah');
    }

    public function down()
    {
        $this->forge->dropTable('tblProfilSekolah');
    }
}

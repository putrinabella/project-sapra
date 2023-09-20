<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SumberDanaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'namaSumberDana' => 'Yayasan',
            ],
            [
                'namaSumberDana' => 'Bantuan Operasional Sekolah',
            ],
            [
                'namaSumberDana' => 'Hibah',
            ],
        ];
        $this->db->table('tblSumberDana')->insertBatch($data);
    }
}

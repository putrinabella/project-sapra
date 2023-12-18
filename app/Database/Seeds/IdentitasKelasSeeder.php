<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class IdentitasKelasSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'namaKelas' => 'Karyawan',
            ],
            [
                'namaKelas' => 'X A',
            ],
            [
                'namaKelas' => 'X B',
            ],
        ];
        $this->db->table('tblIdentitasKelas')->insertBatch($data);
    }
}

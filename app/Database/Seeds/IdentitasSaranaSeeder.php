<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class IdentitasSaranaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'namaSarana' => 'Kursi',
            ],
            [
                'namaSarana' => 'Meja',
            ],
            [
                'namaSarana' => 'Papan tulis',
            ],
        ];
        $this->db->table('tblIdentitasSarana')->insertBatch($data);
    }
}

<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SiswaSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');

        for($i = 1; $i <= 20 ; $i++) {
            $data = [
                'namaSiswa' => $faker->name(),
                'nis' => $faker->unique()->uuid,
                'idIdentitasKelas' => random_int(1,5),
                'created_at' => \CodeIgniter\I18n\Time::now(),
            ];
            $this->db->table('tblDataSiswa')->insert($data);
        }
    }
}

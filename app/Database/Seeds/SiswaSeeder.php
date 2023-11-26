<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SiswaSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');

        for($i = 1; $i <= 500 ; $i++) {
            $data = [
                'namaSiswa' => $faker->name(),
                // 'nis' => $faker->unique()->uuid,
                'nis' => $faker->unique()->numberBetween($min = 1555555555, $max = 1999999999),
                'idIdentitasKelas' => random_int(1,5),
                'created_at' => \CodeIgniter\I18n\Time::now(),
            ];
            $this->db->table('tblDataSiswa')->insert($data);
        }
    }
}

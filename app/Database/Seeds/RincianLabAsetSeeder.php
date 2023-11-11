<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RincianLabAsetSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');

        for ($i = 1; $i <= 50; $i++) {
            $data = [
                'kodeRincianLabAset' => $faker->text(18),
                'idIdentitasSarana' => $faker->numberBetween(1, 20),
                'idSumberDana' => $faker->numberBetween(1, 2),
                'idKategoriManajemen' => $faker->numberBetween(1, 3),
                'idIdentitasLab' => $faker->numberBetween(1, 3),
                'tahunPengadaan' => $faker->numberBetween(2000, 2022),
                'noSeri' => $faker->text(20),
                'merk' => $faker->text(20),
                'warna' => $faker->text(20),
                'nomorBarang' => $faker->numberBetween(1000, 9999),
                'spesifikasi' => $faker->text(200),
                'bukti' => $faker->text(50),
                'hargaBeli' => $faker->numberBetween(1000, 50000),
                'status' => $faker->randomElement(['Bagus']),
                'sectionAset' => $faker->randomElement(['None']),
            ];
            $this->db->table('tblRincianLabAset')->insert($data);
        }
    }
}

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
                'kodeRincianLabAset' =>  $faker->unique()->uuid,
                'idIdentitasSarana' => $faker->numberBetween(1, 20),
                'idSumberDana' => $faker->numberBetween(1, 2),
                'idKategoriManajemen' => $faker->numberBetween(1, 3),
                'idIdentitasLab' => $faker->numberBetween(1, 3),
                'tahunPengadaan' => $faker->numberBetween(2000, 2022),
                'noSeri' => $faker->word,
                'merk' => $faker->word,
                'warna' => $faker->colorName,
                'nomorBarang' => $faker->numberBetween(1000, 9999),
                'spesifikasi' => $faker->text(200),
                'bukti' => 'https://drive.google.com/file/d/1mmJMU9QSvHS0MGRL9gNU1yw8yLyZlYCy/view?usp=sharing',
                'hargaBeli' => $faker->numberBetween(1000, 50000),
                'status' => $faker->randomElement(['Bagus']),
                'sectionAset' => $faker->randomElement(['None']),
            ];
            $this->db->table('tblRincianLabAset')->insert($data);
        }
    }
}

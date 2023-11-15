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
                'idIdentitasSarana' => random_int(10, 200),
                'idSumberDana' => random_int(1,2),
                'idKategoriManajemen' => random_int(1, 5),
                'idIdentitasLab' => $faker->numberBetween(1, 3),
                'tahunPengadaan' => $faker->date('Y'),
                'noSeri' => $faker->unique()->uuid,
                'merk' => $faker->word,
                'warna' => $faker->colorName,
                'nomorBarang' => random_int(1, 99),
                'spesifikasi' => $faker->sentence(15),
                'bukti' => 'https://drive.google.com/file/d/1mmJMU9QSvHS0MGRL9gNU1yw8yLyZlYCy/view?usp=sharing',
                'hargaBeli' => random_int(1000000, 10000000),
                'status' => $faker->randomElement(['Bagus', 'Rusak', 'Hilang']),
                'sectionAset' => $faker->randomElement(['None']),
            ];
            $this->db->table('tblRincianLabAset')->insert($data);
        }
    }
}

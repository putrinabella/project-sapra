<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RincianAsetSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create();

        for ($i = 1; $i <= 50; $i++) {
            $data = [
                'kodeRincianAset' => $faker->unique()->uuid,
                'idIdentitasSarana' => random_int(1, 10),
                'idSumberDana' => random_int(1,2),
                'idKategoriManajemen' => random_int(1, 5),
                'idIdentitasPrasarana' => random_int(1, 10),
                'tahunPengadaan' => $faker->date('Y'),
                'noSeri' => $faker->word,
                'merk' => $faker->word,
                'warna' => $faker->colorName,
                'nomorBarang' => random_int(1, 10),
                'spesifikasi' => $faker->text,
                'bukti' => 'https://drive.google.com/file/d/1mmJMU9QSvHS0MGRL9gNU1yw8yLyZlYCy/view?usp=sharing',
                'hargaBeli' => random_int(1, 100000),
                'status' => $faker->randomElement(['Bagus', 'Rusak', 'Hilang']),
            ];

            $this->db->table('tblRincianAset')->insert($data);
    }
}
}
<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PeminjamanLabSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');

        for($i = 1; $i <= 20 ; $i++) {
            $startDate = strtotime('2023-09-01');
            $endDate = strtotime('2023-10-30');

            $data = [
                'tanggal' => date('Y-m-d', $faker->numberBetween($startDate, $endDate)),
                'namaPeminjam' => $faker->name,
                'asalPeminjam' => $faker->company,
                'status' => $faker->randomElement(['Peminjaman', 'Pengembalian']),
                'tanggalPengembalian' =>  date('Y-m-d', $faker->numberBetween($startDate, $endDate)),
                'idIdentitasSarana' => $faker->numberBetween(1, 2),
                'kodeLab' => 'LAB001/G01/L01',
                'jumlah' => $faker->numberBetween(1, 100),
                'jumlahBarangDikembalikan' => $faker->numberBetween(0, 100),
                'jumlahBarangRusak' => $faker->numberBetween(0, 10),
                'jumlahBarangHilang' => $faker->numberBetween(0, 10),
                'namaPenerima' => $faker->name,
                'created_at' => \CodeIgniter\I18n\Time::now(),
            ];
            $this->db->table('tblManajemenPeminjaman')->insert($data);
        }
    }
}

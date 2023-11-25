<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PegawaiSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');

        for($i = 1; $i <= 20 ; $i++) {
            $data = [
                'namaPegawai' => $faker->name(),
                'nip' => $faker->unique()->uuid,
                'created_at' => \CodeIgniter\I18n\Time::now(),
            ];
            $this->db->table('tblDataPegawai')->insert($data);
        }
    }
}

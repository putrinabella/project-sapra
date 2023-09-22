<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class IdentitasPrasaranaSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');

        for($i = 1; $i <= 20 ; $i++) {
            $data = [
                'namaPrasarana' => $faker->text(20),
                'luas' => $faker->randomFloat(2, 100, 1000), 
                'idIdentitasGedung' => $faker->numberBetween(1, 3),
                'idIdentitasLantai' => $faker->numberBetween(1, 3),
                'created_at' => \CodeIgniter\I18n\Time::now(),
            ];
            $this->db->table('tblIdentitasPrasarana')->insert($data);
        }
    }
}

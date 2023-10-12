<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DokumenSekolahSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');

        for($i = 1; $i <= 20 ; $i++) {
            $data = [
                'namaDokumenSekolah' => $faker->text(10),
                'linkDokumenSekolah' => $faker->url(),
                'created_at' => \CodeIgniter\I18n\Time::now(),
            ];
            $this->db->table('tblDokumenSekolah')->insert($data);
        }
    }
}

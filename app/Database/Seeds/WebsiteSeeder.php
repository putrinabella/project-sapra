<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class WebsiteSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');

        for($i = 1; $i <= 20 ; $i++) {
            $data = [
                'namaWebsite' => $faker->text(5),
                'fungsiWebsite' => $faker->sentence(),
                'linkWebsite' => $faker->url(),
                'picWebsite' => $faker->name(),
                'created_at' => \CodeIgniter\I18n\Time::now(),
            ];
            $this->db->table('tblWebsite')->insert($data);
        }
    }
}

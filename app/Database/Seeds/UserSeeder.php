<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // single data
        // $data = [
        //     'username' => 'admin',
        //     'password' => password_hash('admin', PASSWORD_BCRYPT),
        //     'nama' => 'Administrator',
        //     'role' => 'super user',
        // ];
        // $this->db->table('tblUser')->insert($data);

        // multiple data
        $data = [
            [
                'username' => 'putri',
                'password' => password_hash('putri', PASSWORD_BCRYPT),
                'nama' => 'Putri',
                'role' => 'super user',
            ],
            [
                'username' => 'nabella',
                'password' => password_hash('nabella', PASSWORD_BCRYPT),
                'nama' => 'Nabella',
                'role' => 'user',
            ]
        ];
        $this->db->table('tblUser')->insertBatch($data);
    }
}

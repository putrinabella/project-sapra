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
                'role' => 'Super Admin',
            ],
            [
                'username' => 'nabella',
                'password' => password_hash('nabella', PASSWORD_BCRYPT),
                'nama' => 'Nabella',
                'role' => 'Laboran',
            ],
            [
                'username' => 'hana',
                'password' => password_hash('nabella', PASSWORD_BCRYPT),
                'nama' => 'Nabella',
                'role' => 'Admin IT',
            ],
            [
                'username' => 'budi',
                'password' => password_hash('nabella', PASSWORD_BCRYPT),
                'nama' => 'Nabella',
                'role' => 'Admin Sarpra',
            ],
            [
                'username' => 'aida',
                'password' => password_hash('nabella', PASSWORD_BCRYPT),
                'nama' => 'Nabella',
                'role' => 'User',
            ],

        ];
        $this->db->table('tblUser')->insertBatch($data);
    }
}

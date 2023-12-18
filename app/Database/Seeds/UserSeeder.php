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
                'username' => 'Super Admin',
                'password' => password_hash('superadmin', PASSWORD_BCRYPT),
                'nama' => 'Putri',
                'role' => 'Super Admin',
            ],
            [
                'username' => 'Laboran',
                'password' => password_hash('laboran', PASSWORD_BCRYPT),
                'nama' => 'Nabella',
                'role' => 'Laboran',
            ],
            [
                'username' => 'Admin IT',
                'password' => password_hash('adminit', PASSWORD_BCRYPT),
                'nama' => 'Adha',
                'role' => 'Admin IT',
            ],
            [
                'username' => 'Admin Sarpra',
                'password' => password_hash('adminsarpra', PASSWORD_BCRYPT),
                'nama' => 'Pratama',
                'role' => 'Admin Sarpra',
            ],
        ];
        $this->db->table('tblUser')->insertBatch($data);
    }
}

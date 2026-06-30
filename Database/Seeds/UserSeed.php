<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeed extends Seeder
{
    public function run()
    {
        // Cara bikin seed
        // php spark make:seeder [nama seednya]

        // hanya contoh 1 data
        // $data = [
        //     'nama_user'     => 'Alfharizky Fauzi',
        //     'email_user'    => 'alfharizkyfauzi@staff.gunadarma.ac.id',
        //     'password_user' => password_hash('lpmug2023', PASSWORD_BCRYPT),
        // ];
        // $this->db->table('users')->insert($data);

        // hanya contoh multi data
        $data = [
            [
                'nama_user'     => 'Alfharizky Fauzi',
                'email_user'    => 'alfharizkyfauzi@staff.gunadarma.ac.id',
                'password_user' => password_hash('lpmug2023', PASSWORD_BCRYPT),
            ],
            [
                'nama_user'     => 'Alfharizky Fauzi',
                'email_user'    => 'alfharizkyfauzi@staff.gunadarma.ac.id',
                'password_user' => password_hash('lpmug2023', PASSWORD_BCRYPT),
            ]
        ];

        $this->db->table('users')->insertBatch($data);
        
        // Setelah bikin seed Cara jalanin seednya 
        // php spark db:seed [nama seednya]
    }
}

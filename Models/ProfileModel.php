<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfileModel extends Model
{
    protected $table            = 'tbl_users';
    protected $primaryKey       = 'user_id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = [
        'user_name', 
        'nidn',
        'kontak',
        'email',
        'password',
        'gelar_dpn',
        'gelar_blkng',
        'sinta_id',
        'universitas_id',
        'fungsional_id',
        'jurusan_id',
        'kota_id',
        'alamat',
    ];
    protected $useTimestamps    = true;

    protected $validationRules  = [
        'password' => [
            'rules' => 'required|min_length[6]',
            'errors'=> [
                'required'   => 'Password tidak boleh kosong.',
                'min_length' => 'Password harus 6 karakter.'
            ],
        ],
        'password_konfirmasi' => [
            'rules' => 'required|min_length[6]|matches[password]',
            'errors'=> [
                'required'   => 'Password Konfirmasi tidak boleh kosong.',
                'min_length' => 'Password harus 6 karakter.',
                'matches'    => 'Password konfirmasi harus sama dengan dengan password.'
            ],
        ],
    ];

    function get_id_user($id)
    {
        $this->db->where('user_id', $id);
        return $this->db->get('tbl_users')->row();
    }
}

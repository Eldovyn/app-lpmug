<?php

namespace App\Models;

use CodeIgniter\Model;

class DosenModel extends Model
{
    protected $table            = 'tbl_users';
    protected $primaryKey       = 'user_id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'user_name', 
        'gelar_dpn', 
        'gelar_blkng', 
        'nidn',
        'sinta_id',
        'password',
        'kontak',
        'email',
        'status',
        'role_id',
        'jurusan_id',
        'fungsional_id',
    ];
    protected $useTimestamps    = true;


    protected $validationRules  = [
        'user_id' => [
            'rules' => 'permit_empty',
        ],
        'user_name' => [
            'rules' => 'required|min_length[3]',
            'errors'=> [
                'required' => 'Nama user tidak boleh kosong.',
                'min_length' => 'Nama minimal 3 huruf'
            ],
        ],
       'sinta_id' => [
            'rules' => 'required|is_unique[tbl_users.sinta_id,user_id,{user_id}]',
            'errors'=> [
                'required' => 'SINTA ID tidak boleh kosong.',
                'is_unique' => 'SINTA ID sudah terdaftar, silakan masukan SINTA ID yang berbeda.'
            ],
        ],
        'nidn' => [
            'rules' => 'required|is_unique[tbl_users.nidn,user_id,{user_id}]|min_length[10]',
            'errors'=> [
                'required' => 'NIDN tidak boleh kosong.',
                'is_unique' => 'NIDN sudah terdaftar, silakan masukan NIDN yang berbeda.',
                'min_length' => 'NIDN harus minimal 10 karakter.'
            ],
        ],

        'password' => [
            'rules' => 'required',
            'errors'=> [
                'required' => 'Password tidak boleh kosong.'
            ],
        ],
        'kontak' => [
            'rules' => 'permit_empty|numeric',
            'errors'=> [
                'required' => 'Kontak tidak boleh kosong.',
                'numeric' => 'Kontak harus berupa angka.'
            ],
        ],
        'email' => [
            'rules' => 'permit_empty|valid_email|is_unique[tbl_users.email,user_id,{user_id}]',
            'errors'=> [
                'required' => 'Email tidak boleh kosong.',
                'valid_email' => 'Format email tidak valid.',
                'is_unique' => 'Email sudah terdaftar.'
            ],
        ],
        'jurusan_id' => [
            'rules' => 'required',
            'errors'=> [
                'required' => 'Bidang Ilmu wajib dipilih.'
            ],
        ],

    ];

    function getAll() {
        $builder = $this->builder();
        $builder->select('tbl_users.user_id, tbl_users.user_name, tbl_users.gelar_dpn, tbl_users.gelar_blkng, tbl_users.nidn');
        $builder->join('tbl_laporan', 'tbl_laporan.ketua_id = tbl_users.user_id', 'left');
        $builder->join('tbl_tags', 'tbl_users.user_id = tbl_tags.anggota_id', 'left');
        $query   = $builder->get();
        return $query->getResult();
    }

    function getDosen() {
        $builder = $this->builder()->where('role_id', 4);
        $builder->select('tbl_users.user_id, tbl_users.user_name, tbl_users.gelar_dpn, tbl_users.gelar_blkng, tbl_users.nidn, tbl_users.sinta_id, tbl_users.kontak, tbl_users.email, tbl_users.status, tbl_users.role_id, tbl_jurusan.jurusan_name, tbl_fakultas.fakultas_name');
        $builder->join('tbl_jurusan', 'tbl_jurusan.jurusan_id = tbl_users.jurusan_id', 'left');
        $builder->join('tbl_fakultas', 'tbl_fakultas.fakultas_id = tbl_jurusan.fakultas_id', 'left');
        $query   = $builder->get();
        return $query->getResult();
    }

    function getPaginated($num, $keyword = null) {
        $builder = $this->builder()->where('tbl_users.role_id', 4)->orderBy('user_name', 'ASC');
        $builder->select('tbl_users.user_id, tbl_users.user_name, tbl_users.gelar_dpn, tbl_users.gelar_blkng, tbl_users.nidn, tbl_users.sinta_id, tbl_users.kontak, tbl_users.email, tbl_users.status, tbl_users.role_id, tbl_role.role_name, tbl_jurusan.jurusan_name, tbl_fakultas.fakultas_name');
        $builder->join('tbl_role', 'tbl_role.role_id = tbl_users.role_id');
        $builder->join('tbl_jurusan', 'tbl_jurusan.jurusan_id = tbl_users.jurusan_id', 'left');
        $builder->join('tbl_fakultas', 'tbl_fakultas.fakultas_id = tbl_jurusan.fakultas_id', 'left');
        // $builder->join('tbl_users', 'tbl_users.user_id = tbl_tags.anggota_id');
        if($keyword != '') {
            $builder->like('user_name', $keyword);
            $builder->orLike('gelar_dpn', $keyword);
            $builder->orLike('nidn', $keyword);
            $builder->orLike('sinta_id', $keyword);
            $builder->orLike('role_name', $keyword);
            $builder->orLike('jurusan_name', $keyword);
            $builder->orLike('fakultas_name', $keyword);
        }
        return [
            'title_tab' => 'Dosen &mdash; LPM UG',
            'title'     => 'Dosen',
            'dosen'     => $this->paginate($num),
            'pager'     => $this->pager,
        ];
    }
}

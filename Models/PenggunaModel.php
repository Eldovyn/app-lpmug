<?php

namespace App\Models;

use CodeIgniter\Model;

class PenggunaModel extends Model
{
    protected $table            = 'tbl_users';
    protected $primaryKey       = 'user_id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'user_name', 
        'nidn',
        'sinta_id',
        'password',
        'kontak',
        'email',
        'status',
        'role_id',
    ];
    protected $useTimestamps    = true;

    function getPaginated($num, $keyword = null) {
        $builder = $this->builder();
        $builder->select('tbl_users.*, tbl_role.role_name, tbl_jurusan.jurusan_name, tbl_fakultas.fakultas_name');
        $builder->join('tbl_role', 'tbl_role.role_id = tbl_users.role_id');
        $builder->join('tbl_jurusan', 'tbl_jurusan.jurusan_id = tbl_users.jurusan_id', 'left');
        $builder->join('tbl_fakultas', 'tbl_fakultas.fakultas_id = tbl_jurusan.fakultas_id', 'left');
        if($keyword != '') {
            $builder->like('tbl_users.user_name', $keyword);
            $builder->orLike('tbl_role.role_name', $keyword);
        }
        return [
            'title_tab' => 'Pengguna &mdash; LPM UG',
            'title'     => 'Pengguna',
            'pengguna'   => $this->paginate($num),
            'pager'     => $this->pager,
        ];
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class HakAksesModel extends Model
{
    protected $table            = 'tbl_role';
    protected $primaryKey       = 'role_id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['role_name', 'role_deskripsi'];
    protected $useTimestamps    = true;

    function getPaginated($num, $keyword = null) {
        $builder = $this->builder();
        if($keyword != '') {
            $builder->like('role_name', $keyword);
        }
        return [
            'title_tab'     => 'Hak Akses &mdash; LPM UG',
            'title'         => 'Hak Akses',
            'hak_akses'     => $this->paginate($num),
            'pager'         => $this->pager,
        ];
    }
}

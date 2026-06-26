<?php

namespace App\Models;

use CodeIgniter\Model;

class LuaranModel extends Model
{
    protected $table            = 'tbl_luaran';
    protected $primaryKey       = 'luaran_id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['luaran_name'];
    protected $useTimestamps    = true;

    function getPaginated($num, $keyword = null) {
        $builder = $this->builder();
        if($keyword != '') {
            $builder->like('luaran_name', $keyword);
        }
        return [
            'title_tab'     => 'Luaran Penelitian &mdash; LPM UG',
            'title'         => 'Luaran Penelitian',
            'luaran'         => $this->paginate($num),
            'pager'         => $this->pager,
        ];
    }
}

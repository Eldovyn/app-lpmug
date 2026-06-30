<?php

namespace App\Models;

use CodeIgniter\Model;

class ProvinsiModel extends Model
{
    protected $table            = 'tbl_provinsi';
    protected $primaryKey       = 'provinsi_id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['provinsi_name'];
    protected $useTimestamps    = true;

    function getPaginated($num, $keyword = null) {
        $builder = $this->builder()->orderBy('provinsi_name', 'ASC');
        if($keyword != '') {
            $builder->like('provinsi_name', $keyword);
        }
        return [
            'title_tab'     => 'Provinsi &mdash; LPM UG',
            'title'         => 'Provinsi',
            'provinsi'      => $this->paginate($num),
            'pager'         => $this->pager,
        ];
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class FakultasModel extends Model
{
    protected $table            = 'tbl_fakultas';
    protected $primaryKey       = 'fakultas_id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['fakultas_name'];
    protected $useTimestamps    = true;

    function getPaginated($num, $keyword = null) {
        $builder = $this->builder();
        if($keyword != '') {
            $builder->like('fakultas_name', $keyword);
        }
        return [
            'title_tab'     => 'Fakultas &mdash; LPM UG',
            'title'         => 'Fakultas',
            'fakultas'      => $this->paginate($num),
            'pager'         => $this->pager,
        ];
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class FungsionalModel extends Model
{
    protected $table            = 'tbl_fungsional';
    protected $primaryKey       = 'fungsional_id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['fungsional_name'];
    protected $useTimestamps    = true;

    function getPaginated($num, $keyword = null) {
        $builder = $this->builder();
        if($keyword != '') {
            $builder->like('fungsional_name', $keyword);
        }
        return [
            'title_tab'     => 'Jabatan Fungsional &mdash; LPM UG',
            'title'         => 'Jabatan Fungsional',
            'fungsional'    => $this->paginate($num),
            'pager'         => $this->pager,
        ];
    }
}

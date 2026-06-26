<?php

namespace App\Models;

use CodeIgniter\Model;

class TopikModel extends Model
{
    protected $table            = 'tbl_topik';
    protected $primaryKey       = 'topik_id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['topik_name'];
    protected $useTimestamps    = true;

    function getPaginated($num, $keyword = null) {
        $builder = $this->builder();
        if($keyword != '') {
            $builder->like('topik_name', $keyword);
        }
        return [
            'title_tab'     => 'Topik Penelitian &mdash; LPM UG',
            'title'         => 'Topik Penelitian',
            'topik'         => $this->paginate($num),
            'pager'         => $this->pager,
        ];
    }
}

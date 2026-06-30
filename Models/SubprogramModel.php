<?php

namespace App\Models;

use CodeIgniter\Model;

class SubprogramModel extends Model
{
    protected $table            = 'tbl_subprogram';
    protected $primaryKey       = 'subprogram_id';
    protected $returnType       = 'object';
    // protected $useSoftDeletes   = true;
    protected $allowedFields    = ['subprogram_name', 'program_id'];
    protected $useTimestamps    = true;

    function getAll() {
        $builder = $this->builder();
        $builder->join('tbl_program', 'tbl_program.program_id = tbl_subprogram.program_id');
        $builder->join('tbl_topik', 'tbl_topik.topik_id = tbl_program.topik_id');
        $query   = $builder->get();
        return $query->getResult();
    }


    function getPaginated($num, $keyword = null) {
        $builder = $this->builder();
        $builder->join('tbl_program', 'tbl_program.program_id = tbl_subprogram.program_id');
        $builder->join('tbl_topik', 'tbl_topik.topik_id = tbl_program.topik_id');
        if($keyword != '') {
            $builder->like('subprogram_name', $keyword);
            $builder->orLike('program_name', $keyword);
            $builder->orLike('topik_name', $keyword);
        }
        return [
            'title_tab'    => 'Sub program &mdash; LPM UG',
            'title'        => 'Sub program',
            'subprogram'   => $this->paginate($num),
            'pager'        => $this->pager,
        ];
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class ProgramModel extends Model
{
    protected $table            = 'tbl_program';
    protected $primaryKey       = 'program_id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['program_name', 'topik_id'];
    protected $useTimestamps    = true;

    function getAll() {
        $builder = $this->builder();
        $builder->join('tbl_topik', 'tbl_topik.topik_id = tbl_program.topik_id');
        $query   = $builder->get();
        return $query->getResult();
    }

    function getPaginated($num, $keyword = null) {
        $builder = $this->builder();
        $builder->join('tbl_topik', 'tbl_topik.topik_id = tbl_program.topik_id');
        if($keyword != '') {
            $builder->like('program_name', $keyword);
            $builder->orLike('topik_name', $keyword);
        }
        return [
            'title_tab' => 'program &mdash; LPM UG',
            'title'     => 'program',
            'program'   => $this->paginate($num),
            'pager'     => $this->pager,
        ];
    }
}

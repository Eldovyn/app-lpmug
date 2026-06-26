<?php

namespace App\Models;

use CodeIgniter\Model;

class JurusanModel extends Model
{
    protected $table            = 'tbl_jurusan';
    protected $primaryKey       = 'jurusan_id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['jurusan_name', 'fakultas_id'];
    protected $useTimestamps    = true;

    function getAll() {
        $builder = $this->builder();
        $builder->join('tbl_fakultas', 'tbl_fakultas.fakultas_id = tbl_jurusan.fakultas_id');
        $query   = $builder->get();
        return $query->getResult();
    }

    function getPaginated($num, $keyword = null) {
        $builder = $this->builder();
        $builder->join('tbl_fakultas', 'tbl_fakultas.fakultas_id = tbl_jurusan.fakultas_id');
        if($keyword != '') {
            $builder->like('jurusan_name', $keyword);
            $builder->orLike('fakultas_name', $keyword);
        }
        return [
            'title_tab' => 'Jurusan &mdash; LPM UG',
            'title'     => 'Jurusan',
            'jurusan'   => $this->paginate($num),
            'pager'     => $this->pager,
        ];
    }
}

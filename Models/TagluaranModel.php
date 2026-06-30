<?php

namespace App\Models;

use CodeIgniter\Model;

class TagluaranModel extends Model
{
    protected $table            = 'tbl_tag_luaran';
    protected $primaryKey       = 'tag_luaran_id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'laporan_id',
        'luaran_id',
    ];
    protected $useTimestamps    = true;

    function getAll() {
        $builder = $this->builder();
        $builder->join('tbl_laporan', 'tbl_laporan.laporan_id = tbl_tag_luaran.laporan_id');
        $query   = $builder->get();
        return $query->getResult();
    }

    function getTagLuaran() {
        $builder = $this->builder();
        $builder->join('tbl_laporan', 'tbl_laporan.laporan_id = tbl_tag_luaran.laporan_id');
        $query   = $builder->get();
        return $query->getResult();
    }

    function getLuaran() {
        $builder = $this->builder();
        $builder->join('tbl_luaran', 'tbl_luaran.luaran_id = tbl_tag_luaran.luaran_id');
        $query   = $builder->get();
        return $query->getResult();
    }

    function getPaginated($num, $keyword = null) {
        $builder = $this->builder()->orderBy('tag_luaran_id', 'DESC');
        $builder->join('tbl_laporan', 'tbl_laporan.laporan_id = tbl_tag_luaran.laporan_id');
        $builder->join('tbl_luaran', 'tbl_luaran.luaran_id = tbl_tag_luaran.luaran_id');
        if($keyword != '') {
            $builder->like('tag_luaran_id', $keyword);
        }
        return [
            'title_tab'     => 'Laporan &mdash; LPM UG',
            'title'         => 'Laporan',
            'tags'          => $this->paginate($num),
            'pager'         => $this->pager,
        ];
    }
}

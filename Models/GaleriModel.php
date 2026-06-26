<?php

namespace App\Models;

use CodeIgniter\Model;

class GaleriModel extends Model
{
    protected $table            = 'tbl_galeri';
    protected $primaryKey       = 'galeri_id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['judul', 'deskripsi', 'gambar'];
    protected $useTimestamps    = true;

    function getGaleri() {
        $builder = $this->db->table('tbl_galeri')->orderBy('galeri_id', 'DESC')->limit(8);
        $query   = $builder->get();
        return $query->getResult();
    }

    function getPaginated($num, $keyword = null) {
        $builder = $this->builder();
        if($keyword != '') {
            $builder->like('judul', $keyword);
        }
        return [
            'title_tab'     => 'Galeri &mdash; LPM UG',
            'title'         => 'Galeri LPM UG',
            'galeri'        => $this->paginate($num),
            'pager'         => $this->pager,
        ];
    }
}

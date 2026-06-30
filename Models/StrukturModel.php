<?php

namespace App\Models;

use CodeIgniter\Model;

class StrukturModel extends Model
{
    protected $table            = 'tbl_struktur';
    protected $primaryKey       = 'struktur_id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['judul', 'deskripsi', 'gambar'];
    protected $useTimestamps    = true;

    function getPaginated($num, $keyword = null) {
        $builder = $this->builder();
        if($keyword != '') {
            $builder->like('judul', $keyword);
        }
        return [
            'title_tab'     => 'Struktur &mdash; LPM UG',
            'title'         => 'Struktur LPM UG',
            'struktur'      => $this->paginate($num),
            'pager'         => $this->pager,
        ];
    }
}

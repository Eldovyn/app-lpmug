<?php

namespace App\Models;

use CodeIgniter\Model;

class KontakModel extends Model
{
    protected $table            = 'tbl_kontak';
    protected $primaryKey       = 'kontak_id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'kontak_name',
        'kontak',
        'keterangan',
    ];
    protected $useTimestamps    = true;

    function getPaginated($num, $keyword = null) {
        $builder = $this->builder();
        if($keyword != '') {
            $builder->like('kontak_name', $keyword);
        }
        return [
            'title_tab'     => 'Kontak personal &mdash; LPM UG',
            'title'         => 'kontak personal',
            'kontak'         => $this->paginate($num),
            'pager'         => $this->pager,
        ];
    }
}

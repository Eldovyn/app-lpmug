<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfilestaffModel extends Model
{
    protected $table            = 'tbl_profilestaff';
    protected $primaryKey       = 'profilestaff_id';
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
            'title_tab'     => 'Profile Staff &mdash; LPM UG',
            'title'         => 'Profile Staff',
            'profilestaff'  => $this->paginate($num),
            'pager'         => $this->pager,
        ];
    }
}

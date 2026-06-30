<?php

namespace App\Models;

use CodeIgniter\Model;

class KotaModel extends Model
{
    protected $table            = 'tbl_kota';
    protected $primaryKey       = 'kota_id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['kota_name', 'provinsi_id'];
    protected $useTimestamps = true;

    function getAll() {
        $builder = $this->db->table('tbl_kota');
        $builder->join('tbl_provinsi', 'tbl_provinsi.provinsi_id = tbl_kota.provinsi_id');
        $query   = $builder->get();
        return $query->getResult();
    }

    function getPaginated($num, $keyword = null) {
        $builder = $this->builder();
        $builder->join('tbl_provinsi', 'tbl_provinsi.provinsi_id = tbl_kota.provinsi_id');
        if($keyword != '') {
            $builder->like('kota_name', $keyword);
            $builder->orLike('provinsi_name', $keyword);
        }
        return [
            'title_tab' => 'Kota &mdash; LPM UG',
            'title'     => 'Kota',
            'kota'      => $this->paginate($num),
            'pager'     => $this->pager,
        ];
    }
}

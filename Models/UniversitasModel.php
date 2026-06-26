<?php

namespace App\Models;

use CodeIgniter\Model;

class UniversitasModel extends Model
{
    protected $table            = 'tbl_universitas';
    protected $primaryKey       = 'universitas_id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['universitas_name', 'kontak', 'alamat'];
    protected $useTimestamps    = true;

    function getPaginated($num, $keyword = null) {
        $builder = $this->builder();
        if($keyword != '') {
            $builder->like('universitas_name', $keyword);
        }
        return [
            'title_tab'     => 'Universitas / Instansi &mdash; LPM UG',
            'title'         => 'Universitas / Instansi',
            'universitas'   => $this->paginate($num),
            'pager'         => $this->pager,
        ];
    }
}

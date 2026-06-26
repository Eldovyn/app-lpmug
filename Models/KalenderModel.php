<?php

namespace App\Models;

use CodeIgniter\Model;

class KalenderModel extends Model
{
    protected $table            = 'tbl_kalender';
    protected $primaryKey       = 'kalender_id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'kegiatan',
        'waktu',
        'keterangan',
    ];
    protected $useTimestamps    = true;

    function getPaginated($num, $keyword = null) {
        $builder = $this->builder();
        if($keyword != '') {
            $builder->like('kalender_name', $keyword);
        }
        return [
            'title_tab'     => 'Kelender Pengabdian &mdash; LPM UG',
            'title'         => 'Kalender Pengabdian',
            'kalender'         => $this->paginate($num),
            'pager'         => $this->pager,
        ];
    }
}

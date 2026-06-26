<?php

namespace App\Models;

use CodeIgniter\Model;

class PeriodeModel extends Model
{
    protected $table            = 'tbl_periode';
    protected $primaryKey       = 'periode_id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['periode_name', 'tahun_ajaran', 'info', 'status'];
    protected $useTimestamps    = true;

    function getPaginated($num, $keyword = null)
    {
        $builder = $this->builder();
        if ($keyword != '') {
            $builder->like('periode_name', $keyword);
            $builder->orLike('tahun_ajaran', $keyword);
            $builder->orLike('info', $keyword);
            $builder->orLike('status', $keyword);
        }
        return [
            'title_tab' => 'Periode &mdash; LPM UG',
            'title'     => 'Periode',
            'periode'      => $this->paginate($num),
            'pager'     => $this->pager,
        ];
    }

    // Get active periode (status = 1)
    public function getActivePeriode()
    {
        return $this->where('status', 1)->first();
    }

    // Get all periodes ordered by status and name
    public function getAllPeriodes()
    {
        return $this->orderBy('status DESC, periode_name DESC')->findAll();
    }
    public function getLaporanPerPeriode()
    {
        $db = \Config\Database::connect();
        return $db->table('tbl_laporan l')
            ->select('p.periode_name, COUNT(*) as total_laporan')
            ->join('tbl_periode p', 'p.periode_id = l.periode_id')
            ->groupBy('p.periode_name')
            ->orderBy('p.periode_name', 'ASC')
            ->get()
            ->getResult();
    }
}

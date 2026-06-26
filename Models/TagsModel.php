<?php

namespace App\Models;

use CodeIgniter\Model;

class TagsModel extends Model
{
    protected $table            = 'tbl_tags';
    protected $primaryKey       = 'tags_id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'laporan_id',
        'anggota_id',
    ];
    protected $useTimestamps    = true;

    function getAll() {
        $builder = $this->builder();
        $builder->select('tbl_tags.tags_id, tbl_tags.laporan_id, tbl_tags.anggota_id, tbl_laporan.judul');
        $builder->join('tbl_laporan', 'tbl_laporan.laporan_id = tbl_tags.laporan_id');
        $query   = $builder->get();
        return $query->getResult();
    }

    function getTags() {
        return $this->getAll();
    }

    function getAnggota() {
        $builder = $this->builder();
        $builder->select('tbl_tags.tags_id, tbl_tags.laporan_id, tbl_tags.anggota_id, tbl_users.user_id, tbl_users.user_name, tbl_users.gelar_dpn, tbl_users.gelar_blkng, tbl_users.nidn, tbl_users.sinta_id');
        $builder->join('tbl_users', 'tbl_users.user_id = tbl_tags.anggota_id');
        $query   = $builder->get();
        return $query->getResult();
    }

    function getPaginated($num, $keyword = null) {
        $builder = $this->builder()->orderBy('tags_id', 'DESC');
        $builder->select('tbl_tags.tags_id, tbl_tags.laporan_id, tbl_tags.anggota_id, tbl_laporan.judul, tbl_users.user_id, tbl_users.user_name, tbl_users.gelar_dpn, tbl_users.gelar_blkng, tbl_users.sinta_id');
        $builder->join('tbl_laporan', 'tbl_laporan.laporan_id = tbl_tags.laporan_id');
        $builder->join('tbl_users', 'tbl_users.user_id = tbl_tags.anggota_id');
        if($keyword != '') {
            $builder->like('tags_id', $keyword);
        }
        return [
            'title_tab'     => 'Laporan &mdash; LPM UG',
            'title'         => 'Laporan',
            'tags'          => $this->paginate($num),
            'pager'         => $this->pager,
        ];
    }

    public function countGroupsByAnggotaPerPeriode($anggota_id, $periode_id)
    {
        return $this->db->table('tbl_tags')
            ->select('COUNT(DISTINCT tbl_tags.laporan_id) AS total')
            ->join('tbl_laporan', 'tbl_laporan.laporan_id = tbl_tags.laporan_id', 'left')
            ->where('tbl_tags.anggota_id', $anggota_id)
            ->where('tbl_laporan.periode_id', $periode_id)
            ->where('tbl_laporan.verifikasi !=', -1) // optional, exclude laporan yang dihapus
            ->get()
            ->getRow()
            ->total;
    }

    public function getCountsByAnggotaListPerPeriode(array $anggotaIds, $periodeId)
    {
        if (empty($anggotaIds)) {
            return [];
        }

        $results = $this->db->table('tbl_tags')
            ->select('tbl_tags.anggota_id, COUNT(DISTINCT tbl_tags.laporan_id) AS total')
            ->join('tbl_laporan', 'tbl_laporan.laporan_id = tbl_tags.laporan_id', 'left')
            ->whereIn('tbl_tags.anggota_id', $anggotaIds)
            ->where('tbl_laporan.periode_id', $periodeId)
            ->where('tbl_laporan.verifikasi !=', -1)
            ->groupBy('tbl_tags.anggota_id')
            ->get()
            ->getResultArray();

        $counts = [];
        foreach ($results as $row) {
            $counts[$row['anggota_id']] = (int) $row['total'];
        }
        return $counts;
    }

    function countGroupsByAnggotaExcludingLaporan($anggota_id, $exclude_laporan_id) {
        $builder = $this->builder();
        $builder->where('anggota_id', $anggota_id);
        $builder->where('laporan_id !=', $exclude_laporan_id);
        return $builder->countAllResults();
    }
}

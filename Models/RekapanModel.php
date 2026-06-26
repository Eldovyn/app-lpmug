<?php

namespace App\Models;

use CodeIgniter\Model;

class RekapanModel extends Model
{
    protected $table            = 'tbl_laporan';
    protected $primaryKey       = 'laporan_id';
    protected $returnType       = 'object';
    // protected $useSoftDeletes   = true;
    protected $allowedFields    = [
        'verifikasi',
        'revisi'
    ];
    protected $useTimestamps    = true;

    function getMitra() {
        $builder = $this->builder();
        $builder->select('tbl_laporan.laporan_id, tbl_laporan.mitra_id, tbl_users.user_id, tbl_users.user_name, tbl_users.user_name as mitra_name, tbl_users.email, tbl_users.kontak, tbl_users.alamat');
        $builder->join('tbl_users', 'tbl_users.user_id = tbl_laporan.mitra_id');
        $query   = $builder->get();
        return $query->getResult();
    }
    
    function getPeriode() {
        $builder = $this->builder();
        $builder->select('tbl_laporan.laporan_id, tbl_laporan.periode_id, tbl_periode.periode_name, tbl_periode.tahun_ajaran');
        $builder->join('tbl_periode', 'tbl_periode.periode_id = tbl_laporan.periode_id');
        $query   = $builder->get();
        return $query->getResult();
    }

    function getAnggota($laporan_id = null) {
        $builder = $this->builder();
        $builder->select('tbl_laporan.laporan_id, tbl_tags.anggota_id, tbl_users.user_id, tbl_users.user_name, tbl_users.user_name as anggota_name, tbl_users.gelar_dpn, tbl_users.gelar_blkng, tbl_users.nidn, tbl_users.sinta_id');
        $builder->join('tbl_tags', 'tbl_tags.laporan_id = tbl_laporan.laporan_id', 'left');
        $builder->join('tbl_users', 'tbl_users.user_id = tbl_tags.anggota_id', 'left');
        if ($laporan_id) {
            $builder->where('tbl_laporan.laporan_id', $laporan_id); // Batasi berdasarkan laporan_id
        }
        $builder->limit(100); // Batasi hasil maksimal
        $query = $builder->get();
        return $query->getResult();
    }

    function getPaginated($num = 10, $keyword = null, $status = null) {
        $builder = $this->builder()->orderBy('tbl_laporan.laporan_id', 'DESC');
        $builder->select('tbl_laporan.*, tbl_users.user_name as ketua_name, tbl_users.gelar_dpn, tbl_users.gelar_blkng, tbl_bidang_ilmu.nama as bidang_ilmu_name');
        $builder->join('tbl_users', 'tbl_users.user_id = tbl_laporan.ketua_id');
        $builder->join('tbl_bidang_ilmu', 'tbl_bidang_ilmu.id = tbl_laporan.bidang_ilmu_id', 'left');
        if($keyword) {
            $builder->groupStart()
                ->like('tbl_users.user_name', $keyword)
                ->orLike('tbl_bidang_ilmu.nama', $keyword)
                ->orLike('tbl_laporan.tipe_kegiatan', $keyword)
                ->groupEnd();
        }
        if ($status !== null) {
            $builder->where('tbl_laporan.verifikasi', $status);
        }
        return [
            'title_tab' => 'Rekapan Abdimas &mdash; LPM UG',
            'title'     => 'Rekapan Abdimas',
            'abdimas'   => $this->paginate($num),
            'pager'     => $this->pager,
        ];
    }

}

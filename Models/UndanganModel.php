<?php

namespace App\Models;

use CodeIgniter\Model;

class UndanganModel extends Model
{
    protected $table            = 'tbl_laporan';
    protected $primaryKey       = 'laporan_id';
    protected $returnType       = 'object';
    // protected $useSoftDeletes   = true;
    protected $allowedFields    = [
        'ketua_id',
        'mitra_id',
        'subprogram_id',
        'luaran_id',
        'periode_id',
        'tipe_kegiatan',
        'range_dana',
        'verifikasi',
        'proposal',
        'laporan',
        'bukti_kegiatan',
        'link_luaran',
    ];
    protected $useTimestamps    = true;

    function getAll() {
        $builder = $this->builder();
        $builder->join('tbl_tags', 'tbl_tags.laporan_id = tbl_laporan.laporan_id');
        $builder->join('tbl_users', 'tbl_users.user_id = tbl_laporan.ketua_id');
        $query   = $builder->get();
        return $query->getResult();
    }

    function getMitra() {
        $builder = $this->builder();
        $builder->join('tbl_users', 'tbl_users.user_id = tbl_laporan.mitra_id');
        $query   = $builder->get();
        return $query->getResult();
    }

    function getAnggota() {
        $builder = $this->builder();
        $builder->join('tbl_tags', 'tbl_tags.laporan_id = tbl_laporan.laporan_id');
        $builder->join('tbl_users', 'tbl_users.user_id = tbl_tags.anggota_id');
        $query   = $builder->get();
        return $query->getResult();
    }

    function getPaginated($num, $keyword = null) {
        $userLogin = userLogin()->user_id;
        $builder = $this->builder()->orderBy('tbl_laporan.laporan_id', 'DESC');
        $builder->join('tbl_tags', 'tbl_tags.laporan_id = tbl_laporan.laporan_id')->where('anggota_id', $userLogin);
        $builder->join('tbl_users', 'tbl_users.user_id = tbl_laporan.ketua_id');
        if($keyword != '') {
            $builder->like('ketua_id', $keyword);
        }
        return [
            'title_tab'     => 'Abdimas &mdash; LPM UG',
            'title'         => 'Abdimas',
            'abdimas'       => $this->paginate($num),
            'pager'         => $this->pager,
        ];
    }
}

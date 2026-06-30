<?php

namespace App\Models;

use CodeIgniter\Model;

class DokumenMitraModel extends Model
{
    protected $table = 'tbl_dokumen_mitra';
    protected $primaryKey = 'dokumen_id';
    protected $allowedFields = [
        'mitra_id',
        'periode_id',
        'doc_type',
        'nomor_surat',
        'file_path',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // biar gampang ambil data join sama user dan periode
    public function getDokumenWithRelasi($mitra_id = null)
    {
        $builder = $this->select('tbl_dokumen_mitra.*, tbl_users.user_name, tbl_periode.periode_name')
            ->join('tbl_users', 'tbl_users.user_id = tbl_dokumen_mitra.mitra_id')
            ->join('tbl_periode', 'tbl_periode.periode_id = tbl_dokumen_mitra.periode_id');

        if ($mitra_id) {
            $builder->where('tbl_dokumen_mitra.mitra_id', $mitra_id);
        }

        return $builder->findAll();
    }

    public function getDokumenByMitra($mitra_id)
    {
        return $this->select('tbl_dokumen_mitra.*, tbl_periode.periode_name, tbl_periode.tahun_ajaran')
            ->join('tbl_periode', 'tbl_periode.periode_id = tbl_dokumen_mitra.periode_id')
            ->where('tbl_dokumen_mitra.mitra_id', $mitra_id)
            ->asObject()
            ->findAll();
    }

    public function getDokumenById($id)
    {
        return $this->find($id);
    }

    public function getDokumenByMitraAndType($mitra_id, $doc_type, $periode_id = null)
    {
        $builder = $this->where('mitra_id', $mitra_id)
                        ->where('doc_type', $doc_type);

        if ($periode_id) {
            $builder->where('periode_id', $periode_id);
        }

        return $builder->first();
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class RekapDosenModel extends Model
{
    protected $table            = 'tbl_rekapdosen';
    protected $primaryKey       = 'laporan_id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'nama_dosen',
        'bidang_ilmu',
        'mitra',
        'surat_tugas',
        'tanggal_tugas',
        'surat_permohonan',
        'tanggal_permohonan',
        'surat_keterangan',
        'tanggal_keterangan',
        'kegiatan'
    ];
    protected $useTimestamps    = false;

    /**
     * Get all rekap dosen records
     */
    public function getAll()
    {
        return $this->findAll();
    }

    /**
     * Get rekap dosen by ID
     */
    public function getById($id)
    {
        return $this->find($id);
    }

    /**
     * Insert new rekap dosen record
     */
    public function insertRekapDosen($data)
    {
        return $this->insert($data);
    }

    /**
     * Update rekap dosen record
     */
    public function updateRekapDosen($id, $data)
    {
        return $this->update($id, $data);
    }

    /**
     * Delete rekap dosen record
     */
    public function deleteRekapDosen($id)
    {
        return $this->delete($id);
    }

    /**
     * Get rekap dosen with pagination
     */
    public function getPaginated($perPage = 10, $keyword = null)
    {
        $builder = $this->builder();

        if ($keyword) {
            $builder->groupStart()
                    ->like('nama_dosen', $keyword)
                    ->orLike('bidang_ilmu', $keyword)
                    ->orLike('mitra', $keyword)
                    ->orLike('kegiatan', $keyword)
                    ->groupEnd();
        }

        return [
            'rekap_dosen' => $this->paginate($perPage),
            'pager'       => $this->pager,
        ];
    }

    /**
     * Search rekap dosen by keyword
     */
    public function search($keyword)
    {
        return $this->groupStart()
                   ->like('nama_dosen', $keyword)
                   ->orLike('bidang_ilmu', $keyword)
                   ->orLike('mitra', $keyword)
                   ->orLike('kegiatan', $keyword)
                   ->groupEnd()
                   ->findAll();
    }

    /**
     * Get rekap dosen by bidang ilmu
     */
    public function getByBidangIlmu($bidangIlmu)
    {
        return $this->where('bidang_ilmu', $bidangIlmu)->findAll();
    }

    /**
     * Get unique bidang ilmu values
     */
    public function getBidangIlmuList()
    {
        return $this->select('bidang_ilmu')
                   ->distinct()
                   ->findAll();
    }
}

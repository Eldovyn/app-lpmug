<?php

namespace App\Models;

use CodeIgniter\Model;

class MahasiswaModel extends Model
{
    protected $table            = 'tbl_mahasiswa';
    protected $primaryKey       = 'mahasiswa_id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'laporan_id',
        'jurusan_id',
        'mahasiswa_name',
        'mahasiswa_npm',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Ambil mahasiswa + join jurusan + laporan
    public function getMahasiswaWithRelations($id = null)
    {
        $builder = $this->select('
                tbl_mahasiswa.*,
                tbl_jurusan.jurusan_name,
                tbl_laporan.proposal,
                tbl_laporan.laporan
            ')
            ->join('tbl_jurusan', 'tbl_jurusan.jurusan_id = tbl_mahasiswa.jurusan_id')
            ->join('tbl_laporan', 'tbl_laporan.laporan_id = tbl_mahasiswa.laporan_id');

        if ($id !== null) {
            $builder->where('tbl_mahasiswa.mahasiswa_id', $id);
            return $builder->first();
        }

        return $builder->findAll();
    }

    // Ambil semua mahasiswa berdasarkan laporan_id
    public function getByLaporan($laporanId)
    {
        return $this->select('tbl_mahasiswa.*, tbl_jurusan.jurusan_id as jurusan_id, tbl_jurusan.jurusan_name')
                    ->join('tbl_jurusan', 'tbl_jurusan.jurusan_id = tbl_mahasiswa.jurusan_id', 'left')
                    ->where('laporan_id', $laporanId)->findAll();
    }

    // Ambil semua mahasiswa berdasarkan jurusan
    public function getByJurusan($jurusanId)
    {
        return $this->where('jurusan_id', $jurusanId)->findAll();
    }

    // Ambil mahasiswa join sama jurusan
    public function getAllWithJurusan()
    {
        return $this->select('tbl_mahasiswa.*, tbl_jurusan.jurusan_name')
                    ->join('tbl_jurusan', 'tbl_jurusan.jurusan_id = tbl_mahasiswa.jurusan_id', 'left')
                    ->orderBy('tbl_mahasiswa.mahasiswa_name', 'ASC')
                    ->findAll();
    }
    // Cek apakah mahasiswa sudah ikut di lebih dari 2 kelompok
    public function checkMahasiswaLimit($mahasiswa_id)
    {
        return $this->where('mahasiswa_id', $mahasiswa_id)
                    ->countAllResults();
    }
}

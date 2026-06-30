<?php

namespace App\Models;

use CodeIgniter\Model;

class PengesahanModel extends Model
{
    protected $table            = 'tbl_laporan';
    protected $primaryKey       = 'laporan_id';
    protected $returnType       = 'object';
    protected $allowedFields    = [
        'ketua_id', 'mitra_id', 'judul_kegiatan', 'tanggal_kegiatan', 'range_dana', 'periode_id'
    ];
    protected $useTimestamps    = true;

    public function getPengesahanData($laporan_id)
    {
        $builder = $this->db->table('tbl_laporan');
        $builder->select('
            tbl_laporan.*,
            ketua.user_name as ketua_nama, 
            ketua.nidn as ketua_nidn, 
            jur_ketua.jurusan_name as program_studi, 
            univ_ketua.universitas_name as universitas, 
            ketua.jurusan_nya as bidang_keahlian,
            mitra.user_name as nama_mitra, 
            kota_mitra.kota_name as kabupaten, 
            prov_mitra.provinsi_name as provinsi, 
            0 as jarak,
            periode.periode_name as nama_periode, 
            periode.tahun_ajaran as tahun,
            NULL as pejabat_nama, 
            NULL as pejabat_nip, 
            NULL as pejabat_jabatan,
            NULL as pejabat_ttd, 
            NULL as ketua_ttd
        ');
        $builder->join('tbl_users ketua', 'ketua.user_id = tbl_laporan.ketua_id', 'left');
        $builder->join('tbl_jurusan jur_ketua', 'jur_ketua.jurusan_id = ketua.jurusan_id', 'left');
        $builder->join('tbl_universitas univ_ketua', 'univ_ketua.universitas_id = ketua.universitas_id', 'left');
        $builder->join('tbl_users mitra', 'mitra.user_id = tbl_laporan.mitra_id', 'left');
        $builder->join('tbl_kota kota_mitra', 'kota_mitra.kota_id = mitra.kota_id', 'left');
        $builder->join('tbl_provinsi prov_mitra', 'prov_mitra.provinsi_id = kota_mitra.provinsi_id', 'left');
        $builder->join('tbl_periode periode', 'periode.periode_id = tbl_laporan.periode_id', 'left');
        if ($laporan_id === null) {
            return null;
        }
        $builder->where('tbl_laporan.laporan_id', $laporan_id);

        
        $result = $builder->get()->getRow();
        
        if (!$result) {
            return null;
        }
        
        return $result;
    }

    public function getAnggota($laporan_id)
    {
        $builder = $this->db->table('tbl_tags');
        $builder->select('
            tbl_users.user_id,
            tbl_users.user_name, 
            tbl_users.nidn');
        $builder->join('tbl_users', 'tbl_users.user_id = tbl_tags.anggota_id');
        $builder->where('tbl_tags.laporan_id', $laporan_id);
        //$builder->orderBy('tbl_tags.is_koordinator', 'DESC'); // Put coordinators first
        $builder->orderBy('tbl_users.user_name', 'ASC'); // Then alphabetically
        
        return $builder->get()->getResult();
    }
    
    public function isExists($laporan_id)
    {
        return $this->where('laporan_id', $laporan_id)->countAllResults() > 0;
    }

}

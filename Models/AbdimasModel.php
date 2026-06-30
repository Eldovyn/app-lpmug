<?php

namespace App\Models;

use CodeIgniter\Model;

class AbdimasModel extends Model
{
    protected $table            = 'tbl_laporan';
    protected $primaryKey       = 'laporan_id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'ketua_id',
        'mitra_id',
        'subprogram_id',
        'luaran_id',
        'periode_id',
        'tipe_kegiatan',
        'range_dana',
        'bidang_ilmu_id',
        'verifikasi',
        'proposal',
        'laporan',
        'tanggal_kegiatan',
        'tanggal_selesai',
        'judul_kegiatan',
        'bukti_kegiatan',
        'skm',
        'spm',
        'surat_undangan',
        'link_luaran',
        'masalah_mitra',
        'solusi_mitra',
        'sumber_dana',
        'revisi',
        'nt1',
        'nt2',
        'nt3',
        'nt4',
        'nt5',
        'nt6',
        'nt7',
        'nt8',
        'nt9',
        'nlpm1',
        'nlpm2',
        'nlpm3',
        'nlpm4',
        'nlpm5',
        'nlpm6',
        'nlpm7',
        'nlpm8',
        'nlpm9',
        'saran_masukan',
    ];
    protected $useTimestamps    = true;

    public function exists($id)
    {
        return $this->where('laporan_id', $id)->countAllResults() > 0;
    }

    function getAll()
    {
        $builder = $this->builder();
        $builder->select('tbl_laporan.*, tbl_users.user_name as ketua_name, tbl_users.gelar_dpn, tbl_users.gelar_blkng, tbl_periode.periode_name, tbl_periode.tahun_ajaran, tbl_tags.anggota_id');
        $builder->join('tbl_tags', 'tbl_tags.laporan_id = tbl_laporan.laporan_id', 'left');
        $builder->join('tbl_users', 'tbl_users.user_id = tbl_laporan.ketua_id', 'left');
        $builder->join('tbl_periode', 'tbl_periode.periode_id = tbl_laporan.periode_id', 'left');
        $builder->orderBy('tbl_laporan.laporan_id', 'DESC');
        $query   = $builder->get();
        return $query->getResult();
    }


    function getMitra()
    {
        $builder = $this->builder();
        $builder->select('tbl_laporan.laporan_id, tbl_laporan.mitra_id, tbl_users.user_id, tbl_users.user_name, tbl_users.user_name as mitra_name, tbl_users.email, tbl_users.kontak, tbl_users.alamat');
        $builder->join('tbl_users', 'tbl_users.user_id = tbl_laporan.mitra_id');
        $query   = $builder->get();
        return $query->getResult();
    }

    function getAnggota()
    {
        $builder = $this->builder();
        $builder->select('tbl_laporan.laporan_id, tbl_tags.anggota_id, tbl_users.user_id, tbl_users.user_name, tbl_users.user_name as anggota_name, tbl_users.gelar_dpn, tbl_users.gelar_blkng, tbl_users.nidn, tbl_users.sinta_id');
        $builder->join('tbl_tags', 'tbl_tags.laporan_id = tbl_laporan.laporan_id');
        $builder->join('tbl_users', 'tbl_users.user_id = tbl_tags.anggota_id');
        $query   = $builder->get();
        return $query->getResult();
    }

<<<<<<< HEAD
    public function countJurusanUnik()
    {
        $builder = $this->builder('tbl_jurusan');
        $builder->select("COUNT(DISTINCT jurusan_name) AS jumlah_jurusan_unik", false);
        $builder->where('(deleted_at IS NULL OR UNIX_TIMESTAMP(deleted_at) = 0)', null, false);

        $query = $builder->get();
        return $query->getRow()->jumlah_jurusan_unik;
    }

    public function getLuaranChartData(?string $jurusan = null): array
    {
        if ($jurusan == null) {
            // Semua prodi: UNIX_TIMESTAMP()=0 untuk zero-date, aman di MySQL strict mode
            $b = $this->db->table('tbl_tag_luaran t')
                ->select('
                    lu.luaran_id,
                    lu.luaran_name,
                    COUNT(DISTINCT t.laporan_id) AS total_laporan
                ', false)
                ->join('tbl_luaran lu', 'lu.luaran_id = t.luaran_id', 'inner')
                ->join('tbl_laporan r', 'r.laporan_id = t.laporan_id', 'inner')
                ->where('(lu.deleted_at IS NULL OR UNIX_TIMESTAMP(lu.deleted_at) = 0)', null, false)
                ->where('(t.deleted_at IS NULL OR UNIX_TIMESTAMP(t.deleted_at) = 0)', null, false)
                ->where('(r.deleted_at IS NULL OR UNIX_TIMESTAMP(r.deleted_at) = 0)', null, false)
                ->groupBy('lu.luaran_id, lu.luaran_name')
                ->orderBy('total_laporan', 'DESC')
                ->orderBy('lu.luaran_name', 'ASC');

            return $b->get()->getResultArray();
        }

        // Filter per jurusan
        $b = $this->db->table('tbl_tag_luaran t')
            ->select('j.jurusan_id, j.jurusan_name, lu.luaran_id, lu.luaran_name, COUNT(DISTINCT t.laporan_id) AS total_laporan', false)
            ->join('tbl_laporan r', 'r.laporan_id = t.laporan_id', 'inner')
            ->join('tbl_users u', 'u.user_id = r.ketua_id', 'inner')
            ->join('tbl_jurusan j', 'j.jurusan_id = u.jurusan_id', 'inner')
            ->join('tbl_luaran lu', 'lu.luaran_id = t.luaran_id', 'inner')
            ->where('(lu.deleted_at IS NULL OR UNIX_TIMESTAMP(lu.deleted_at) = 0)', null, false)
            ->where('(r.deleted_at IS NULL OR UNIX_TIMESTAMP(r.deleted_at) = 0)', null, false)
            ->where('(u.deleted_at IS NULL OR UNIX_TIMESTAMP(u.deleted_at) = 0)', null, false)
            ->where('(j.deleted_at IS NULL OR UNIX_TIMESTAMP(j.deleted_at) = 0)', null, false)
            ->where('j.jurusan_name', $jurusan)
            ->groupBy('j.jurusan_id, j.jurusan_name, lu.luaran_id, lu.luaran_name')
            ->orderBy('total_laporan', 'DESC')
            ->orderBy('lu.luaran_name', 'ASC');

        return $b->get()->getResultArray();
    }



    public function getJurusanList()
    {
        return $this->db->table('tbl_jurusan')
            ->select('jurusan_id, jurusan_name')
            ->distinct()
            ->get()
            ->getResultArray();
    }

    public function getLuaranDataByJurusan(): array
    {
        // Get all luaran data grouped by jurusan (for JS-side filtering by jurusan_id)
        // UNIX_TIMESTAMP()=0 untuk zero-date, aman di MySQL strict mode
        $builder = $this->db->table('tbl_tag_luaran t');

        $builder->select('
            j.jurusan_id,
            j.jurusan_name,
            lu.luaran_id,
            lu.luaran_name,
            COUNT(DISTINCT t.laporan_id) AS total_laporan
        ', false);

        $builder->join('tbl_laporan r', 'r.laporan_id = t.laporan_id', 'inner');
        $builder->join('tbl_users u', 'u.user_id = r.ketua_id', 'inner');
        $builder->join('tbl_jurusan j', 'j.jurusan_id = u.jurusan_id', 'inner');
        $builder->join('tbl_luaran lu', 'lu.luaran_id = t.luaran_id', 'inner');

        $builder->where('(j.deleted_at IS NULL OR UNIX_TIMESTAMP(j.deleted_at) = 0)', null, false);
        $builder->where('(lu.deleted_at IS NULL OR UNIX_TIMESTAMP(lu.deleted_at) = 0)', null, false);
        $builder->where('(t.deleted_at IS NULL OR UNIX_TIMESTAMP(t.deleted_at) = 0)', null, false);
        $builder->where('(r.deleted_at IS NULL OR UNIX_TIMESTAMP(r.deleted_at) = 0)', null, false);
        $builder->where('(u.deleted_at IS NULL OR UNIX_TIMESTAMP(u.deleted_at) = 0)', null, false);

        $builder->groupBy('j.jurusan_id, j.jurusan_name, lu.luaran_id, lu.luaran_name');
        $builder->orderBy('j.jurusan_name', 'ASC');
        $builder->orderBy('lu.luaran_name', 'ASC');

        return $builder->get()->getResultArray();
    }

    public function getDataJumlahPerProdi()
    {
        $builder = $this->db->table('tbl_jurusan AS J');

        $builder->select('
        J.jurusan_id,
        J.jurusan_name,
        COUNT(DISTINCT L.ketua_id) AS jumlah_ketua,
        COUNT(DISTINCT U_anggota.user_id) AS jumlah_anggota
    ', false);

        $builder->join('tbl_users AS U_ketua', 'U_ketua.jurusan_id = J.jurusan_id', 'left');
        $builder->join('tbl_laporan AS L', 'L.ketua_id = U_ketua.user_id', 'left');
        $builder->join('tbl_tags AS T', 'T.laporan_id = L.laporan_id', 'left');
        $builder->join('tbl_users AS U_anggota', 'U_anggota.user_id = T.anggota_id', 'left');

        $builder->where('(J.deleted_at IS NULL OR UNIX_TIMESTAMP(J.deleted_at) = 0)', null, false);

        $builder->groupBy('J.jurusan_id, J.jurusan_name');
        $builder->orderBy('J.jurusan_name', 'ASC');

        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getDataJumlahPerProdiUnique()
    {
        $builder = $this->db->table('tbl_jurusan AS J');

        $builder->select('
            MAX(J.jurusan_id) AS jurusan_id,
            J.jurusan_name,
            COUNT(DISTINCT L.ketua_id) AS jumlah_ketua,
            COUNT(DISTINCT U_anggota.user_id) AS jumlah_anggota
        ', false);

        $builder->join('tbl_users AS U_ketua', 'U_ketua.jurusan_id = J.jurusan_id', 'left');
        $builder->join('tbl_laporan AS L', 'L.ketua_id = U_ketua.user_id', 'left');
        $builder->join('tbl_tags AS T', 'T.laporan_id = L.laporan_id', 'left');
        $builder->join('tbl_users AS U_anggota', 'U_anggota.user_id = T.anggota_id', 'left');

        $builder->where('(J.deleted_at IS NULL OR UNIX_TIMESTAMP(J.deleted_at) = 0)', null, false);

        $builder->groupBy('J.jurusan_name');
        $builder->orderBy('J.jurusan_name', 'ASC');

        return $builder->get()->getResultArray();
    }

    public function getJumlahKetuaAnggota()
    {
        $builder = $this->db->table('tbl_jurusan AS J');

        $builder->select(
            'COUNT(DISTINCT L.ketua_id)        AS total_ketua, ' .
                'COUNT(DISTINCT U_anggota.user_id) AS total_anggota',
            false
        );

        $builder->join('tbl_users   AS U_ketua',   'U_ketua.jurusan_id = J.jurusan_id', 'left');
        $builder->join('tbl_laporan AS L',         'L.ketua_id = U_ketua.user_id',      'left');
        $builder->join('tbl_tags    AS T',         'T.laporan_id = L.laporan_id',       'left');
        $builder->join('tbl_users   AS U_anggota', 'U_anggota.user_id = T.anggota_id',  'left');

        $builder->where('(J.deleted_at IS NULL OR UNIX_TIMESTAMP(J.deleted_at) = 0)', null, false);

        $query = $builder->get();
        return (array) $query->getRowArray();
    }

    function getPaginated($num, $keyword = null)
    {
=======

    function getPaginated($num, $keyword = null) {
>>>>>>> 55c0835 (refactor: update code)
        $userLogin = userLogin()->user_id;
        $builder = $this->builder()->orderBy('tbl_laporan.laporan_id', 'DESC');
        $builder->select('tbl_laporan.*, tbl_users.user_name as ketua_name, tbl_users.gelar_dpn, tbl_users.gelar_blkng, tbl_periode.periode_name, tbl_periode.tahun_ajaran');
        $builder->join('tbl_tags', 'tbl_tags.laporan_id = tbl_laporan.laporan_id')->where('anggota_id', $userLogin);
        $builder->join('tbl_users', 'tbl_users.user_id = tbl_laporan.ketua_id');
        $builder->join('tbl_periode', 'tbl_periode.periode_id = tbl_laporan.periode_id', 'left');
        if ($keyword != '') {
            $builder->like('ketua_id', $keyword);
        }
        return [
            'title_tab'     => 'Abdimas &mdash; LPM UG',
            'abdimas'       => $this->paginate($num),
            'pager'         => $this->pager,
        ];
    }
<<<<<<< HEAD

    public function getLaporanById($id)
    {
        return $this->db->table('tbl_laporan l')
            ->select('l.*, m.user_name as mitra_name, k.user_name as ketua, k.nidn as ketua_nidn')
            ->join('tbl_users m', 'm.user_id = l.mitra_id', 'left') // Mitra
            ->join('tbl_users k', 'k.user_id = l.ketua_id', 'left') // Ketua
            ->where('l.laporan_id', $id)
            ->get()
            ->getRowArray();
    }
=======
    
 public function getLaporanById($id)
{
    return $this->db->table('tbl_laporan l')
        ->select('l.*, m.user_name as mitra_name, k.user_name as ketua, k.nidn as ketua_nidn')
        ->join('tbl_users m', 'm.user_id = l.mitra_id', 'left') // Mitra
        ->join('tbl_users k', 'k.user_id = l.ketua_id', 'left') // Ketua
        ->where('l.laporan_id', $id)
        ->get()
        ->getRowArray();
}
>>>>>>> 55c0835 (refactor: update code)



    // Ambil anggota by laporan (kecuali ketua)
    public function getAnggotaByLaporan($laporan_id, $ketua_id)
    {
        return $this->db->table('tbl_tags t')
            ->select('u.user_id, u.user_name,u.nidn, j.jurusan_name')
<<<<<<< HEAD
            ->join('tbl_users u', 'u.user_id = t.anggota_id')
=======
            ->join('tbl_users u', 'u.user_id = t.anggota_id') 
>>>>>>> 55c0835 (refactor: update code)
            ->join('tbl_jurusan j', 'j.jurusan_id = u.jurusan_id', 'left')
            ->where('t.laporan_id', $laporan_id)
            ->where('t.anggota_id !=', $ketua_id)
            ->orderBy('u.user_name', 'ASC')
            ->get()
            ->getResultArray();
    }

<<<<<<< HEAD
    /**
     * Ambil semua laporan di mana user adalah ketua pengusul
     */
    public function getLaporanByKetua($userId)
    {
        return $this->db->table('tbl_laporan l')
            ->select('l.laporan_id, l.judul_kegiatan, l.tanggal_kegiatan, l.verifikasi, l.ketua_id,
                      p.periode_name, p.tahun_ajaran,
                      m.user_name as mitra_name')
            ->join('tbl_periode p', 'p.periode_id = l.periode_id', 'left')
            ->join('tbl_users m', 'm.user_id = l.mitra_id', 'left')
            ->where('l.ketua_id', $userId)
            ->where('l.deleted_at', null)
            ->orderBy('l.laporan_id', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Ambil semua laporan di mana user adalah anggota (tbl_tags)
     */
    public function getLaporanByAnggota($userId)
    {
        return $this->db->table('tbl_laporan l')
            ->select('l.laporan_id, l.judul_kegiatan, l.tanggal_kegiatan, l.verifikasi, l.ketua_id,
                      p.periode_name, p.tahun_ajaran,
                      m.user_name as mitra_name,
                      k.user_name as ketua_nama')
            ->join('tbl_tags t', 't.laporan_id = l.laporan_id')
            ->join('tbl_periode p', 'p.periode_id = l.periode_id', 'left')
            ->join('tbl_users m', 'm.user_id = l.mitra_id', 'left')
            ->join('tbl_users k', 'k.user_id = l.ketua_id', 'left')
            ->where('t.anggota_id', $userId)
            ->where('l.deleted_at', null)
            ->orderBy('l.laporan_id', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getAbdimasById($laporan_id)
    {
        return $this->db->table('tbl_laporan l')
            ->select('l.*, COALESCE(NULLIF(l.spm, ""), u.spm) as spm, COALESCE(NULLIF(l.skm, ""), u.skm) as skm')
            ->join('tbl_users u', 'u.user_id = l.mitra_id', 'left')
            ->where('l.laporan_id', $laporan_id)
            ->get()
            ->getRow();
    }
}
=======
}
>>>>>>> 55c0835 (refactor: update code)

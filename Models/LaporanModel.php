<?php

namespace App\Models;

use CodeIgniter\Model;

class LaporanModel extends Model
{
    protected $table            = 'tbl_laporan';
    protected $primaryKey       = 'laporan_id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'mitra_id',
        'periode_id',
        'spm',
        'skm',
        'nomor_surat',
        'verifikasi',
        'laporan',
        'bukti_kegiatan',
        'ketua_id',
        'subprogram_id',
        'program_id',
        'topik_id',
        'judul',
        'tanggal_mulai',
        'tanggal_selesai',
        'tempat',
        'luaran',
        'pejabat_id'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // 🔒 Validasi dasar
    protected $validationRules = [
        'mitra_id'    => 'required|integer',
        'periode_id'  => 'required|integer',
    ];

    protected $validationMessages = [
        'mitra_id' => [
            'required' => 'Mitra harus dipilih.',
            'integer'  => 'ID Mitra tidak valid.'
        ],
        'periode_id' => [
            'required' => 'Periode harus dipilih.',
            'integer'  => 'ID Periode tidak valid.'
        ]
    ];

    // ======================================================
    // 🔍 CUSTOM FUNCTIONS
    // ======================================================

    /**
     * Ambil laporan berdasarkan mitra dan periode tertentu
     */
    public function getLaporanByMitraAndPeriode($mitraId, $periodeId)
    {
        return $this->where('mitra_id', $mitraId)
                    ->where('periode_id', $periodeId)
                    ->first();
    }

    /**
     * Ambil semua laporan berdasarkan mitra (lengkap dengan info periode)
     */
    public function getLaporanByMitra($mitraId)
    {
        return $this->select('tbl_laporan.*, tbl_periode.periode_name, tbl_periode.tahun_ajaran')
                    ->join('tbl_periode', 'tbl_periode.periode_id = tbl_laporan.periode_id', 'left')
                    ->where('tbl_laporan.mitra_id', $mitraId)
                    ->orderBy('tbl_laporan.periode_id', 'DESC')
                    ->findAll();
    }

    /**
     * Cek apakah laporan sudah ada untuk mitra & periode tertentu
     */
    public function hasLaporan($mitraId, $periodeId)
    {
        return $this->where('mitra_id', $mitraId)
                    ->where('periode_id', $periodeId)
                    ->countAllResults() > 0;
    }

    /**
     * Insert/Update laporan dengan auto-overwrite file lama
     */
   public function upsertLaporan($mitraId, $periodeId, $data)
{
    $existing = $this->getLaporanByMitraAndPeriode($mitraId, $periodeId);

    if ($existing) {
        // Hapus file lama kalau diganti
        if (!empty($existing->spm) && isset($data['spm']) && $data['spm'] !== $existing->spm) {
            $this->deleteFile($existing->spm, 'spm', $periodeId);
        }
        if (!empty($existing->skm) && isset($data['skm']) && $data['skm'] !== $existing->skm) {
            $this->deleteFile($existing->skm, 'skm', $periodeId);
        }

        // ✅ Update semua kolom yang dikirim (termasuk nomor_surat)
        return $this->update($existing->laporan_id, $data);
    } else {
        // Insert baru
        $data['mitra_id']   = $mitraId;
        $data['periode_id'] = $periodeId;
        return $this->insert($data);
    }
}

    /**
     * Hapus file SPM & SKM dari record tanpa hapus data laporan
     */
    public function deleteLaporanFiles($laporanId)
    {
        $laporan = $this->find($laporanId);
        if (!$laporan) return false;

        $updateData = [];

        if (!empty($laporan->spm)) {
            $this->deleteFile($laporan->spm, 'spm');
            $updateData['spm'] = null;
        }

        if (!empty($laporan->skm)) {
            $this->deleteFile($laporan->skm, 'skm');
            $updateData['skm'] = null;
        }

        if (!empty($updateData)) {
            return $this->update($laporanId, $updateData);
        }

        return true;
    }

    /**
     * Hapus laporan beserta file (gunakan hati-hati ⚠️)
     */
    public function deleteLaporan($laporanId)
    {
        $laporan = $this->find($laporanId);
        if (!$laporan) return false;

        if (!empty($laporan->spm)) $this->deleteFile($laporan->spm, 'spm');
        if (!empty($laporan->skm)) $this->deleteFile($laporan->skm, 'skm');

        return $this->delete($laporanId);
    }

    /**
     * Hapus file dari penyimpanan
     */
    private function deleteFile($fileName, $type, $periodeId = null)
    {
        // Path file (new structure pakai periode_id)
        $filePath = $periodeId
            ? WRITEPATH . 'berkas/' . $type . '/' . $periodeId . '/' . $fileName
            : WRITEPATH . 'berkas/' . $type . '/' . $fileName;

        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return false;
    }

    /**
     * Ambil laporan + info periode + info mitra (user)
     */
    public function getLaporanWithPeriode($mitraId = null, $periodeId = null)
    {
        $builder = $this->select('
                        tbl_laporan.*,
                        tbl_periode.periode_name,
                        tbl_periode.tahun_ajaran,
                        tbl_users.user_name AS mitra_name
                    ')
                    ->join('tbl_periode', 'tbl_periode.periode_id = tbl_laporan.periode_id', 'left')
                    ->join('tbl_users', 'tbl_users.user_id = tbl_laporan.mitra_id', 'left');

        if ($mitraId) {
            $builder->where('tbl_laporan.mitra_id', $mitraId);
        }

        if ($periodeId) {
            $builder->where('tbl_laporan.periode_id', $periodeId);
        }

        return $builder->orderBy('tbl_laporan.periode_id', 'DESC')->findAll();
    }

    /**
     * Ambil laporan berdasarkan nomor surat
     */
    public function getByNomorSurat($nomorSurat)
    {
        return $this->where('nomor_surat', $nomorSurat)->first();
    }

    /**
     * Update nomor surat laporan
     */
    public function updateNomorSurat($laporanId, $nomorSurat)
    {
        return $this->update($laporanId, ['nomor_surat' => $nomorSurat]);
    }
}

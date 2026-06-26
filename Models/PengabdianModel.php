<?php

namespace App\Models;

use CodeIgniter\Model;

class PengabdianModel extends Model
{
    protected $table = 'pengabdian_masyarakat';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'nomor_urut',
        'tanggal_surat', 
        'nama_kegiatan',
        'lokasi_mitra',
        'jangka_waktu',
        'bidang_ilmu',
        'ketua',
        'deskripsi',
        'status',
        'created_at',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'nama_kegiatan' => 'required|min_length[5]|max_length[255]',
        'lokasi_mitra' => 'required|min_length[5]|max_length[500]',
        'jangka_waktu' => 'required|min_length[5]|max_length[100]',
        'bidang_ilmu' => 'required|min_length[3]|max_length[100]',
        'ketua' => 'required|min_length[3]|max_length[100]',
        'tanggal_surat' => 'required|valid_date'
    ];

    protected $validationMessages = [
        'nama_kegiatan' => [
            'required' => 'Nama kegiatan harus diisi',
            'min_length' => 'Nama kegiatan minimal 5 karakter',
            'max_length' => 'Nama kegiatan maksimal 255 karakter'
        ],
        'lokasi_mitra' => [
            'required' => 'Lokasi dan nama mitra harus diisi',
            'min_length' => 'Lokasi dan nama mitra minimal 5 karakter'
        ],
        'ketua' => [
            'required' => 'Nama ketua harus diisi',
            'min_length' => 'Nama ketua minimal 3 karakter'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['generateNomorUrut'];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Generate nomor urut otomatis berdasarkan urutan upload
     */
    protected function generateNomorUrut(array $data)
    {
        if (!isset($data['data']['nomor_urut'])) {
            $lastNumber = $this->selectMax('nomor_urut')->first();
            $nextNumber = ($lastNumber['nomor_urut'] ?? 0) + 1;
            $data['data']['nomor_urut'] = $nextNumber;
        }
        return $data;
    }

    /**
     * Get data pengabdian dengan format nomor surat
     */
    public function getPengabdianWithNomor($id = null)
    {
        $builder = $this->builder();
        
        if ($id) {
            $builder->where('id', $id);
            $result = $builder->get()->getRowArray();
            
            if ($result) {
                $result['nomor_surat'] = $this->generateNomorSurat($result);
            }
            
            return $result;
        }
        
        $results = $builder->get()->getResultArray();
        
        foreach ($results as &$result) {
            $result['nomor_surat'] = $this->generateNomorSurat($result);
        }
        
        return $results;
    }

    /**
     * Generate format nomor surat
     */
    private function generateNomorSurat($data)
    {
        $tahun = date('Y', strtotime($data['tanggal_surat']));
        return sprintf('%03d', $data['nomor_urut']) . '/LPKM-UG/' . $tahun;
    }

    /**
     * Get pengabdian aktif
     */
    public function getPengabdianAktif()
    {
        return $this->where('status', 'aktif')
                   ->orderBy('nomor_urut', 'DESC')
                   ->findAll();
    }

    /**
     * Get pengabdian by tahun
     */
    public function getPengabdianByTahun($tahun)
    {
        return $this->where('YEAR(tanggal_surat)', $tahun)
                   ->orderBy('nomor_urut', 'DESC')
                   ->findAll();
    }

    /**
     * Search pengabdian
     */
    public function searchPengabdian($keyword)
    {
        return $this->groupStart()
                   ->like('nama_kegiatan', $keyword)
                   ->orLike('lokasi_mitra', $keyword)
                   ->orLike('bidang_ilmu', $keyword)
                   ->orLike('ketua', $keyword)
                   ->groupEnd()
                   ->orderBy('nomor_urut', 'DESC')
                   ->findAll();
    }
}
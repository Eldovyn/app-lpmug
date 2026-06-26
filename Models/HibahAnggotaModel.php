<?php

namespace App\Models;

use CodeIgniter\Model;

class HibahAnggotaModel extends Model
{
    protected $table = 'tbl_hibah_anggota';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['hibah_id', 'user_id', 'posisi'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'hibah_id' => 'required|integer',
        'user_id' => 'required|integer',
        'posisi' => 'required|in_list[ketua,anggota]',
    ];

    protected $validationMessages = [
        'hibah_id' => [
            'required' => 'Hibah ID wajib diisi.',
            'integer' => 'Hibah ID harus berupa angka.',
        ],
        'user_id' => [
            'required' => 'User ID wajib diisi.',
            'integer' => 'User ID harus berupa angka.',
        ],
        'posisi' => [
            'required' => 'Posisi wajib diisi.',
            'in_list' => 'Posisi harus ketua atau anggota.',
        ],
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Get anggota by hibah_id
     */
    public function getAnggotaByHibahId($hibahId)
    {
        return $this->select('hibah_anggota.*, tbl_users.user_name, tbl_users.nidn, tbl_users.sinta_id')
                    ->join('tbl_users', 'tbl_users.user_id = hibah_anggota.anggota_id', 'left')
                    ->where('hibah_id', $hibahId)
                    ->orderBy('posisi', 'ASC') // ketua first
                    ->findAll();
    }

    /**
     * Check if user is already anggota of hibah
     */
    public function isUserAnggota($hibahId, $userId)
    {
        return $this->where('hibah_id', $hibahId)
                    ->where('anggota_id', $userId)
                    ->first() !== null;
    }

    /**
     * Get ketua of hibah
     */
    public function getKetua($hibahId)
    {
        return $this->select('hibah_anggota.*, tbl_users.user_name, tbl_users.nidn, tbl_users.sinta_id')
                    ->join('tbl_users', 'tbl_users.user_id = hibah_anggota.anggota_id', 'left')
                    ->where('hibah_id', $hibahId)
                    ->where('posisi', 'ketua')
                    ->first();
    }

    /**
     * Add anggota to hibah
     */
    public function addAnggota($hibahId, $anggotaId, $posisi = 'anggota')
    {
        // Check if already exists
        if ($this->isUserAnggota($hibahId, $anggotaId)) {
            return false;
        }

        return $this->insert([
            'hibah_id' => $hibahId,
            'anggota_id' => $anggotaId,
            'posisi' => $posisi,
        ]);
    }

    /**
     * Remove anggota from hibah
     */
    public function removeAnggota($hibahId, $anggotaId)
    {
        return $this->where('hibah_id', $hibahId)
                    ->where('anggota_id', $anggotaId)
                    ->delete();
    }

    /**
     * Update posisi anggota
     */
    public function updatePosisi($hibahId, $anggotaId, $posisi)
    {
        return $this->where('hibah_id', $hibahId)
                    ->where('anggota_id', $anggotaId)
                    ->set('posisi', $posisi)
                    ->update();
    }
}

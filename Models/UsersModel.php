<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $table = 'tbl_users';
    protected $primaryKey = 'user_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'sinta_id', 'nidn', 'user_name', 'gelar_dpn', 'gelar_blkng',
        'universitas_id', 'jurusan_id', 'jurusan_nya', 'fungsional_id',
        'email', 'password', 'kontak', 'photo', 'status', 'kota_id',
        'alamat', 'kebutuhan', 'role_id', 'spm', 'skm',
        'flag_status', 'flag_verified_at', 'flag_verified_by', 'flag_notes'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Get user by ID
    public function getUserById($userId)
    {
        return $this->find($userId);
    }

    // Update flag status
    public function updateFlagStatus($userId, $status)
    {
        $data = ['flag_status' => $status];

        if ($status === 'inactive') {
            $data['flag_verified_at'] = null;
            $data['flag_verified_by'] = null;
            $data['flag_notes'] = null;
        }

        return $this->update($userId, $data);
    }

    // Get dosen dengan flag aktif (role_id = 4)
    public function getDosenWithActiveFlag()
    {
        return $this->select('tbl_users.*, COUNT(tbl_hibah.id) as total_hibah')
            ->join('tbl_hibah', 'tbl_users.user_id = tbl_hibah.user_id AND tbl_hibah.verification_status = "approved"', 'left')
            ->where('tbl_users.role_id', 4)
            ->where('tbl_users.flag_status', 'active')
            ->groupBy('tbl_users.user_id')
            ->orderBy('tbl_users.user_name', 'ASC')
            ->findAll();
    }

    // Get dosen pending verifikasi
    public function getDosenPendingVerification()
    {
        return $this->select('tbl_users.*, COUNT(tbl_hibah.id) as pending_hibah_count')
            ->join('tbl_hibah', 'tbl_users.user_id = tbl_hibah.user_id AND tbl_hibah.verification_status = "submitted"', 'inner')
            ->where('tbl_users.role_id', 4)
            ->where('tbl_users.flag_status', 'pending')
            ->groupBy('tbl_users.user_id')
            ->orderBy('tbl_users.user_name', 'ASC')
            ->findAll();
    }

    // Count dosen with active flag
    public function countDosenWithActiveFlag()
    {
        return $this->where('role_id', 4)
            ->where('flag_status', 'active')
            ->countAllResults();
    }
}

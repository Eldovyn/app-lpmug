<?php

namespace App\Models;

use CodeIgniter\Model;

class UserFlagsModel extends Model
{
    protected $table = 'tbl_user_flags';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'user_id', 'hibah_id', 'flag_type', 'flag_value', 'action', 'action_by', 'notes', 'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Get flags for a user
    public function getUserFlags($userId)
    {
        return $this->where('user_id', $userId)->findAll();
    }

    // Set or update a flag for a user
    public function setUserFlag($userId, $flagType, $flagValue)
    {
        $existing = $this->where('user_id', $userId)->where('flag_type', $flagType)->first();

        if ($existing) {
            return $this->update($existing->id, ['flag_value' => $flagValue]);
        } else {
            return $this->insert([
                'user_id' => $userId,
                'flag_type' => $flagType,
                'flag_value' => $flagValue
            ]);
        }
    }

    // Get flag value for a user
    public function getUserFlagValue($userId, $flagType)
    {
        $flag = $this->where('user_id', $userId)->where('flag_type', $flagType)->first();
        return $flag ? $flag->flag_value : null;
    }

    // Create a new flag
    public function createFlag($data)
    {
        return $this->insert($data);
    }

    // Get flags by user
    public function getFlagsByUser($userId)
    {
        return $this->where('user_id', $userId)->findAll();
    }

    // Get hibah flags for user
    public function getHibahFlags($userId)
    {
        return $this->where('user_id', $userId)->where('flag_type', 'hibah_approved')->findAll();
    }

    /**
     * Check if user has hibah_approved flag for specific hibah
     * 
     * @param int $userId User ID
     * @param int $hibahId Hibah ID
     * @return bool
     */
    public function hasFlagForHibah($userId, $hibahId)
    {
        return $this->where('user_id', $userId)
            ->where('hibah_id', $hibahId)
            ->where('flag_type', 'hibah_approved')
            ->first() !== null;
    }
}

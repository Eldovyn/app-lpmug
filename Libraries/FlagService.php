<?php

namespace App\Libraries;

use App\Models\UserFlagsModel;
use App\Models\UsersModel;

class FlagService
{
    protected $flagModel;
    protected $usersModel;

    public function __construct()
    {
        $this->flagModel = new UserFlagsModel();
        $this->usersModel = new UsersModel();
    }

    /**
     * Create hibah flag for a single user
     */
    public function createHibahFlag($userId, $hibahId, $adminId)
    {
        return $this->flagModel->createFlag([
            'user_id' => $userId,
            'hibah_id' => $hibahId,
            'flag_type' => 'hibah_approved',
            'flag_value' => 'approved',
            'action' => 'approve',
            'action_by' => $adminId
        ]);
    }

    /**
     * Create hibah flags for all members (ketua and anggota) of a hibah
     * and update their flag_status in tbl_users
     * 
     * @param array $memberIds Array of user IDs (ketua + anggota)
     * @param int $hibahId Hibah ID
     * @param int $adminId Admin user ID who approved
     * @return array Results of flag creation
     */
    public function createHibahFlagsForAllMembers($memberIds, $hibahId, $adminId)
    {
        $results = [
            'flags_created' => 0,
            'users_updated' => 0,
            'errors' => []
        ];

        foreach ($memberIds as $userId) {
            try {
                // Create flag in tbl_user_flags
                $this->createHibahFlag($userId, $hibahId, $adminId);
                $results['flags_created']++;

                // Update flag_status in tbl_users
                $this->updateUserFlagStatus($userId, 'active', $adminId);
                $results['users_updated']++;
            } catch (\Exception $e) {
                $results['errors'][] = "Error for user {$userId}: " . $e->getMessage();
            }
        }

        return $results;
    }

    /**
     * Update flag_status in tbl_users for a user
     * 
     * @param int $userId User ID
     * @param string $status Status (active, pending, inactive)
     * @param int|null $verifiedBy Admin ID who verified
     * @param string|null $notes Notes
     * @return bool
     */
    public function updateUserFlagStatus($userId, $status, $verifiedBy = null, $notes = null)
    {
        $data = [
            'flag_status' => $status,
            'flag_verified_at' => date('Y-m-d H:i:s'),
            'flag_verified_by' => $verifiedBy
        ];

        if ($notes) {
            $data['flag_notes'] = $notes;
        }

        if ($status === 'inactive') {
            $data['flag_verified_at'] = null;
            $data['flag_verified_by'] = null;
            $data['flag_notes'] = null;
        }

        return $this->usersModel->update($userId, $data);
    }

    /**
     * Get active flags for user
     */
    public function getActiveFlags($userId)
    {
        return $this->flagModel->getFlagsByUser($userId);
    }

    /**
     * Get hibah flags for user
     */
    public function getHibahFlags($userId)
    {
        return $this->flagModel->getHibahFlags($userId);
    }

    /**
     * Count approved hibah for user
     */
    public function countApprovedHibah($userId)
    {
        $flags = $this->flagModel->getHibahFlags($userId);
        return count($flags);
    }

    /**
     * Check if user has hibah flag
     */
    public function hasHibahFlag($userId)
    {
        $flags = $this->flagModel->getHibahFlags($userId);
        return !empty($flags);
    }

    /**
     * Check if user already has hibah_approved flag for specific hibah
     * 
     * @param int $userId User ID
     * @param int $hibahId Hibah ID
     * @return bool
     */
    public function hasHibahFlagForHibah($userId, $hibahId)
    {
        return $this->flagModel->where('user_id', $userId)
            ->where('hibah_id', $hibahId)
            ->where('flag_type', 'hibah_approved')
            ->first() !== null;
    }
}

<?php

namespace App\Libraries;

use App\Models\HibahModel;
use App\Models\HibahAnggotaModel;
use App\Models\UserFlagsModel;
use App\Models\UsersModel;

class HibahService
{
    protected $hibahModel;
    protected $hibahAnggotaModel;
    protected $userFlagsModel;
    protected $usersModel;

    public function __construct()
    {
        $this->hibahModel        = new HibahModel();
        $this->hibahAnggotaModel = new HibahAnggotaModel();
        $this->userFlagsModel    = new UserFlagsModel();
        $this->usersModel        = new UsersModel();
    }

    /**
     * Approve hibah and create flag for all members
     */
    public function approveHibah($hibahId, $adminId, $notes = null)
    {
        $hibah = $this->hibahModel->find($hibahId);

        if (!$hibah) {
            throw new \Exception('Hibah tidak ditemukan');
        }

        $hibah = (array) $hibah;

        if (!in_array($hibah['verification_status'] ?? '', ['draft', 'submitted'])) {
            throw new \Exception('Hibah tidak dapat diapprove karena sudah berstatus: ' . ($hibah['verification_status'] ?? 'unknown'));
        }

        // Update hibah status
        $this->hibahModel->update($hibahId, [
            'verification_status' => 'approved',
            'status'              => 'approved',
            'verified_at'         => date('Y-m-d H:i:s'),
            'verified_by'         => $adminId,
            'verification_notes'  => $notes,
        ]);

        // Get all members (ketua + anggota)
        $memberIds = $this->getAllMemberIds($hibah);

        // Create flags for all members
        $flagResults = $this->createHibahFlagsForAllMembers($memberIds, $hibahId, $adminId, $notes);

        log_message('info', 'Hibah approved: ID=' . $hibahId .
            '. Flags created: ' . $flagResults['flags_created'] .
            ', Users updated: ' . $flagResults['users_updated']);

        if (!empty($flagResults['errors'])) {
            log_message('error', 'Hibah approval flag errors: ' . implode(', ', $flagResults['errors']));
        }

        return true;
    }

    /**
     * Reject hibah
     */
    public function rejectHibah($hibahId, $adminId, $notes)
    {
        $hibah = $this->hibahModel->find($hibahId);

        if (!$hibah) {
            throw new \Exception('Hibah tidak ditemukan');
        }

        $hibah = (array) $hibah;

        if (!in_array($hibah['verification_status'] ?? '', ['draft', 'submitted'])) {
            throw new \Exception('Hibah tidak dapat direject karena sudah berstatus: ' . ($hibah['verification_status'] ?? 'unknown'));
        }

        // Update hibah status
        $this->hibahModel->update($hibahId, [
            'verification_status' => 'rejected',
            'status'              => 'rejected',
            'verified_at'         => date('Y-m-d H:i:s'),
            'verified_by'         => $adminId,
            'verification_notes'  => $notes,
        ]);

        // Update flag tbl_user_flags jika ada yang pending → reject
        $this->rejectUserFlags($hibahId, $adminId, $notes);

        return true;
    }

    /**
     * Get all member IDs (ketua + anggota) for a hibah
     */
    private function getAllMemberIds(array $hibah): array
    {
        $memberIds = [];

        if (!empty($hibah['user_id'])) {
            $memberIds[] = (int) $hibah['user_id'];
        }

        if (!empty($hibah['ketua_id']) && !in_array((int)$hibah['ketua_id'], $memberIds)) {
            $memberIds[] = (int) $hibah['ketua_id'];
        }

        $anggota = $this->hibahAnggotaModel->where('hibah_id', $hibah['id'])->findAll();

        foreach ($anggota as $member) {
            $member = (array) $member;
            if (!empty($member['anggota_id']) && !in_array((int)$member['anggota_id'], $memberIds)) {
                $memberIds[] = (int) $member['anggota_id'];
            }
        }

        return $memberIds;
    }

    /**
     * Create flags di tbl_user_flags dan aktifkan flag_status di tbl_users
     * Disesuaikan dengan struktur tabel aktual:
     * tbl_user_flags: id, user_id, hibah_id, flag_type, flag_value, status, action_by, notes, created_at, updated_at
     * tbl_users: flag_status enum('inactive','pending','active'), flag_verified_at, flag_verified_by, flag_notes
     */
    private function createHibahFlagsForAllMembers(array $memberIds, $hibahId, $adminId, $notes = null): array
    {
        $results = [
            'flags_created' => 0,
            'users_updated' => 0,
            'errors'        => [],
        ];

        foreach ($memberIds as $userId) {

            // === INSERT / UPDATE tbl_user_flags ===
            try {
                $existing = $this->userFlagsModel
                    ->where('user_id', $userId)
                    ->where('hibah_id', $hibahId)
                    ->where('flag_type', 'hibah_approved')
                    ->first();

                if (!$existing) {
                    $this->userFlagsModel->insert([
                        'user_id'    => $userId,
                        'hibah_id'   => $hibahId,
                        'flag_type'  => 'hibah_approved',
                        'flag_value' => 'approved',           // kolom flag_value
                        'status'     => 'approve',            // enum: 'request','approve','reject'
                        'action_by'  => $adminId,             // kolom action_by (bukan created_by)
                        'notes'      => $notes,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                    $results['flags_created']++;
                } else {
                    // Update status jika sudah ada
                    $existingArr = (array) $existing;
                    $this->userFlagsModel->update($existingArr['id'], [
                        'flag_value' => 'approved',
                        'status'     => 'approve',
                        'action_by'  => $adminId,
                        'notes'      => $notes,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            } catch (\Exception $e) {
                $results['errors'][] = '[FLAG] User ID ' . $userId . ': ' . $e->getMessage();
                log_message('error', 'createHibahFlag insert error uid=' . $userId . ': ' . $e->getMessage());
            }

            // === UPDATE tbl_users flag_status ===
            try {
                // flag_status enum: 'inactive','pending','active'
                $updated = $this->usersModel->update($userId, [
                    'flag_status'      => 'active',           // enum value yang benar
                    'flag_verified_at' => date('Y-m-d H:i:s'),
                    'flag_verified_by' => $adminId,           // kolom flag_verified_by
                    'flag_notes'       => $notes,             // kolom flag_notes
                ]);

                if ($updated) {
                    $results['users_updated']++;
                }
            } catch (\Exception $e) {
                $results['errors'][] = '[USER] User ID ' . $userId . ': ' . $e->getMessage();
                log_message('error', 'createHibahFlag update user error uid=' . $userId . ': ' . $e->getMessage());
            }
        }

        return $results;
    }

    /**
     * Update flag di tbl_user_flags menjadi reject saat hibah ditolak
     */
    private function rejectUserFlags($hibahId, $adminId, $notes): void
    {
        try {
            $flags = $this->userFlagsModel
                ->where('hibah_id', $hibahId)
                ->where('flag_type', 'hibah_approved')
                ->findAll();

            foreach ($flags as $flag) {
                $flag = (array) $flag;
                $this->userFlagsModel->update($flag['id'], [
                    'flag_value' => 'rejected',
                    'status'     => 'reject',     // enum: 'request','approve','reject'
                    'action_by'  => $adminId,
                    'notes'      => $notes,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                // Reset flag_status user ke inactive
                $this->usersModel->update($flag['user_id'], [
                    'flag_status'      => 'inactive',
                    'flag_verified_at' => date('Y-m-d H:i:s'),
                    'flag_verified_by' => $adminId,
                    'flag_notes'       => $notes,
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'rejectUserFlags error: ' . $e->getMessage());
        }
    }
}
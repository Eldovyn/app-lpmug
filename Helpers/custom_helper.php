<?php

use App\Models\UserFlagsModel;
use App\Models\UsersModel;
// userLogin() is declared in auth_helper.php

// ============================================================
// Count helpers — simple per-request cached versions
// ============================================================

function countData($table)
{
    $db = \Config\Database::connect();
    return $db->table($table)->countAllResults();
}

function countDataPengguna()
{
    $db = \Config\Database::connect();
    return $db->table('tbl_users')->where('role_id !=', 1)->countAllResults();
}

function countDataDosen()
{
    $db = \Config\Database::connect();
    return $db->table('tbl_users')->where('role_id', 4)->countAllResults();
}

function countDataMitra()
{
    $db = \Config\Database::connect();
    return $db->table('tbl_users')->where('role_id', 5)->countAllResults();
}

// ============================================================
// User Flag helpers — per-request cached to avoid N+1
// ============================================================

/**
 * Internal: Load all hibah flags for a user (cached per request).
 *
 * All flag helper functions share this cache so we never query
 * tbl_hibah_flags more than once per request per user.
 */
function _getHibahFlagsCached(int $userId): array
{
    static $cache = [];

    if (! array_key_exists($userId, $cache)) {
        try {
            $flagModel     = new UserFlagsModel();
            $cache[$userId] = $flagModel->getHibahFlags($userId) ?? [];
        } catch (\Exception $e) {
            log_message('error', 'getHibahFlags cache error: ' . $e->getMessage());
            $cache[$userId] = [];
        }
    }

    return $cache[$userId];
}

/**
 * Check if user has hibah flag (approved hibah).
 */
function hasHibahFlag(int $userId = null): bool
{
    $userId = $userId ?? (userLogin()->user_id ?? null);

    if (! $userId) {
        return false;
    }

    return ! empty(_getHibahFlagsCached((int) $userId));
}

/**
 * Get user's flag status from tbl_users (active/pending/inactive/null).
 */
function getUserFlagStatus(int $userId = null): ?string
{
    static $statusCache = [];

    $userId = $userId ?? (userLogin()->user_id ?? null);

    if (! $userId) {
        return null;
    }

    $userId = (int) $userId;

    if (! array_key_exists($userId, $statusCache)) {
        try {
            $usersModel            = new UsersModel();
            $user                  = $usersModel->find($userId);
            $statusCache[$userId]  = $user->flag_status ?? null;
        } catch (\Exception $e) {
            log_message('error', 'getUserFlagStatus error: ' . $e->getMessage());
            $statusCache[$userId] = null;
        }
    }

    return $statusCache[$userId];
}

/**
 * Count approved hibah for user.
 */
function countApprovedHibah(int $userId = null): int
{
    $userId = $userId ?? (userLogin()->user_id ?? null);

    if (! $userId) {
        return 0;
    }

    return count(_getHibahFlagsCached((int) $userId));
}

/**
 * Get all hibah flags for user.
 */
function getHibahFlags(int $userId = null): array
{
    $userId = $userId ?? (userLogin()->user_id ?? null);

    if (! $userId) {
        return [];
    }

    return _getHibahFlagsCached((int) $userId);
}

/**
 * Check if user's hibah flag is active.
 */
function isFlagActive(int $userId = null): bool
{
    return getUserFlagStatus($userId) === 'active';
}

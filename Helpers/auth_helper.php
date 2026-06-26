<?php

/**
 * Auth Helper
 *
 * Provides per-request static-cached user lookup and language resolution.
 * The userLogin() function uses a static variable so that repeated calls
 * within the same request only hit the database ONCE, not once per call.
 */

/**
 * Get the currently logged-in user.
 *
 * Cached in a static variable for the lifetime of the request, so
 * multiple calls within a single page load cost exactly 1 DB query.
 *
 * Returns null if no user is logged in.
 */
function userLogin(): ?object
{
    static $cachedUser = false;

    if ($cachedUser === false) {
        $userId = session('user_id');

        if (! $userId) {
            $cachedUser = null;
            return null;
        }

        $db = \Config\Database::connect();
        $cachedUser = $db->table('tbl_users')
            ->select(
                'user_id, user_name, role_id, email, nidn, sinta_id, ' .
                'gelar_dpn, gelar_blkng, jurusan_id, jurusan_nya, fungsional_id, ' .
                'universitas_id, kota_id, kontak, photo, status, alamat, ' .
                'kebutuhan, flag_status, flag_verified_at, flag_verified_by, flag_notes, ' .
                'spm, skm, created_at, updated_at'
            )
            ->where('user_id', $userId)
            ->get()
            ->getRow();
    }

    return $cachedUser;
}

/**
 * Resolve the active language from GET param, cookie, or default.
 *
 * Replaces the ~15-line language detection block that was copy-pasted
 * into every controller method.
 *
 * @param  \CodeIgniter\HTTP\IncomingRequest $request
 * @param  array  $allowed  Allowed language codes
 * @param  string $default  Default language
 * @return string
 */
function resolveLanguage($request, array $allowed = ['id', 'en'], string $default = 'id'): string
{
    $langGet    = $request->getGet('lang');
    $langCookie = $request->getCookie('lang');

    $lang = $langGet ?? $langCookie ?? $default;
    $lang = strtolower(trim((string) $lang));

    if (! in_array($lang, $allowed, true)) {
        $lang = $default;
    }

    // Persist to cookie if it came from GET param
    if ($langGet && in_array($langGet, $allowed, true) && $langGet !== $langCookie) {
        set_cookie('lang', $lang, 60 * 60 * 24 * 30);
    }

    return $lang;
}

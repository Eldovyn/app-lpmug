<?php

namespace App\Controllers;

use App\Models\HakAksesModel;
use App\Models\ProfileModel;
use App\Models\DosenModel;
use App\Models\UniversitasModel;
use App\Models\FakultasModel;
use App\Models\JurusanModel;
use App\Models\FungsionalModel;
use App\Models\MitraModel;
use App\Models\ProvinsiModel;
use App\Models\KotaModel;
use App\Models\PesanModel;
use App\Models\AbdimasModel;
use Google\Cloud\Translate\V2\TranslateClient;

class Dashboard extends BaseController
{
    function __construct()
    {
        $this->hak_akses    = new HakAksesModel();
        $this->dosen        = new DosenModel();
        $this->universitas  = new UniversitasModel();
        $this->fakultas     = new FakultasModel();
        $this->jurusan      = new JurusanModel();
        $this->fungsional   = new FungsionalModel();
        $this->mitra        = new MitraModel();
        $this->provinsi     = new ProvinsiModel();
        $this->kota         = new KotaModel();
        $this->profile      = new ProfileModel();
        $this->pesan        = new PesanModel();
        $this->abdimas      = new AbdimasModel();
    }

    public function index(): string
{
    helper('cookie');
    helper('custom');

    // ===== LANG (GET > cookie > default) =====
    $langCookie = $this->request->getCookie('lang');
    $langGet    = $this->request->getGet('lang');

    $allowed = ['id', 'en'];

    if ($langGet && in_array($langGet, $allowed, true)) {
        $lang = $langGet;
    } elseif ($langCookie && in_array($langCookie, $allowed, true)) {
        $lang = $langCookie;
    } else {
        $lang = 'id';
    }

    if ($langCookie !== $lang) {
        set_cookie('lang', $lang, 60 * 60 * 24 * 30);
    }

    // ===== INISIALISASI $data TERLEBIH DAHULU =====
    $data         = [];
    $data['lang'] = $lang;

    // ===== PESAN - gunakan array_merge, JANGAN overwrite =====
    $keyword = $this->request->getGet('keyword');
    $paginated = $this->pesan->getPaginated(100000, $keyword);
    $data = array_merge($data, $paginated);
    $data['keyword'] = $keyword;
    $data['pesan']   = $this->pesan->getPesan();

// ===== GRAFIK LAPORAN PER PERIODE (FIXED - Pastikan 5 periode lengkap) =====
        $customOrder = ['ATA 2023/2024', 'PTA 2024/2025', 'ATA 2024/2025', 'PTA 2025/2026', 'ATA 2025/2026'];

        // Ambil count laporan per periode
        $laporanBuilder = $this->db->table('tbl_periode');
        $laporanBuilder->select("
            CONCAT(tbl_periode.periode_name, ' ', tbl_periode.tahun_ajaran) AS periode_label,
            COUNT(tbl_laporan.laporan_id) AS total
        ");
        $laporanBuilder->join(
            'tbl_laporan',
            'tbl_periode.periode_id = tbl_laporan.periode_id AND tbl_laporan.periode_id IS NOT NULL',
            'left'
        );
        $laporanBuilder->whereIn("CONCAT(tbl_periode.periode_name, ' ', tbl_periode.tahun_ajaran)", $customOrder);
        $laporanBuilder->groupBy('tbl_periode.periode_id');

        $laporanCounts = $laporanBuilder->get()->getResultArray();

        // Buat map: label => total (untuk lookup cepat)
        $countsMap = array_column($laporanCounts, 'total', 'periode_label');

        // Pastikan semua periode ada (isi 0 jika tidak ada laporan)
        $labels = [];
        $totals = [];
        foreach ($customOrder as $periode) {
            $labels[] = $periode;
            $totals[] = isset($countsMap[$periode]) ? (int)$countsMap[$periode] : 0;
        }

        // DEBUG (hapus setelah testing)
        // log_message('debug', 'Labels: ' . json_encode($labels));
        // log_message('debug', 'Totals: ' . json_encode($totals));

        $data['periodeLabels']           = json_encode($labels);
        $data['periodeTotals']           = json_encode($totals);
        $data['totalLaporanKeseluruhan'] = array_sum($totals);
        $data['countJurusanUnik']        = $this->abdimas->countJurusanUnik();

        // ===== FILTER JURUSAN =====
        $jurusan                 = $this->request->getGet('jurusan') ?? null;
        $data['selectedJurusan'] = $jurusan;

        $data['dataPerProdi']     = $this->cacheRemember('data_per_prodi_unique', 1800, function() {
            return $this->abdimas->getDataJumlahPerProdiUnique();
        });
        $data['dataKetuaAnggota'] = $this->cacheRemember('data_ketua_anggota', 1800, function() {
            return $this->abdimas->getJumlahKetuaAnggota();
        });

    // ===== JURUSAN LIST =====
    $data['jurusanList'] = $this->cacheRemember("jurusan_list_{$lang}", 60 * 60 * 24, function () use ($lang) {
        $list = $this->abdimas->getJurusanList();

        $out = [];
        foreach ($list as $row) {
            $value = $row['jurusan_id'] ?? $row['id'] ?? $row['kode'] ?? null;
            $label = $row['jurusan_name'] ?? $row['nama'] ?? $row['nama_jurusan'] ?? $row['jurusan'] ?? '';

            if ($lang === 'en') {
                $label = $this->translateText($label, 'id', 'en');
            }

            $out[] = ['value' => $value, 'label' => $label];
        }
        return $out;
    });

    // ===== LUARAN CHART DATA (all, for JS filtering) =====
    $data['luaranChartData'] = $this->cacheRemember("luaran_all_{$lang}", 60 * 60 * 24, function () use ($lang) {
        $rows = $this->abdimas->getLuaranDataByJurusan();

        if ($lang === 'en') {
            foreach ($rows as &$r) {
                if (isset($r['luaran_name'])) {
                    $r['luaran_name'] = $this->translateText((string) $r['luaran_name'], 'id', 'en');
                }
                if (isset($r['jurusan_name'])) {
                    $r['jurusan_name'] = $this->translateText((string) $r['jurusan_name'], 'id', 'en');
                }
            }
            unset($r);
        }

        return $rows;
    });

    // ===== CHART DATA LUARAN DEFAULT =====
    $data['chartData'] = $this->cacheRemember("luaran_default_{$lang}", 60 * 60 * 24, function () use ($lang) {
        $rows = $this->abdimas->getLuaranChartData(null);

        if ($lang === 'en') {
            foreach ($rows as &$r) {
                if (isset($r['luaran_name'])) {
                    $r['luaran_name'] = $this->translateText((string) $r['luaran_name'], 'id', 'en');
                }
            }
            unset($r);
        }

        return $rows;
    });

    // ===== FLAG DATA FOR LOGGED IN USER =====
    $user = userLogin();
    if ($user && isset($user->user_id)) {
        $data['user_flag_status']         = getUserFlagStatus($user->user_id);
        $data['user_has_hibah_flag']       = hasHibahFlag($user->user_id);
        $data['user_approved_hibah_count'] = countApprovedHibah($user->user_id);
        $data['user_hibah_flags']          = getHibahFlags($user->user_id);
    } else {
        $data['user_flag_status']         = null;
        $data['user_has_hibah_flag']       = false;
        $data['user_approved_hibah_count'] = 0;
        $data['user_hibah_flags']          = [];
    }

    return view('dashboard', $data);
}


    // CONTROLLER UNTUK PROFILE SUPERIOR //
    public function profile($id = null)
    {
        if (userLogin()->role_id != 1) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '' && userLogin()->user_id == '') {
            return redirect()->to(site_url('login'));
        }
        $data['title_tab'] = 'Profile user &mdash; LPM UG';
        $data['title'] = 'Profile user';
        $data['pesan'] = $this->pesan->getPesan();
        $profile_user =  $this->profile->find($id);
        if (is_object($profile_user) && $profile_user->role_id != 2 && $profile_user->role_id != 3 && $profile_user->role_id != 4 && $profile_user->role_id != 5) {
            $data['profile_user'] = $profile_user;
            return view('profile/edit_profile', $data);
        } else {
            // throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            return redirect()->to(site_url('profile_user/update/' . userLogin()->user_id));
        }
        return view('profile/edit_profile', $data);
    }
    public function update_profile($id)
    {
        $currentUser = userLogin();
        if (!$currentUser || ($currentUser->user_id != $id && !in_array((int)$currentUser->role_id, [1, 2], true))) {
            return redirect()->to(site_url('dashboard'))->with('error', 'Akses tidak sah.');
        }
        $data = [
            'user_name'     => $this->request->getVar('user_name'),
            'nidn'          => $this->request->getVar('nidn'),
            'email'         => $this->request->getVar('email'),
            'kontak'        => $this->request->getVar('kontak'),
            // 'password'      => password_hash($this->request->getVar('password'), PASSWORD_BCRYPT),
        ];
        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $message = 'Data anda berhasil diupdate';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $message = service('translation')->translateCached('Data anda berhasil diupdate', 'id', 'en');
        }
        // dd($data);
        $this->profile->update($id, $data);
        return redirect()->to(site_url('profile_user/update/' . userLogin()->user_id))->with('success', $message);
    }

    public function ubah_password($id = null)
    {
        if (userLogin()->role_id != 1) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '' && userLogin()->user_id == '') {
            return redirect()->to(site_url('login'));
        }

        $data['title_tab'] = 'Ubah password &mdash; LPM UG';
        $data['title'] = 'Ubah Password';
        $data['pesan'] = $this->pesan->getPesan();
        $data['validation'] = \Config\Services::validation();

        $ubah_password =  $this->profile->find($id);
        if (is_object($ubah_password) && $ubah_password->role_id != 2 && $ubah_password->role_id != 3 && $ubah_password->role_id != 4 && $ubah_password->role_id != 5) {
            $data['ubah_password'] = $ubah_password;
            $data['validation'] = \Config\Services::validation();

            return view('profile/ubah_password', $data);
        } else {
            // throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            return redirect()->to(site_url('profile_user/update/' . userLogin()->user_id));
        }

        return view('profile/ubah_password', $data);
    }

    public function update_password($id)
    {
        $currentUser = userLogin();
        if (!$currentUser || ($currentUser->user_id != $id && !in_array((int)$currentUser->role_id, [1, 2], true))) {
            return redirect()->to(site_url('dashboard'))->with('error', 'Akses tidak sah.');
        }
        if (!$this->validate($this->profile->getValidationRules())) {
            $validation = \Config\Services::validation();
            // dd($validation);
            return redirect()->back()->withInput()->with('validation', $validation);
        }

        $data = [
            'password'      => password_hash($this->request->getVar('password'), PASSWORD_BCRYPT),
        ];
        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $message = 'Data anda berhasil diupdate';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $message = service('translation')->translateCached('Data anda berhasil diupdate', 'id', 'en');
        }
        // dd($data);
        $this->profile->update($id, $data);
        return redirect()->to(site_url('ubah_password/update/' . userLogin()->user_id))->with('success', $message);
    }
    // END CONTROLLER UNTUK PROFILE SUPERIOR //


    // CONTROLLER UNTUK PROFILE ADMINISTRATOR//
    public function profile_admin($id = null)
    {
        if (userLogin()->role_id != 2) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '' && userLogin()->user_id == '') {
            return redirect()->to(site_url('login'));
        }

        $baseTitle = 'Profile User';
        $title = $baseTitle;

        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }
        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $title = service('translation')->translateCached($baseTitle, 'id', 'en'); // V2 translate :contentReference[oaicite:0]{index=0}
        }
        $data['title'] = $title;
        $data['title_tab'] = $title . ' &mdash; LPM UG';

        $data['pesan'] = $this->pesan->getPesan();

        $profile_user_admin =  $this->profile->find($id);
        if (is_object($profile_user_admin) && $profile_user_admin->role_id != 1 && $profile_user_admin->role_id != 3 && $profile_user_admin->role_id != 4 && $profile_user_admin->role_id != 5) {
            $data['profile_user_admin'] = $profile_user_admin;
            $data['universitas'] = $this->universitas->findAll();
            $data['fungsional'] = $this->fungsional->findAll();
            $data['jurusan'] = $this->jurusan->getAll();
            $data['kota'] = $this->kota->getAll();
            return view('profile/edit_profile_admin', $data);
        } else {
            // throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            return redirect()->to(site_url('profile_user_admin/update/' . userLogin()->user_id));
        }

        return view('profile/edit_profile_admin', $data);
    }

    public function update_profile_admin($id)
    {
        $currentUser = userLogin();
        if (!$currentUser || ($currentUser->user_id != $id && !in_array((int)$currentUser->role_id, [1, 2], true))) {
            return redirect()->to(site_url('dashboard'))->with('error', 'Akses tidak sah.');
        }
        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $message = 'Data anda berhasil diupdate';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $message = service('translation')->translateCached('Data anda berhasil diupdate', 'id', 'en');
        }
        $data = [
            'gelar_dpn'     => $this->request->getVar('gelar_dpn'),
            'user_name'     => $this->request->getVar('user_name'),
            'gelar_blkng'   => $this->request->getVar('gelar_blkng'),
            'sinta_id'      => $this->request->getVar('sinta_id'),
            'nidn'          => $this->request->getVar('nidn'),
            'email'         => $this->request->getVar('email'),
            'kontak'        => $this->request->getVar('kontak'),
            'universitas_id' => $this->request->getVar('universitas_id'),
            'jurusan_id'    => $this->request->getVar('jurusan_id'),
            'fungsional_id' => $this->request->getVar('fungsional_id'),
            'kota_id'       => $this->request->getVar('kota_id'),
            'alamat'        => $this->request->getVar('alamat'),
        ];
        // dd($data);
        $this->profile->update($id, $data);
        return redirect()->to(site_url('profile_user_admin/update/' . userLogin()->user_id))->with('success', $message);
    }

    public function ubah_password_admin($id = null)
    {
        helper('cookie');
        if (userLogin()->role_id != 2) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '' && userLogin()->user_id == '') {
            return redirect()->to(site_url('login'));
        }

        $baseTitle = 'Ubah Password';
        $title = $baseTitle;

        $allowed = ['id', 'en'];

        $lang = $this->request->getCookie('lang') ?? 'id';

        if (!in_array($lang, $allowed, true)) {
            $lang = 'id';
        }

        $reqLang = $this->request->getGet('lang');
        if ($reqLang && in_array($reqLang, $allowed, true)) {
            set_cookie('lang', $reqLang, 60 * 60 * 24 * 30);
            $lang = $reqLang;
        }

        if ($lang === 'en') {
            $title = service('translation')->translateCached($baseTitle, 'id', 'en');
        }
        $data['title'] = $title;
        $data['title_tab'] = $title . ' &mdash; LPM UG';

        $data['pesan'] = $this->pesan->getPesan();
        $data['validation'] = \Config\Services::validation();

        $ubah_password_admin =  $this->profile->find($id);
        if (is_object($ubah_password_admin) && $ubah_password_admin->role_id != 1 && $ubah_password_admin->role_id != 3 && $ubah_password_admin->role_id != 4 && $ubah_password_admin->role_id != 5) {
            $data['ubah_password_admin'] = $ubah_password_admin;
            $data['validation'] = \Config\Services::validation();

            return view('profile/ubah_password_admin', $data);
        } else {
            // throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            return redirect()->to(site_url('ubah_password_admin/update/' . userLogin()->user_id));
        }

        return view('profile/ubah_password_admin', $data);
    }

    public function update_password_admin($id)
    {
        $currentUser = userLogin();
        if (!$currentUser || ($currentUser->user_id != $id && !in_array((int)$currentUser->role_id, [1, 2], true))) {
            return redirect()->to(site_url('dashboard'))->with('error', 'Akses tidak sah.');
        }
        if (!$this->validate($this->profile->getValidationRules())) {
            $validation = \Config\Services::validation();
            // dd($validation);
            return redirect()->back()->withInput()->with('validation', $validation);
        }

        $data = [
            'password'      => password_hash($this->request->getVar('password'), PASSWORD_BCRYPT),
        ];
        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $message = 'Data anda berhasil diupdate';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $message = service('translation')->translateCached('Data anda berhasil diupdate', 'id', 'en');
        }
        // dd($data);
        $this->profile->update($id, $data);
        return redirect()->to(site_url('ubah_password_admin/update/' . userLogin()->user_id))->with('success', $message);
    }
    // END CONTROLLER UNTUK PROFILE ADMINISTRATOR //

    // CONTROLLER UNTUK PROFILE STAFF LPM UG //
    public function profile_staff($id = null)
    {
        if (userLogin()->role_id != 3) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '' && userLogin()->user_id == '') {
            return redirect()->to(site_url('login'));
        }

        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $baseTitle = 'Profile User'; // kalau mau ID lebih "indo": 'Profil Staff'
        $title = $baseTitle;

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $title = service('translation')->translateCached($baseTitle, 'id', 'en'); // V2 translate :contentReference[oaicite:0]{index=0}
        }
        $data['title'] = $title;
        $data['title_tab'] = $title . ' &mdash; LPM UG';

        $profile_user_staff =  $this->profile->find($id);
        if (is_object($profile_user_staff) && $profile_user_staff->role_id != 1 && $profile_user_staff->role_id != 2 && $profile_user_staff->role_id != 4 && $profile_user_staff->role_id != 5) {
            $data['profile_user_staff'] = $profile_user_staff;
            $data['universitas'] = $this->universitas->findAll();
            $data['fungsional'] = $this->fungsional->findAll();
            $data['jurusan'] = $this->jurusan->getAll();
            $data['kota'] = $this->kota->getAll();
            return view('profile/edit_profile_staff', $data);
        } else {
            // throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            return redirect()->to(site_url('profile_user_staff/update/' . userLogin()->user_id));
        }

        return view('profile/edit_profile_staff', $data);
    }


    public function update_profile_staff($id)
    {
        $currentUser = userLogin();
        if (!$currentUser || ($currentUser->user_id != $id && !in_array((int)$currentUser->role_id, [1, 2], true))) {
            return redirect()->to(site_url('dashboard'))->with('error', 'Akses tidak sah.');
        }
        $data = [
            'gelar_dpn'     => $this->request->getVar('gelar_dpn'),
            'user_name'     => $this->request->getVar('user_name'),
            'gelar_blkng'   => $this->request->getVar('gelar_blkng'),
            'sinta_id'      => $this->request->getVar('sinta_id'),
            'nidn'          => $this->request->getVar('nidn'),
            'email'         => $this->request->getVar('email'),
            'kontak'        => $this->request->getVar('kontak'),
            'universitas_id' => $this->request->getVar('universitas_id'),
            'jurusan_id'    => $this->request->getVar('jurusan_id'),
            'fungsional_id' => $this->request->getVar('fungsional_id'),
            'kota_id'       => $this->request->getVar('kota_id'),
            'alamat'        => $this->request->getVar('alamat'),
        ];
        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $message = 'Data anda berhasil diupdate';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $message = service('translation')->translateCached('Data anda berhasil diupdate', 'id', 'en');
        }
        // dd($data);
        $this->profile->update($id, $data);
        return redirect()->to(site_url('profile_user_staff/update/' . userLogin()->user_id))->with('success', $message);
    }

    public function ubah_password_staff($id = null)
    {
        if (userLogin()->role_id != 3) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '' && userLogin()->user_id == '') {
            return redirect()->to(site_url('login'));
        }

        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $baseTitle = 'Ubah Password'; // kalau mau ID lebih "indo": 'Profil Staff'
        $title = $baseTitle;

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $title = service('translation')->translateCached($baseTitle, 'id', 'en'); // V2 translate :contentReference[oaicite:0]{index=0}
        }
        $data['title'] = $title;
        $data['title_tab'] = $title . ' &mdash; LPM UG';

        $data['validation'] = \Config\Services::validation();

        $ubah_password_staff =  $this->profile->find($id);
        if (is_object($ubah_password_staff) && $ubah_password_staff->role_id != 1 && $ubah_password_staff->role_id != 2 && $ubah_password_staff->role_id != 4 && $ubah_password_staff->role_id != 5) {
            $data['ubah_password_staff'] = $ubah_password_staff;
            $data['validation'] = \Config\Services::validation();

            return view('profile/ubah_password_staff', $data);
        } else {
            // throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            return redirect()->to(site_url('ubah_password_staff/update/' . userLogin()->user_id));
        }

        return view('profile/ubah_password_staff', $data);
    }

    public function update_password_staff($id)
    {
        $currentUser = userLogin();
        if (!$currentUser || ($currentUser->user_id != $id && !in_array((int)$currentUser->role_id, [1, 2], true))) {
            return redirect()->to(site_url('dashboard'))->with('error', 'Akses tidak sah.');
        }
        if (!$this->validate($this->profile->getValidationRules())) {
            $validation = \Config\Services::validation();
            // dd($validation);
            return redirect()->back()->withInput()->with('validation', $validation);
        }

        $data = [
            'password'      => password_hash($this->request->getVar('password'), PASSWORD_BCRYPT),
        ];
        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $message = 'Data anda berhasil diupdate';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $message = service('translation')->translateCached('Data anda berhasil diupdate', 'id', 'en');
        }
        // dd($data);
        $this->profile->update($id, $data);
        return redirect()->to(site_url('ubah_password_staff/update/' . userLogin()->user_id))->with('success', $message);
    }
    // END CONTROLLER UNTUK PROFILE STAFF LPM UG //

    // CONTROLLER UNTUK PROFILE DOSEN //
    public function profile_dosen($id = null)
    {
        if (userLogin()->role_id != 4) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '' && userLogin()->user_id == '') {
            return redirect()->to(site_url('login'));
        }

        $data['title_tab'] = 'Profile user &mdash; LPM UG';
        $data['title'] = 'Profile user';

        $profile_user_dosen =  $this->profile->find($id);
        if (is_object($profile_user_dosen) && $profile_user_dosen->role_id != 1 && $profile_user_dosen->role_id != 2 && $profile_user_dosen->role_id != 3 && $profile_user_dosen->role_id != 5) {
            $data['profile_user_dosen'] = $profile_user_dosen;
            $data['universitas'] = $this->universitas->findAll();
            $data['fungsional'] = $this->fungsional->findAll();
            $data['jurusan'] = $this->jurusan->getAll();
            $data['kota'] = $this->kota->getAll();
            return view('profile/edit_profile_dosen', $data);
        } else {
            // throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            return redirect()->to(site_url('profile_user_dosen/update/' . userLogin()->user_id));
        }

        return view('profile/edit_profile_dosen', $data);
    }

    public function update_profile_dosen($id)
    {
        $currentUser = userLogin();
        if (!$currentUser || ($currentUser->user_id != $id && !in_array((int)$currentUser->role_id, [1, 2], true))) {
            return redirect()->to(site_url('dashboard'))->with('error', 'Akses tidak sah.');
        }
        $data = [
            'gelar_dpn'     => $this->request->getVar('gelar_dpn'),
            'user_name'     => $this->request->getVar('user_name'),
            'gelar_blkng'   => $this->request->getVar('gelar_blkng'),
            'sinta_id'      => $this->request->getVar('sinta_id'),
            'nidn'          => $this->request->getVar('nidn'),
            'email'         => $this->request->getVar('email'),
            'kontak'        => $this->request->getVar('kontak'),
            'universitas_id' => $this->request->getVar('universitas_id'),
            'jurusan_id'    => $this->request->getVar('jurusan_id'),
            'fungsional_id' => $this->request->getVar('fungsional_id'),
            'kota_id'       => $this->request->getVar('kota_id'),
            'alamat'        => $this->request->getVar('alamat'),
        ];
        helper('cookie');

        $allowed = ['id', 'en'];

        $lang = $this->request->getGet('lang');

        if (! $lang) {
            $lang = $this->request->getCookie('lang');
        }

        $lang = strtolower(trim((string) ($lang ?? 'id')));
        if (! in_array($lang, $allowed, true)) {
            $lang = 'id';
        }

        $reqLang = $this->request->getGet('lang');
        if ($reqLang && in_array($reqLang, $allowed, true)) {
            set_cookie('lang', $reqLang, 60 * 60 * 24 * 30);
        }

        $message = 'Data anda berhasil diupdate';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $message = service('translation')->translateCached('Data anda berhasil diupdate', 'id', 'en');
        }
        // dd($data);
        $this->profile->update($id, $data);
        return redirect()->to(site_url('profile_user_dosen/update/' . userLogin()->user_id))->with('success', $message);
    }

    public function ubah_password_dosen($id = null)
    {
        helper('cookie');

        $langCookie = $this->request->getCookie('lang');
        $langGet    = $this->request->getGet('lang');

        $allowed = ['id', 'en'];

        if ($langGet && in_array($langGet, $allowed, true)) {
            $lang = $langGet;
        } elseif ($langCookie && in_array($langCookie, $allowed, true)) {
            $lang = $langCookie;
        } else {
            $lang = 'id';
        }

        if ($langCookie !== $lang) {
            set_cookie('lang', $lang);
        }

        service('language')->setLocale($lang);

        if (userLogin()->role_id == '' && userLogin()->user_id == '') {
            return redirect()->to(site_url('login'));
        }

        if (userLogin()->role_id != 4) {
            return redirect()->to(site_url('dashboard'));
        }

        $data['lang'] = $lang;

        if ($lang === 'en') {
            $data['title_tab'] = 'Change password &mdash; LPM UG';
            $data['title']     = 'Change Password';
        } else {
            $data['title_tab'] = 'Ubah password &mdash; LPM UG';
            $data['title']     = 'Ubah Password';
        }

        $data['validation'] = \Config\Services::validation();

        $ubah_password_dosen = $this->profile->find($id);

        if (
            is_object($ubah_password_dosen) &&
            $ubah_password_dosen->role_id != 1 &&
            $ubah_password_dosen->role_id != 2 &&
            $ubah_password_dosen->role_id != 3 &&
            $ubah_password_dosen->role_id != 5
        ) {
            $data['ubah_password_dosen'] = $ubah_password_dosen;
            return view('profile/ubah_password_dosen', $data);
        }

        return redirect()->to(site_url('ubah_password_dosen/update/' . userLogin()->user_id));
    }

    public function update_password_dosen($id)
    {
        $currentUser = userLogin();
        if (!$currentUser || ($currentUser->user_id != $id && !in_array((int)$currentUser->role_id, [1, 2], true))) {
            return redirect()->to(site_url('dashboard'))->with('error', 'Akses tidak sah.');
        }
        if (!$this->validate($this->profile->getValidationRules())) {
            $validation = \Config\Services::validation();
            // dd($validation);
            return redirect()->back()->withInput()->with('validation', $validation);
        }

        $data = [
            'password'      => password_hash($this->request->getVar('password'), PASSWORD_BCRYPT),
        ];
        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $message = 'Data anda berhasil diupdate';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $message = service('translation')->translateCached('Data anda berhasil diupdate', 'id', 'en');
        }
        // dd($data);
        $this->profile->update($id, $data);
        return redirect()->to(site_url('ubah_password_dosen/update/' . userLogin()->user_id))->with('success', $message);
    }
    // END CONTROLLER UNTUK PROFILE DOSEN //

    // CONTROLLER UNTUK PROFILE MITRA //
    public function profile_mitra($id = null)
    {
        if (userLogin()->role_id != 5) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '' && userLogin()->user_id == '') {
            return redirect()->to(site_url('login'));
        }

        $data['title_tab'] = 'Profile user &mdash; LPM UG';
        $data['title'] = 'Profile user';

        $profile_user_mitra =  $this->profile->find($id);
        if (is_object($profile_user_mitra) && $profile_user_mitra->role_id != 1 && $profile_user_mitra->role_id != 2 && $profile_user_mitra->role_id != 3 && $profile_user_mitra->role_id != 4) {
            $data['profile_user_mitra'] = $profile_user_mitra;
            $data['kota'] = $this->kota->getAll();
            return view('profile/edit_profile_mitra', $data);
        } else {
            // throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            return redirect()->to(site_url('profile_user_mitra/update/' . userLogin()->user_id));
        }

        return view('profile/edit_profile_mitra', $data);
    }

    public function update_profile_mitra($id)
    {
        $currentUser = userLogin();
        if (!$currentUser || ($currentUser->user_id != $id && !in_array((int)$currentUser->role_id, [1, 2], true))) {
            return redirect()->to(site_url('dashboard'))->with('error', 'Akses tidak sah.');
        }
        $data = [
            'user_name'     => $this->request->getVar('user_name'),
            'nidn'          => $this->request->getVar('nidn'),
            'email'         => $this->request->getVar('email'),
            'kontak'        => $this->request->getVar('kontak'),
            'kota_id'       => $this->request->getVar('kota_id'),
            'alamat'        => $this->request->getVar('alamat'),
        ];
        // dd($data);
        $this->profile->update($id, $data);
        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $message = 'Data anda berhasil diupdate';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $message = service('translation')->translateCached('Data anda berhasil diupdate', 'id', 'en');
        }
        return redirect()->to(site_url('profile_user_mitra/update/' . userLogin()->user_id))->with('success', $message);
    }

    public function ubah_password_mitra($id = null)
    {
        if (userLogin()->role_id != 5) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '' && userLogin()->user_id == '') {
            return redirect()->to(site_url('login'));
        }

        $data['title_tab'] = 'Ubah password &mdash; LPM UG';
        $data['title'] = 'Ubah Password';

        $data['validation'] = \Config\Services::validation();

        $ubah_password_mitra =  $this->profile->find($id);
        if (is_object($ubah_password_mitra) && $ubah_password_mitra->role_id != 1 && $ubah_password_mitra->role_id != 2 && $ubah_password_mitra->role_id != 3 && $ubah_password_mitra->role_id != 4) {
            $data['ubah_password_mitra'] = $ubah_password_mitra;
            $data['validation'] = \Config\Services::validation();

            return view('profile/ubah_password_mitra', $data);
        } else {
            // throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            return redirect()->to(site_url('ubah_password_mitra/update/' . userLogin()->user_id));
        }

        return view('profile/ubah_password_mitra', $data);
    }


    public function update_password_mitra($id)
    {
        $currentUser = userLogin();
        if (!$currentUser || ($currentUser->user_id != $id && !in_array((int)$currentUser->role_id, [1, 2], true))) {
            return redirect()->to(site_url('dashboard'))->with('error', 'Akses tidak sah.');
        }
        if (!$this->validate($this->profile->getValidationRules())) {
            $validation = \Config\Services::validation();
            // dd($validation);
            return redirect()->back()->withInput()->with('validation', $validation);
        }

        $data = [
            'password'      => password_hash($this->request->getVar('password'), PASSWORD_BCRYPT),
        ];
        // dd($data);
        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $message = 'Data anda berhasil diupdate';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $message = service('translation')->translateCached('Data anda berhasil diupdate', 'id', 'en');
        }
        $this->profile->update($id, $data);
        return redirect()->to(site_url('ubah_password_mitra/update/' . userLogin()->user_id))->with('success', $message);
    }
    // END CONTROLLER UNTUK PROFILE DOSEN //

}

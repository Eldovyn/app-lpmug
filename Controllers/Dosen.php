<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

use App\Models\HakAksesModel;
use App\Models\DosenModel;
use App\Models\FakultasModel;
use App\Models\JurusanModel;
use App\Models\FungsionalModel;
use App\Models\PesanModel;
use Google\Cloud\Translate\V2\TranslateClient;

class Dosen extends ResourceController
{
    function __construct()
    {
        $this->hak_akses    = new HakAksesModel();
        $this->dosen        = new DosenModel();
        $this->fakultas     = new FakultasModel();
        $this->jurusan      = new JurusanModel();
        $this->fungsional   = new FungsionalModel();
        $this->pesan        = new PesanModel();
    }
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        helper('auth');

        $user = \userLogin();
        if (!$user) return redirect()->to(site_url('login'));
        if (!in_array((int) $user->role_id, [1, 2, 3], true)) return redirect()->to(site_url('dashboard'));

        $keyword = $this->request->getGet('keyword');

        $data = $this->dosen->getPaginated(100000, $keyword);

        $baseTitle = 'Dosen'; // kalau mau ID lebih "indo": 'Profil Staff'
        $title = $baseTitle;

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
        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $title = service('translation')->translateCached($baseTitle, 'id', 'en'); // V2 translate :contentReference[oaicite:0]{index=0}
        }
        $data['title'] = $title;
        $data['title_tab'] = $title . ' &mdash; LPM UG';
        $data['keyword'] = $keyword;
        $data['pesan']   = $this->pesan->getPesan();
        $data['lang']    = $lang;

        // translate fakultas/jurusan hanya kalau EN
        if ($lang === 'en' && !empty($data['dosen'])) {
            $this->translateFacultyFields($data['dosen']);
        }

        return view('dosen/index', $data);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        //
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        helper('auth');
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2 && userLogin()->role_id != 3) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $baseTitle = 'Tambah Dosen'; // kalau mau ID lebih "indo": 'Profil Staff'
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
        $data['validation'] = \Config\Services::validation();

        // $data['hak_akses'] = $this->hak_akses->findAll();
        $data['fungsional'] = $this->fungsional->findAll();
        $data['jurusan'] = $this->jurusan->getAll();
        $data['pesan'] = $this->pesan->getPesan();

        return view('dosen/new', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {

        // if(!$this->validate($this->dosen->getValidationRules())) {
        //     $validation = \Config\Services::validation();
        //     // dd($validation);
        //     return redirect()->back()->withInput()->with('validation', $validation);
        // }

        $data = [
            'gelar_dpn'     => $this->request->getVar('gelar_dpn'),
            'user_name'     => $this->request->getVar('user_name'),
            'gelar_blkng'   => $this->request->getVar('gelar_blkng'),
            'sinta_id'      => $this->request->getVar('sinta_id'),
            'nidn'          => $this->request->getVar('nidn'),
            'email'         => $this->request->getVar('email'),
            'kontak'        => $this->request->getVar('kontak'),
            'jurusan_id'    => $this->request->getVar('jurusan_id'),
            'fungsional_id' => $this->request->getVar('fungsional_id'),
            'password'      => password_hash($this->request->getVar('password'), PASSWORD_BCRYPT),
            'role_id'       => 4,
            'status'        => 1,
        ];
        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $messageBerhasil = 'Data berhasil disimpan';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $messageBerhasil = service('translation')->translateCached($messageBerhasil, 'id', 'en');
        }


        $this->dosen->insert($data);
        return redirect()->to(site_url('dosen'))->with('success', $messageBerhasil);
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        helper('auth');
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2 && userLogin()->role_id != 3) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $baseTitle = 'Edit Dosen'; // kalau mau ID lebih "indo": 'Profil Staff'
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

        $dosen =  $this->dosen->find($id);
        if (is_object($dosen)) {
            $data['dosen'] = $dosen;
            $data['hak_akses'] = $this->hak_akses->findAll();
            $data['fungsional'] = $this->fungsional->findAll();
            $data['jurusan'] = $this->jurusan->getAll();
            return view('dosen/edit', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('dosen/edit', $data);
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $data = [
            'gelar_dpn'     => $this->request->getVar('gelar_dpn'),
            'user_name'     => $this->request->getVar('user_name'),
            'gelar_blkng'   => $this->request->getVar('gelar_blkng'),
            'sinta_id'      => $this->request->getVar('sinta_id'),
            'email'         => $this->request->getVar('email'),
            'kontak'        => $this->request->getVar('kontak'),
            'jurusan_id'    => $this->request->getVar('jurusan_id'),
            'fungsional_id' => $this->request->getVar('fungsional_id'),
            // 'password'      => password_hash($this->request->getVar('password'), PASSWORD_BCRYPT),
        ];
        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $messageBerhasil = 'Data berhasil disimpan';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $messageBerhasil = service('translation')->translateCached($messageBerhasil, 'id', 'en');
        }

        $this->dosen->update($id, $data);
        return redirect()->to(site_url('dosen'))->with('success', $messageBerhasil);
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2 && userLogin()->role_id != 3) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $this->dosen->delete($id);
        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $messageBerhasil = 'Data berhasil dihapus';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $messageBerhasil = service('translation')->translateCached($messageBerhasil, 'id', 'en');
        }
        return redirect()->to(site_url('dosen'))->with('success', $messageBerhasil);
    }

    private function translateFacultyFields(array &$rows): void
    {
        $uniqFak = [];
        $uniqJur = [];

        foreach ($rows as $r) {
            if (!empty($r->fakultas_name)) $uniqFak[$r->fakultas_name] = true;
            if (!empty($r->jurusan_name))  $uniqJur[$r->jurusan_name]  = true;
        }

        $fakMap = $this->translateMany(array_keys($uniqFak), 'id', 'en', 'tr_fakultas_');
        $jurMap = $this->translateMany(array_keys($uniqJur), 'id', 'en', 'tr_jurusan_');

        foreach ($rows as $r) {
            if (!empty($r->fakultas_name) && isset($fakMap[$r->fakultas_name])) {
                $r->fakultas_name = $fakMap[$r->fakultas_name];
            }
            if (!empty($r->jurusan_name) && isset($jurMap[$r->jurusan_name])) {
                $r->jurusan_name = $jurMap[$r->jurusan_name];
            }
        }
    }

    private function translateMany(array $texts, string $source, string $target, string $cachePrefix): array
    {
        $out = [];

        foreach ($texts as $text) {
            $text = (string) $text;
            if (trim($text) === '') continue;

            // cache key aman: hanya prefix + md5 (tanpa karakter reserved PSR-16) :contentReference[oaicite:2]{index=2}
            $cacheKey = $cachePrefix . md5($source . '|' . $target . '|' . $text);

            $cached = cache()->get($cacheKey);
            if (is_string($cached) && $cached !== '') {
                $out[$text] = $cached;
                continue;
            }

            $translated = $this->translateText($text, $source, $target);
            $out[$text] = $translated;

            cache()->save($cacheKey, $translated, 60 * 60 * 24 * 30);
        }

        return $out;
    }

    private function translateText(string $text, string $source, string $target): string
    {
        $key = $_ENV['apiKeyGoogleTranslateApi'] ?? null;
        if (!$key || trim($text) === '') return $text;

        // Google Translate v2 client :contentReference[oaicite:3]{index=3}
        $client = new TranslateClient(['key' => $key]);

        $result = $client->translate($text, [
            'source' => $source,
            'target' => $target,
        ]);

        return $result['text'] ?? $text;
    }

    private function translateTextCached(string $text, string $source, string $target): string
    {
        $text = trim($text);
        if ($text === '' || $source === $target) {
            return $text;
        }

        // cache key aman (tanpa ':')
        $cacheKey = 'gtr_' . md5($source . '|' . $target . '|' . $text);
        $cache = cache();

        $cached = $cache->get($cacheKey);
        if (is_string($cached) && $cached !== '') {
            return $cached;
        }

        $translated = $this->translateText($text, $source, $target);

        // cache 30 hari
        $cache->save($cacheKey, $translated, 60 * 60 * 24 * 30);

        return $translated;
    }
}

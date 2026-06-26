<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

use App\Models\PeriodeModel;
use App\Models\PesanModel;
use Google\Cloud\Translate\V2\TranslateClient;

class Periode extends ResourceController
{
    function __construct()
    {
        $this->periode = new PeriodeModel();
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
        if (!$user) {
            return redirect()->to(site_url('login'));
        }

        if (!in_array((int) $user->role_id, [1, 2, 3], true)) {
            return redirect()->to(site_url('dashboard'));
        }

        // lang dari cookie: default id, hanya 'en' valid
        $lang = (string) ($this->request->getCookie('lang') ?? 'id'); // CI4 getCookie :contentReference[oaicite:1]{index=1}
        $lang = strtolower(trim($lang));
        $lang = ($lang === 'en') ? 'en' : 'id';

        $keyword = $this->request->getGet('keyword');

        $data = $this->periode->getPaginated(10, $keyword);
        $data['keyword'] = $keyword;
        $data['pesan']   = $this->pesan->getPesan();

        $baseTitle = 'Periode'; // kalau mau ID lebih "indo": 'Profil Staff'
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

        // kalau view kamu pakai title_tab juga:
        // $data['title_tab'] = $titles[$lang];

        return view('periode/index', $data);
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

        $baseTitle = 'Tambah Periode'; // kalau mau ID lebih "indo": 'Profil Staff'
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
        $data['title'] = $title;
        $data['title_tab'] = $title . ' &mdash; LPM UG';
        $data['pesan'] = $this->pesan->getPesan();

        $data['periode'] = $this->periode->findAll();

        return view('periode/new', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $data = [
            'periode_name' => $this->request->getVar('periode_name'),
            'tahun_ajaran' => $this->request->getVar('tahun_ajaran'),
            'info'         => 1,
            'status'       => $this->request->getVar('status'),
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

        // $this->db->table('tbl_periode')->insert($data);
        $this->periode->insert($data);
        return redirect()->to(site_url('periode'))->with('success', $messageBerhasil);
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

        $baseTitle = 'Edit Periode'; // kalau mau ID lebih "indo": 'Profil Staff'
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


        $periode = $this->periode->where('periode_id', $id)->first();
        if (is_object($periode)) {
            $data['periode'] = $periode;
            return view('periode/edit', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $messageBerhasil = 'Data berhasil disimpan';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $messageBerhasil = service('translation')->translateCached($messageBerhasil, 'id', 'en');
        }
        $data = $this->request->getPost();
        $this->periode->update($id, $data);
        return redirect()->to(site_url('periode'))->with('success', $messageBerhasil);
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
        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $messageBerhasil = 'Data berhasil dihapus';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $messageBerhasil = service('translation')->translateCached($messageBerhasil, 'id', 'en');
        }

        $this->periode->delete($id);
        return redirect()->to(site_url('periode'))->with('success', $messageBerhasil);
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

    private function translateText(string $text, string $source, string $target, string $format = 'text'): string
    {
        $key = $_ENV['apiKeyGoogleTranslateApi'] ?? null;
        if (!$key || trim($text) === '') return $text;

        // Google Cloud Translation v2 PHP client
        $client = new \Google\Cloud\Translate\V2\TranslateClient(['key' => $key]);

        $result = $client->translate($text, [
            'source' => $source,
            'target' => $target,
            'format' => $format, // v2 REST mendukung format html/text :contentReference[oaicite:2]{index=2}
        ]);

        return $result['text'] ?? $text;
    }
}

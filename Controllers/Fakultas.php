<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

use App\Models\FakultasModel;
use App\Models\PesanModel;
use Google\Cloud\Translate\V2\TranslateClient;

class Fakultas extends ResourceController
{
    function __construct()
    {
        $this->fakultas = new FakultasModel();
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

        // guard lebih aman (hindari userLogin() null)
        $user = \userLogin();
        if (! $user) {
            return redirect()->to(site_url('login'));
        }
        if (! in_array((int) $user->role_id, [1, 2, 3], true)) {
            return redirect()->to(site_url('dashboard'));
        }

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

        $keyword = $this->request->getGet('keyword');

        $data = $this->fakultas->getPaginated(10, $keyword);
        $data['keyword'] = $keyword;
        $data['pesan']   = $this->pesan->getPesan();
        $data['lang']    = $lang;

        // title/title_tab (tanpa API biar hemat & stabil)
        $titles = [
            'id' => ['title' => 'Fakultas',  'title_tab' => 'Fakultas &mdash; LPM UG'],
            'en' => ['title' => 'Faculties', 'title_tab' => 'Faculties &mdash; LPM UG'],
        ];
        $data['title']     = $titles[$lang]['title'];
        $data['title_tab'] = $titles[$lang]['title_tab'];

        // ===== opsional: translate isi kolom fakultas_name kalau EN =====
        if ($lang === 'en' && !empty($data['fakultas']) && is_array($data['fakultas'])) {
            $this->translateFakultasNames($data['fakultas']);
        }

        return view('fakultas/index', $data);
    }

    private function translateFakultasNames(array &$rows): void
    {
        // ambil nama unik
        $uniq = [];
        foreach ($rows as $r) {
            $name = is_object($r) ? ($r->fakultas_name ?? '') : ($r['fakultas_name'] ?? '');
            $name = trim((string) $name);
            if ($name !== '') $uniq[$name] = true;
        }

        $map = $this->translateMany(array_keys($uniq), 'id', 'en', 'tr_fakultas_');

        // set hasil translate ke rows (object/array)
        foreach ($rows as &$r) {
            if (is_object($r)) {
                if (!empty($r->fakultas_name) && isset($map[$r->fakultas_name])) {
                    $r->fakultas_name = $map[$r->fakultas_name];
                }
            } else {
                if (!empty($r['fakultas_name']) && isset($map[$r['fakultas_name']])) {
                    $r['fakultas_name'] = $map[$r['fakultas_name']];
                }
            }
        }
        unset($r);
    }

    private function translateMany(array $texts, string $source, string $target, string $cachePrefix): array
    {
        $out = [];

        foreach ($texts as $text) {
            $text = (string) $text;
            if (trim($text) === '') continue;

            // cache key CI4 harus bebas karakter reserved -> pakai md5 :contentReference[oaicite:2]{index=2}
            $cacheKey = $cachePrefix . md5($source . '|' . $target . '|' . $text);

            $cached = cache()->get($cacheKey);
            if (is_string($cached) && $cached !== '') {
                $out[$text] = $cached;
                continue;
            }

            $translated = $this->translateText($text, $source, $target);
            $out[$text] = $translated;

            cache()->save($cacheKey, $translated, 60 * 60 * 24 * 30); // 30 hari
        }

        return $out;
    }

    private function translateText(string $text, string $source, string $target): string
    {
        $key = $_ENV['apiKeyGoogleTranslateApi'] ?? null;
        if (! $key || trim($text) === '') {
            return $text;
        }

        // Google Cloud Translation (Basic) PHP client :contentReference[oaicite:3]{index=3}
        $client = new TranslateClient(['key' => $key]);

        $result = $client->translate($text, [
            'source' => $source,
            'target' => $target,
        ]);

        return $result['text'] ?? $text;
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

        $lang = strtolower(trim((string) ($this->request->getCookie('lang') ?? 'id'))); // :contentReference[oaicite:1]{index=1}
        $lang = ($lang === 'en') ? 'en' : 'id';

        $titles = [
            'id' => [
                'title_tab' => 'Tambah Fakultas &mdash; LPM UG',
                'title'     => 'Tambah Fakultas',
            ],
            'en' => [
                'title_tab' => 'Add Faculty &mdash; LPM UG',
                'title'     => 'Add Faculty',
            ],
        ];

        $data['title_tab'] = $titles[$lang]['title_tab'];
        $data['title']     = $titles[$lang]['title'];
        $data['pesan'] = $this->pesan->getPesan();

        return view('fakultas/new', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $data = $this->request->getPost();
        $this->fakultas->insert($data);
        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $messageBerhasil = 'Data berhasil disimpan';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $messageBerhasil = service('translation')->translateCached($messageBerhasil, 'id', 'en');
        }
        return redirect()->to(site_url('fakultas'))->with('success', $messageBerhasil);
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

        // lang dari cookie: default id, hanya 'en' valid
        $lang = strtolower(trim((string) ($this->request->getCookie('lang') ?? 'id')));
        $lang = ($lang === 'en') ? 'en' : 'id';

        $titles = [
            'id' => [
                'title_tab' => 'Edit Fakultas &mdash; LPM UG',
                'title'     => 'Edit Fakultas',
            ],
            'en' => [
                'title_tab' => 'Edit Faculty &mdash; LPM UG',
                'title'     => 'Edit Faculty',
            ],
        ];

        $data['title_tab'] = $titles[$lang]['title_tab'];
        $data['title']     = $titles[$lang]['title'];
        $data['pesan'] = $this->pesan->getPesan();

        $fakultas = $this->fakultas->where('fakultas_id', $id)->first();
        if (is_object($fakultas)) {
            $data['fakultas'] = $fakultas;
            return view('fakultas/edit', $data);
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
        $data = $this->request->getPost();
        $this->fakultas->update($id, $data);
        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $messageBerhasil = 'Data berhasil disimpan';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $messageBerhasil = service('translation')->translateCached($messageBerhasil, 'id', 'en');
        }
        return redirect()->to(site_url('fakultas'))->with('success', $messageBerhasil);
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

        $this->fakultas->delete($id);
        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $messageBerhasil = 'Data berhasil dihapus';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $messageBerhasil = service('translation')->translateCached($messageBerhasil, 'id', 'en');
        }
        return redirect()->to(site_url('fakultas'))->with('success', $messageBerhasil);
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

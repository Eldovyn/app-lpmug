<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

use App\Models\LuaranModel;
use App\Models\PesanModel;
use Google\Cloud\Translate\V2\TranslateClient;

class Luaran extends ResourceController
{
    function __construct()
    {
        $this->luaran = new LuaranModel();
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

        if (userLogin()->role_id != 1 && userLogin()->role_id != 2 && userLogin()->role_id != 3) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $keyword = $this->request->getGet('keyword');

        // IMPORTANT: jangan set $data[...] dulu lalu ketimpa oleh getPaginated()
        $data = $this->luaran->getPaginated(10, $keyword);
        $data['keyword'] = $keyword;
        $data['pesan']   = $this->pesan->getPesan();

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

        // Title/tab base (ID)
        $titleId    = 'Luaran Penelitian';
        $titleTabId = 'Luaran Penelitian — LPM UG';

        // Title/tab untuk EN (bisa statik atau via translate)
        // Kalau mau statik:
        // $data['title']     = ($lang === 'en') ? 'Research Output' : $titleId;
        // $data['title_tab'] = ($lang === 'en') ? 'Research Output — LPM UG' : $titleTabId;

        // Kalau mau pakai Google Translate:
        $data['title']     = ($lang === 'en') ? service('translation')->translateCached($titleId, 'id', 'en') : $titleId;
        $data['title_tab'] = ($lang === 'en') ? service('translation')->translateCached($titleTabId, 'id', 'en') : $titleTabId;

        // ===== Translate atribut luaran_name saat EN =====
        // asumsi view: foreach($luaran as ...) -> berarti getPaginated() ngasih $data['luaran']
        if ($lang === 'en' && ! empty($data['luaran'])) {
            foreach ($data['luaran'] as $i => $row) {
                // kalau object
                if (is_object($row) && isset($row->luaran_name)) {
                    $data['luaran'][$i]->luaran_name = service('translation')->translateCached((string) $row->luaran_name, 'id', 'en');
                }

                // kalau array
                if (is_array($row) && isset($row['luaran_name'])) {
                    $data['luaran'][$i]['luaran_name'] = service('translation')->translateCached((string) $row['luaran_name'], 'id', 'en');
                }
            }
        }

        return view('luaran/index', $data);
    }

    /**
     * Cache wrapper biar gak hit Google Translate terus.
     * Cache key HARUS aman (CI4 melarang karakter {}()/\@:) :contentReference[oaicite:3]{index=3}
     */
    private function translateTextCached(string $text, string $source, string $target): string
    {
        $text = trim($text);
        if ($text === '' || $source === $target) {
            return $text;
        }

        // PAKAI prefix aman + md5 (hindari ":" karena reserved) :contentReference[oaicite:4]{index=4}
        $cacheKey = 'gtrans_' . md5($source . '|' . $target . '|' . $text);

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

    private function translateText(string $text, string $source, string $target): string
    {
        $key = $_ENV['apiKeyGoogleTranslateApi'] ?? null;

        $text = trim((string) $text);
        if (! $key || $text === '' || $source === $target) {
            return $text;
        }

        try {
            $client = new TranslateClient(['key' => $key]);

            $result = $client->translate($text, [
                'source' => $source,
                'target' => $target,
            ]);

            return $result['text'] ?? $text;
        } catch (\Throwable $e) {
            return $text; // fallback kalau API error
        }
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

        // lang cookie: default id, selain en => id
        $lang = strtolower(trim((string) ($this->request->getCookie('lang') ?? 'id'))); // :contentReference[oaicite:2]{index=2}
        $lang = ($lang === 'en') ? 'en' : 'id';
        $data['lang']    = $lang;

        // Title/tab base (ID)
        $titleTabId    = 'Tambah Luaran &mdash; LPM UG';
        $titleId = 'Tambah Luaran';

        // Title/tab untuk EN (bisa statik atau via translate)
        // Kalau mau statik:
        // $data['title']     = ($lang === 'en') ? 'Research Output' : $titleId;
        // $data['title_tab'] = ($lang === 'en') ? 'Research Output — LPM UG' : $titleTabId;

        // Kalau mau pakai Google Translate:
        $data['title']     = ($lang === 'en') ? service('translation')->translateCached($titleId, 'id', 'en') : $titleId;
        $data['title_tab'] = ($lang === 'en') ? service('translation')->translateCached($titleTabId, 'id', 'en') : $titleTabId;
        $data['pesan'] = $this->pesan->getPesan();

        return view('luaran/new', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $data = $this->request->getPost();
        $this->luaran->insert($data);
        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $messageBerhasil = 'Data berhasil disimpan';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $messageBerhasil = service('translation')->translateCached($messageBerhasil, 'id', 'en');
        }
        return redirect()->to(site_url('luaran'))->with('success', $messageBerhasil);
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

        // lang cookie: default id, selain en => id
        $lang = strtolower(trim((string) ($this->request->getCookie('lang') ?? 'id'))); // :contentReference[oaicite:2]{index=2}
        $lang = ($lang === 'en') ? 'en' : 'id';
        $data['lang']    = $lang;

        // Title/tab base (ID)
        $titleTabId    = 'Edit Luaran &mdash; LPM UG';
        $titleId = 'Edit Luaran';

        // Title/tab untuk EN (bisa statik atau via translate)
        // Kalau mau statik:
        // $data['title']     = ($lang === 'en') ? 'Research Output' : $titleId;
        // $data['title_tab'] = ($lang === 'en') ? 'Research Output — LPM UG' : $titleTabId;

        // Kalau mau pakai Google Translate:
        $data['title']     = ($lang === 'en') ? service('translation')->translateCached($titleId, 'id', 'en') : $titleId;
        $data['title_tab'] = ($lang === 'en') ? service('translation')->translateCached($titleTabId, 'id', 'en') : $titleTabId;
        $data['pesan'] = $this->pesan->getPesan();

        $luaran = $this->luaran->where('luaran_id', $id)->first();
        if (is_object($luaran)) {
            $data['luaran'] = $luaran;
            return view('luaran/edit', $data);
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
        $this->luaran->update($id, $data);
        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $messageBerhasil = 'Data berhasil disimpan';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $messageBerhasil = service('translation')->translateCached($messageBerhasil, 'id', 'en');
        }
        return redirect()->to(site_url('luaran'))->with('success', $messageBerhasil);
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

        $this->luaran->delete($id);
        return redirect()->to(site_url('luaran'))->with('success', $messageBerhasil);
    }
}

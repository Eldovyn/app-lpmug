<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Google\Cloud\Translate\V2\TranslateClient;
use App\Models\TopikModel;
use App\Models\PesanModel;

class Topik extends ResourceController
{

    function __construct()
    {
        $this->topik = new TopikModel();
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
        $data = $this->topik->getPaginated(10, $keyword);

        $data['keyword'] = $keyword;
        $data['pesan']   = $this->pesan->getPesan();

        // Title/tab (sesuaikan kalau kamu mau singular)
        $data['title']     = ($lang === 'en') ? 'Research Topic' : 'Topik Penelitian';
        $data['title_tab'] = ($lang === 'en') ? 'Research Topic — LPM UG' : 'Topik Penelitian — LPM UG';
        $data['lang']      = $lang;

        // ===== Translate atribut topik_name saat EN =====
        // asumsi getPaginated() ngasih $data['topik'] (sesuai view kamu foreach ($topik as ...))
        if ($lang === 'en' && !empty($data['topik'])) {
            foreach ($data['topik'] as $i => $row) {
                // kalau object
                if (is_object($row) && isset($row->topik_name)) {
                    $data['topik'][$i]->topik_name = service('translation')->translateCached((string) $row->topik_name, 'id', 'en');
                }

                // kalau array
                if (is_array($row) && isset($row['topik_name'])) {
                    $data['topik'][$i]['topik_name'] = service('translation')->translateCached((string) $row['topik_name'], 'id', 'en');
                }
            }
        }

        return view('topik/index', $data);
    }

    /**
     * Cache wrapper biar gak hit Google Translate terus.
     * Cache key dibuat aman (md5) supaya gak kena reserved characters error di CI4. :contentReference[oaicite:5]{index=5}
     */
    private function translateTextCached(string $text, string $source, string $target): string
    {
        $text = trim($text);
        if ($text === '') {
            return $text;
        }

        // ✅ key aman: gtrans_<md5>
        $cacheKey = $this->makeTranslateCacheKey($text, $source, $target);

        return cache()->remember($cacheKey, 60 * 60 * 24 * 30, function () use ($text, $source, $target) {
            return $this->translateText($text, $source, $target);
        });
    }

    private function makeTranslateCacheKey(string $text, string $source, string $target): string
    {
        // JANGAN pakai ":" karena reserved di CI4
        // Hash supaya key aman & tidak kepanjangan
        return 'gtrans_' . md5($source . '|' . $target . '|' . $text);
    }

    private function translateText(string $text, string $source, string $target): string
    {
        $key = $_ENV['apiKeyGoogleTranslateApi'] ?? null;

        $text = trim((string) $text);
        if (! $key || $text === '' || $source === $target) {
            return $text;
        }

        $cacheKey = $this->makeTranslateCacheKey($text, $source, $target);

        $cache = cache();
        $cached = $cache->get($cacheKey);
        if (is_string($cached) && $cached !== '') {
            return $cached;
        }

        try {
            $client = new TranslateClient(['key' => $key]);

            $result = $client->translate($text, [
                'source' => $source,
                'target' => $target,
            ]);

            $translated = $result['text'] ?? $text;

            // simpan cache (misal 30 hari)
            $cache->save($cacheKey, $translated, 60 * 60 * 24 * 30);

            return $translated;
        } catch (\Throwable $e) {
            // fallback kalau API error
            return $text;
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

        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $baseTitle = 'Tambah Topik'; // kalau mau ID lebih "indo": 'Profil Staff'
        $title = $baseTitle;

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $title = service('translation')->translateCached($baseTitle, 'id', 'en'); // V2 translate :contentReference[oaicite:0]{index=0}
        }
        $data['title'] = $title;
        $data['title_tab'] = $title . ' &mdash; LPM UG';
        $data['pesan'] = $this->pesan->getPesan();

        return view('topik/new', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $data = $this->request->getPost();
        $this->topik->insert($data);
        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $messageBerhasil = 'Data berhasil disimpan';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $messageBerhasil = service('translation')->translateCached($messageBerhasil, 'id', 'en');
        }

        return redirect()->to(site_url('topik'))->with('success', $messageBerhasil);
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

        $data['title_tab'] = 'Edit Topik &mdash; LPM UG';
        $data['title'] = 'Edit Topik';
        $data['pesan'] = $this->pesan->getPesan();

        $topik = $this->topik->where('topik_id', $id)->first();
        if (is_object($topik)) {
            $data['topik'] = $topik;
            return view('topik/edit', $data);
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
        $this->topik->update($id, $data);
        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $messageBerhasil = 'Data berhasil disimpan';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $messageBerhasil = service('translation')->translateCached($messageBerhasil, 'id', 'en');
        }
        return redirect()->to(site_url('topik'))->with('success', $messageBerhasil);
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

        $this->topik->delete($id);
        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $messageBerhasil = 'Data berhasil dihapus';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $messageBerhasil = service('translation')->translateCached($messageBerhasil, 'id', 'en');
        }
        return redirect()->to(site_url('topik'))->with('success', $messageBerhasil);
    }
}

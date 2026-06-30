<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

use App\Models\FungsionalModel;
use App\Models\PesanModel;
use Google\Cloud\Translate\V2\TranslateClient;

class Fungsional extends ResourceController
{

    function __construct()
    {
        $this->fungsional = new FungsionalModel();
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
        $data    = $this->fungsional->getPaginated(10, $keyword);

        $data['keyword'] = $keyword;
        $data['pesan']   = $this->pesan->getPesan();
        $data['lang']    = $lang;

        // title/title_tab (base ID)
        $titleId    = 'Jabatan Fungsional';
        $titleTabId = 'Fungsional &mdash; LPM UG';

        $data['title']     = ($lang === 'en') ? $this->translateText($titleId, 'id', 'en') : $titleId;
        $data['title_tab'] = ($lang === 'en') ? $this->translateText($titleTabId, 'id', 'en') : $titleTabId;

        // translate field fungsional_name jika EN
        if ($lang === 'en' && !empty($data['fungsional']) && is_array($data['fungsional'])) {
            $this->translateFungsionalFields($data['fungsional']);
        }

        return view('fungsional/index', $data);
    }

    private function translateFungsionalFields(array &$rows): void
    {
        $uniq = [];
        foreach ($rows as $r) {
            $name = is_object($r) ? ($r->fungsional_name ?? '') : ($r['fungsional_name'] ?? '');
            if (trim((string) $name) !== '') {
                $uniq[(string) $name] = true;
            }
        }

        $map = $this->translateMany(array_keys($uniq), 'id', 'en', 'tr_fungsional_');

        foreach ($rows as &$r) {
            if (is_object($r)) {
                if (!empty($r->fungsional_name) && isset($map[$r->fungsional_name])) {
                    $r->fungsional_name = $map[$r->fungsional_name];
                }
            } else {
                if (!empty($r['fungsional_name']) && isset($map[$r['fungsional_name']])) {
                    $r['fungsional_name'] = $map[$r['fungsional_name']];
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

            // cache key CI4/PSR: hindari {}()/\@:  -> pakai md5 :contentReference[oaicite:3]{index=3}
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
        if (!$key || trim($text) === '') {
            return $text;
        }

        // TranslateClient V2 :contentReference[oaicite:4]{index=4}
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

        // lang dari cookie: default id, hanya 'en' valid
        // lang dari cookie: default id, hanya 'en' yang valid
        $lang = strtolower(trim((string) ($this->request->getCookie('lang') ?? 'id'))); // :contentReference[oaicite:0]{index=0}
        $lang = ($lang === 'en') ? 'en' : 'id';

        // title (ID/EN)
        $titles = [
            'id' => [
                'title_tab' => 'Tambah jabatan fungsional &mdash; LPM UG',
                'title'     => 'Tambah jabatan fungsional',
            ],
            'en' => [
                'title_tab' => 'Add functional position &mdash; LPM UG',
                'title'     => 'Add functional position',
            ],
        ];

        $data['title_tab'] = $titles[$lang]['title_tab'];
        $data['title']     = $titles[$lang]['title'];
        $data['pesan'] = $this->pesan->getPesan();

        return view('fungsional/new', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $data = $this->request->getPost();
        $this->fungsional->insert($data);
        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $messageBerhasil = 'Data berhasil disimpan';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $messageBerhasil = service('translation')->translateCached($messageBerhasil, 'id', 'en');
        }
        return redirect()->to(site_url('fungsional'))->with('success', $messageBerhasil);
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
        $lang = strtolower(trim((string) ($this->request->getCookie('lang') ?? 'id'))); // :contentReference[oaicite:1]{index=1}
        $lang = ($lang === 'en') ? 'en' : 'id';

        $titles = [
            'id' => [
                'title_tab' => 'Edit Jabatan Fungsional &mdash; LPM UG',
                'title'     => 'Edit Jabatan Fungsional',
            ],
            'en' => [
                'title_tab' => 'Edit Functional Position &mdash; LPM UG',
                'title'     => 'Edit Functional Position',
            ],
        ];

        $data['title_tab'] = $titles[$lang]['title_tab'];
        $data['title']     = $titles[$lang]['title'];
        $data['pesan'] = $this->pesan->getPesan();

        $fungsional = $this->fungsional->where('fungsional_id', $id)->first();
        if (is_object($fungsional)) {
            $data['fungsional'] = $fungsional;
            return view('fungsional/edit', $data);
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
        $this->fungsional->update($id, $data);
        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $messageBerhasil = 'Data berhasil disimpan';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $messageBerhasil = service('translation')->translateCached($messageBerhasil, 'id', 'en');
        }
        return redirect()->to(site_url('fungsional'))->with('success', $messageBerhasil);
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

        $this->fungsional->delete($id);
        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $messageBerhasil = 'Data berhasil dihapus';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $messageBerhasil = service('translation')->translateCached($messageBerhasil, 'id', 'en');
        }
        return redirect()->to(site_url('fungsional'))->with('success', $messageBerhasil);
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

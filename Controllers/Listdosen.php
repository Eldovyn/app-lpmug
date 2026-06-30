<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

use App\Models\DosenModel;

class Listdosen extends ResourceController
{
    function __construct()
    {
        $this->dosen        = new DosenModel();
    }
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {

        helper('auth');

        if (userLogin()->role_id != 4) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $keyword = $this->request->getGet('keyword');
        $baseTitle = 'List Dosen'; // kalau mau ID lebih "indo": 'Profil Staff'
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

        // Get lang from query param first, then cookie
        $lang = $this->request->getGet('lang');
        if (! $lang) {
            $lang = $this->request->getCookie('lang') ?? 'id';
        }
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        // Set cookie if lang from query param
        if ($this->request->getGet('lang')) {
            helper('cookie');
            set_cookie('lang', $lang, 60 * 60 * 24 * 30);
        }

        // Simple dictionary for title translation
        $titleDict = [
            'id' => 'List Dosen',
            'en' => 'Lecturer List'
        ];
        $title = $titleDict[$lang] ?? 'List Dosen';

        // Get pagination data first
        $paginatedData = $this->dosen->getPaginated(100000, $keyword);

        // Merge with title data
        $data = array_merge($paginatedData, [
            'title' => $title,
            'title_tab' => $title . ' — LPM UG',
            'keyword' => $keyword
        ]);
        // dd($data);
        return view('listdosen/index', $data);
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
        //
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        //
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        //
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        //
    }

    private function translateText(string $text, string $source, string $target): string
    {
        $text = trim($text);
        if ($text === '' || $source === $target) {
            return $text;
        }

        $key = $_ENV['apiKeyGoogleTranslateApi'] ?? null;
        if (! $key) {
            return $text;
        }

        $client = new \Google\Cloud\Translate\V2\TranslateClient(['key' => $key]);

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

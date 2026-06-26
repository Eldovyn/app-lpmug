<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

use App\Models\ProvinsiModel;
use App\Models\KotaModel;
use App\Models\PesanModel;
use Google\Cloud\Translate\V2\TranslateClient;

class Kota extends ResourceController
{
    function __construct()
    {
        $this->provinsi = new ProvinsiModel();
        $this->kota     = new KotaModel();
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

        // lang dari cookie: default id, hanya 'en' valid
        $lang = strtolower(trim((string) ($this->request->getCookie('lang') ?? 'id'))); // :contentReference[oaicite:1]{index=1}
        $lang = ($lang === 'en') ? 'en' : 'id';

        // $data['kota'] = $this->kota->getAll();
        $data = $this->kota->getPaginated(10, $keyword);
        $data['keyword'] = $keyword;
        $data['pesan'] = $this->pesan->getPesan();

        $titles = [
            'id' => [
                'title_tab' => 'Kota &mdash; LPM UG',
                'title'     => 'Kota',
            ],
            'en' => [
                'title_tab' => 'City &mdash; LPM UG',
                'title'     => 'City',
            ],
        ];

        $data['title_tab'] = $titles[$lang]['title_tab'];
        $data['title']     = $titles[$lang]['title'];
        return view('kota/index', $data);
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

        $user = \userLogin();
        if (!$user) {
            return redirect()->to(site_url('login'));
        }
        if (!in_array((int) $user->role_id, [1, 2, 3], true)) {
            return redirect()->to(site_url('dashboard'));
        }

        // lang dari cookie: default id, hanya 'en' valid
        $lang = strtolower(trim((string) ($this->request->getCookie('lang') ?? 'id'))); // :contentReference[oaicite:1]{index=1}
        $lang = ($lang === 'en') ? 'en' : 'id';

        // titles
        $titles = [
            'id' => [
                'title_tab' => 'Tambah kota &mdash; LPM UG',
                'title'     => 'Tambah kota',
            ],
            'en' => [
                'title_tab' => 'Add City &mdash; LPM UG',
                'title'     => 'Add City',
            ],
        ];

        $data['title_tab'] = $titles[$lang]['title_tab'];
        $data['title']     = $titles[$lang]['title'];

        $data['pesan']    = $this->pesan->getPesan();
        $data['provinsi'] = $this->provinsi->findAll();
        $data['lang']     = $lang; // opsional kalau view butuh

        return view('kota/new', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $data = $this->request->getPost();
        $this->kota->insert($data);

        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $messageBerhasil = 'Data berhasil disimpan';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
        $messageBerhasil = service('translation')->translateCached($messageBerhasil, 'id', 'en');
        }
        return redirect()->to(site_url('kota'))->with('success', $messageBerhasil);
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        helper('auth');

        // guard lebih aman (hindari userLogin null)
        $user = \userLogin();
        if (!$user) {
            return redirect()->to(site_url('login'));
        }
        if (!in_array((int) $user->role_id, [1, 2, 3], true)) {
            return redirect()->to(site_url('dashboard'));
        }

        // lang dari cookie: default id, hanya 'en' valid
        $lang = strtolower(trim((string) ($this->request->getCookie('lang') ?? 'id'))); // :contentReference[oaicite:1]{index=1}
        $lang = ($lang === 'en') ? 'en' : 'id';

        // title i18n
        $titles = [
            'id' => [
                'title_tab' => 'Edit kota &mdash; LPM UG',
                'title'     => 'Edit kota',
            ],
            'en' => [
                'title_tab' => 'Edit City &mdash; LPM UG',
                'title'     => 'Edit City',
            ],
        ];

        $data['title_tab'] = $titles[$lang]['title_tab'];
        $data['title']     = $titles[$lang]['title'];
        $data['pesan']     = $this->pesan->getPesan();
        $data['lang']      = $lang; // opsional kalau view butuh

        $kota = $this->kota->find($id);
        if (!is_object($kota)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(); // :contentReference[oaicite:2]{index=2}
        }

        $data['kota']     = $kota;
        $data['provinsi'] = $this->provinsi->findAll();

        return view('kota/edit', $data);
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $data = $this->request->getPost();
        $this->kota->update($id, $data);
        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $messageBerhasil = 'Data berhasil disimpan';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
        $messageBerhasil = service('translation')->translateCached($messageBerhasil, 'id', 'en');
        }
        return redirect()->to(site_url('kota'))->with('success', $messageBerhasil);
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

        $this->kota->delete($id);
        return redirect()->to(site_url('kota'))->with('success', 'Data anda berhasil dihapus.');
    }

    private function translateText(string $text, string $source, string $target): string
    {
        $key = $_ENV['apiKeyGoogleTranslateApi'] ?? null;
        if (! $key || trim($text) === '') {
            return $text;
        }

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

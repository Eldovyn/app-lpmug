<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

use App\Models\UniversitasModel;
use App\Models\PesanModel;
use Google\Cloud\Translate\V2\TranslateClient;

class Universitas extends ResourceController
{

    function __construct()
    {
        $this->universitas = new UniversitasModel();
        $this->pesan       = new PesanModel();
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
        $data    = $this->universitas->getPaginated(10, $keyword);

        $data['keyword'] = $keyword;
        $data['pesan']   = $this->pesan->getPesan();
        $data['lang']    = $lang;

        $titles = [
            'id' => [
                'title_tab' => 'Universitas / Instansi &mdash; LPM UG',
                'title'     => 'Universitas / Instansi',
            ],
            'en' => [
                'title_tab' => 'University / Institution &mdash; LPM UG',
                'title'     => 'University / Institution',
            ],
        ];

        $data['title_tab'] = $titles[$lang]['title_tab'];
        $data['title']     = $titles[$lang]['title'];

        // Translate atribut universitas kalau EN (hindari translate per-row tanpa cache)
        if ($lang === 'en' && !empty($data['universitas']) && is_array($data['universitas'])) {
            $this->translateUniversitasFields($data['universitas']);
        }

        return view('universitas/index', $data);
    }

    private function translateUniversitasFields(array &$rows): void
    {
        $uniqNames = [];
        $uniqAddr  = [];

        foreach ($rows as $r) {
            $name  = is_object($r) ? ($r->universitas_name ?? '') : ($r['universitas_name'] ?? '');
            $alamat = is_object($r) ? ($r->alamat ?? '')           : ($r['alamat'] ?? '');

            if (trim($name) !== '')   $uniqNames[$name] = true;
            if (trim($alamat) !== '') $uniqAddr[$alamat] = true;
        }

        $nameMap = $this->translateMany(array_keys($uniqNames), 'id', 'en', 'tr_univ_name_');
        $addrMap = $this->translateMany(array_keys($uniqAddr),  'id', 'en', 'tr_univ_addr_');

        foreach ($rows as &$r) {
            if (is_object($r)) {
                if (!empty($r->universitas_name) && isset($nameMap[$r->universitas_name])) {
                    $r->universitas_name = $nameMap[$r->universitas_name];
                }
                if (!empty($r->alamat) && isset($addrMap[$r->alamat])) {
                    $r->alamat = $addrMap[$r->alamat];
                }
            } else {
                if (!empty($r['universitas_name']) && isset($nameMap[$r['universitas_name']])) {
                    $r['universitas_name'] = $nameMap[$r['universitas_name']];
                }
                if (!empty($r['alamat']) && isset($addrMap[$r['alamat']])) {
                    $r['alamat'] = $addrMap[$r['alamat']];
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

            // PSR-16: key tidak boleh mengandung {}()/\@:  -> pakai prefix underscore + md5 :contentReference[oaicite:3]{index=3}
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

        // Google Cloud Translation (Basic) V2 client :contentReference[oaicite:4]{index=4}
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

        $baseTitle = 'Tambah Universitas'; // kalau mau ID lebih "indo": 'Profil Staff'
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

        return view('universitas/new', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $data = $this->request->getPost();
        $this->universitas->insert($data);
        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $messageBerhasil = 'Data berhasil disimpan';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $messageBerhasil = service('translation')->translateCached($messageBerhasil, 'id', 'en');
        }
        return redirect()->to(site_url('universitas'))->with('success', $messageBerhasil);
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

        $data['title_tab'] = 'Edit universitas &mdash; LPM UG';
        $data['title'] = 'Edit universitas';
        $data['pesan'] = $this->pesan->getPesan();

        $universitas = $this->universitas->where('universitas_id', $id)->first();
        if (is_object($universitas)) {
            $data['universitas'] = $universitas;
            return view('universitas/edit', $data);
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
        $this->universitas->update($id, $data);
        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $messageBerhasil = 'Data berhasil disimpan';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $messageBerhasil = service('translation')->translateCached($messageBerhasil, 'id', 'en');
        }
        return redirect()->to(site_url('universitas'))->with('success', $messageBerhasil);
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

        $this->universitas->delete($id);
        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $messageBerhasil = 'Data berhasil dihapus';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $messageBerhasil = service('translation')->translateCached($messageBerhasil, 'id', 'en');
        }
        return redirect()->to(site_url('universitas'))->with('success', $messageBerhasil);
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

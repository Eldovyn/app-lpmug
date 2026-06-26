<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

use App\Models\TopikModel;
use App\Models\ProgramModel;
use App\Models\SubprogramModel;
use App\Models\PesanModel;

use Google\Cloud\Translate\V2\TranslateClient;

class Subprogram extends ResourceController
{

    function __construct()
    {
        $this->topik        = new TopikModel();
        $this->program      = new ProgramModel();
        $this->subprogram   = new SubprogramModel();
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

        // guard yang aman
        $user = function_exists('userLogin') ? userLogin() : null;
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

        $data = $this->subprogram->getPaginated(10, $keyword);
        $data['keyword'] = $keyword;
        $data['pesan']   = $this->pesan->getPesan();

        // ===== title/title_tab =====
        $data['title']     = ($lang === 'en') ? 'Sub Program' : 'Sub program';
        $data['title_tab'] = ($lang === 'en') ? 'Sub Program &mdash; LPM UG' : 'Sub program &mdash; LPM UG';
        $data['lang']      = $lang;

        // ===== translate list saat EN =====
        if ($lang === 'en' && ! empty($data['subprogram'])) {
            foreach ($data['subprogram'] as $i => $row) {

                // object
                if (is_object($row)) {
                    if (isset($row->topik_name)) {
                        $data['subprogram'][$i]->topik_name =
                            service('translation')->translateCached((string) $row->topik_name, 'id', 'en');
                    }
                    if (isset($row->program_name)) {
                        $data['subprogram'][$i]->program_name =
                            service('translation')->translateCached((string) $row->program_name, 'id', 'en');
                    }
                    if (isset($row->subprogram_name)) {
                        $data['subprogram'][$i]->subprogram_name =
                            service('translation')->translateCached((string) $row->subprogram_name, 'id', 'en');
                    }
                    continue;
                }

                // array
                if (is_array($row)) {
                    if (isset($row['topik_name'])) {
                        $data['subprogram'][$i]['topik_name'] =
                            service('translation')->translateCached((string) $row['topik_name'], 'id', 'en');
                    }
                    if (isset($row['program_name'])) {
                        $data['subprogram'][$i]['program_name'] =
                            service('translation')->translateCached((string) $row['program_name'], 'id', 'en');
                    }
                    if (isset($row['subprogram_name'])) {
                        $data['subprogram'][$i]['subprogram_name'] =
                            service('translation')->translateCached((string) $row['subprogram_name'], 'id', 'en');
                    }
                }
            }
        }

        return view('subprogram/index', $data);
    }

    /**
     * Cache wrapper biar gak hit Translate API terus.
     * IMPORTANT: cache key WAJIB aman (jangan pakai ":" dll) karena CI4 melarang karakter tertentu. 
     * Pakai md5 biar aman + pendek.
     */
    private function translateTextCached(string $text, string $source, string $target): string
    {
        $text = trim($text);
        if ($text === '' || $source === $target) {
            return $text;
        }

        // aman: hanya huruf/angka/underscore
        $cacheKey = 'gtrans_' . md5($source . '|' . $target . '|' . $text);

        $cache = cache();

        $cached = $cache->get($cacheKey);
        if (is_string($cached) && $cached !== '') {
            return $cached;
        }

        $translated = $this->translateText($text, $source, $target);

        // simpan cache 30 hari (sesuaikan)
        $cache->save($cacheKey, $translated, 60 * 60 * 24 * 30); // cache save/get :contentReference[oaicite:3]{index=3}

        return $translated;
    }

    // ini pakai method kamu yang sudah ada (TranslateClient)
    // private function translateText(string $text, string $source, string $target): string
    // { ... }

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

        $baseTitle = 'Tambah Sub program'; // kalau mau ID lebih "indo": 'Profil Staff'
        $title = $baseTitle;

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $title = service('translation')->translateCached($baseTitle, 'id', 'en'); // V2 translate :contentReference[oaicite:0]{index=0}
        }
        $data['title'] = $title;
        $data['title_tab'] = $title . ' &mdash; LPM UG';
        $data['pesan'] = $this->pesan->getPesan();
        $data['program'] = $this->program->getAll();

        return view('subprogram/new', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $data = $this->request->getPost();

        $this->subprogram->insert($data);
        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $messageBerhasil = 'Data berhasil disimpan';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $messageBerhasil = service('translation')->translateCached($messageBerhasil, 'id', 'en');
        }
        return redirect()->to(site_url('subprogram'))->with('success', $messageBerhasil);
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

        $data['title_tab'] = 'Edit sub program &mdash; LPM UG';
        $data['title'] = 'Edit sub program';
        $data['pesan'] = $this->pesan->getPesan();

        $subprogram =  $this->subprogram->find($id);
        if (is_object($subprogram)) {
            $data['subprogram'] = $subprogram;
            $data['program'] = $this->program->getAll();
            return view('subprogram/edit', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('subprogram/edit', $data);
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $data = $this->request->getPost();
        $this->subprogram->update($id, $data);
        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $messageBerhasil = 'Data berhasil disimpan';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $messageBerhasil = service('translation')->translateCached($messageBerhasil, 'id', 'en');
        }
        return redirect()->to(site_url('subprogram'))->with('success', $messageBerhasil);
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

        $this->subprogram->delete($id);
        return redirect()->to(site_url('subprogram'))->with('success', $messageBerhasil);
    }
}

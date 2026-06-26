<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

use App\Models\TopikModel;
use App\Models\ProgramModel;
use App\Models\PesanModel;

use Google\Cloud\Translate\V2\TranslateClient;

class Program extends ResourceController
{

    function __construct()
    {
        $this->topik   = new TopikModel();
        $this->program = new ProgramModel();
        $this->pesan   = new PesanModel();
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

        // lang cookie: default id, selain en => id
        $lang = strtolower(trim((string) ($this->request->getCookie('lang') ?? 'id')));
        $lang = ($lang === 'en') ? 'en' : 'id';

        $keyword = $this->request->getGet('keyword');
        $data    = $this->program->getPaginated(10, $keyword);

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

        $baseTitle = 'Program'; // kalau mau ID lebih "indo": 'Profil Staff'
        $title = $baseTitle;

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $title = service('translation')->translateCached($baseTitle, 'id', 'en'); // V2 translate :contentReference[oaicite:0]{index=0}
        }
        $data['title'] = $title;
        $data['title_tab'] = $title . ' &mdash; LPM UG';

        // translate program_name & topik_name saat EN
        // asumsi getPaginated() ngasih list di $data['program'] (karena view foreach($program as ...))
        if ($lang === 'en' && !empty($data['program'])) {
            foreach ($data['program'] as $i => $row) {

                // object row
                if (is_object($row)) {
                    if (isset($row->program_name)) {
                        $data['program'][$i]->program_name = service('translation')->translateCached((string) $row->program_name, 'id', 'en');
                    }
                    if (isset($row->topik_name)) {
                        $data['program'][$i]->topik_name = service('translation')->translateCached((string) $row->topik_name, 'id', 'en');
                    }
                }

                // array row (kalau model kamu return array)
                if (is_array($row)) {
                    if (isset($row['program_name'])) {
                        $data['program'][$i]['program_name'] = service('translation')->translateCached((string) $row['program_name'], 'id', 'en');
                    }
                    if (isset($row['topik_name'])) {
                        $data['program'][$i]['topik_name'] = service('translation')->translateCached((string) $row['topik_name'], 'id', 'en');
                    }
                }
            }
        }

        $data['lang'] = $lang;

        return view('program/index', $data);
    }

    /**
     * Cache wrapper supaya gak hit Google Translate terus.
     * IMPORTANT: cache key jangan pakai karakter reserved (contoh ":"), amanin pakai md5.
     */
    private function translateTextCached(string $text, string $source, string $target): string
    {
        $text = trim($text);
        if ($text === '' || $source === $target) {
            return $text;
        }

        $cacheKey = $this->makeTranslateCacheKey($text, $source, $target);

        $cache  = cache();
        $cached = $cache->get($cacheKey);
        if (is_string($cached) && $cached !== '') {
            return $cached;
        }

        $translated = $this->translateText($text, $source, $target);

        // simpan cache 30 hari (detik)
        $cache->save($cacheKey, $translated, 60 * 60 * 24 * 30);

        return $translated;
    }

    private function makeTranslateCacheKey(string $text, string $source, string $target): string
    {
        // Hindari ":" karena termasuk reserved characters yang bikin InvalidArgumentException
        // Hash biar key aman & pendek
        return 'gtrans_' . md5($source . '|' . $target . '|' . $text);
    }

    private function translateText(string $text, string $source, string $target): string
    {
        $key  = $_ENV['apiKeyGoogleTranslateApi'] ?? null;
        $text = trim((string) $text);

        if (!$key || $text === '' || $source === $target) {
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
        $lang = strtolower(trim((string) ($this->request->getCookie('lang') ?? 'id')));
        $lang = ($lang === 'en') ? 'en' : 'id'; // :contentReference[oaicite:0]{index=0}

        $titles = [
            'id' => [
                'title_tab' => 'Tambah program &mdash; LPM UG',
                'title'     => 'Tambah program',
            ],
            'en' => [
                'title_tab' => 'Add program &mdash; LPM UG',
                'title'     => 'Add program',
            ],
        ];

        $data['title_tab'] = $titles[$lang]['title_tab'];
        $data['title']     = $titles[$lang]['title'];

        $data['pesan']    = $this->pesan->getPesan();
        $data['topik']    = $this->topik->findAll(); // contoh kalau form butuh opsi topik

        return view('program/new', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $data = $this->request->getPost();
        // $data = [
        //     'program_name' => $this->request->getVar('program_name'),
        //     'topik_id' => $this->request->getVar('topik_id'),
        // ];

        $this->program->insert($data);
        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $messageBerhasil = 'Data berhasil disimpan';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $messageBerhasil = service('translation')->translateCached($messageBerhasil, 'id', 'en');
        }
        return redirect()->to(site_url('program'))->with('success', $messageBerhasil);
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

        $lang = strtolower(trim((string) ($this->request->getCookie('lang') ?? 'id')));
        $lang = ($lang === 'en') ? 'en' : 'id';

        $titles = [
            'id' => ['title_tab' => 'Edit program &mdash; LPM UG', 'title' => 'Edit program'],
            'en' => ['title_tab' => 'Edit Program &mdash; LPM UG', 'title' => 'Edit Program'],
        ];

        $data['title_tab'] = $titles[$lang]['title_tab'];
        $data['title']     = $titles[$lang]['title'];
        $data['pesan'] = $this->pesan->getPesan();

        $program =  $this->program->find($id);
        if (is_object($program)) {
            $data['program'] = $program;
            $data['topik'] = $this->topik->findAll();
            return view('program/edit', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('program/edit', $data);
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $data = $this->request->getPost();
        $this->program->update($id, $data);
        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $messageBerhasil = 'Data berhasil disimpan';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $messageBerhasil = service('translation')->translateCached($messageBerhasil, 'id', 'en');
        }
        return redirect()->to(site_url('program'))->with('success', $messageBerhasil);
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

        $this->program->delete($id);
        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $messageBerhasil = 'Data berhasil disimpan';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $messageBerhasil = service('translation')->translateCached($messageBerhasil, 'id', 'en');
        }
        return redirect()->to(site_url('program'))->with('success', $messageBerhasil);
    }
}

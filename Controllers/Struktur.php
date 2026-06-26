<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

use App\Models\StrukturModel;
use App\Models\PesanModel;

use Google\Cloud\Translate\V2\TranslateClient;

class Struktur extends ResourceController
{
    function __construct()
    {
        $this->struktur = new StrukturModel();
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

        $user = userLogin();
        if (!$user) return redirect()->to(site_url('login'));
        if (!in_array((int) $user->role_id, [1, 2], true)) return redirect()->to(site_url('dashboard'));

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

        $data = $this->struktur->getPaginated(10, $keyword);
        $data['keyword'] = $keyword;
        $data['pesan']   = $this->pesan->getPesan();

        // ====== title/title_tab (awal dalam ID, lalu translate kalau EN)
        $titleId = 'Data Struktur';

        $data['title']       = $titleId;
        $data['title_tab']   = $titleId;
        $data['description'] = 'Kelola data struktur';

        if ($lang === 'en') {
            $data['title']       = $this->translateText($titleId, 'id', 'en');
            $data['title_tab']   = $this->translateText($titleId, 'id', 'en') . ' — LPM UG';
            $data['description'] = $this->translateText($data['description'], 'id', 'en');

            // Translate judul & deskripsi dari rows (key list biasanya 'struktur' karena view foreach($struktur...))
            if (!empty($data['struktur'])) {
                $this->translateStrukturFields($data['struktur']);
            }
        }

        return view('struktur/index', $data);
    }

    /**
     * Translate field judul & deskripsi pada rows Struktur.
     * NOTE: deskripsi biasanya HTML (summernote). Kalau translate HTML mentah kadang berantakan,
     * jadi di sini saya translate deskripsi HANYA kalau tidak ada tag HTML.
     */
    /**
     * Translate field judul & deskripsi pada rows Struktur.
     * Deskripsi (summernote) biasanya HTML, jadi kita translate pakai format=html.
     */
    private function translateStrukturFields(array &$rows): void
    {
        $uniqTitle = [];
        $uniqDesc  = [];

        foreach ($rows as $r) {
            if (!empty($r->judul)) {
                $uniqTitle[(string) $r->judul] = true;
            }
            if (!empty($r->deskripsi)) {
                $uniqDesc[(string) $r->deskripsi] = true; // ikutkan HTML juga
            }
        }

        // judul = text
        $titleMap = $this->translateMany(array_keys($uniqTitle), 'id', 'en', 'text', 'tr_struktur_title_');
        // deskripsi = html (summernote)
        $descMap  = $this->translateMany(array_keys($uniqDesc),  'id', 'en', 'html', 'tr_struktur_desc_');

        foreach ($rows as $r) {
            if (!empty($r->judul) && isset($titleMap[$r->judul])) {
                $r->judul = $titleMap[$r->judul];
            }
            if (!empty($r->deskripsi) && isset($descMap[$r->deskripsi])) {
                $r->deskripsi = $descMap[$r->deskripsi];
            }
        }
    }

    private function translateMany(
        array $texts,
        string $source,
        string $target,
        string $format,      // 'text' atau 'html'
        string $cachePrefix
    ): array {
        $out = [];

        foreach ($texts as $text) {
            $text = (string) $text;
            if (trim($text) === '') continue;

            // cache key aman + bedakan berdasarkan format juga
            $cacheKey = $cachePrefix . md5($source . '|' . $target . '|' . $format . '|' . $text);

            $cached = cache()->get($cacheKey);
            if (is_string($cached) && $cached !== '') {
                $out[$text] = $cached;
                continue;
            }

            $translated = $this->translateText($text, $source, $target, $format);
            $out[$text] = $translated;

            cache()->save($cacheKey, $translated, 60 * 60 * 24 * 30);
        }

        return $out;
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
        helper('form');
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }
        $baseTitle = 'Tambah Struktur'; // kalau mau ID lebih "indo": 'Profil Staff'
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
        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $title = service('translation')->translateCached($baseTitle, 'id', 'en'); // V2 translate :contentReference[oaicite:0]{index=0}
        }
        $data['title'] = $title;
        $data['title_tab'] = $title . ' &mdash; LPM UG';
        $data['pesan'] = $this->pesan->getPesan();

        // $data['validation'] = \Config\Services::validation();

        $data['struktur'] = $this->struktur->findAll();

        return view('struktur/new', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        helper('form');
        // if(!$this->validate($this->struktur->getValidationRules())) {
        //     $validation = \Config\Services::validation();
        //     // dd($validation);
        //     return redirect()->back()->withInput()->with('validation', $validation);
        // }

        $file = $this->request->getFile('gambar');
        $namaGambar = '';
        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $ext = strtolower($file->getExtension());
            $mime = $file->getMimeType();
            $allowedExts = ['png', 'jpg', 'jpeg', 'gif', 'webp'];
            if (!in_array($ext, $allowedExts, true) || strpos($mime, 'image/') !== 0) {
                return redirect()->back()->withInput()->with('error', 'File harus berupa gambar (png, jpg, jpeg, gif, webp).');
            }
            if ($file->getSize() > 5 * 1024 * 1024) {
                return redirect()->back()->withInput()->with('error', 'Ukuran gambar maksimal 5MB.');
            }

            $namaGambar = $file->getRandomName();
            $file->move('img/upload/struktur', $namaGambar);
        }

        $data = [
            'judul'     => $this->request->getPost('judul'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'gambar'    => $namaGambar,
        ];
        // dd($data);

        $this->struktur->save($data);
        return redirect()->to(site_url('struktur'))->with('success', 'Data baru anda berhasil disimpan.');
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        helper('auth');
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $baseTitle = 'Edit Struktur'; // kalau mau ID lebih "indo": 'Profil Staff'
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
        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $title = service('translation')->translateCached($baseTitle, 'id', 'en'); // V2 translate :contentReference[oaicite:0]{index=0}
        }
        $data['title'] = $title;
        $data['title_tab'] = $title . ' &mdash; LPM UG';
        $data['pesan'] = $this->pesan->getPesan();

        $struktur =  $this->struktur->find($id);
        if (is_object($struktur)) {
            $data['struktur'] = $struktur;
            return view('struktur/edit', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('struktur/edit', $data);
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $struktur = $this->struktur->find($id);
        $old_img_name = $struktur->gambar;

        $file = $this->request->getFile('gambar');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $ext = strtolower($file->getExtension());
            $mime = $file->getMimeType();
            $allowedExts = ['png', 'jpg', 'jpeg', 'gif', 'webp'];
            if (!in_array($ext, $allowedExts, true) || strpos($mime, 'image/') !== 0) {
                return redirect()->back()->withInput()->with('error', 'File harus berupa gambar (png, jpg, jpeg, gif, webp).');
            }
            if ($file->getSize() > 5 * 1024 * 1024) {
                return redirect()->back()->withInput()->with('error', 'Ukuran gambar maksimal 5MB.');
            }

            if (!empty($old_img_name) && file_exists('img/upload/struktur/' . $old_img_name)) {
                unlink('img/upload/struktur/' . $old_img_name);
            }

            $namaGambar = $file->getRandomName();
            $file->move('img/upload/struktur', $namaGambar);
        } else {
            $namaGambar = $old_img_name;
        }

        $data = [
            'judul'     => $this->request->getPost('judul'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'gambar'    => $namaGambar,
        ];

        $this->struktur->update($id, $data);
        return redirect()->to(site_url('struktur'))->with('success', 'Data anda berhasil diupdate.');
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

        $struktur = $this->struktur->find($id);
        $namaGambar = $struktur->gambar;
        if (!empty($namaGambar) && file_exists('img/upload/struktur/' . $namaGambar)) {
            unlink('img/upload/struktur/' . $namaGambar);
        }

        $this->struktur->delete($id);
        return redirect()->to(site_url('struktur'))->with('success', 'Data anda berhasil dihapus.');
    }
}

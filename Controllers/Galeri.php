<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

use App\Models\GaleriModel;
use App\Models\PesanModel;
use Google\Cloud\Translate\V2\TranslateClient;

class Galeri extends ResourceController
{
    function __construct()
    {
        $this->galeri = new GaleriModel();
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

        $keyword = $this->request->getGet('keyword');

        $data = $this->galeri->getPaginated(10, $keyword);
        $data['keyword'] = $keyword;
        $data['pesan']   = $this->pesan->getPesan();

        $baseTitle = 'Data Galeri'; // kalau mau ID lebih "indo": 'Profil Staff'
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
        $data['title'] = $title;
        $data['title_tab'] = $title . ' &mdash; LPM UG';

        if ($lang === 'en') {
            // Translate judul & deskripsi rows galeri
            if (!empty($data['galeri'])) {
                $this->translateGaleriFields($data['galeri']);
            }
        }

        return view('galeri/index', $data);
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

    private function translateGaleriFields(array &$rows): void
    {
        $uniqTitle = [];
        $uniqDesc  = [];

        foreach ($rows as $r) {
            if (!empty($r->judul)) {
                $uniqTitle[(string) $r->judul] = true;
            }
            if (!empty($r->deskripsi)) {
                $uniqDesc[(string) $r->deskripsi] = true; // HTML ikut
            }
        }

        // judul = text, deskripsi = html
        $titleMap = $this->translateMany(array_keys($uniqTitle), 'id', 'en', 'text', 'tr_galeri_title_');
        $descMap  = $this->translateMany(array_keys($uniqDesc),  'id', 'en', 'html', 'tr_galeri_desc_');

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
        string $format,      // 'text' | 'html'
        string $cachePrefix
    ): array {
        $out = [];

        foreach ($texts as $text) {
            $text = (string) $text;
            if (trim($text) === '') continue;

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

        // Client library Cloud Translation (V2) :contentReference[oaicite:3]{index=3}
        $client = new TranslateClient(['key' => $key]);

        $result = $client->translate($text, [
            'source' => $source,
            'target' => $target,
            'format' => $format, // 'html' untuk summernote
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
        helper('form');
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2) {
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

        $baseTitle = 'Tambah Galeri'; // kalau mau ID lebih "indo": 'Profil Staff'
        $title = $baseTitle;

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $title = service('translation')->translateCached($baseTitle, 'id', 'en'); // V2 translate :contentReference[oaicite:0]{index=0}
        }
        $data['title'] = $title;
        $data['title_tab'] = $title . ' &mdash; LPM UG';
        $data['pesan'] = $this->pesan->getPesan();

        // $data['validation'] = \Config\Services::validation();

        $data['galeri'] = $this->galeri->findAll();

        return view('galeri/new', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        helper('auth');
        helper('form');
        // if(!$this->validate($this->galeri->getValidationRules())) {
        //     $validation = \Config\Services::validation();
        //     // dd($validation);
        //     return redirect()->back()->withInput()->with('validation', $validation);
        // }

        $file = $this->request->getFile('gambar');
        $namaGambar = '';
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

            $namaGambar = $file->getRandomName();
            $file->move('img/upload/galeri', $namaGambar);
        }

        $data = [
            'judul'     => $this->request->getPost('judul'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'gambar'    => $namaGambar,
        ];
        // dd($data);

        $this->galeri->save($data);
        return redirect()->to(site_url('galeri'))->with('success', 'Data baru anda berhasil disimpan.');
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

        $baseTitle = 'Edit Galeri'; // kalau mau ID lebih "indo": 'Profil Staff'
        $title = $baseTitle;

        if ($lang === 'en') {
            $title = service('translation')->translateCached($baseTitle, 'id', 'en'); // V2 translate :contentReference[oaicite:0]{index=0}
        }
        $data['title'] = $title;
        $data['title_tab'] = $title . ' &mdash; LPM UG';
        $data['pesan'] = $this->pesan->getPesan();

        $galeri =  $this->galeri->find($id);
        if (is_object($galeri)) {
            $data['galeri'] = $galeri;
            return view('galeri/edit', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('galeri/edit', $data);
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $galeri = $this->galeri->find($id);
        $old_img_name = $galeri->gambar;

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

            if (!empty($old_img_name) && file_exists('img/upload/galeri/' . $old_img_name)) {
                unlink('img/upload/galeri/' . $old_img_name);
            }

            $namaGambar = $file->getRandomName();
            $file->move('img/upload/galeri', $namaGambar);
        } else {
            $namaGambar = $old_img_name;
        }

        $data = [
            'judul'     => $this->request->getPost('judul'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'gambar'    => $namaGambar,
        ];

        $this->galeri->update($id, $data);
        return redirect()->to(site_url('galeri'))->with('success', 'Data anda berhasil diupdate.');
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        helper('auth');
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2 && userLogin()->role_id != 3) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $galeri = $this->galeri->find($id);
        $namaGambar = $galeri->gambar;
        if (file_exists('img/upload/galeri' . $namaGambar)) {
            unlink('img/upload/galeri' . $namaGambar);
        }

        $this->galeri->delete($id);
        return redirect()->to(site_url('galeri'))->with('success', 'Data anda berhasil dihapus.');
    }
}

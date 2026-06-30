<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

use App\Models\ProfilelpmModel;
use App\Models\PesanModel;
use Google\Cloud\Translate\V2\TranslateClient;

class Profilelpm extends ResourceController
{
    function __construct()
    {
        $this->profilelpm = new ProfilelpmModel();
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

        $data = $this->profilelpm->getPaginated(10, $keyword);
        $data['keyword'] = $keyword;
        $data['pesan']   = $this->pesan->getPesan();
        $data['lang']    = $lang;

        // ====== title/title_tab (awal dalam ID, lalu translate kalau EN)
        $titleId = 'Data Profile';

        $data['title']     = $titleId;
        $data['title_tab'] = $titleId;

        if ($lang === 'en') {
            $data['title']     = $this->translateText($titleId, 'id', 'en');
            $data['title_tab'] = $this->translateText($titleId, 'id', 'en') . ' — LPM UG';

            // Translate judul & deskripsi dari rows
            if (!empty($data['profilelpm'])) {
                $this->translateProfilelpmFields($data['profilelpm']);
            }
        }

        return view('profilelpm/index', $data);
    }

    /**
     * Translate field judul & deskripsi pada rows ProfileLPM.
     * NOTE: deskripsi biasanya HTML (summernote). Kalau kamu translate HTML mentah,
     * hasilnya sering berantakan. Di bawah ini saya translate deskripsi HANYA kalau tidak ada tag HTML.
     */
    private function translateProfilelpmFields(array &$rows): void
    {
        $uniqTitle = [];
        $uniqDesc  = [];

        foreach ($rows as $r) {
            if (!empty($r->judul)) {
                $uniqTitle[(string) $r->judul] = true;
            }

            if (!empty($r->deskripsi)) {
                $desc = (string) $r->deskripsi;

                // aman: translate hanya plain text (tidak mengandung "<")
                if (strpos($desc, '<') === false) {
                    $uniqDesc[$desc] = true;
                }
            }
        }

        $titleMap = $this->translateMany(array_keys($uniqTitle), 'id', 'en', 'tr_profilelpm_title_');
        $descMap  = $this->translateMany(array_keys($uniqDesc),  'id', 'en', 'tr_profilelpm_desc_');

        foreach ($rows as $r) {
            if (!empty($r->judul) && isset($titleMap[$r->judul])) {
                $r->judul = $titleMap[$r->judul];
            }
            if (!empty($r->deskripsi) && isset($descMap[$r->deskripsi])) {
                $r->deskripsi = $descMap[$r->deskripsi];
            }
        }
    }

    private function translateMany(array $texts, string $source, string $target, string $cachePrefix): array
    {
        $out = [];

        foreach ($texts as $text) {
            $text = (string) $text;
            if (trim($text) === '') continue;

            // key cache aman -> pakai md5 agar tidak kena karakter terlarang PSR-16
            $cacheKey = $cachePrefix . md5($source . '|' . $target . '|' . $text);

            $cached = cache()->get($cacheKey);
            if (is_string($cached) && $cached !== '') {
                $out[$text] = $cached;
                continue;
            }

            $translated = $this->translateText($text, $source, $target);
            $out[$text] = $translated;

            // simpan 30 hari
            cache()->save($cacheKey, $translated, 60 * 60 * 24 * 30);
        }

        return $out;
    }

    private function translateText(string $text, string $source, string $target): string
    {
        $key = $_ENV['apiKeyGoogleTranslateApi'] ?? null;
        if (!$key || trim($text) === '') return $text;

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
        $titleMap = [
            'id' => [
                'title'     => 'Tambah profile',
                'title_tab' => 'Tambah profile &mdash; LPM UG',
            ],
            'en' => [
                'title'     => 'Add profile',
                'title_tab' => 'Add profile &mdash; LPM UG',
            ],
        ];

        $data['title']     = $titleMap[$lang]['title'];
        $data['title_tab'] = $titleMap[$lang]['title_tab'];
        $data['pesan'] = $this->pesan->getPesan();

        // $data['validation'] = \Config\Services::validation();

        $data['profilelpm'] = $this->profilelpm->findAll();

        return view('profilelpm/new', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        helper('form');
        // if(!$this->validate($this->profilelpm->getValidationRules())) {
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
            $file->move('img/upload/profilelpm', $namaGambar);
        }

        $data = [
            'judul'     => $this->request->getPost('judul'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'gambar'    => $namaGambar,
        ];
        // dd($data);

        $this->profilelpm->save($data);
        return redirect()->to(site_url('profilelpm'))->with('success', 'Data baru anda berhasil disimpan.');
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

        $titleMap = [
            'id' => [
                'title'     => 'Edit profile',
                'title_tab' => 'Edit profile &mdash; LPM UG',
            ],
            'en' => [
                'title'     => 'Edit profile',
                'title_tab' => 'Edit profile &mdash; LPM UG',
            ],
        ];

        $data['title']     = $titleMap[$lang]['title'];
        $data['title_tab'] = $titleMap[$lang]['title_tab'];
        $data['pesan'] = $this->pesan->getPesan();

        $profilelpm =  $this->profilelpm->find($id);
        if (is_object($profilelpm)) {
            $data['profilelpm'] = $profilelpm;
            return view('profilelpm/edit', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('profilelpm/edit', $data);
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $profilelpm = $this->profilelpm->find($id);
        $old_img_name = $profilelpm->gambar;

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

            if (!empty($old_img_name) && file_exists('img/upload/profilelpm/' . $old_img_name)) {
                unlink('img/upload/profilelpm/' . $old_img_name);
            }

            $namaGambar = $file->getRandomName();
            $file->move('img/upload/profilelpm', $namaGambar);
        } else {
            $namaGambar = $old_img_name;
        }

        $data = [
            'judul'     => $this->request->getPost('judul'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'gambar'    => $namaGambar,
        ];

        $this->profilelpm->update($id, $data);
        return redirect()->to(site_url('profilelpm'))->with('success', 'Data anda berhasil diupdate.');
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

        $profilelpm = $this->profilelpm->find($id);
        $namaGambar = $profilelpm->gambar;
        if (!empty($namaGambar) && file_exists('img/upload/profilelpm/' . $namaGambar)) {
            unlink('img/upload/profilelpm/' . $namaGambar);
        }

        $this->profilelpm->delete($id);
        return redirect()->to(site_url('profilelpm'))->with('success', 'Data anda berhasil dihapus.');
    }
}

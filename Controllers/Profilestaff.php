<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

use App\Models\ProfilestaffModel;
use App\Models\PesanModel;
use Google\Cloud\Translate\V2\TranslateClient;

class Profilestaff extends ResourceController
{
    function __construct()
    {
        $this->profilestaff = new ProfilestaffModel();
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

        if (userLogin()->role_id != 1 && userLogin()->role_id != 2) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $keyword = $this->request->getGet('keyword');
        $data = $this->profilestaff->getPaginated(10, $keyword);
        $data['keyword'] = $keyword;
        $data['pesan'] = $this->pesan->getPesan();

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

        // ===== Translate judul+deskripsi kalau EN =====
        if ($lang === 'en') {
            $rowsKey = 'profilestaff'; // sesuai view kamu: foreach($profilestaff as ...)
            if (!empty($data[$rowsKey]) && is_array($data[$rowsKey])) {
                $data[$rowsKey] = $this->translateProfileStaffRows($data[$rowsKey], 'id', 'en');
            }
        }

        $baseTitle = 'Profile Staff'; // kalau mau ID lebih "indo": 'Profil Staff'
        $title = $baseTitle;

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $title = service('translation')->translateCached($baseTitle, 'id', 'en'); // V2 translate :contentReference[oaicite:0]{index=0}
        }
        $data['title'] = $title;
        $data['title_tab'] = $title . ' &mdash; LPM UG';

        return view('profilestaff/index', $data);
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

    private function translateText(string $text, string $source, string $target): string
    {
        $apiKey = env('apiKeyGoogleTranslateApi') ?? ($_ENV['apiKeyGoogleTranslateApi'] ?? null);

        $text = trim((string) $text);
        if (! $apiKey || $text === '' || $source === $target) {
            return $text;
        }

        try {
            // Cloud Translation Basic (V2) client, bisa pakai API key :contentReference[oaicite:2]{index=2}
            $client = new TranslateClient([
                'key'    => $apiKey,
                'target' => $target,
            ]);

            $result = $client->translate($text, [
                'source' => $source,
                'target' => $target,
                'format' => 'text',
            ]);

            return $result['text'] ?? $text;
        } catch (\Throwable $e) {
            return $text; // fallback kalau API error
        }
    }


    /**
     * Translate judul & deskripsi (batch) + cache.
     */
    private function translateProfileStaffRows(array $rows, string $source, string $target): array
    {
        $apiKey = env('apiKeyGoogleTranslateApi') ?? ($_ENV['apiKeyGoogleTranslateApi'] ?? null);
        if (! $apiKey) {
            return $rows; // kalau key kosong, skip (hindari 403)
        }

        $translate = new TranslateClient([
            'key'    => $apiKey,  // API key (identity) :contentReference[oaicite:2]{index=2}
            'target' => $target,  // default target client :contentReference[oaicite:3]{index=3}
        ]);

        $cache  = cache();
        $fields = ['judul', 'deskripsi'];

        $texts = [];
        $map   = []; // [rowIndex, fieldName, cacheKey]

        foreach ($rows as $i => $row) {
            foreach ($fields as $f) {
                $val = is_object($row) ? ($row->$f ?? '') : ($row[$f] ?? '');
                $val = trim((string) $val);
                if ($val === '' || $source === $target) continue;

                // cacheKey aman (tanpa ":" dkk) :contentReference[oaicite:4]{index=4}
                $cacheKey = 'tr_' . md5($target . '|' . $val);

                $cached = $cache->get($cacheKey);
                if (is_string($cached) && $cached !== '') {
                    if (is_object($rows[$i])) $rows[$i]->$f = $cached;
                    else $rows[$i][$f] = $cached;
                    continue;
                }

                $texts[] = $val;
                $map[] = [$i, $f, $cacheKey];
            }
        }

        if ($texts) {
            $results = $translate->translateBatch($texts, [
                'source' => $source,
                'target' => $target,
                'format' => 'html', // deskripsi dari summernote biasanya HTML :contentReference[oaicite:5]{index=5}
            ]); // translateBatch :contentReference[oaicite:6]{index=6}

            foreach ($results as $idx => $res) {
                $translated = $res['text'] ?? $texts[$idx];
                [$rowIndex, $fieldName, $cacheKey] = $map[$idx];

                if (is_object($rows[$rowIndex])) $rows[$rowIndex]->$fieldName = $translated;
                else $rows[$rowIndex][$fieldName] = $translated;

                $cache->save($cacheKey, $translated, 60 * 60 * 24 * 30); // 30 hari
            }
        }

        return $rows;
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
        $baseTitle = 'Tambah Staff'; // kalau mau ID lebih "indo": 'Profil Staff'
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

        $data['profilestaff'] = $this->profilestaff->findAll();

        return view('profilestaff/new', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        helper('form');
        // if(!$this->validate($this->profilestaff->getValidationRules())) {
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
            $file->move('img/upload/profilestaff', $namaGambar);
        }

        $data = [
            'judul'     => $this->request->getPost('judul'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'gambar'    => $namaGambar,
        ];
        // dd($data);

        $this->profilestaff->save($data);
        return redirect()->to(site_url('profilestaff'))->with('success', 'Data baru anda berhasil disimpan.');
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

        // ===== Language switch (ID/EN) =====
        helper('cookie');

        $allowed = ['id', 'en'];

        // kalau ada ?lang=..., set cookie lalu redirect agar cookie kebaca di request berikutnya
        $reqLang = $this->request->getGet('lang');
        if ($reqLang) {
            $reqLang = strtolower(trim((string) $reqLang));
            if (in_array($reqLang, $allowed, true)) {
                set_cookie('lang', $reqLang, 60 * 60 * 24 * 30);

                // redirect ke URL edit yang sama TANPA query lang
                // (cookie akan terbaca setelah redirect)
                return redirect()
                    ->to(site_url('profilestaff/' . $id . '/edit'))
                    ->withCookies();
            }
        }

        // baca bahasa dari cookie (default id)
        $lang = $this->request->getCookie('lang') ?: 'id';
        $lang = strtolower(trim((string) $lang));
        if (!in_array($lang, $allowed, true)) {
            $lang = 'id';
        }

        // ===== Data halaman =====
        $profilestaff = $this->profilestaff->find($id);
        if (!is_object($profilestaff)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // judul (contoh sederhana, tanpa translate API)
        $baseTitle = 'Edit Profile';
        $title = ($lang === 'en') ? 'Edit Profile' : 'Edit Profil';

        $data = [];
        $data['title'] = $title;
        $data['title_tab'] = $title . ' &mdash; LPM UG';
        $data['pesan'] = $this->pesan->getPesan();
        $data['profilestaff'] = $profilestaff;

        // kirim juga lang ke view biar view bisa pilih label
        $data['lang'] = $lang;

        return view('profilestaff/edit', $data);
    }


    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $profilestaff = $this->profilestaff->find($id);
        $old_img_name = $profilestaff->gambar;

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

            if (!empty($old_img_name) && file_exists('img/upload/profilestaff/' . $old_img_name)) {
                unlink('img/upload/profilestaff/' . $old_img_name);
            }

            $namaGambar = $file->getRandomName();
            $file->move('img/upload/profilestaff', $namaGambar);
        } else {
            $namaGambar = $old_img_name;
        }

        $data = [
            'judul'     => $this->request->getPost('judul'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'gambar'    => $namaGambar,
        ];

        $this->profilestaff->update($id, $data);
        return redirect()->to(site_url('profilestaff'))->with('success', 'Data anda berhasil diupdate.');
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

        $profilestaff = $this->profilestaff->find($id);
        $namaGambar = $profilestaff->gambar;
        if (!empty($namaGambar) && file_exists('img/upload/profilestaff/' . $namaGambar)) {
            unlink('img/upload/profilestaff/' . $namaGambar);
        }

        $this->profilestaff->delete($id);
        return redirect()->to(site_url('profilestaff'))->with('success', 'Data anda berhasil dihapus.');
    }
}

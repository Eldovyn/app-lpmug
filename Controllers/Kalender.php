<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

use App\Models\KalenderModel;
use App\Models\PesanModel;
use Google\Cloud\Translate\V2\TranslateClient;

class Kalender extends ResourceController
{
    function __construct()
    {
        $this->kalender = new KalenderModel();
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
        $data = $this->kalender->getPaginated(10, $keyword);
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

        // ===== Translate hanya kalau EN =====
        if ($lang === 'en') {
            $rowsKey = 'kalender'; // ganti kalau list kamu bukan $data['kalender']
            if (!empty($data[$rowsKey]) && is_array($data[$rowsKey])) {
                $data[$rowsKey] = $this->translateKalenderRows($data[$rowsKey], 'id', 'en');
            }
        }

        $baseTitle = 'Kalender Pengabdian'; // kalau mau ID lebih "indo": 'Profil Staff'
        $title = $baseTitle;

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $title = service('translation')->translateCached($baseTitle, 'id', 'en'); // V2 translate :contentReference[oaicite:0]{index=0}
        }
        $data['title'] = $title;
        $data['title_tab'] = $title . ' &mdash; LPM UG';

        return view('kalender/index', $data);
    }

    /**
     * Translate kegiatan, waktu, keterangan (batch) + cache biar hemat biaya.
     */
    private function translateKalenderRows(array $rows, string $source, string $target): array
    {
        $apiKey = env('apiKeyGoogleTranslateApi') ?? ($_ENV['apiKeyGoogleTranslateApi'] ?? null);
        if (! $apiKey) {
            return $rows; // kalau key kosong, jangan translate (hindari 403)
        }

        $translate = new \Google\Cloud\Translate\V2\TranslateClient([
            'key'    => $apiKey,  // ✅ ini yang bikin request “registered caller” :contentReference[oaicite:1]{index=1}
            'target' => $target,  // ISO 639-1 :contentReference[oaicite:2]{index=2}
        ]);

        $cache = cache();
        $fields = ['kegiatan', 'waktu', 'keterangan'];
        $texts = [];
        $map   = [];

        foreach ($rows as $i => $row) {
            foreach ($fields as $f) {
                $val = is_object($row) ? ($row->$f ?? '') : ($row[$f] ?? '');
                if ($val === '' || $val === null) continue;

                $cacheKey = 'tr_' . md5($target . '|' . $val);
                $cached = $cache->get($cacheKey);

                if (is_string($cached) && $cached !== '') {
                    if (is_object($rows[$i])) $rows[$i]->$f = $cached;
                    else $rows[$i][$f] = $cached;
                } else {
                    $texts[] = $val;
                    $map[] = [$i, $f, $cacheKey];
                }
            }
        }

        if (!empty($texts)) {
            $results = $translate->translateBatch($texts, [
                'source' => $source,
                'target' => $target,
                'format' => 'html',
            ]); // translateBatch memang method resmi :contentReference[oaicite:3]{index=3}

            foreach ($results as $idx => $res) {
                $translated = $res['text'] ?? $texts[$idx];
                [$rowIndex, $fieldName, $cacheKey] = $map[$idx];

                if (is_object($rows[$rowIndex])) $rows[$rowIndex]->$fieldName = $translated;
                else $rows[$rowIndex][$fieldName] = $translated;

                $cache->save($cacheKey, $translated, 60 * 60 * 24 * 30);
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
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2 && userLogin()->role_id != 3) {
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

        $baseTitle = 'Tambah Kalender'; // kalau mau ID lebih "indo": 'Profil Staff'
        $title = $baseTitle;

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $title = service('translation')->translateCached($baseTitle, 'id', 'en'); // V2 translate :contentReference[oaicite:0]{index=0}
        }
        $data['title'] = $title;
        $data['title_tab'] = $title . ' &mdash; LPM UG';
        $data['pesan'] = $this->pesan->getPesan();

        return view('kalender/new', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $data = $this->request->getPost();
        $this->kalender->insert($data);
        return redirect()->to(site_url('kalender'))->with('success', 'Data baru anda berhasil disimpan.');
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

        $baseTitle = 'Edit Kalender'; // kalau mau ID lebih "indo": 'Profil Staff'
        $title = $baseTitle;

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $title = service('translation')->translateCached($baseTitle, 'id', 'en'); // V2 translate :contentReference[oaicite:0]{index=0}
        }
        $data['title'] = $title;
        $data['title_tab'] = $title . ' &mdash; LPM UG';
        $data['pesan'] = $this->pesan->getPesan();

        $kalender = $this->kalender->where('kalender_id', $id)->first();
        if (is_object($kalender)) {
            $data['kalender'] = $kalender;
            return view('kalender/edit', $data);
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
        $this->kalender->update($id, $data);
        return redirect()->to(site_url('kalender'))->with('success', 'Data anda berhasil diupdate.');
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

        $this->kalender->delete($id);
        return redirect()->to(site_url('kalender'))->with('success', 'Data anda berhasil dihapus.');
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

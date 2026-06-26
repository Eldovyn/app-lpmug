<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

use App\Models\AbdimasModel;
use App\Models\TagsModel;
use App\Models\TagluaranModel;
use App\Models\DosenModel;
use App\Models\MitraModel;
use App\Models\SubprogramModel;
use App\Models\LuaranModel;
use App\Models\PeriodeModel;
use App\Models\PesanModel;
use Google\Cloud\Translate\V2\TranslateClient;


class Pelaporan extends ResourceController
{
    function __construct()
    {
        $this->abdimas      = new AbdimasModel();
        $this->tags         = new TagsModel();
        $this->tagluaran    = new TagluaranModel();
        $this->dosen        = new DosenModel();
        $this->mitra        = new MitraModel();
        $this->subprogram   = new SubprogramModel();
        $this->luaran       = new LuaranModel();
        $this->periode      = new PeriodeModel();
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
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2 && userLogin()->role_id != 3 && userLogin()->role_id != 4) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $keyword = $this->request->getGet('keyword');
        $data = $this->abdimas->getPaginated(10, $keyword);
        $baseTitle = 'Pelaporan'; // kalau mau ID lebih "indo": 'Profil Staff'
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

        $data['keyword'] = $keyword;
        $data['tags'] = $this->tags->getAnggota();
        $data['pesan'] = $this->pesan->getPesan();
        $data['mitra'] = $this->abdimas->getMitra();
        $data['anggota'] = $this->tags->getAnggota();
        $data['laporan'] = $this->abdimas->getAll();

        return view('abdimas/index_pelaporan', $data);
    }



    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null) {}

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new() {}

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create() {}

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        helper('auth');
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2 && userLogin()->role_id != 3 && userLogin()->role_id != 4) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $baseTitle = 'Update Pelaporan'; // kalau mau ID lebih "indo": 'Profil Staff'
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

        $abdimas =  $this->abdimas->find($id);
        if (is_object($abdimas)) {
            $data['abdimas']    = $abdimas;
            $data['dosen']      = $this->dosen->findAll();
            $data['anggota']    = $this->abdimas->getAnggota();
            $data['tags']       = $this->tags->getAnggota($id);
            $data['tagluaran']  = $this->tagluaran->getLuaran($id);
            $data['mitra']      = $this->mitra->getAll();
            $data['subprogram'] = $this->subprogram->getAll();
            $data['luaran']     = $this->luaran->findAll();
            $data['periode']    = $this->periode->findAll();
            $data['pesan']      = $this->pesan->getPesan();
            $data['laporan'] = $this->abdimas->getAll();
            return view('abdimas/edit_pelaporan', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('abdimas/edit_pelaporan', $data);
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $db      = \Config\Database::connect();
        $abdimas =  $this->abdimas->find($id);

        $old_laporan_name = $abdimas->laporan;
        // LAPORAN
        $laporan = $this->request->getFile('laporan');
        if ($laporan && $laporan->isValid() && !$laporan->hasMoved()) {
            // Validate size (max 10MB)
            if ($laporan->getSize() > 10 * 1024 * 1024) {
                return redirect()->back()->withInput()->with('error', 'Ukuran file laporan maksimal 10MB.');
            }
            // Validate extension and MIME type
            if (strtolower($laporan->getExtension()) !== 'pdf' || $laporan->getMimeType() !== 'application/pdf') {
                return redirect()->back()->withInput()->with('error', 'File laporan harus berformat PDF.');
            }

            if (!empty($old_laporan_name) && file_exists('berkas/laporan/' . $old_laporan_name)) {
                unlink('berkas/laporan/' . $old_laporan_name);
            }

            $namaLaporan = $laporan->getRandomName();
            $laporan->move('berkas/laporan', $namaLaporan);
        } else {
            $namaLaporan = $old_laporan_name;
        }


        $old_kegiatan_name = $abdimas->bukti_kegiatan;
        // BUKTI KEGIATAN
        $kegiatan = $this->request->getFile('bukti_kegiatan');
        if ($kegiatan && $kegiatan->isValid() && !$kegiatan->hasMoved()) {
            // Validate size (max 10MB)
            if ($kegiatan->getSize() > 10 * 1024 * 1024) {
                return redirect()->back()->withInput()->with('error', 'Ukuran file bukti kegiatan maksimal 10MB.');
            }
            // Validate extension and MIME type
            if (strtolower($kegiatan->getExtension()) !== 'pdf' || $kegiatan->getMimeType() !== 'application/pdf') {
                return redirect()->back()->withInput()->with('error', 'File bukti kegiatan harus berformat PDF.');
            }

            if (!empty($old_kegiatan_name) && file_exists('berkas/kegiatan/' . $old_kegiatan_name)) {
                unlink('berkas/kegiatan/' . $old_kegiatan_name);
            }

            $namaKegiatan = $kegiatan->getRandomName();
            $kegiatan->move('berkas/kegiatan', $namaKegiatan);
        } else {
            $namaKegiatan = $old_kegiatan_name;
        }


        $data = [
            'laporan'           => $namaLaporan,
            'bukti_kegiatan'    => $namaKegiatan,
            'tanggal_kegiatan'  => $abdimas->tanggal_kegiatan, // Retain existing value
            'judul_kegiatan'    => $abdimas->judul_kegiatan, // Retain existing value
            'link_luaran'       => $this->request->getVar('link_luaran'),
        ];

        $this->abdimas->update($id, $data);

        return redirect()->to(site_url('pelaporan'))->with('success', 'Data anda berhasil diupdate.');
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null) {}

    private function translateText(string $text, string $source, string $target): string
    {
        $key = $_ENV['apiKeyGoogleTranslateApi'] ?? null;
        if (!$key || trim($text) === '') return $text;

        // Google Translate v2 client :contentReference[oaicite:3]{index=3}
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

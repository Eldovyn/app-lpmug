<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

use App\Models\RekapanModel;
use App\Models\TagsModel;
use App\Models\TagluaranModel;
use App\Models\DosenModel;
use App\Models\MitraModel;
use App\Models\SubprogramModel;
use App\Models\LuaranModel;
use App\Models\PeriodeModel;
use App\Models\MahasiswaModel;
use App\Models\PesanModel;
use App\Models\BidangIlmuModel;
use Google\Cloud\Translate\V2\TranslateClient;

class Rekapan extends ResourceController
{
    function __construct()
    {
        $this->rekapan      = new RekapanModel();
        $this->tags         = new TagsModel();
        $this->tagluaran    = new TagluaranModel();
        $this->dosen        = new DosenModel();
        $this->mitra        = new MitraModel();
        $this->subprogram   = new SubprogramModel();
        $this->luaran       = new LuaranModel();
        $this->periode      = new PeriodeModel();
        $this->pesan        = new PesanModel();
        $this->mahasiswa    = new MahasiswaModel();
        $this->bidangIlmu = new BidangIlmuModel();
        
    }
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        helper('auth');
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2 && userLogin()->role_id != 3 && userLogin()->role_id != 6) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $lang = resolveLanguage($this->request);
        $keyword = $this->request->getGet('keyword');
        $data = $this->_prepareCommonData($keyword, $lang, null);

        return view('rekapan/index', $data);
    }

    public function proses()
    {
        helper('auth');
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2 && userLogin()->role_id != 3 && userLogin()->role_id != 6) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $lang = resolveLanguage($this->request);
        $keyword = $this->request->getGet('keyword');
        $data = $this->_prepareCommonData($keyword, $lang, 0);

        return view('rekapan/index_proses', $data);
    }

    public function revisi()
    {
        helper('auth');
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2 && userLogin()->role_id != 3 && userLogin()->role_id != 6) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $lang = resolveLanguage($this->request);
        $keyword = $this->request->getGet('keyword');
        $data = $this->_prepareCommonData($keyword, $lang, 2);

        return view('rekapan/index_revisi', $data);
    }

    public function setuju()
    {
        helper('auth');
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2 && userLogin()->role_id != 3 && userLogin()->role_id != 6) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $lang = resolveLanguage($this->request);
        $keyword = $this->request->getGet('keyword');
        $data = $this->_prepareCommonData($keyword, $lang, 1);

        return view('rekapan/index_setuju', $data);
    }

    public function download()
    {
        helper('auth');
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2 && userLogin()->role_id != 3 && userLogin()->role_id != 6) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $lang = resolveLanguage($this->request);
        $keyword = $this->request->getGet('keyword');
        $data = $this->_prepareCommonData($keyword, $lang, null);

        return view('rekapan/index_download', $data);
    }

    private function _prepareCommonData($keyword, $lang, $status = null)
    {
        $limit = (strpos($this->request->getUri()->getPath(), 'rekapan/download') !== false) ? 100000 : 100000;
        $data = $this->rekapan->getPaginated($limit, $keyword, $status);

        $baseTitle = 'Rekapan Abdimas';
        $title = $lang === 'en' ? service('translation')->translateCached($baseTitle, 'id', 'en') : $baseTitle;

        $data['title'] = $title;
        $data['title_tab'] = $title . ' &mdash; LPM UG';
        $data['keyword'] = $keyword;
        $data['lang'] = $lang;

        $targetKey = isset($data['abdimas']) ? 'abdimas' : (isset($data['rekapan']) ? 'rekapan' : null);
        if ($targetKey && isset($data['rekapan'])) {
            $data['abdimas'] = &$data['rekapan'];
        }

        if ($lang === 'en' && $targetKey && !empty($data[$targetKey])) {
            foreach ($data[$targetKey] as &$item) {
                if (is_object($item)) {
                    if (isset($item->judul_kegiatan) && trim($item->judul_kegiatan) !== '') {
                        $item->judul_kegiatan = service('translation')->translateCached($item->judul_kegiatan, 'id', 'en');
                    }
                } elseif (is_array($item)) {
                    if (isset($item['judul_kegiatan']) && trim($item['judul_kegiatan']) !== '') {
                        $item['judul_kegiatan'] = service('translation')->translateCached($item['judul_kegiatan'], 'id', 'en');
                    }
                }
            }
            unset($item);
        }

        $data['tags'] = $this->tags->getAnggota();
        $data['dosen'] = $this->dosen->GetDosen();
        $data['pesan'] = $this->pesan->getPesan();
        $data['mitra'] = $this->rekapan->getMitra();
        $data['periode'] = $this->periode->findAll();
        $data['anggota'] = $this->rekapan->getAnggota();

        return $data;
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
        //
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        //
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        // 1. Cek Auth
        helper('auth');
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2 && userLogin()->role_id != 3 && userLogin()->role_id != 4) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        // 2. Setup Bahasa
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

        // 3. Setup Title
        $baseTitle = 'Verifikasi Laporan';
        $title = $baseTitle;

        if ($lang === 'en') {
            $title = service('translation')->translateCached($baseTitle, 'id', 'en');
        }
        $data['title'] = $title;
        $data['title_tab'] = $title . ' &mdash; LPM UG';
        $data['pesan'] = $this->pesan->getPesan();

        // 4. Ambil Data Utama - FIX: Join bidang_ilmu seperti Abdimas
        $rekapan = $this->rekapan
            ->select('tbl_laporan.*, tbl_bidang_ilmu.nama as bidang_ilmu')
            ->join('tbl_bidang_ilmu', 'tbl_bidang_ilmu.id = tbl_laporan.bidang_ilmu_id', 'left')
            ->find($id);

        if (is_object($rekapan)) {

            // ==========================================================
            // TRANSLATE DATA JIKA BAHASA INGGRIS (EN)
            // ==========================================================
            if ($lang === 'en') {

                // A. Translate Data Utama (Rekapan/Abdimas)
                $fieldsToTranslate = [
                    'judul_kegiatan',
                    'masalah_mitra',
                    'solusi_mitra',
                    'tipe_kegiatan',
                    'revisi'
                ];
                foreach ($fieldsToTranslate as $field) {
                    if (isset($rekapan->$field) && !empty($rekapan->$field)) {
                        $rekapan->$field = service('translation')->translateCached($rekapan->$field, 'id', 'en');
                    }
                }

                // B. Translate Data Tag Luaran (Output)
                $tagluaran = $this->tagluaran->getLuaran($id);
                if (!empty($tagluaran)) {
                    foreach ($tagluaran as &$tl) {
                        if (isset($tl->luaran_name)) {
                            $tl->luaran_name = service('translation')->translateCached($tl->luaran_name, 'id', 'en');
                        }
                    }
                    unset($tl);
                }
                $data['tagluaran'] = $tagluaran;

                // C. Translate Data Subprogram (Topik, Program, Subprogram)
                $subprogram = $this->subprogram->getAll();
                if (!empty($subprogram)) {
                    foreach ($subprogram as &$sp) {
                        if (isset($sp->topik_name)) $sp->topik_name = service('translation')->translateCached($sp->topik_name, 'id', 'en');
                        if (isset($sp->program_name)) $sp->program_name = service('translation')->translateCached($sp->program_name, 'id', 'en');
                        if (isset($sp->subprogram_name)) $sp->subprogram_name = service('translation')->translateCached($sp->subprogram_name, 'id', 'en');
                    }
                    unset($sp);
                }
                $data['subprogram'] = $subprogram;

                // D. Translate Data Periode (Semester Ganjil/Genap)
                $periode = $this->periode->findAll();
                if (!empty($periode)) {
                    foreach ($periode as &$p) {
                        if (isset($p->periode_name)) {
                            // Contoh: "Semester Ganjil" -> "Odd Semester"
                            $p->periode_name = service('translation')->translateCached($p->periode_name, 'id', 'en');
                        }
                    }
                    unset($p);
                }
                $data['periode'] = $periode;
            } else {
                // Jika ID, ambil data mentah tanpa translate
                $data['tagluaran']  = $this->tagluaran->getLuaran($id);
                $data['subprogram'] = $this->subprogram->getAll();
                $data['periode']    = $this->periode->findAll();
            }

            // ==========================================================

            $data['rekapan']    = $rekapan;
            $data['abdimas']    = $rekapan; // Alias untuk view

            // Data yang biasanya tidak perlu diterjemahkan (Nama Orang, Alamat, dll)
            $data['dosen']      = $this->dosen->findAll();
            $data['anggota']    = $this->rekapan->getAnggota();
            $data['mahasiswa']  = $this->mahasiswa->getByLaporan($id);
            $data['tags']       = $this->tags->getAnggota($id);
            $data['mitra']      = $this->mitra->getAll();
            $data['luaran']     = $this->luaran->findAll();

            return view('rekapan/edit', $data);
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
        $data = [
            'verifikasi'     => $this->request->getVar('verifikasi'),
            'revisi'         => $this->request->getVar('revisi'),
        ];

        $this->rekapan->update($id, $data);
        return redirect()->to(site_url('rekapan'))->with('success', 'Data anda berhasil diupdate.');
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        helper('auth');
        // hanya role 1,2,3 yang bisa hapus
        if (!in_array(userLogin()->role_id, [1, 2, 3])) {
            return redirect()->to(site_url('dashboard'));
        }

        if (empty(userLogin()->role_id)) {
            return redirect()->to(site_url('login'));
        }

        // hapus data
        $this->rekapan->delete($id);

        // redirect balik ke proses (lebih masuk akal kalau hapus dari situ)
        return redirect()->to(site_url('rekapan/proses'))
            ->with('success', 'Data berhasil dihapus.');
    }
}

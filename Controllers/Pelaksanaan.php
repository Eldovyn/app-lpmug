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
use App\Models\MahasiswaModel;
use App\Models\JurusanModel;
use App\Models\DokumenMitraModel;
use App\Models\BidangIlmuModel;
use TCPDF;
use TCPDF2DBarcode;
use Google\Cloud\Translate\V2\TranslateClient;

class Abdimas extends ResourceController
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
        $this->mahasiswa    = new MahasiswaModel();
        $this->jurusan      = new JurusanModel();
        $this->dokumenMitra = new DokumenMitraModel();
        $this->bidangIlmu = new BidangIlmuModel();
        helper(['auth', 'url', 'form']);
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

        $baseTitle = 'Abdimas';
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
            $title = service('translation')->translateCached($baseTitle, 'id', 'en');
        }
        $data['title'] = $title;
        $data['title_tab'] = $title . ' — LPM UG';

        $data['keyword'] = $keyword;
        $anggota = $this->tags->getAnggota();
        $data['tags'] = $anggota;
        $data['pesan'] = $this->pesan->getPesan();
        $data['mitra'] = $this->abdimas->getMitra();
        $data['anggota'] = $anggota;
        $data['laporan'] = $this->abdimas->getAll();
        $data['mahasiswa'] = $this->mahasiswa->findAll();

        return view('abdimas/index', $data);
    }

    public function pelaporan()
    {
        helper('auth');
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2 && userLogin()->role_id != 3 && userLogin()->role_id != 4) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $keyword = $this->request->getGet('keyword');
        $data = $this->abdimas->getPaginated(10, $keyword);
        $data['keyword'] = $keyword;
        $anggota = $this->tags->getAnggota();
        $data['tags'] = $anggota;
        $data['pesan'] = $this->pesan->getPesan();
        $data['mitra'] = $this->abdimas->getMitra();
        $data['anggota'] = $anggota;
        $data['laporan'] = $this->abdimas->getAll();

        return view('abdimas/index_pelaporan', $data);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        helper('auth');
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2 && userLogin()->role_id != 3 && userLogin()->role_id != 4) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $baseTitle = 'Detail Laporan';
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
            $title = service('translation')->translateCached($baseTitle, 'id', 'en');
        }
        $data['title'] = $title;
        $data['title_tab'] = $title . ' — LPM UG';
        $data['pesan']     = $this->pesan->getPesan();

        $abdimas = $this->abdimas->select('tbl_laporan.*, tbl_bidang_ilmu.nama as bidang_ilmu')
                ->join('tbl_bidang_ilmu', 'tbl_bidang_ilmu.id = tbl_laporan.bidang_ilmu_id', 'left')
                ->find($id);

        if (is_object($abdimas)) {
            // Format tanggal sebelum dikirim ke view
            if (!empty($abdimas->tanggal_kegiatan)) {
                $tanggal = explode(' - ', $abdimas->tanggal_kegiatan);
                $tanggalMulai = $tanggal[0] ?? null;
                $tanggalSelesai = $tanggal[1] ?? null;

                try {
                    $formatter = new \IntlDateFormatter(
                        'id_ID',
                        \IntlDateFormatter::FULL,
                        \IntlDateFormatter::NONE,
                        'Asia/Jakarta',
                        \IntlDateFormatter::GREGORIAN
                    );
                    $formatter->setPattern('EEEE, dd MMMM yyyy');

                    $abdimas->formatted_tanggal_mulai = $tanggalMulai ? $formatter->format(date_create($tanggalMulai)) : '-';
                    $abdimas->formatted_tanggal_selesai = $tanggalSelesai ? $formatter->format(date_create($tanggalSelesai)) : '-';
                } catch (\Exception $e) {
                    // fallback kalau locale gak support
                    $abdimas->formatted_tanggal_mulai = $tanggalMulai;
                    $abdimas->formatted_tanggal_selesai = $tanggalSelesai;
                }
            } else {
                $abdimas->formatted_tanggal_mulai = null;
                $abdimas->formatted_tanggal_selesai = null;
            }

            // Data lainnya
            $data['abdimas']    = $abdimas;
            $data['dosen']      = $this->dosen->findAll();
            $data['tags']       = $this->tags->getAnggota($id);
            $data['tagluaran']  = $this->tagluaran->getLuaran($id);
            $data['mahasiswa']  = $this->mahasiswa->getByLaporan($id);
            $data['anggota']    = array_merge($data['tags'], $data['mahasiswa']);
            $data['mitra']      = $this->mitra->getAll();
            $data['subprogram'] = $this->subprogram->getAll();
            $data['luaran']     = $this->luaran->findAll();
            $data['periode']    = $this->periode->findAll();
            $data['laporan']    = $this->abdimas->getAll();
            $data['jurusan']    = $this->jurusan->getAll();

            return view('abdimas/show', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        helper('auth');
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2 && userLogin()->role_id != 3 && userLogin()->role_id != 4) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $baseTitle = 'Registrasi Abdimas';
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
        $data['title_tab'] = $title . ' — LPM UG';
        $data['pesan'] = $this->pesan->getPesan();
        $data['validation'] = \Config\Services::validation();

        // $data['dosen']      = $this->dosen->findAll();
        $data['dosen']      = $this->dosen->getDosen();
        $data['mitra']      = $this->mitra->getAll();
        $data['subprogram'] = $this->subprogram->getAll();
        $data['luaran']     = $this->luaran->findAll();
        $data['periode']    = $this->periode->findAll();
        $data['jurusan']    = $this->jurusan->getAll();
        $data['bidangIlmus'] = $this->bidangIlmu->getAllActive();

        return view('abdimas/new', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('tbl_laporan');

        // Get form data
        $mitraIds       = $this->request->getVar('mitra_id');
        $subprogramIds  = $this->request->getVar('subprogram_id');
        $tipe_kegiatan   = $this->request->getVar('tipe_kegiatan');
        $periodeId      = $this->request->getVar('periode_id');

        // Convert arrays to single values
        $mitraId        = is_array($mitraIds) ? $mitraIds[0] : $mitraIds;
        $subprogramId   = is_array($subprogramIds) ? $subprogramIds[0] : $subprogramIds;
        $tipe_kegiatanValue = is_array($tipe_kegiatan) ? $tipe_kegiatan[0] : $tipe_kegiatan;

        // ====== CEK SPM TERLEBIH DAHULU ======
        if (!$this->checkSpmExists($mitraId, $periodeId)) {
            return redirect()->back()->withInput()->with(
                'error',
                "<strong>SPM Belum Diupload!</strong><br><br>" .
                    "Mitra belum mengupload file SPM untuk periode yang dipilih.<br><br>" .
                    "<strong>Solusi:</strong><br>" .
                    "1. Silakan hubungi mitra untuk mengupload file SPM terlebih dahulu<br>" .
                    "2. Atau pilih mitra lain yang sudah mengupload SPM<br>" .
                    "3. Pastikan periode yang dipilih sesuai dengan periode upload SPM<br><br>" .
                    "<em>Pengusulan tidak dapat dilanjutkan sampai SPM tersedia.</em>"
            );
        }

        // ====== VALIDASI FORM ======
        $rules = [
            'syarat' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Silahkan menyutujui syarat dan ketentuan yang berlaku.'
                ],
            ],
            'periode_id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Periode tidak boleh kosong atau tidak ada periode pendaftaran yang sedang dibuka.'
                ],
            ],
            'range_dana' => [
                'rules' => 'required|numeric|greater_than_equal_to[2500000]',
                'errors' => [
                    'required' => 'Estimasi pendanaan wajib diisi.',
                    'numeric'  => 'Estimasi pendanaan harus berupa angka.',
                    'greater_than_equal_to' => 'Estimasi pendanaan minimal Rp 2.500.000.'
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            $validation = \Config\Services::validation();
            return redirect()->back()->withInput()->with('validation', $validation);
        }

        // ====== SIAPKAN DATA UNTUK TBL_LAPORAN ======
        $sumber_dana_array = $this->request->getVar('sumber_dana');
        $sumber_dana_str   = is_array($sumber_dana_array) ? implode(', ', $sumber_dana_array) : $sumber_dana_array;

        $data = [
            'ketua_id'      => userLogin()->user_id,
            'mitra_id'      => $mitraId,
            'subprogram_id' => $subprogramId,
            'periode_id'    => $periodeId,
            'tipe_kegiatan' => $tipe_kegiatanValue,
            'range_dana'    => $this->request->getVar('range_dana'),
            'masalah_mitra' => $this->request->getVar('masalah_mitra'),
            'solusi_mitra'  => $this->request->getVar('solusi_mitra'),
            'sumber_dana'   => $sumber_dana_str,
            'bidang_ilmu_id'  => $this->bidangIlmu->getBidangIlmuId($this->request->getVar('bidang_ilmu') ?: 'teknik-rekayasa'),  // Default fallback
            'verifikasi'    => 0,
        ];

        $this->abdimas->insert($data);
        $laporan_id = $db->insertID();

        // ======================
        // ANGGOTA (max 10)
        // ======================
        $anggota_ids = $this->request->getVar('anggota_id') ?? [];

        if (count($anggota_ids) > 10) {
            return redirect()->back()->withInput()->with('error', 'Maksimal hanya boleh 10 anggota.');
        }

        // Cek anggota tidak lebih dari 2 grup
        $periodeId = $this->request->getVar('periode_id');

        $counts = $this->tags->getCountsByAnggotaListPerPeriode($anggota_ids, $periodeId);
        foreach ($anggota_ids as $anggota_id) {
            $groupCount = $counts[$anggota_id] ?? 0;

            if ($groupCount >= 2) {
                $anggotaModel = new \App\Models\DosenModel();
                $anggota = $anggotaModel->find($anggota_id);
                $anggotaName = $anggota ? $anggota->user_name : 'Anggota';

                // Rollback / clean up the inserted abdimas record since the group limit was reached
                $this->abdimas->delete($laporan_id);

                return redirect()->back()->withInput()->with(
                    'error',
                    "<strong>Batas Grup Tercapai!</strong><br><br>" .
                        "Anggota <strong>{$anggotaName}</strong> sudah terdaftar dalam 2 kelompok abdimas untuk periode ini.<br><br>" .
                        "<strong>Solusi:</strong><br>" .
                        "1. Pilih anggota lain yang belum mencapai batas 2 kelompok di periode ini<br>" .
                        "2. Atau tunggu periode berikutnya untuk pengusulan baru<br><br>" .
                        "<em>Pengusulan tidak dapat dilanjutkan untuk periode ini.</em>"
                );
            }
        }


        // Simpan anggota ke pivot tabel
        foreach ($anggota_ids as $anggota_id) {
            $this->tags->insert([
                'laporan_id' => $laporan_id,
                'anggota_id' => $anggota_id,
            ]);
        }

        // ======================
        // LUARAN (pivot table)
        // ======================
        $luaran_ids = $this->request->getVar('luaran_id') ?? [];
        foreach ($luaran_ids as $luaran_id) {
            $this->tagluaran->insert([
                'laporan_id' => $laporan_id,
                'luaran_id'  => $luaran_id,
            ]);
        }

        // ======================
        // MAHASISWA
        // ======================
        $mahasiswa_nama        = $this->request->getVar('mahasiswa_nama');
        $mahasiswa_npm         = $this->request->getVar('mahasiswa_npm');
        $mahasiswa_jurusan_id  = $this->request->getVar('mahasiswa_jurusan_id');

        // Hitung mahasiswa yang valid (nama tidak kosong)
        $jumlahMahasiswa = 0;

        if ($mahasiswa_nama && is_array($mahasiswa_nama)) {
            foreach ($mahasiswa_nama as $nama) {
                if (!empty(trim($nama))) {
                    $jumlahMahasiswa++;
                }
            }
        }

        // Validasi minimal 2
        if ($jumlahMahasiswa < 2) {
            return redirect()->back()->withInput()->with(
                'error',
                'Minimal harus ada 2 mahasiswa dalam pengusulan.'
            );
        }

        // Insert mahasiswa (no limit)
        foreach ($mahasiswa_nama as $index => $nama) {
            if (!empty(trim($nama))) {
                $this->mahasiswa->insert([
                    'laporan_id'       => $laporan_id,
                    'mahasiswa_name'   => $nama,
                    'mahasiswa_npm'    => $mahasiswa_npm[$index] ?? '',
                    'jurusan_id'       => $mahasiswa_jurusan_id[$index] ?? '',
                ]);
            }
        }

        return redirect()->to(site_url('abdimas'))->with('success', 'Data baru anda berhasil disimpan.');
    }


    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        helper('auth');
        // === CEK ROLE DAN LOGIN ===
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2 && userLogin()->role_id != 3 && userLogin()->role_id != 4) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        helper('cookie');

        $allowed = ['id', 'en'];

        // bahasa aktif - check query param first, then cookie, default to id
        $lang = $this->request->getGet('lang');
        if (! $lang) {
            $lang = $this->request->getCookie('lang') ?? 'id';
        }

        if (! in_array($lang, $allowed, true)) {
            $lang = 'id';
        }

        // Set cookie if lang from query param
        if ($this->request->getGet('lang')) {
            set_cookie('lang', $lang, 60 * 60 * 24 * 30);
        }

        // ===== PERBAIKAN: Definisikan $baseTitle dan $title =====
        $titleDict = [
            'id' => 'Edit Proposal',
            'en' => 'Edit Proposal'
        ];
        $title = $titleDict[$lang] ?? 'Edit Proposal';

        $data['title'] = $title;
        $data['title_tab'] = $title . ' — LPM UG';

        $data['pesan']     = $this->pesan->getPesan();

        // === AMBIL DATA ABDIMAS BERDASARKAN ID ===
        $abdimas = $this->abdimas->select('tbl_laporan.*, tbl_bidang_ilmu.nama as bidang_ilmu')
                ->join('tbl_bidang_ilmu', 'tbl_bidang_ilmu.id = tbl_laporan.bidang_ilmu_id', 'left')
                ->find($id);
        if (is_object($abdimas)) {

            // Simpan data utama ke array $data
            $data['abdimas']    = $abdimas;
            $data['dosen']      = $this->dosen->findAll();
            $data['anggota']    = $this->abdimas->getAnggota($id);
            $data['tags']       = $this->tags->getAnggota($id);
            $data['tagluaran']  = $this->tagluaran->getLuaran($id);
            $data['mahasiswa']  = $this->mahasiswa->getByLaporan($id);
            $data['mitra']      = $this->mitra->getAll();
            $data['subprogram'] = $this->subprogram->getAll();
            $data['luaran']     = $this->luaran->findAll();
            $data['periode']    = $this->periode->findAll();
            $data['laporan']    = $this->abdimas->getAll();
            $data['jurusan']    = $this->jurusan->getAll();
            $data['bidangIlmus'] = $this->bidangIlmu->getAllActive();

            // === BACA DATA TANGGAL DARI DATABASE ===
            if (!empty($abdimas->tanggal_kegiatan)) {
                $tanggalParts = explode(' - ', $abdimas->tanggal_kegiatan);
                $data['tanggal_mulai'] = $tanggalParts[0] ?? '';
                $data['tanggal_selesai'] = $tanggalParts[1] ?? '';
            } else {
                $data['tanggal_mulai'] = '';
                $data['tanggal_selesai'] = '';
            }

            return view('abdimas/edit', $data);
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
        // Cek data laporan
        $abdimas = $this->abdimas->getAbdimasWithBidangIlmu($id);
        if (!$abdimas) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Ambil semua data dari form
        $mitra_id       = $this->request->getVar('mitra_id');
        $subprogram_id  = $this->request->getVar('subprogram_id');
        $periode_id     = $this->request->getVar('periode_id');
        $tipe_kegiatan  = $this->request->getVar('tipe_kegiatan');
        $range_dana     = $this->request->getVar('range_dana');
        $sumber_dana    = $this->request->getVar('sumber_dana');
        $masalah_mitra  = $this->request->getVar('masalah_mitra');
        $solusi_mitra   = $this->request->getVar('solusi_mitra');

        // Kalau sumber_dana berupa array, ubah jadi string (biar bisa disimpan di DB)
        $sumber_dana_str = is_array($sumber_dana) ? implode(', ', $sumber_dana) : $sumber_dana;

        // Update data utama di tabel abdimas
        $this->abdimas->update($id, [
            'mitra_id'       => $mitra_id,
            'subprogram_id'  => $subprogram_id,
            'periode_id'     => $periode_id,
            'tipe_kegiatan'  => $tipe_kegiatan,
            'range_dana'     => $range_dana,
            'sumber_dana'    => $sumber_dana_str,
            'bidang_ilmu_id'  => $this->bidangIlmu->getBidangIlmuId($this->request->getVar('bidang_ilmu') ?: 'teknik-rekayasa'),  // Default
            'masalah_mitra'  => $masalah_mitra,
            'solusi_mitra'   => $solusi_mitra,
        ]);

        // Ambil data anggota dan mahasiswa
        $anggota_ids = $this->request->getVar('anggota_id');
        $mahasiswa_nama = $this->request->getVar('mahasiswa_nama');
        $mahasiswa_npm = $this->request->getVar('mahasiswa_npm');
        $mahasiswa_jurusan_id = $this->request->getVar('mahasiswa_jurusan_id');

        // Update anggota (tbl_tags)
        $this->tags->where('laporan_id', $id)->delete();

        if (!empty($anggota_ids) && is_array($anggota_ids)) {
            foreach ($anggota_ids as $anggota_id) {
                if (!empty($anggota_id)) {
                    $this->tags->insert([
                        'laporan_id' => $id,
                        'anggota_id' => $anggota_id,
                    ]);
                }
            }
        }

        // Update mahasiswa (tbl_mahasiswa)
        $this->mahasiswa->where('laporan_id', $id)->delete();

        if (!empty($mahasiswa_nama) && is_array($mahasiswa_nama)) {
            foreach ($mahasiswa_nama as $index => $nama) {
                if (!empty($nama)) {
                    $this->mahasiswa->insert([
                        'laporan_id'     => $id,
                        'mahasiswa_name' => $nama,
                        'mahasiswa_npm'  => $mahasiswa_npm[$index] ?? '',
                        'jurusan_id'     => $mahasiswa_jurusan_id[$index] ?? null,
                    ]);
                }
            }
        }

        // Redirect setelah berhasil
        return redirect()->to(site_url('abdimas'))->with('success', 'Data berhasil diupdate.');
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        helper('auth');
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2 && userLogin()->role_id != 3 && userLogin()->role_id != 4) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $this->tags->where('laporan_id', $id)->delete();
        $this->tagluaran->where('laporan_id', $id)->delete();
        $this->mahasiswa->where('laporan_id', $id)->delete();

        $this->abdimas->delete($id);
        return redirect()->to(site_url('abdimas'))->with('success', 'Data anda berhasil dihapus.');
    }

    public function uploadProposal($id = null)
    {
        helper('auth');
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2 && userLogin()->role_id != 3 && userLogin()->role_id != 4) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $data['title_tab'] = 'Update Laporan — LPM UG';
        $data['title'] = 'Update Laporan';
        $data['pesan'] = $this->pesan->getPesan();

        $abdimas = $this->abdimas->getAbdimasWithBidangIlmu($id);
        if (is_object($abdimas)) {
            $data['abdimas']    = $abdimas;
            return view('abdimas/upload_proposal', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('abdimas/upload_proposal', $data);
    }

    public function updateProposal($id = null)
    {
        $abdimas = $this->abdimas->getAbdimasWithBidangIlmu($id);
        $old_pdf_name = $abdimas->proposal;

        $file = $this->request->getFile('proposal');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Validate size (max 10MB)
            if ($file->getSize() > 10 * 1024 * 1024) {
                return redirect()->back()->with('error', 'Ukuran proposal maksimal 10MB.');
            }
            // Validate extension and MIME type
            if (strtolower($file->getExtension()) !== 'pdf' || $file->getMimeType() !== 'application/pdf') {
                return redirect()->back()->with('error', 'File proposal harus berformat PDF.');
            }

            if (!empty($old_pdf_name) && file_exists('berkas/proposal/' . $old_pdf_name)) {
                unlink('berkas/proposal/' . $old_pdf_name);
            }

            $namaBerkas = $file->getRandomName();
            $file->move('berkas/proposal', $namaBerkas);
        } else {
            $namaBerkas = $old_pdf_name;
        }

        $data = [
            'proposal'    => $namaBerkas,
        ];

        $this->abdimas->update($id, $data);
        return redirect()->to(site_url('abdimas'))->with('success', 'Proposal anda berhasil diupload.');
    }

    public function arsip($id)
    {
        $abdimasModel = new \App\Models\AbdimasModel();
        $periodeModel = new \App\Models\PeriodeModel();

        $abdimas = $abdimasModel->getAbdimasById($id);
        if (!$abdimas) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Data Abdimas dengan ID $id tidak ditemukan");
        }

        $periodes = $periodeModel->orderBy('periode_id', 'DESC')->findAll();

        // Fetch SPM and SKM from tbl_dokumen_mitra
        $spm = $this->dokumenMitra->getDokumenByMitraAndType($abdimas->mitra_id, 'spm', $abdimas->periode_id);
        $skm = $this->dokumenMitra->getDokumenByMitraAndType($abdimas->mitra_id, 'skm', $abdimas->periode_id);

        $data = [
            'abdimas'  => $abdimas,
            'periodes' => $periodes,
            'title'    => 'Arsip Surat Abdimas',
            'pesan'    => $this->pesan->getPesan(),
            'spm'      => $spm,
            'skm'      => $skm,
        ];

        return view('abdimas/arsip_surat', $data);
    }


    // === Lihat file PDF (inline / preview) ===
    public function lihatDokumen($tipe, $mitraId, $periodeId)
    {
        // Cek di tbl_dokumen_mitra
        $dokumen = $this->dokumenMitra->getDokumenByMitraAndType($mitraId, $tipe, $periodeId);

        if ($dokumen && file_exists($dokumen['file_path'])) {
            $filePath = $dokumen['file_path'];
            $filename = basename($filePath);

            return $this->response
                ->setHeader('Content-Type', 'application/pdf')
                ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
                ->setBody(file_get_contents($filePath));
        }

        // Jika tidak ditemukan
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
            "File $tipe untuk periode $periodeId tidak ditemukan"
        );
    }

    // === Download file PDF ===
    public function downloadDokumen($tipe, $mitraId, $periodeId)
    {
        // Cek di tbl_dokumen_mitra
        $dokumen = $this->dokumenMitra->getDokumenByMitraAndType($mitraId, $tipe, $periodeId);

        if ($dokumen && file_exists($dokumen['file_path'])) {
            $filePath = $dokumen['file_path'];
            $filename = basename($filePath);

            return $this->response->download($filePath, null)->setFileName($filename);
        }

        // Jika tidak ditemukan
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
            "File $tipe untuk periode $periodeId tidak ditemukan"
        );
    }
        public function generatePdf($id = null)
    {
        helper('auth');
        // === CEK ROLE DAN LOGIN ===
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2 && userLogin()->role_id != 3 && userLogin()->role_id != 4 && userLogin()->role_id != 6) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        helper('cookie');
        try {
            $settings = []; // Define settings to avoid undefined variable
            require_once ROOTPATH . 'vendor/tecnickcom/tcpdf/tcpdf.php';
            require_once ROOTPATH . 'vendor/tecnickcom/tcpdf/tcpdf_barcodes_2d.php';
            $abdimas = $this->abdimas->select('tbl_laporan.*, tbl_users.user_name as ketua_nama, tbl_users.nidn as ketua_nidn, tbl_jurusan.jurusan_name, tbl_fakultas.fakultas_name')
                ->join('tbl_users', 'tbl_users.user_id = tbl_laporan.ketua_id', 'left')
                ->join('tbl_jurusan', 'tbl_jurusan.jurusan_id = tbl_users.jurusan_id', 'left')
                ->join('tbl_fakultas', 'tbl_fakultas.fakultas_id = tbl_jurusan.fakultas_id', 'left')
                ->join('tbl_tags', 'tbl_tags.laporan_id = tbl_laporan.laporan_id', 'left')
                ->asArray()
                ->find($id);
            if (!$abdimas) throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
            if (empty($abdimas['judul_kegiatan'])) throw new \Exception('Judul kegiatan tidak boleh kosong');
            $selectedMitra = (new \App\Models\MitraModel())
                ->select('tbl_users.*, tbl_kota.kota_name, tbl_provinsi.provinsi_name')
                ->join('tbl_kota', 'tbl_kota.kota_id = tbl_users.kota_id', 'left')
                ->join('tbl_provinsi', 'tbl_provinsi.provinsi_id = tbl_kota.provinsi_id', 'left')
                ->find($abdimas['mitra_id']);
            // Ambil anggota kecuali ketua
            $dosenList = $this->abdimas->getAnggotaByLaporan($id, $abdimas['ketua_id']);
            
            // Default kalau kosong
            if (empty($dosenList)) {
                $dosenList[] = [
                    'user_id' => null,
                    'user_name' => 'Tidak ada anggota',
                    'jurusan_name' => '-',
                    'nidn' => '-',
                ];
            }
            // === Format Waktu Pelaksanaan ===
$waktuPelaksanaan = '-';
$tanggal_kegiatan = $abdimas['tanggal_kegiatan'] ?? '';
if (!empty($tanggal_kegiatan)) {
    $tanggal = explode(' - ', $tanggal_kegiatan);
    $tanggalMulai = isset($tanggal[0]) ? trim($tanggal[0]) : null;
    $tanggalSelesai = isset($tanggal[1]) ? trim($tanggal[1]) : null;

    // Helper format tanggal Indonesia
    function formatTanggalIndo($tgl) {
        $bulan = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        $pecah = explode('-', $tgl);
        if (count($pecah) === 3) {
            return (int)$pecah[2] . ' ' . $bulan[(int)$pecah[1]] . ' ' . $pecah[0];
        }
        return $tgl;
    }

    if ($tanggalMulai && $tanggalSelesai) {
        // Format as range with duration
        $tglMulaiObj = new \DateTime($tanggalMulai);
        $tglSelesaiObj = new \DateTime($tanggalSelesai);
        $interval = $tglMulaiObj->diff($tglSelesaiObj);
        $jumlahBulan = max(1, $interval->m + ($interval->y * 12));

        $waktuPelaksanaan = $jumlahBulan . ' bulan';
    } else {
        // Format as single date
        $waktuPelaksanaan = formatTanggalIndo($tanggalMulai ?? $tanggal_kegiatan);
    }
}

            // Hitung jumlah anggota
            $jumlahAnggota = count($dosenList);
            
            // Bidang unik dari jurusan anggota
            $jurusanList = array_column($dosenList, 'jurusan_name');
            $jurusanList = array_filter($jurusanList, function($j){ return $j != '-' && !empty($j); });
            $bidang = !empty($jurusanList) ? implode(', ', array_unique($jurusanList)) : '-';
            
            // Cast ke object supaya gampang di view
            $anggota = array_map(function($item){ return (object)$item; }, $dosenList);


            $luaranData = $this->tagluaran
                ->select('tbl_luaran.luaran_name as luaran_name')
                ->join('tbl_luaran', 'tbl_luaran.luaran_id = tbl_tag_luaran.luaran_id')
                ->where('tbl_tag_luaran.laporan_id', $id)
                ->findAll();
            $luaran = array_map(function ($item) {
                $item = (array)$item;
                return [
                    'nama' => $item['luaran_name'] ?? 'N/A',
                    'jenis' => '-',
                    'deskripsi' => '-'
                ];
            }, $luaranData);
            
            // Ambil data periode berdasarkan ID dari abdimas
            $periodeObj = $this->periode->find($abdimas['periode_id'] ?? 0);
            
            // Default nilai
            $periode_display = '-';
            
            if (!empty($periodeObj)) {
                $periode_name = $periodeObj->periode_name ?? '-';
                $tahun_ajaran = $periodeObj->tahun_ajaran ?? '-';
            
                // Gabungkan Periode + Tahun Ajaran
                $periode_display = $periode_name . ' (' . $tahun_ajaran . ')';
            }
            
            // === QR CODE Ketua Pengusul ===
            $qr_ketua_content = ($abdimas['ketua_nama'] ?? '-') .
                "\nNIDN: " . ($abdimas['ketua_nidn'] ?? '-') .
                "\nPeriode: " . $periode_display .
                "\nAnggota:\n";
            

            
            // === QR CODE LPM ===
            $qr_lpm_content = "Dr. Aris Budi Setyawan, SE., MM., M.Si\n" .
                "NIDN: 0326057004\n" .
                "Periode: " . $periode_display;

            // Siapkan data ke view
            $data = [
                'title'             => 'Laporan Pengabdian kepada Masyarakat',
                'title_tab'         => 'Laporan Pengabdian Masyarakat',
                'periode'           => (object)($this->periode->asArray()->find($abdimas['periode_id'] ?? 0) ?? []),
                'abdimas'           => (object)$abdimas,
                'jumlah_anggota'    => $jumlahAnggota,
                'luaran'            => $luaran,
                'mitra'             => $selectedMitra ? (object)$selectedMitra : (object)[
                'user_name'         => '-', 'alamat' => '-', 'kota_name' => '-', 'provinsi_name' => '-'],
                'mitra_list'        => [],
                'subprogram'        => (object)($this->subprogram->asArray()->find($abdimas['subprogram_id'] ?? 0) ?? []),
                'perguruan_tinggi'  => $settings['institute_name'] ?? 'Universitas Gunadarma',
                'lokasi_kegiatan'   => $selectedMitra->alamat ?? '-',
                'provinsi'          => $selectedMitra->provinsi_name ?? '-',
                'jarak_kampus'      => $abdimas['jarak_kampus'] ?? '-',
                'waktu_pelaksanaan' => $waktuPelaksanaan,
                'total_pembiayaan'  => $abdimas['total_biaya'] ?? '0',
                'dana_dprm'         => $abdimas['dana_dprm'] ?? '0',
                'dana_lain'         => $abdimas['dana_lain'] ?? '0',
                'bidang'           => $bidang,
                'anggota'          => $anggota,
                'jumlah_anggota'   => $jumlahAnggota,
            ];

            $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
            $pdf->setPrintHeader(false); 
            $pdf->setPrintFooter(false); 
            $pdf->SetCreator('Sistem Pengabdian Masyarakat');
            $pdf->SetAuthor($abdimas['ketua_nama'] ?? 'Unknown');
            $pdf->SetTitle('Lembar Pengesahan - ' . $abdimas['judul_kegiatan']);
            $pdf->AddPage();
        
            // Render HTML View
            $html = view('abdimas/lembar_pengesahan', $data);
            $pdf->writeHTML($html, true, false, true, false, '');
        
            // ==== Cek Apakah Masih Cukup Ruang untuk QR + TTD ====
            $pdf->SetFont('helvetica', '', 10); // Font kecil 10pt
        
            $currentY = $pdf->GetY();
            $pageHeight = $pdf->getPageHeight();
            $bottomMargin = 15;
            $neededSpace = 65; 
        
            if ($currentY + $neededSpace > ($pageHeight - $bottomMargin)) {
                $spaceToJump = ($pageHeight - $bottomMargin) - $currentY - $neededSpace;
                if ($spaceToJump > 0) {
                    $pdf->Ln($spaceToJump);
                }
            }
        
            // ==== Cek Apakah Masih Cukup Ruang untuk QR + TTD ==== 
            $pdf->SetFont('helvetica', '', 10); // Font kecil 10pt
            
            $currentY = $pdf->GetY();
            $pageHeight = $pdf->getPageHeight();
            $bottomMargin = 15;
            $neededSpace = 70; // Estimasi tinggi konten QR + tanda tangan
            
            // Kalau gak cukup ruang, tambahkan halaman baru
            if ($currentY + $neededSpace > ($pageHeight - $bottomMargin)) {
                $pdf->AddPage(); 
                $currentY = $pdf->GetY(); // Reset Y setelah AddPage
            }
            
            $y_pos = $currentY + 10;
            $marginLeft = 20; // margin kiri tetap (biar QR rapi)
            $pageWidth = $pdf->getPageWidth();
            $contentWidth = $pageWidth - 2 * $marginLeft;
            $colWidth = $contentWidth / 2;
            $qrSize = 20; // Ukuran QR kecil
            
            // === Kolom Kiri - LPM ===
            $pdf->SetXY($marginLeft, $y_pos);
            $pdf->MultiCell($colWidth, 5, 
                "Mengetahui,\nKetua Lembaga Pengabdian kepada Masyarakat\nUniversitas Gunadarma", 
                0, 'C', false);
            
            $pdf->SetXY($marginLeft + ($colWidth - $qrSize) / 2, $y_pos + 20);
            $pdf->write2DBarcode($qr_lpm_content, 'QRCODE,L', $pdf->GetX(), $pdf->GetY(), $qrSize, $qrSize, [], 'N');
            
            $pdf->SetXY($marginLeft, $y_pos + 45);
            $pdf->MultiCell($colWidth, 5, 
                "(Dr. Aris Budi Setyawan, SE., MM., M.Si)\nNIDN/NIP: 0326057004 / 930391", 
                0, 'C', false);
            
            // === Kolom Kanan - Ketua Pengusul ===
            $pdf->SetXY($marginLeft + $colWidth, $y_pos);
            $pdf->MultiCell($colWidth, 5, 
                "Ketua Pengusul", 
                0, 'C', false);
            
            $pdf->SetXY($marginLeft + $colWidth + ($colWidth - $qrSize) / 2, $y_pos + 20);
            $pdf->write2DBarcode($qr_ketua_content, 'QRCODE,L', $pdf->GetX(), $pdf->GetY(), $qrSize, $qrSize, [], 'N');
            
            $pdf->SetXY($marginLeft + $colWidth, $y_pos + 45);
            $pdf->MultiCell($colWidth, 5, 
                "(" . ($abdimas['ketua_nama'] ?? '-') . ")\nNIDN: " . ($abdimas['ketua_nidn'] ?? '-'), 
                0, 'C', false);

        
            // ==== Output ====
            ob_clean(); // Important: clear buffer to avoid corrupt PDF
            $filename = 'Laporan_Pengabdian_' . preg_replace('/[^a-z0-9]/i', '_', $abdimas['judul_kegiatan'] ?? 'report') . '.pdf';
            $pdf->Output($filename, 'I');
            exit;
        
    } catch (\Exception $e) {
        log_message('error', 'PDF Error - ' . $e->getMessage());
        return redirect()->back()->with('error', 'Gagal generate PDF: ' . $e->getMessage());
    }
}
    public function suratBalasanPdf($id = null)
{
    helper('auth');
        // === CEK ROLE DAN LOGIN ===
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2 && userLogin()->role_id != 3 && userLogin()->role_id != 4) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        helper('cookie');

    // Validasi khusus untuk mitra (role_id = 5)
    if (userLogin()->role_id == 5) {
        $laporan = $this->abdimas->getAbdimasWithBidangIlmu($id);
        if (!$laporan || $laporan->mitra_id != userLogin()->user_id) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan atau tidak memiliki akses');
        }
    }

    try {
        require_once ROOTPATH . 'vendor/tecnickcom/tcpdf/tcpdf.php';
        require_once ROOTPATH . 'vendor/tecnickcom/tcpdf/tcpdf_barcodes_2d.php';

        // ===== Ambil Data Laporan =====
        $abdimas = $this->abdimas
            ->select('tbl_laporan.*, tbl_users.user_name as ketua_nama, tbl_users.nidn as ketua_nidn, tbl_jurusan.jurusan_name as ketua_jurusan, tbl_subprogram.subprogram_name as bidang_ilmu')
            ->join('tbl_users', 'tbl_users.user_id = tbl_laporan.ketua_id', 'left')
            ->join('tbl_jurusan', 'tbl_jurusan.jurusan_id = tbl_users.jurusan_id', 'left')
            ->join('tbl_subprogram', 'tbl_subprogram.subprogram_id = tbl_laporan.subprogram_id', 'left')
            ->asArray()
            ->find($id);

        if (!$abdimas) throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        if (empty($abdimas['judul_kegiatan'])) throw new \Exception('Judul kegiatan tidak boleh kosong');

        // Ambil mitra langsung berdasarkan mitra_id laporan
        $selectedMitra = (new \App\Models\MitraModel())->find($abdimas['mitra_id']);

        // Pastikan kalau tidak ada data, kasih default object supaya view aman
        if (!$selectedMitra) {
            $selectedMitra = (object)[
                'user_id' => 0,
                'user_name' => '-',
                'alamat' => '-',
                'kota_name' => '-',
                'provinsi_name' => '-'
            ];
        } else {
            // Kalau model return array, cast ke object
            $selectedMitra = (object)$selectedMitra;
        }

        // ===== Periode =====
        $periodeObj = $this->periode->find($abdimas['periode_id'] ?? 0);
        $periode_display = '-';
        if (!empty($periodeObj)) {
            $periode_name = $periodeObj->periode_name ?? '-';
            $tahun_ajaran = $periodeObj->tahun_ajaran ?? '-';
            $periode_display = $periode_name . ' (' . $tahun_ajaran . ')';
        }

        // ===== Nomor Surat (dari upload SPM oleh mitra) =====
        $spmRecord = (object) $this->dokumenMitra->getDokumenByMitraAndType($abdimas['mitra_id'], 'spm', $abdimas['periode_id']);
        $nomorSuratMitra = $spmRecord->nomor_surat ?? 'Nomor surat belum diupload oleh mitra';

        // ===== Tanggal Surat (dari created_at upload SPM) =====
        $tanggalSuratRaw = $spmRecord->created_at ?? null;
        if (!empty($tanggalSuratRaw)) {
            $day = date('d', strtotime($tanggalSuratRaw));
            $month = (int)date('m', strtotime($tanggalSuratRaw));
            $year = date('Y', strtotime($tanggalSuratRaw));
            $bulanIndo = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
            $tanggalSurat = $day . ' ' . $bulanIndo[$month] . ' ' . $year;
        } else {
            $tanggalSurat = '-';
        }


        // ===== Nomor Surat (Auto Generate dengan Reset per Periode) =====
        $createdAt = $abdimas['created_at'] ?? date('Y-m-d H:i:s');
        $timestamp = strtotime($createdAt);

        $tahun = date('Y', $timestamp);
        $bulan = (int)date('m', $timestamp);

        $romawi = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        $bulanRomawi = $romawi[$bulan] ?? '';

        // Hitung jumlah laporan dalam periode yang sama (reset per periode)
        $jumlahPeriode = $this->abdimas
            ->where('periode_id', $abdimas['periode_id'])
            ->countAllResults();

        // Nomor urut berdasarkan periode (reset tiap periode)
        $nomorUrut = str_pad($jumlahPeriode, 4, '0', STR_PAD_LEFT);

        // Format akhir: 0001/LPM/UG/X/2025
        $nomorSuratAuto = $nomorUrut . '/LPM/UG/' . $bulanRomawi . '/' . $tahun;

        // ===== Tanggal Display sesuai periode PTA/ATA =====
        $tanggal_kegiatan_db = $abdimas['tanggal_kegiatan'] ?? date('Y-m-d');
        $bulan  = (int)date('m', strtotime($tanggal_kegiatan_db));
        $tahun  = (int)date('Y', strtotime($tanggal_kegiatan_db));
        
        if ($bulan >= 9 && $bulan <= 12) { 
            // PTA: September - Februari
            $tanggal_mulai = "September $tahun";
            $tanggal_selesai = "Februari " . ($tahun + 1);
        } elseif ($bulan >= 3 && $bulan <= 8) { 
            // ATA: Maret - Agustus
            $tanggal_mulai = "Maret $tahun";
            $tanggal_selesai = "Agustus $tahun";
        } else {
            // Bulan Januari & Februari → bagian PTA sebelumnya
            $tanggal_mulai = "September " . ($tahun - 1);
            $tanggal_selesai = "Februari $tahun";
        }
        
        $tanggal_display = $tanggal_mulai . ' s/d ' . $tanggal_selesai;
        
        // Ambil tanggal kegiatan dari DB, fallback ke hari ini
        $tanggal_kegiatan_raw = $abdimas['tanggal_kegiatan'] ?? date('Y-m-d');

        if (empty($tanggal_kegiatan_raw)) {
            $tanggal_kegiatan_formatted = 'Belum ada tanggal kegiatan';
        } else {
            // Pisahkan tanggal mulai dan tanggal selesai
            $tanggal = explode(' - ', $tanggal_kegiatan_raw);
            $tanggalMulai = $tanggal[0] ?? null;
            $tanggalSelesai = $tanggal[1] ?? null;

            // Gunakan IntlDateFormatter untuk format tampilan Indonesia
            $formatter = new \IntlDateFormatter(
                'id_ID',
                \IntlDateFormatter::FULL,
                \IntlDateFormatter::NONE,
                'Asia/Jakarta',
                \IntlDateFormatter::GREGORIAN
            );
            $formatter->setPattern('dd MMMM yyyy');

            // Format tanggal mulai dan selesai
            $formattedMulai = $tanggalMulai ? $formatter->format(date_create($tanggalMulai)) : '-';
            $formattedSelesai = $tanggalSelesai ? $formatter->format(date_create($tanggalSelesai)) : '-';

            // Tampilkan tanggal range
            $tanggal_kegiatan_formatted = $formattedMulai . ' - ' . $formattedSelesai;
        }

        $ketua_id = $abdimas['ketua_id'] ?? 0;

        // Ambil anggota (tidak termasuk ketua)
        $dosenList = $this->abdimas->getAnggotaByLaporan($id, $ketua_id);

        // Tambahkan ketua ke dalam daftar untuk perhitungan bidang
        array_unshift($dosenList, [
            'user_id' => $ketua_id,
            'user_name' => $abdimas['ketua_nama'],
            'nidn' => $abdimas['ketua_nidn'],
            'jurusan_name' => $abdimas['ketua_jurusan']
        ]);

        // Default kalau kosong
        if (empty($dosenList)) {
            $dosenList[] = [
                'user_name'    => 'Tidak ada anggota',
                'jurusan_name' => '-'
            ];
        }

        // Ambil bidang unik dari semua anggota termasuk ketua
        $jurusanList = array_column($dosenList, 'jurusan_name');
        $jurusanList = array_filter($jurusanList, function($j) {
            return $j != '-' && !empty($j);
        });
        $jurusanList = array_unique($jurusanList);
        $bidang = !empty($jurusanList) ? implode(', ', $jurusanList) : '-';

        // ===== QR CODE Content =====
        $qr_ketua_content = ($abdimas['ketua_nama'] ?? '-') .
            "\nNIDN: " . ($abdimas['ketua_nidn'] ?? '-') .
            "\nPeriode: " . $periode_display .
            "\nAnggota:\n";

        $qr_lpm_content = "Dr. Aris Budi Setyawan, SE., MM., M.Si\n" .
            "NIDN: 0326057004\n" .
            "Periode: " . $periode_display;

        // ===== Data View =====
        $mitra = $this->mitra->find($abdimas['mitra_id']);
        $lokasi_mitra = $mitra ? $mitra->alamat : '-';

        $data = [
            'judul_kegiatan'    => $abdimas['judul_kegiatan'] ?? '-',
            'ketua'             => $abdimas['ketua_nama'] ?? '-',
            'ketua_nidn'        => $abdimas['ketua_nidn'] ?? '-',
            'lokasi_mitra'      => $lokasi_mitra,
            'tanggal_kegiatan'  => $tanggal_kegiatan_formatted,
            'tanggal_display'   => $tanggal_display,
            'mitra'             => $selectedMitra,
            'periode_display'   => $periode_display,
            'bidang'            => $bidang,
            'dosenList'         => $dosenList,
            'nomor_surat_auto'  => $nomorSuratAuto,
            'nomor_surat_mitra' => $nomorSuratMitra,
            'tanggal_surat'     => $tanggalSurat,
            'spmRecord'         => (object)$spmRecord,

        ];

        // ===== Init PDF =====
        ini_set('memory_limit', '1028M');
        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetCreator('Sistem Pengabdian Masyarakat');
        $pdf->SetAuthor($data['ketua']);
        $pdf->SetTitle('Surat Balasan - ' . $data['judul_kegiatan']);
        $pdf->AddPage();

        // ===== Render HTML Surat =====
        $html = view('abdimas/surat_balasan', $data);
        $pdf->writeHTML($html, true, false, true, false, '');

        // ===== Tanda Tangan & QR =====
        $pdf->SetFont('helvetica', '', 10);
        $currentY = $pdf->GetY();
        $pageHeight = $pdf->getPageHeight();
        $bottomMargin = 15;
        $neededSpace = 45;

        if ($currentY + $neededSpace > ($pageHeight - $bottomMargin)) {
            $pdf->AddPage();
            $currentY = $pdf->GetY();
        }

        $y_pos = $currentY + 5;
        $marginLeft = 20;
        $pageWidth = $pdf->getPageWidth();
        $contentWidth = $pageWidth - 2 * $marginLeft;
        $colWidth = $contentWidth / 2;
        $qrSize = 18;

        // LPM
        $pdf->SetXY($marginLeft, $y_pos);
        $pdf->MultiCell($colWidth, 5,
            "Mengetahui,\nKetua Lembaga Pengabdian kepada Masyarakat\nUniversitas Gunadarma",
            0, 'C', false);
        $pdf->SetXY($marginLeft + ($colWidth - $qrSize) / 2, $y_pos + 15);
        $pdf->write2DBarcode($qr_lpm_content, 'QRCODE,L', $pdf->GetX(), $pdf->GetY(), $qrSize, $qrSize, [], 'N');
        $pdf->SetXY($marginLeft, $y_pos + 35);
        $pdf->MultiCell($colWidth, 5,
            "(Dr. Aris Budi Setyawan, SE., MM., M.Si)\nNIDN/NIP: 0326057004 / 930391",
            0, 'C', false);

        // Ketua Pengusul
        $pdf->SetXY($marginLeft + $colWidth, $y_pos);
        $pdf->MultiCell($colWidth, 5, "Ketua Pengusul", 0, 'C', false);
        $pdf->SetXY($marginLeft + $colWidth + ($colWidth - $qrSize) / 2, $y_pos + 15);
        $pdf->write2DBarcode($qr_ketua_content, 'QRCODE,L', $pdf->GetX(), $pdf->GetY(), $qrSize, $qrSize, [], 'N');
        $pdf->SetXY($marginLeft + $colWidth, $y_pos + 35);
        $pdf->MultiCell($colWidth, 5,
            "(" . ($abdimas['ketua_nama'] ?? '-') . ")\nNIDN: " . ($abdimas['ketua_nidn'] ?? '-') ,
            0, 'C', false);

        // ===== Render Lampiran =====
        $lampiran = view('abdimas/lampiran', $data);
        $pdf->writeHTML($lampiran, true, false, true, false, '');

        // ===== Output PDF =====
        if (ob_get_length()) ob_end_clean();

        $filename = 'Surat_Balasan_' . preg_replace('/[^a-z0-9]/i', '_', $data['judul_kegiatan']) . '.pdf';
        $pdf->Output($filename, 'I');
        exit;

    } catch (\Exception $e) {
        log_message('error', 'PDF Error - ' . $e->getMessage());
        return redirect()->back()->with('error', 'Gagal generate PDF: ' . $e->getMessage());
    }
    }


    /**
     * Check if SPM file exists for the given mitra and period
     *
     * @param int $mitraId
     * @param int $periodeId
     * @return bool
     */
    private function checkSpmExists($mitraId, $periodeId)
    {
        if (empty($mitraId) || empty($periodeId)) {
            return false;
        }

        // Check in tbl_dokumen_mitra if SPM exists for this mitra and period
        $existingSpm = $this->dokumenMitra->getDokumenByMitraAndType($mitraId, 'spm', $periodeId);

        return !empty($existingSpm);
    }

    public function formSuratBalasan()
    {
        helper('auth');
        if (!in_array(userLogin()->role_id, [1, 2, 3, 4])) {
            return redirect()->to(site_url('dashboard'));
        }
        $data = [
            'title'     => 'Form Surat Balasan',
            'title_tab' => 'Surat Balasan'
        ];
        return view('abdimas/form_surat_balasan', $data);
    }

    public function generateSuratBalasanPdfFromForm()
    {
        helper('auth');
        if (!in_array(userLogin()->role_id, [1, 2, 3, 4])) {
            return redirect()->to(site_url('dashboard'));
        }

        $post = $this->request->getPost();

        // Validate
        $rules = [
            'judul_kegiatan'   => 'required',
            'ketua'            => 'required',
            'ketua_nidn'       => 'required',
            'lokasi_mitra'     => 'required',
            'tanggal_kegiatan' => 'required',
            'bidang_ilmu'      => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Silakan lengkapi semua field yang wajib.');
        }

        try {
            require_once ROOTPATH . 'vendor/tecnickcom/tcpdf/tcpdf.php';
            require_once ROOTPATH . 'vendor/tecnickcom/tcpdf/tcpdf_barcodes_2d.php';

            $judul_kegiatan = $post['judul_kegiatan'];
            $ketua = $post['ketua'];
            $ketua_nidn = $post['ketua_nidn'];
            $lokasi_mitra = $post['lokasi_mitra'];
            $tanggal_kegiatan_raw = $post['tanggal_kegiatan'];
            $bidang_ilmu = $post['bidang_ilmu'];
            $anggota = $post['anggota'] ?? [];
            $bidang_ilmu_anggota = $post['bidang_ilmu_anggota'] ?? [];

            // Format tanggal
            $formatter = new \IntlDateFormatter('id_ID', \IntlDateFormatter::FULL, \IntlDateFormatter::NONE, 'Asia/Jakarta', \IntlDateFormatter::GREGORIAN);
            $formatter->setPattern('dd MMMM yyyy');
            $tanggal_kegiatan_formatted = $formatter->format(date_create($tanggal_kegiatan_raw));

            // Format display PTA/ATA based on the date
            $bulan = (int)date('m', strtotime($tanggal_kegiatan_raw));
            $tahun = (int)date('Y', strtotime($tanggal_kegiatan_raw));
            if ($bulan >= 9 && $bulan <= 12) { 
                $tanggal_mulai = "September $tahun";
                $tanggal_selesai = "Februari " . ($tahun + 1);
                $periode_display = "PTA $tahun/".($tahun+1);
            } elseif ($bulan >= 3 && $bulan <= 8) { 
                $tanggal_mulai = "Maret $tahun";
                $tanggal_selesai = "Agustus $tahun";
                $periode_display = "ATA ".($tahun-1)."/$tahun";
            } else {
                $tanggal_mulai = "September " . ($tahun - 1);
                $tanggal_selesai = "Februari $tahun";
                $periode_display = "PTA ".($tahun-1)."/$tahun";
            }
            $tanggal_display = $tanggal_mulai . ' s/d ' . $tanggal_selesai;

            // Dosen List
            $dosenList = [];
            
            // Map bidang_ilmu code to actual text
            $bidangMap = [
                'ipa-matematika' => 'Ilmu Pengetahuan Alam (IPA) & Matematika',
                'teknik-rekayasa' => 'Ilmu Teknik & Rekayasa',
                'kesehatan-kedokteran' => 'Ilmu Kesehatan & Kedokteran',
                'sosial-humaniora-seni' => 'Ilmu Sosial, Humaniora, & Seni',
                'pertanian-tanaman' => 'Ilmu Pertanian & Tanaman'
            ];
            
            $dosenList[] = [
                'user_name' => $ketua,
                'jurusan_name' => $bidangMap[$bidang_ilmu] ?? $bidang_ilmu
            ];

            foreach ($anggota as $index => $nama_anggota) {
                if (!empty($nama_anggota)) {
                    $bidang = $bidang_ilmu_anggota[$index] ?? '';
                    $dosenList[] = [
                        'user_name' => $nama_anggota,
                        'jurusan_name' => $bidangMap[$bidang] ?? $bidang
                    ];
                }
            }

            // Bidang unik
            $jurusanList = array_column($dosenList, 'jurusan_name');
            $jurusanList = array_unique(array_filter($jurusanList));
            $bidang = !empty($jurusanList) ? implode(', ', $jurusanList) : '-';

            // Data untuk View
            $mitraObj = (object)['user_name' => 'Pimpinan/Perwakilan Mitra', 'alamat' => $lokasi_mitra];
            
            $data = [
                'judul_kegiatan'    => $judul_kegiatan,
                'ketua'             => $ketua,
                'ketua_nidn'        => $ketua_nidn,
                'lokasi_mitra'      => $lokasi_mitra,
                'tanggal_kegiatan'  => $tanggal_kegiatan_formatted,
                'tanggal_display'   => $tanggal_display,
                'mitra'             => $mitraObj,
                'periode_display'   => $periode_display,
                'bidang'            => $bidang,
                'dosenList'         => $dosenList,
                'nomor_surat_auto'  => '..../LPM/UG/.../'.date('Y'),
                'nomor_surat_mitra' => '.........................',
                'tanggal_surat'     => '.........................',
                'spmRecord'         => (object)['created_at' => date('Y-m-d H:i:s')],
            ];

            // QR Content
            $qr_ketua_content = $ketua . "\nNIDN: " . $ketua_nidn . "\nPeriode: " . $periode_display . "\nAnggota:\n";
            $qr_lpm_content = "Dr. Aris Budi Setyawan, SE., MM., M.Si\nNIDN: 0326057004\nPeriode: " . $periode_display;

            // Init PDF
            ini_set('memory_limit', '1028M');
            $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->SetCreator('Sistem Pengabdian Masyarakat');
            $pdf->SetAuthor($ketua);
            $pdf->SetTitle('Surat Balasan - ' . $judul_kegiatan);
            $pdf->AddPage();

            // Render Surat
            $html = view('abdimas/surat_balasan', $data);
            $pdf->writeHTML($html, true, false, true, false, '');

            // Tanda Tangan & QR
            $pdf->SetFont('helvetica', '', 10);
            $currentY = $pdf->GetY();
            $pageHeight = $pdf->getPageHeight();
            $bottomMargin = 15;
            $neededSpace = 45;

            if ($currentY + $neededSpace > ($pageHeight - $bottomMargin)) {
                $pdf->AddPage();
                $currentY = $pdf->GetY();
            }

            $y_pos = $currentY + 5;
            $marginLeft = 20;
            $pageWidth = $pdf->getPageWidth();
            $contentWidth = $pageWidth - 2 * $marginLeft;
            $colWidth = $contentWidth / 2;
            $qrSize = 18;

            // LPM
            $pdf->SetXY($marginLeft, $y_pos);
            $pdf->MultiCell($colWidth, 5, "Mengetahui,\nKetua Lembaga Pengabdian kepada Masyarakat\nUniversitas Gunadarma", 0, 'C', false);
            $pdf->SetXY($marginLeft + ($colWidth - $qrSize) / 2, $y_pos + 15);
            $pdf->write2DBarcode($qr_lpm_content, 'QRCODE,L', $pdf->GetX(), $pdf->GetY(), $qrSize, $qrSize, [], 'N');
            $pdf->SetXY($marginLeft, $y_pos + 35);
            $pdf->MultiCell($colWidth, 5, "(Dr. Aris Budi Setyawan, SE., MM., M.Si)\nNIDN/NIP: 0326057004 / 930391", 0, 'C', false);

            // Ketua Pengusul
            $pdf->SetXY($marginLeft + $colWidth, $y_pos);
            $pdf->MultiCell($colWidth, 5, "Ketua Pengusul", 0, 'C', false);
            $pdf->SetXY($marginLeft + $colWidth + ($colWidth - $qrSize) / 2, $y_pos + 15);
            $pdf->write2DBarcode($qr_ketua_content, 'QRCODE,L', $pdf->GetX(), $pdf->GetY(), $qrSize, $qrSize, [], 'N');
            $pdf->SetXY($marginLeft + $colWidth, $y_pos + 35);
            $pdf->MultiCell($colWidth, 5, "(" . $ketua . ")\nNIDN: " . $ketua_nidn, 0, 'C', false);

            // Lampiran
            $lampiran = view('abdimas/lampiran', $data);
            $pdf->writeHTML($lampiran, true, false, true, false, '');

            // Output
            if (ob_get_length()) ob_end_clean();
            $filename = 'Surat_Balasan_' . preg_replace('/[^a-z0-9]/i', '_', $judul_kegiatan) . '.pdf';
            $pdf->Output($filename, 'I');
            exit;

        } catch (\Exception $e) {
            log_message('error', 'PDF Error - ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal generate PDF: ' . $e->getMessage());
        }
    }

}

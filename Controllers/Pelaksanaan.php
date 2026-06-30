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
use App\Models\DokumenMitraModel;
use Google\Cloud\Translate\V2\TranslateClient;


class Pelaksanaan extends ResourceController
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
        $this->dokumenMitra = new DokumenMitraModel();
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

        $baseTitle = 'Pelaksanaan'; // kalau mau ID lebih "indo": 'Profil Staff'
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

        return view('abdimas/index_pelaksanaan', $data);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2 && userLogin()->role_id != 3 && userLogin()->role_id != 4) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $data['title_tab'] = 'Detail Laporan &mdash; LPM UG';
        $data['title'] = 'Detail Laporan';
        $data['pesan'] = $this->pesan->getPesan();

        $abdimas = $this->abdimas->find($id);

        if (is_object($abdimas)) {
            $data['abdimas']    = $abdimas;
            $data['tags']       = $this->tags->getAnggota($id);
            $data['tagluaran']  = $this->tagluaran->getLuaran($id);
            $data['mahasiswa']  = $this->mahasiswa->getByLaporan($id);
            $data['anggota']    = array_merge($data['tags'], $data['mahasiswa']);
            $data['mitra']      = $this->mitra->getAll();
            $data['subprogram'] = $this->subprogram->getAll();
            $data['periode']    = $this->periode->findAll();
            $data['laporan']    = $this->abdimas->getAll();

            return view('abdimas/show_pelaksanaan', $data);
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
        // Implementasi jika diperlukan
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        // Implementasi jika diperlukan
    }

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
        $title = $titleDict[$lang] ?? 'Update Laporan';

        $data['title_tab'] = $title . ' — LPM UG';
        $data['title'] = $title;
        $data['pesan'] = $this->pesan->getPesan();

$abdimas = $this->abdimas->select('tbl_laporan.*, tbl_bidang_ilmu.nama as bidang_ilmu, tbl_laporan.tipe_kegiatan')
                ->join('tbl_bidang_ilmu', 'tbl_bidang_ilmu.id = tbl_laporan.bidang_ilmu_id', 'left')
                ->find($id);

            if (is_object($abdimas)) {
                // Ambil mitra_id & periode_id dari data abdimas
                $mitraId   = $abdimas->mitra_id;
                $periodeId = $abdimas->periode_id;

            if (!$this->checkSpmExists($mitraId, $periodeId)) {
                $mitraModel = new \App\Models\MitraModel();
                $mitra = $mitraModel->find($mitraId);
                $mitraName = $mitra ? $mitra->user_name : 'Mitra';

                return redirect()->back()->withInput()->with(
                    'error',
                    "<strong>SPM Belum Diupload!</strong><br><br>" .
                        "Mitra <strong>{$mitraName}</strong> belum mengupload file SPM untuk periode yang dipilih.<br><br>" .
                        "<strong>Solusi:</strong><br>" .
                        "1. Silakan hubungi mitra untuk mengupload file SPM terlebih dahulu<br>" .
                        "2. Atau pilih mitra lain yang sudah mengupload SPM<br>" .
                        "3. Pastikan periode yang dipilih sesuai dengan periode upload SPM<br><br>" .
                        "<em>Pengusulan tidak dapat dilanjutkan sampai SPM tersedia.</em>"
                );
            }

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
            $data['pesan']      = $this->pesan->getPesan();
            $data['laporan']    = $this->abdimas->getAll();

            // Parse tanggal untuk populate form saat edit
            if (!empty($abdimas->tanggal_kegiatan)) {
                $tanggalArray = explode(' - ', $abdimas->tanggal_kegiatan);
                $data['tanggal_mulai'] = isset($tanggalArray[0]) ? trim($tanggalArray[0]) : '';
                $data['tanggal_selesai'] = isset($tanggalArray[1]) ? trim($tanggalArray[1]) : '';
            }

            return view('abdimas/edit_pelaksanaan', $data);
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
        $abdimas = $this->abdimas->find($id);

        if (!$abdimas) {
            return redirect()->to(site_url('pelaksanaan'))->with('error', 'Data tidak ditemukan.');
        }

        // ===== VALIDASI TANGGAL =====
        $tanggalMulai = $this->request->getPost('tanggal_mulai');
        $tanggalSelesai = $this->request->getPost('tanggal_selesai');

        // Cek apakah tanggal diisi
        if (empty($tanggalMulai) || empty($tanggalSelesai)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Tanggal mulai dan tanggal selesai harus diisi!');
        }

        // Validasi tanggal selesai tidak boleh lebih awal dari tanggal mulai
        if (strtotime($tanggalSelesai) < strtotime($tanggalMulai)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai!');
        }

        // Gabungkan tanggal untuk disimpan ke database (format VARCHAR)
        $tanggalKegiatan = $tanggalMulai . ' - ' . $tanggalSelesai;

        // ===== HANDLE UPLOAD LAPORAN =====
        $old_laporan_name = $abdimas->laporan;
        $namaLaporan = $old_laporan_name;

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

            // Hapus file lama jika ada
            if (!empty($old_laporan_name) && file_exists('berkas/laporan/' . $old_laporan_name)) {
                unlink('berkas/laporan/' . $old_laporan_name);
            }

            $namaLaporan = $laporan->getRandomName();
            $laporan->move('berkas/laporan', $namaLaporan);
        }

        // ===== HANDLE UPLOAD BUKTI KEGIATAN =====
        $old_kegiatan_name = $abdimas->bukti_kegiatan;
        $namaKegiatan = $old_kegiatan_name;

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

            // Hapus file lama jika ada
            if (!empty($old_kegiatan_name) && file_exists('berkas/kegiatan/' . $old_kegiatan_name)) {
                unlink('berkas/kegiatan/' . $old_kegiatan_name);
            }

            $namaKegiatan = $kegiatan->getRandomName();
            $kegiatan->move('berkas/kegiatan', $namaKegiatan);
        }

        // ===== HANDLE UPLOAD SURAT UNDANGAN =====
        $old_undangan_name = $abdimas->surat_undangan;
        $namaUndangan = $old_undangan_name;

        $undangan = $this->request->getFile('surat_undangan');
        if ($undangan && $undangan->isValid() && !$undangan->hasMoved()) {
            // Validate size (max 10MB)
            if ($undangan->getSize() > 10 * 1024 * 1024) {
                return redirect()->back()->withInput()->with('error', 'Ukuran file surat undangan maksimal 10MB.');
            }
            // Validate extension and MIME type
            if (strtolower($undangan->getExtension()) !== 'pdf' || $undangan->getMimeType() !== 'application/pdf') {
                return redirect()->back()->withInput()->with('error', 'File surat undangan harus berformat PDF.');
            }

            // Hapus file lama jika ada
            if (!empty($old_undangan_name) && file_exists('berkas/undangan/' . $old_undangan_name)) {
                unlink('berkas/undangan/' . $old_undangan_name);
            }

            $namaUndangan = $undangan->getRandomName();
            $undangan->move('berkas/undangan', $namaUndangan);
        }

        // ===== PREPARE DATA =====
        $data = [
            'laporan'           => $namaLaporan,
            'bukti_kegiatan'    => $namaKegiatan,
            'surat_undangan'    => $namaUndangan,
            'tanggal_kegiatan'  => $tanggalKegiatan,  // Simpan gabungan tanggal
            'judul_kegiatan'    => $this->request->getPost('judul_kegiatan'),
            'link_luaran'       => $this->request->getPost('link_luaran'),
        ];

        // ===== UPDATE DATABASE =====
        try {
            $this->abdimas->update($id, $data);
            return redirect()->to(site_url('pelaksanaan'))->with('success', 'Data laporan berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate data: ' . $e->getMessage());
        }
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $abdimas = $this->abdimas->find($id);

        if ($abdimas) {
            // Hapus file laporan jika ada
            if (!empty($abdimas->laporan) && file_exists('berkas/laporan/' . $abdimas->laporan)) {
                unlink('berkas/laporan/' . $abdimas->laporan);
            }

            // Hapus file bukti kegiatan jika ada
            if (!empty($abdimas->bukti_kegiatan) && file_exists('berkas/kegiatan/' . $abdimas->bukti_kegiatan)) {
                unlink('berkas/kegiatan/' . $abdimas->bukti_kegiatan);
            }

            // Hapus file surat undangan jika ada
            if (!empty($abdimas->surat_undangan) && file_exists('berkas/undangan/' . $abdimas->surat_undangan)) {
                unlink('berkas/undangan/' . $abdimas->surat_undangan);
            }

            $this->abdimas->delete($id);
            return redirect()->to(site_url('pelaksanaan'))->with('success', 'Data berhasil dihapus!');
        }

        return redirect()->to(site_url('pelaksanaan'))->with('error', 'Data tidak ditemukan!');
    }

    private function checkSpmExists($mitraId, $periodeId)
    {
        if (empty($mitraId) || empty($periodeId)) {
            return false;
        }

        // Check in tbl_dokumen_mitra if SPM exists for this mitra and period
        $existingSpm = $this->dokumenMitra->getDokumenByMitraAndType($mitraId, 'spm', $periodeId);
        if (!empty($existingSpm)) {
            return true;
        }

        // Check if SPM file exists in the filesystem
        $spmDir = WRITEPATH . 'berkas/spm/' . $periodeId . '/';
        $spmPattern = 'spm_' . $mitraId . '_' . $periodeId . '_*.pdf';
        $spmFiles = glob($spmDir . $spmPattern);

        if (!empty($spmFiles)) {
            return true;
        }

        // Also check in the database (tbl_laporan) if there's a record with SPM
        $laporan = $this->abdimas->getSpmRecordByMitraAndPeriode($mitraId, $periodeId);

        if ($laporan && !empty($laporan->spm)) {
            // Check if the file actually exists on disk
            $filePath = WRITEPATH . 'berkas/spm/' . $periodeId . '/' . $laporan->spm;
            return file_exists($filePath);
        }

        return false;
    }

    /**
     * Show upload form for surat undangan
     *
     * @param int $id
     * @return mixed
     */
    public function uploadUndangan($id = null)
    {
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2 && userLogin()->role_id != 3 && userLogin()->role_id != 4) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        // bahasa aktif - check query param first, then cookie, default to id
        $allowed = ['id', 'en'];
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

        // Translate title based on language
        $titleDict = [
            'id' => 'Upload Surat Undangan',
            'en' => 'Upload Invitation Letter'
        ];
        $title = $titleDict[$lang] ?? 'Upload Surat Undangan';

        $data['title_tab'] = $title . ' — LPM UG';
        $data['title'] = $title;
        $data['pesan'] = $this->pesan->getPesan();

        $abdimas = $this->abdimas->find($id);

        if (is_object($abdimas)) {
            $data['abdimas'] = $abdimas;
            $data['periode'] = $this->periode->findAll();
            $data['mitra'] = $this->mitra->getAll();

            return view('abdimas/upload_undangan', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    /**
     * Handle upload surat undangan
     *
     * @param int $id
     * @return mixed
     */
    public function updateUndangan($id = null)
    {
        $abdimas = $this->abdimas->find($id);

        // bahasa aktif - check query param first, then cookie, default to id
        $allowed = ['id', 'en'];
        $lang = $this->request->getGet('lang');
        if (! $lang) {
            $lang = $this->request->getCookie('lang') ?? 'id';
        }
        if (! in_array($lang, $allowed, true)) {
            $lang = 'id';
        }

        if (!$abdimas) {
            $errorMsg = ($lang === 'en') ? 'Data not found.' : 'Data tidak ditemukan.';
            return redirect()->to(site_url('pelaksanaan'))->with('error', $errorMsg);
        }

        $undangan = $this->request->getFile('surat_undangan');

        // ========== CEK: User tidak upload file ==========
        if (!$undangan || !$undangan->isValid() || $undangan->getError() == UPLOAD_ERR_NO_FILE) {
            $errorMsg = ($lang === 'en') ? 'Please select a file first.' : 'Silakan pilih file terlebih dahulu!';
            return redirect()->back()
                ->withInput()
                ->with('error', $errorMsg);
        }

        // Validasi ukuran max 5MB
        if ($undangan->getSize() > 5 * 1024 * 1024) {
            $errorMsg = ($lang === 'en') ? 'Maximum file size is 5MB!' : 'Ukuran file maksimal 5MB!';
            return redirect()->back()->withInput()->with('error', $errorMsg);
        }

        // Validasi tipe file PDF
        if (strtolower($undangan->getExtension()) !== 'pdf' || $undangan->getMimeType() !== 'application/pdf') {
            $errorMsg = ($lang === 'en') ? 'File must be in PDF format!' : 'File harus berformat PDF!';
            return redirect()->back()->withInput()->with('error', $errorMsg);
        }

        // Hapus file lama jika ada
        if (!empty($abdimas->surat_undangan) && file_exists('berkas/undangan/' . $abdimas->surat_undangan)) {
            unlink('berkas/undangan/' . $abdimas->surat_undangan);
        }

        // Pastikan direktori ada
        $uploadDir = FCPATH . 'berkas/undangan';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Upload file baru
        $namaUndangan = $undangan->getRandomName();
        $undangan->move('berkas/undangan', $namaUndangan);

        // ========== UPDATE DB ==========
        try {
            $this->abdimas->update($id, ['surat_undangan' => $namaUndangan]);

            $successMsg = ($lang === 'en') ? 'Invitation letter uploaded successfully!' : 'Surat undangan berhasil diupload!';

            return redirect()
                ->to(site_url('pelaksanaan'))
                ->with('success', $successMsg);
        } catch (\Exception $e) {
            $errorMsg = ($lang === 'en') ? 'Failed to upload invitation letter: ' . $e->getMessage() : 'Gagal mengupload surat undangan: ' . $e->getMessage();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $errorMsg);
        }
    }

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

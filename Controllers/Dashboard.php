<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

use App\Models\HakAksesModel;
use App\Models\MitraModel;
use App\Models\LaporanModel;
use App\Models\ProvinsiModel;
use App\Models\KotaModel;
use App\Models\PesanModel;
use Google\Cloud\Translate\V2\TranslateClient;

class Mitra extends ResourceController
{
    function __construct()
    {
        $this->hak_akses    = new HakAksesModel();
        $this->mitra        = new MitraModel();
        $this->laporan      = new LaporanModel();
        $this->provinsi     = new ProvinsiModel();
        $this->kota         = new KotaModel();
        $this->pesan        = new PesanModel();
        helper('filesystem');
    }

    public function updateAlamat()
    {
        if ($this->request->getMethod() !== 'post') {
            return $this->response->setStatusCode(405)->setJSON(['success' => false, 'message' => 'Method not allowed']);
        }

        $mitraId = $this->request->getPost('mitra_id');
        $newAlamat = $this->request->getPost('alamat');

        if (empty($mitraId) || empty($newAlamat)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid input']);
        }

        $mitra = $this->mitra->find($mitraId);
        if (!$mitra) {
            return $this->response->setJSON(['success' => false, 'message' => 'Mitra not found']);
        }

        // Update alamat in database
        $this->mitra->update($mitraId, ['alamat' => $newAlamat]);

        // Optionally, update related laporan records if needed
        // $this->laporan->where('mitra_id', $mitraId)->set(['alamat' => $newAlamat])->update();

        return $this->response->setJSON(['success' => true, 'message' => 'Alamat updated']);
    }
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        helper('auth');

        $lang = get_cookie('lang') ?: 'id';
        $allowed = ['id', 'en'];
        if (!in_array($lang, $allowed, true)) {
            $lang = 'id';
        }

        $user = userLogin();

        if (!$user) {
            $errorMsg = $this->translateText('Silakan login terlebih dahulu', 'id', $lang);
            return redirect()->to(site_url('login'))->with('error', $errorMsg);
        }

        // Cek role_id user
        if (!in_array($user->role_id, [1, 2, 3, 4])) {
            $errorMsg = $this->translateText('Anda tidak memiliki akses ke halaman ini', 'id', $lang);
            return redirect()->to(site_url('dashboard'))->with('error', $errorMsg);
        }

        $keyword = $this->request->getGet('keyword');

        $data = $this->mitra->getPaginated(100000, $keyword);
        $data['keyword'] = $keyword;
        $data['pesan'] = $this->pesan->getPesan();

        return view('mitra/index', $data);
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
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2 && userLogin()->role_id != 3 && userLogin()->role_id != 4) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        // Language support - check query param first, then cookie, default to id
        $lang = $this->request->getGet('lang');
        if (! $lang) {
            $lang = get_cookie('lang') ?: 'id';
        }
        if (!in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        // Set cookie if lang from query param
        if ($this->request->getGet('lang')) {
            set_cookie('lang', $lang, 60 * 60 * 24 * 30);
        }

        $titleDict = [
            'id' => 'Tambah Mitra',
            'en' => 'Add Partner'
        ];
        $title = $titleDict[$lang] ?? 'Tambah Mitra';

        $data['title_tab'] = $title . ' — LPM UG';
        $data['title'] = $title;
        $data['pesan'] = $this->pesan->getPesan();
        $data['validation'] = \Config\Services::validation();

        // $data['hak_akses'] = $this->hak_akses->findAll();
        $data['kota'] = $this->kota->getAll();

        return view('mitra/new', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        if (!$this->validate($this->mitra->getValidationRules())) {
            $validation = \Config\Services::validation();
            // dd($validation);
            return redirect()->back()->withInput()->with('validation', $validation);
        }

        $data = [
            'user_name'     => $this->request->getVar('user_name'),
            'nidn'          => $this->request->getVar('nidn'),
            'email'         => $this->request->getVar('email'),
            'kontak'        => $this->request->getVar('kontak'),
            'kota_id'       => $this->request->getVar('kota_id'),
            'alamat'        => $this->request->getVar('alamat'),
            'kebutuhan'     => $this->request->getVar('kebutuhan'),
            'password'      => password_hash($this->request->getVar('password'), PASSWORD_BCRYPT),
            'role_id'       => 5,
            'status'        => 1,
        ];


        $this->mitra->insert($data);

        // Language support for flash message - check query param first, then cookie, default to id
        $lang = $this->request->getGet('lang');
        if (! $lang) {
            $lang = get_cookie('lang') ?: 'id';
        }
        if (!in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $successMsg = ($lang === 'en') ? 'New data has been successfully saved.' : 'Data baru anda berhasil disimpan.';

        return redirect()->to(site_url('mitra'))->with('success', $successMsg);
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2 && userLogin()->role_id != 3 && userLogin()->role_id != 4) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        // Language support - check query param first, then cookie, default to id
        $lang = $this->request->getGet('lang');
        if (! $lang) {
            $lang = get_cookie('lang') ?: 'id';
        }
        if (!in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        // Set cookie if lang from query param
        if ($this->request->getGet('lang')) {
            set_cookie('lang', $lang, 60 * 60 * 24 * 30);
        }

        $titleDict = [
            'id' => 'Edit Mitra',
            'en' => 'Edit Partner'
        ];
        $title = $titleDict[$lang] ?? 'Edit Mitra';

        $data['title_tab'] = $title . ' — LPM UG';
        $data['title'] = $title;
        $data['pesan'] = $this->pesan->getPesan();

        $mitra =  $this->mitra->find($id);
        if (is_object($mitra)) {
            $data['mitra'] = $mitra;
            $data['hak_akses'] = $this->hak_akses->findAll();
            $data['kota'] = $this->kota->getAll();
            return view('mitra/edit', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('mitra/edit', $data);
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $data = $this->request->getPost();
        // $data = [
        //     'user_name'     => $this->request->getVar('user_name'),
        //     'nidn'          => $this->request->getVar('nidn'),
        //     'email'         => $this->request->getVar('email'),
        //     'kontak'        => $this->request->getVar('kontak'),
        //     'kota_id'       => $this->request->getVar('kota_id'),
        //     'alamat'        => $this->request->getVar('alamat'),
        //     // 'password'      => password_hash($this->request->getVar('password'), PASSWORD_BCRYPT),
        // ];

        $this->mitra->update($id, $data);

        // Language support for flash message - check query param first, then cookie, default to id
        $lang = $this->request->getGet('lang');
        if (! $lang) {
            $lang = get_cookie('lang') ?: 'id';
        }
        if (!in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $successMsg = ($lang === 'en') ? 'Your data has been successfully updated.' : 'Data anda berhasil diupdate.';

        return redirect()->to(site_url('mitra'))->with('success', $successMsg);
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        if (!in_array(userLogin()->role_id, [1, 2, 3, 4])) {
            return redirect()->to(site_url('dashboard'));
        }

        $mitra = $this->mitra->find($id);
        if (!$mitra) {
            return redirect()->to(site_url('mitra'))->with('error', 'Data mitra tidak ditemukan.');
        }

        // Hapus semua laporan terkait mitra terlebih dahulu
        $db = \Config\Database::connect();
        $db->table('tbl_laporan')->where('mitra_id', $id)->delete();

        if ($this->mitra->delete($id)) {
            // Language support for flash message - check query param first, then cookie, default to id
            $lang = $this->request->getGet('lang');
            if (! $lang) {
                $lang = get_cookie('lang') ?: 'id';
            }
            if (!in_array($lang, ['id', 'en'], true)) {
                $lang = 'id';
            }

            $successMsg = ($lang === 'en') ? 'Partner data has been successfully deleted.' : 'Data mitra berhasil dihapus.';
            return redirect()->to(site_url('mitra'))->with('success', $successMsg);
        } else {
            $lang = $this->request->getGet('lang');
            if (! $lang) {
                $lang = get_cookie('lang') ?: 'id';
            }
            if (!in_array($lang, ['id', 'en'], true)) {
                $lang = 'id';
            }

            $errorMsg = ($lang === 'en') ? 'Failed to delete partner data.' : 'Gagal menghapus data mitra.';
            return redirect()->to(site_url('mitra'))->with('error', $errorMsg);
        }
    }


    // === Form Upload SPM (halaman terpisah) - UPDATED TO USE tbl_dokumen_mitra ===
    public function uploadFormSpm($id)
    {
        helper(['auth', 'cookie']);

        $allowed = ['id', 'en'];

        $lang = get_cookie('lang') ?: 'id';
        $allowed = ['id', 'en'];
        if (!in_array($lang, $allowed, true)) {
            $lang = 'id';
        }

        $user = userLogin();

        if (!$user) {
            $errorMsg = $this->translateText('Silakan login terlebih dahulu', 'id', $lang);
            return redirect()->to(site_url('dashboard'))->with('error', $errorMsg);
        }

        // Cek role_id dan user_id
        if ($user->role_id != 5 || $user->user_id != $id) {
            $errorMsg = $this->translateText('Anda tidak memiliki akses ke halaman ini', 'id', $lang);
            return redirect()->to(site_url('dashboard'))->with('error', $errorMsg);
        }

        $mitra = $this->mitra->find($id);
        if (!$mitra) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Get only active periods (status = 1)
        $periodeModel = new \App\Models\PeriodeModel();
        $periodes = $periodeModel->where('status', 1)->findAll();

        // Get existing dokumen from tbl_dokumen_mitra for this mitra
        $dokumenMitraModel = new \App\Models\DokumenMitraModel();
        $dokumen_mitra = $dokumenMitraModel->getDokumenByMitra($id);

        $data['mitra'] = $mitra;
        $data['periodes'] = $periodes;
        $data['dokumen_mitra'] = $dokumen_mitra;
        $data['title_tab'] = 'Upload SPM';
        $data['title'] = 'Upload SPM';
        $data['pesan'] = $this->pesan->getPesan();

        return view('mitra/upload_spm', $data);
    }

    // === Form Upload SKM (halaman terpisah) - UPDATED TO USE tbl_dokumen_mitra ===
    public function uploadFormSkm($id)
    {
        helper(['auth', 'cookie']);

        // ✅ Cek apakah user sudah login
        $user = userLogin();

        $lang = get_cookie('lang') ?: 'id';
        $allowed = ['id', 'en'];
        if (!in_array($lang, $allowed, true)) {
            $lang = 'id';
        }

        // ✅ Jika user tidak ada (null), redirect ke login
        if (!$user) {
            $errorMsg = $this->translateText('Silakan login terlebih dahulu', 'id', $lang);
            return redirect()->to(site_url('login'))
                ->with('error', $errorMsg);
        }

        // ✅ Cek role_id dan user_id
        if ($user->role_id != 5 || $user->user_id != $id) {
            $errorMsg = $this->translateText('Anda tidak memiliki akses ke halaman ini', 'id', $lang);
            return redirect()->to(site_url('dashboard'))
                ->with('error', $errorMsg);
        }

        $mitra = $this->mitra->find($id);
        if (!$mitra) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Get only active periods (status = 1)
        $periodeModel = new \App\Models\PeriodeModel();
        $periodes = $periodeModel->where('status', 1)->findAll();

        // Get existing dokumen from tbl_dokumen_mitra for this mitra
        $dokumenMitraModel = new \App\Models\DokumenMitraModel();
        $dokumen_mitra = $dokumenMitraModel->getDokumenByMitra($id);

        $data['mitra'] = $mitra;
        $data['periodes'] = $periodes;
        $data['dokumen_mitra'] = $dokumen_mitra;
        $data['title_tab'] = 'Upload SKM';
        $data['title'] = 'Upload SKM';
        $data['pesan'] = $this->pesan->getPesan();

        return view('mitra/upload_skm', $data);
    }

    // === Submit Upload (SPM / SKM) - UPDATED TO USE tbl_dokumen_mitra ===
    public function uploadSubmit($id)
    {
        if (userLogin()->role_id != 5 || userLogin()->user_id != $id) {
            return redirect()->to(site_url('dashboard'))->with('error', 'Unauthorized');
        }

        $docType = $this->request->getPost('doc_type'); // spm atau skm
        $periodeId = $this->request->getPost('periode_id'); // periode yang dipilih
        $nomorSurat = $this->request->getPost('nomor_surat'); // ambil nomor surat dari form

        if (!$periodeId) {
            return redirect()->back()->with('error', 'Periode harus dipilih');
        }

        // Nomor surat hanya wajib untuk SPM
        if ($docType === 'spm' && empty($nomorSurat)) {
            return redirect()->back()->with('error', 'Nomor surat SPM harus diisi');
        }

        $file = $this->request->getFile($docType);

        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid');
        }

        if ($file->getSize() > 5 * 1024 * 1024) {
            return redirect()->back()->with('error', 'Maksimal ukuran file 5 MB');
        }

        if ($file->getExtension() !== 'pdf') {
            return redirect()->back()->with('error', 'File harus PDF');
        }

        // Load DokumenMitraModel
        $dokumenMitraModel = new \App\Models\DokumenMitraModel();

        // Cek apakah dokumen sudah ada untuk mitra, periode, dan tipe ini
        $existingDokumen = $dokumenMitraModel->getDokumenByMitraAndType($id, $docType, $periodeId);
        if ($existingDokumen) {
            return redirect()->back()->with('error', strtoupper($docType) . ' untuk periode ini sudah diupload. Silakan hapus yang ada terlebih dahulu jika ingin mengganti.');
        }

        // Cek apakah nomor surat sudah pernah dipakai di periode yang sama (hanya untuk SPM)
        if (!empty($nomorSurat)) {
            $existingNomor = $dokumenMitraModel->where('periode_id', $periodeId)
                ->where('nomor_surat', $nomorSurat)
                ->first();
            if ($existingNomor) {
                return redirect()->back()->with('error', 'Nomor surat tersebut sudah digunakan di periode ini!');
            }
        }

        // Rename file
        $newName = $docType . '_' . $id . '_' . $periodeId . '_' . time() . '.pdf';
        $uploadPath = 'uploads/dokumen_mitra/';
        if (!is_dir($uploadPath)) mkdir($uploadPath, 0777, true);

        $file->move($uploadPath, $newName, true);

        // Data untuk insert ke tbl_dokumen_mitra
        $data = [
            'mitra_id'    => $id,
            'periode_id'  => $periodeId,
            'doc_type'    => $docType,
            'nomor_surat' => $nomorSurat,
            'file_path'   => $uploadPath . $newName,
        ];

        // Simpan ke tbl_dokumen_mitra
        if ($dokumenMitraModel->insert($data)) {
            return redirect()->back()->with('success', strtoupper($docType) . ' dan nomor surat berhasil diupload untuk periode yang dipilih!');
        } else {
            return redirect()->back()->with('error', 'Gagal menyimpan data ke database.');
        }
    }



    // === View/Download Dokumen (akses dosen role_id = 4) ===
    public function downloadDokumen($id, $type, $periodeId = null)
    {
        if (userLogin()->role_id != 4) {
            return redirect()->to(site_url('dashboard'))->with('error', 'Unauthorized');
        }

        $mitra = $this->mitra->find($id);
        if (!$mitra) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        // Get file from tbl_laporan (period-based)
        if ($periodeId) {
            $laporan = $this->laporan->getLaporanByMitraAndPeriode($id, $periodeId);
            $fileName = ($type === 'spm') ? $laporan->spm : $laporan->skm;
        } else {
            // Fallback: get latest file if no period specified
            $laporans = $this->laporan->getLaporanByMitra($id);
            $fileName = null;
            foreach ($laporans as $laporan) {
                if (($type === 'spm' && !empty($laporan->spm)) || ($type === 'skm' && !empty($laporan->skm))) {
                    $fileName = ($type === 'spm') ? $laporan->spm : $laporan->skm;
                    break;
                }
            }
        }

        if (!$fileName) {
            return redirect()->back()->with('error', 'File tidak ditemukan untuk periode yang dipilih');
        }

        $filePath = WRITEPATH . 'berkas/' . $type . '/' . $periodeId . '/' . $fileName;

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan di: ' . $filePath);
        }

        return $this->response->download($filePath, null);
    }

    // === Preview Dokumen (akses untuk mitra sendiri dan dosen role_id = 4) ===
    public function previewDokumen($id, $type, $periodeId = null)
    {
        try {
            // Debug: Log the current user info
            log_message('debug', 'PreviewDokumen called with ID: ' . $id . ', Type: ' . $type . ', Periode: ' . $periodeId);

            // Allow access for: mitra owner (role_id = 5) or dosen (role_id = 4)
            $currentUser = userLogin();
            if (!$currentUser) {
                log_message('error', 'User not logged in');
                return redirect()->to(site_url('login'))->with('error', 'Silakan login terlebih dahulu');
            }

            log_message('debug', 'Current user: ' . json_encode($currentUser));

            if (($currentUser->role_id != 5 || $currentUser->user_id != $id) &&
                $currentUser->role_id != 4
            ) {
                log_message('error', 'Unauthorized access attempt by user ID: ' . $currentUser->user_id . ', Role: ' . $currentUser->role_id);
                return redirect()->to(site_url('dashboard'))->with('error', 'Unauthorized');
            }

            $mitra = $this->mitra->find($id);
            if (!$mitra) {
                log_message('error', 'Mitra not found with ID: ' . $id);
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }

            // Get file from tbl_laporan (period-based)
            $fileName = null;
            if ($periodeId) {
                $laporan = $this->laporan->getLaporanByMitraAndPeriode($id, $periodeId);
                if ($laporan) {
                    $fileName = ($type === 'spm') ? $laporan->spm : $laporan->skm;
                }
            } else {
                // Fallback: get latest file if no period specified
                $laporans = $this->laporan->getLaporanByMitra($id);
                foreach ($laporans as $laporan) {
                    if (($type === 'spm' && !empty($laporan->spm)) || ($type === 'skm' && !empty($laporan->skm))) {
                        $fileName = ($type === 'spm') ? $laporan->spm : $laporan->skm;
                        break;
                    }
                }
            }

            if (!$fileName) {
                log_message('error', 'File not found for mitra ID: ' . $id . ', type: ' . $type . ', periode: ' . $periodeId);
                return redirect()->back()->with('error', 'File tidak ditemukan untuk periode yang dipilih');
            }

            $filePath = WRITEPATH . 'berkas/' . $type . '/' . $periodeId . '/' . $fileName;

            log_message('debug', 'File path: ' . $filePath);
            log_message('debug', 'File exists: ' . (file_exists($filePath) ? 'YES' : 'NO'));

            if (!file_exists($filePath)) {
                log_message('error', 'File not found at path: ' . $filePath);
                return redirect()->back()->with('error', 'File tidak ditemukan di: ' . $filePath);
            }

            // Read file content
            $fileContent = file_get_contents($filePath);
            if ($fileContent === false) {
                log_message('error', 'Failed to read file content from: ' . $filePath);
                return redirect()->back()->with('error', 'Gagal membaca file');
            }

            // Set headers for PDF preview in browser
            return $this->response
                ->setStatusCode(200)
                ->setContentType('application/pdf')
                ->setHeader('Content-Disposition', 'inline; filename="' . $fileName . '"')
                ->setHeader('Content-Length', strlen($fileContent))
                ->setHeader('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->setHeader('Pragma', 'no-cache')
                ->setHeader('Expires', '0')
                ->setBody($fileContent);
        } catch (\Exception $e) {
            log_message('error', 'Error in previewDokumen: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // === Simple Preview Test (for debugging) ===
    public function previewTest($id, $type)
    {
        try {
            log_message('debug', 'PreviewTest called with ID: ' . $id . ', Type: ' . $type);

            $currentUser = userLogin();
            if (!$currentUser) {
                return $this->response->setStatusCode(401)->setBody('Unauthorized: User not logged in');
            }

            $filePath = WRITEPATH . 'berkas/' . $type . '/test.pdf';
            log_message('debug', 'Test file path: ' . $filePath);
            log_message('debug', 'Test file exists: ' . (file_exists($filePath) ? 'YES' : 'NO'));

            if (!file_exists($filePath)) {
                return $this->response->setStatusCode(404)->setBody('Test file not found at: ' . $filePath);
            }

            $fileContent = file_get_contents($filePath);
            return $this->response
                ->setStatusCode(200)
                ->setContentType('application/pdf')
                ->setHeader('Content-Disposition', 'inline; filename="test.pdf"')
                ->setBody($fileContent);
        } catch (\Exception $e) {
            log_message('error', 'Error in previewTest: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setBody('Error: ' . $e->getMessage());
        }
    }

    // === Delete Laporan (SPM/SKM) ===
    public function deleteLaporan($laporanId, $type = 'all')
    {
        try {
            $currentUser = userLogin();
            if (!$currentUser) {
                return redirect()->to(site_url('login'))->with('error', 'Silakan login terlebih dahulu');
            }

            if (!in_array($currentUser->role_id, [4, 5])) {
                return redirect()->to(site_url('dashboard'))->with('error', 'Unauthorized');
            }

            $laporan = $this->laporan->find($laporanId);
            if (!$laporan) {
                return redirect()->back()->with('error', 'Laporan tidak ditemukan');
            }

            if ($currentUser->role_id == 5 && $laporan->mitra_id != $currentUser->user_id) {
                return redirect()->back()->with('error', 'Unauthorized');
            }

            // Use the new method that only deletes files and clears database fields
            // without deleting the entire record (avoids foreign key constraints)
            $result = $this->laporan->deleteLaporanFiles($laporanId);

            if ($result) {
                return redirect()->back()->with('success', 'File berhasil dihapus');
            } else {
                return redirect()->back()->with('error', 'Gagal menghapus file');
            }
        } catch (\Exception $e) {
            log_message('error', 'Error in deleteLaporan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // === Serve Laporan Files ===
    public function laporan($filename)
    {
        $path = WRITEPATH . 'berkas/laporan/' . $filename;

        if (!file_exists($path)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("File not found: $filename");
        }

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
            ->setBody(file_get_contents($path));
    }

    // === Serve Bukti Kegiatan Files ===
    public function kegiatan($filename)
    {
        $path = WRITEPATH . 'berkas/kegiatan/' . $filename;

        if (!file_exists($path)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("File not found: $filename");
        }

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
            ->setBody(file_get_contents($path));
    }

    // === Serve SPM Files ===
    public function spm($filename)
    {
        $path = WRITEPATH . 'berkas/spm/' . $filename;

        if (!file_exists($path)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("File not found: $filename");
        }

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
            ->setBody(file_get_contents($path));
    }

    // === Serve SKM Files ===
    public function skm($filename)
    {
        $path = WRITEPATH . 'berkas/skm/' . $filename;

        if (!file_exists($path)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("File not found: $filename");
        }

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
            ->setBody(file_get_contents($path));
    }

    // === Generate Surat Balasan PDF (untuk mitra) - FIXED VERSION ===
    public function generateSuratBalasanPdf($laporanId)
    {
        if (userLogin()->role_id != 5) {
            return redirect()->to(site_url('dashboard'))->with('error', 'Unauthorized');
        }

        $mitraId = userLogin()->user_id;
        $laporan = $this->laporan->find($laporanId);

        // Check if laporan belongs to this mitra
        if (!$laporan || $laporan->mitra_id != $mitraId) {
            return redirect()->to(site_url('mitra/surat-balasan'))->with('error', 'Unauthorized access');
        }

        // Check if laporan has required data
        if (empty($laporan->judul_kegiatan) || empty($laporan->ketua_id)) {
            return redirect()->to(site_url('mitra/surat-balasan'))
                ->with('error', 'Data laporan belum lengkap. Pastikan Judul Kegiatan dan Ketua Pengusul sudah terisi.');
        }

        try {
            // Create AbdimasController instance and call the PDF method directly
            $abdimasController = new \App\Controllers\Abdimas();

            // Set the user context for the PDF generation
            // The AbdimasController will handle role validation internally

            return $abdimasController->suratBalasanPdf($laporanId);
        } catch (\Exception $e) {
            log_message('error', 'Error generating PDF from Mitra controller: ' . $e->getMessage());
            return redirect()->to(site_url('mitra/surat-balasan'))
                ->with('error', 'Gagal generate PDF: ' . $e->getMessage());
        }
    }
    public function updateNomorSurat()
    {
        if ($this->request->getMethod() !== 'post') {
            return $this->response->setStatusCode(405)->setJSON(['success' => false, 'message' => 'Method not allowed']);
        }

        $laporanId = $this->request->getPost('laporan_id');
        $nomorSurat = $this->request->getPost('nomor_surat');

        if (empty($laporanId) || empty($nomorSurat)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data tidak lengkap']);
        }

        $laporan = $this->laporan->find($laporanId);
        if (!$laporan) {
            return $this->response->setJSON(['success' => false, 'message' => 'Laporan tidak ditemukan']);
        }

        $this->laporan->update($laporanId, ['nomor_surat' => $nomorSurat]);

        return $this->response->setJSON(['success' => true, 'message' => 'Nomor surat berhasil diperbarui']);
    }


    // === Alternative method jika ingin lebih aman ===
    public function generateSuratBalasanPdfSafe($laporanId)
    {
        if (userLogin()->role_id != 5) {
            return redirect()->to(site_url('dashboard'))->with('error', 'Unauthorized');
        }

        $mitraId = userLogin()->user_id;
        $laporan = $this->laporan->find($laporanId);

        // Validation
        if (!$laporan || $laporan->mitra_id != $mitraId) {
            return redirect()->to(site_url('mitra/surat-balasan'))->with('error', 'Unauthorized access');
        }

        if (empty($laporan->judul_kegiatan) || empty($laporan->ketua_id)) {
            return redirect()->to(site_url('mitra/surat-balasan'))
                ->with('error', 'Data laporan belum lengkap. Pastikan Judul Kegiatan dan Ketua Pengusul sudah terisi.');
        }

        // Store session data for PDF generation
        session()->setTempdata('pdf_generation', [
            'mitra_id' => $mitraId,
            'laporan_id' => $laporanId,
            'timestamp' => time()
        ], 300); // 5 minutes expiry

        // Direct URL construction for Abdimas controller
        $pdfUrl = site_url('abdimas/surat-balasan-pdf/' . $laporanId);

        // Return redirect that opens in same tab/new tab
        return redirect()->to($pdfUrl);
    }

    // === Update method suratBalasan untuk handle success messages ===
    public function suratBalasan()
    {
        helper('auth');
        if (userLogin()->role_id != 5) {
            return redirect()->to(site_url('dashboard'))->with('error', 'Unauthorized');
        }

        $mitraId = userLogin()->user_id;

        // Get all laporan for this mitra (dengan atau tanpa surat balasan)
        $laporans = $this->laporan->getLaporanByMitra($mitraId);

        // Pisahkan laporan berdasarkan status surat balasan
        $laporanWithSurat = [];
        $laporanWithoutSurat = [];

        foreach ($laporans as $laporan) {
            if (!empty($laporan->surat_balasan_path) && file_exists(WRITEPATH . 'berkas/' . $laporan->surat_balasan_path)) {
                $laporanWithSurat[] = $laporan;
            } else {
                // Cek apakah laporan sudah memiliki data lengkap untuk generate surat balasan
                if (!empty($laporan->judul_kegiatan) && !empty($laporan->ketua_id)) {
                    $laporanWithoutSurat[] = $laporan;
                }
            }
        }

        // Handle flash messages
        $flashData = session()->getFlashdata();

        $data = [
            'title_tab' => 'Surat Balasan &mdash; LPM UG',
            'title' => 'Surat Balasan',
            'pesan' => $this->pesan->getPesan(),
            'laporan_with_surat' => $laporanWithSurat,
            'laporan_without_surat' => $laporanWithoutSurat,
            'all_laporans' => $laporans, // untuk backward compatibility
            'flash_messages' => $flashData
        ];

        return view('mitra/surat_balasan', $data);
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
        $dokumenMitraModel = new \App\Models\DokumenMitraModel();
        $existingSpm = $dokumenMitraModel->getDokumenByMitraAndType($mitraId, 'spm', $periodeId);
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
        $laporan = $this->laporan->getSpmRecordByMitraAndPeriode($mitraId, $periodeId);

        if ($laporan && !empty($laporan->spm)) {
            // Check if the file actually exists on disk
            $filePath = WRITEPATH . 'berkas/spm/' . $periodeId . '/' . $laporan->spm;
            return file_exists($filePath);
        }

        return false;
    }

    // Temporary test method for checkSpmExists - REMOVE AFTER TESTING
    public function testCheckSpmExists($mitraId = null, $periodeId = null)
    {
        if (userLogin()->role_id != 1) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        $mitraId = $mitraId ?? $this->request->getGet('mitra_id');
        $periodeId = $periodeId ?? $this->request->getGet('periode_id');

        $result = $this->checkSpmExists($mitraId, $periodeId);

        return $this->response->setJSON([
            'mitra_id' => $mitraId,
            'periode_id' => $periodeId,
            'spm_exists' => $result
        ]);
    }

    /**
     * Update password for a mitra account (called from list page modal)
     */
    public function updatePassword($id = null)
    {
        if (!in_array(userLogin()->role_id, [1, 2, 3])) {
            return redirect()->to(site_url('mitra'))->with('error', 'Anda tidak memiliki akses untuk mengubah password.');
        }

        $mitra = $this->mitra->find($id);
        if (!$mitra) {
            return redirect()->to(site_url('mitra'))->with('error', 'Data mitra tidak ditemukan.');
        }

        $newPassword     = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        // Validasi server-side
        if (empty($newPassword) || strlen($newPassword) < 6) {
            return redirect()->back()->with('error', 'Password minimal 6 karakter.');
        }

        if ($newPassword !== $confirmPassword) {
            return redirect()->back()->with('error', 'Password dan konfirmasi tidak cocok.');
        }

        $this->mitra->update($id, [
            'password' => password_hash($newPassword, PASSWORD_BCRYPT),
        ]);

        return redirect()->to(site_url('mitra'))->with('success', 'Password mitra "' . esc($mitra->user_name) . '" berhasil diperbarui.');
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
}

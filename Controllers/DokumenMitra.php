<?php

namespace App\Controllers;

use App\Models\DokumenMitraModel;
use App\Models\PeriodeModel;
use App\Models\MitraModel;
use CodeIgniter\Controller;
use Google\Cloud\Translate\V2\TranslateClient;

class DokumenMitra extends Controller
{
    protected $dokumenModel;
    protected $periodeModel;
    protected $mitraModel;

    public function __construct()
    {
        $this->dokumenModel = new DokumenMitraModel();
        $this->periodeModel = new PeriodeModel();
        $this->mitraModel   = new MitraModel();
        helper(['form', 'url']);
    }

    // Halaman Upload
    public function upload($mitra_id)
    {
        $data['title_tab'] = 'Upload SPM Mitra';
        $data['mitra'] = $this->mitraModel->find($mitra_id);
        $data['periodes'] = $this->periodeModel->findAll();
        $data['dokumen_mitra'] = $this->dokumenModel->getDokumenByMitra($mitra_id);

        return view('dokumen_mitra/upload_spm', $data);
    }

    public function store()
    {
        helper('cookie');
        $file = $this->request->getFile('spm');
        $mitra_id = $this->request->getPost('mitra_id');
        $periode_id = $this->request->getPost('periode_id');
        $nomor_surat = $this->request->getPost('nomor_surat');
        $doc_type = $this->request->getPost('doc_type');

        $allowed = ['id', 'en'];

        $lang = get_cookie('lang') ?: 'id';
        $allowed = ['id', 'en'];
        if (!in_array($lang, $allowed, true)) {
            $lang = 'id';
        }

        // Cek apakah SPM sudah diupload untuk mitra dan periode ini
        if ($this->checkSpmExists($mitra_id, $periode_id)) {
            $errorMessage = $this->translateText('SPM untuk periode ini sudah diupload. Silakan hapus SPM yang ada terlebih dahulu jika ingin mengganti.', 'id', $lang);
            return redirect()->back()->with('error', $errorMessage);
        }

        if (!$file || !$file->isValid()) {
            $errorMessage = $this->translateText('File tidak valid', 'id', $lang);
            return redirect()->back()->with('error', $errorMessage);
        }

        // Validasi file PDF & ukuran
        if (strtolower($file->getExtension()) !== 'pdf' || $file->getMimeType() !== 'application/pdf') {
            $errorMessage = $this->translateText('File harus dalam format PDF', 'id', $lang);
            return redirect()->back()->with('error', $errorMessage);
        }
        if ($file->getSizeByUnit('mb') > 5) {
            $errorMessage = $this->translateText('Ukuran file maksimal 5MB', 'id', $lang);
            return redirect()->back()->with('error', $errorMessage);
        }

        // Simpan file
        $newName = $file->getRandomName();
        $uploadPath = 'uploads/dokumen_mitra/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
        $file->move($uploadPath, $newName);

        // Data untuk insert ke DB
        $data = [
            'mitra_id'    => $mitra_id,
            'periode_id'  => $periode_id,
            'doc_type'    => $doc_type,
            'nomor_surat' => $nomor_surat,
            'file_path'   => $uploadPath . $newName,
        ];

        if ($this->dokumenModel->insert($data)) {
            $errorMessage = $this->translateText('File SPM berhasil diupload', 'id', $lang);
            return redirect()->back()->with('success', $errorMessage);
        } else {
            $errorMessage = $this->translateText('Gagal menyimpan data ke database', 'id', $lang);
            return redirect()->back()->with('error', $errorMessage);
        }
    }

    public function storeSkm()
    {
        helper('cookie');
        $file = $this->request->getFile('skm');
        $mitra_id = $this->request->getPost('mitra_id');
        $periode_id = $this->request->getPost('periode_id');
        $nomor_surat = $this->request->getPost('nomor_surat');

        $allowed = ['id', 'en'];

        $lang = get_cookie('lang') ?: 'id';
        $allowed = ['id', 'en'];
        if (!in_array($lang, $allowed, true)) {
            $lang = 'id';
        }

        if (!$file || !$file->isValid()) {
            $errorMessage = $this->translateText('File tidak valid', 'id', $lang);
            return redirect()->back()->with('error', $errorMessage);
        }

        // Validasi file PDF & ukuran
        if (strtolower($file->getExtension()) !== 'pdf' || $file->getMimeType() !== 'application/pdf') {
            $errorMessage = $this->translateText('File harus dalam format PDF', 'id', $lang);
            return redirect()->back()->with('error', $errorMessage);
        }
        if ($file->getSizeByUnit('mb') > 5) {
            $errorMessage = $this->translateText('Ukuran file maksimal 5MB', 'id', $lang);
            return redirect()->back()->with('error', $errorMessage);
        }

        // Simpan file
        $newName = $file->getRandomName();
        $uploadPath = 'uploads/dokumen_mitra/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
        $file->move($uploadPath, $newName);

        // Data untuk insert ke DB
        $data = [
            'mitra_id'    => $mitra_id,
            'periode_id'  => $periode_id,
            'doc_type'    => 'skm',
            'nomor_surat' => $nomor_surat,
            'file_path'   => $uploadPath . $newName,
        ];

        if ($this->dokumenModel->insert($data)) {
            $errorMessage = $this->translateText('File SKM berhasil diupload', 'id', $lang);
            return redirect()->back()->with('success', $errorMessage);
        } else {
            $errorMessage = $this->translateText('Gagal menyimpan data ke database', 'id', $lang);
            return redirect()->back()->with('error', $errorMessage);
        }
    }

    // Download Dokumen
    public function download($id)
    {
        helper('cookie');
        $dokumen = $this->dokumenModel->getDokumenById($id);
        $allowed = ['id', 'en'];

        $lang = get_cookie('lang') ?: 'id';
        $allowed = ['id', 'en'];
        if (!in_array($lang, $allowed, true)) {
            $lang = 'id';
        }

        if (!$dokumen || !file_exists($dokumen['file_path'])) {
            $errorMessage = $this->translateText('File tidak ditemukan', 'id', $lang);
            return redirect()->back()->with('error', $errorMessage);
        }

        return $this->response->download($dokumen['file_path'], null);
    }

    // Preview Dokumen
    public function preview($id)
    {
        helper('cookie');
        $dokumen = $this->dokumenModel->getDokumenById($id);
        $allowed = ['id', 'en'];

        $lang = get_cookie('lang') ?: 'id';
        $allowed = ['id', 'en'];
        if (!in_array($lang, $allowed, true)) {
            $lang = 'id';
        }

        if (!$dokumen || !file_exists($dokumen['file_path'])) {
            $errorMessage = $this->translateText('File tidak ditemukan', 'id', $lang);
            return redirect()->back()->with('error', $errorMessage);
        }

        // Read file content
        $fileContent = file_get_contents($dokumen['file_path']);
        if ($fileContent === false) {
            $errorMessage = $this->translateText('Gagal membaca file', 'id', $lang);
            return redirect()->back()->with('error', $errorMessage);
        }

        // Set headers for PDF preview in browser
        return $this->response
            ->setStatusCode(200)
            ->setContentType('application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="' . basename($dokumen['file_path']) . '"')
            ->setHeader('Content-Length', strlen($fileContent))
            ->setHeader('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->setHeader('Pragma', 'no-cache')
            ->setHeader('Expires', '0')
            ->setBody($fileContent);
    }

    // Hapus Dokumen
    public function delete($id)
    {
        helper('cookie');
        $dokumen = $this->dokumenModel->getDokumenById($id);

        $lang = get_cookie('lang') ?: 'id';
        $allowed = ['id', 'en'];
        if (!in_array($lang, $allowed, true)) {
            $lang = 'id';
        }

        if ($dokumen) {
            $errorMessage = $this->translateText('File berhasil dihapus', 'id', $lang);
            if (file_exists($dokumen['file_path'])) {
                unlink($dokumen['file_path']);
            }
            $this->dokumenModel->delete($id);
            return redirect()->back()->with('success', $errorMessage);
        }
        $errorMessage = $this->translateText('File tidak ditemukan', 'id', $lang);

        return redirect()->back()->with('error', $errorMessage);
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

        // Check in database if SPM exists for this mitra and period
        $existingSpm = $this->dokumenModel->getDokumenByMitraAndType($mitraId, 'spm', $periodeId);

        return !empty($existingSpm);
    }
    private function checkSkmExists($mitraId, $periodeId)
    {
        if (empty($mitraId) || empty($periodeId)) {
            return false;
        }

        // Check in database if SKM exists for this mitra and period
        $existingSkm = $this->dokumenModel->getDokumenByMitraAndType($mitraId, 'skm', $periodeId);

        return !empty($existingSkm);
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

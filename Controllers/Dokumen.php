<?php

namespace App\Controllers;

use App\Models\DokumenModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use Google\Cloud\Translate\V2\TranslateClient;

class Dokumen extends BaseController
{
    protected $dokumenModel;

    public function __construct()
    {
        $this->dokumenModel = new DokumenModel();
    }

    // Tampil semua dokumen aktif
    public function index()
    {
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

        $data = [];
        $data['dokumen'] = $this->dokumenModel->findAll() ?? [];
        $data['keyword'] = $keyword;
        $data['pesan']   = session()->getFlashdata('pesan') ?? [];
        $data['lang']    = $lang;

        $titles = [
            'id' => ['title' => 'Dokumen',   'title_tab' => 'Dokumen &mdash; LPM UG'],
            'en' => ['title' => 'Documents', 'title_tab' => 'Documents &mdash; LPM UG'],
        ];
        $data['title']     = $titles[$lang]['title'];
        $data['title_tab'] = $titles[$lang]['title_tab'];

        if ($lang === 'en' && ! empty($data['dokumen']) && is_array($data['dokumen'])) {
            $this->translateDokumenFields($data['dokumen']);
        }

        return view('dokumen/index', $data);
    }

    private function translateDokumenFields(array &$rows): void
    {
        foreach ($rows as &$row) {
            if (isset($row['nama_file']) && $row['nama_file'] !== '') {
                $row['nama_file'] = service('translation')->translateCached($row['nama_file'], 'id', 'en');
            }
        }
        unset($row);
    }



    // Upload dokumen baru
    public function upload()
    {
        $file = $this->request->getFile('file_dokumen');
        $nama = $this->request->getPost('nama_file');

        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $messageBerhasil = 'File berhasil diunggah';
        $messageGagal = 'Gagal mengunggah file';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $messageBerhasil = service('translation')->translateCached($messageBerhasil, 'id', 'en');
            $messageGagal = service('translation')->translateCached($messageGagal, 'id', 'en');
        }

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $ext = strtolower($file->getExtension());
            $mime = $file->getMimeType();
            $allowedExts = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'zip', 'rar', 'png', 'jpg', 'jpeg', 'gif'];
            
            if (!in_array($ext, $allowedExts, true)) {
                return redirect()->to('/dokumen')->with('pesan', 'Ekstensi file tidak diizinkan.');
            }
            if ($file->getSize() > 10 * 1024 * 1024) {
                return redirect()->to('/dokumen')->with('pesan', 'Ukuran file maksimal 10MB.');
            }
            if (preg_match('/(php|html|htm|js|css|htaccess)/i', $ext) || strpos($mime, 'text/html') !== false || strpos($mime, 'application/x-httpd-php') !== false) {
                return redirect()->to('/dokumen')->with('pesan', 'Tipe file tidak valid.');
            }

            $newName = $file->getRandomName();
            $file->move('uploads/dokumen', $newName);

            $this->dokumenModel->save([
                'nama_file' => $nama,
                'file_path' => 'uploads/dokumen/' . $newName
            ]);

            return redirect()->to('/dokumen')->with('pesan', $messageBerhasil);
        }

        return redirect()->to('/dokumen')->with('pesan', $messageGagal);
    }

    // Form edit dokumen
    public function edit($id = null)
    {
        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $messageDokumenTidakDitemukan = 'Dokumen tidak ditemukan';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $messageDokumenTidakDitemukan = service('translation')->translateCached($messageDokumenTidakDitemukan, 'id', 'en');
        }
        if (!$id) throw PageNotFoundException::forPageNotFound($messageDokumenTidakDitemukan);

        $dokumen = $this->dokumenModel->find($id);
        if (!$dokumen) throw PageNotFoundException::forPageNotFound($messageDokumenTidakDitemukan);

        $data = [
            'title'     => 'Edit Dokumen',
            'title_tab' => 'Edit Dokumen',
            'dokumen'   => $dokumen,
            'pesan'     => session()->getFlashdata('pesan') ?? []
        ];

        return view('dokumen/edit', $data);
    }

    // Update dokumen
    public function update($id = null)
    {
        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $messageIdNotFound = 'ID dokumen tidak valid';
        $messageDokumenTidakDitemukan = 'Dokumen tidak ditemukan';
        $messageBerhasil = 'Dokumen berhasil diubah';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $messageIdNotFound = service('translation')->translateCached('ID dokumen tidak valid', 'id', 'en');
            $messageDokumenTidakDitemukan = service('translation')->translateCached('Dokumen tidak ditemukan', 'id', 'en');
            $messageBerhasil = service('translation')->translateCached('Dokumen berhasil diubah', 'id', 'en');
        }
        if (!$id) return redirect()->to('/dokumen')->with('pesan', $messageIdNotFound);

        $dokumen = $this->dokumenModel->find($id);
        if (!$dokumen) return redirect()->to('/dokumen')->with('pesan', $messageDokumenTidakDitemukan);

        $nama = $this->request->getPost('nama_file');
        $updateData = ['nama_file' => $nama];

        $file = $this->request->getFile('file_dokumen');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $ext = strtolower($file->getExtension());
            $mime = $file->getMimeType();
            $allowedExts = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'zip', 'rar', 'png', 'jpg', 'jpeg', 'gif'];
            
            if (!in_array($ext, $allowedExts, true)) {
                return redirect()->to('/dokumen')->with('pesan', 'Ekstensi file tidak diizinkan.');
            }
            if ($file->getSize() > 10 * 1024 * 1024) {
                return redirect()->to('/dokumen')->with('pesan', 'Ukuran file maksimal 10MB.');
            }
            if (preg_match('/(php|html|htm|js|css|htaccess)/i', $ext) || strpos($mime, 'text/html') !== false || strpos($mime, 'application/x-httpd-php') !== false) {
                return redirect()->to('/dokumen')->with('pesan', 'Tipe file tidak valid.');
            }

            if (!empty($dokumen['file_path']) && file_exists(FCPATH . $dokumen['file_path'])) {
                unlink(FCPATH . $dokumen['file_path']);
            }
            $newName = $file->getRandomName();
            $file->move('uploads/dokumen', $newName);
            $updateData['file_path'] = 'uploads/dokumen/' . $newName;
        }

        $this->dokumenModel->update($id, $updateData);
        return redirect()->to('/dokumen')->with('pesan', $messageBerhasil);
    }

    // Soft delete dokumen
    public function delete($id = null)
    {
        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $messageIdNotFound = 'ID dokumen tidak valid';
        $messageDokumenTidakDitemukan = 'Dokumen tidak ditemukan';
        $messageBerhasil = 'Dokumen berhasil dihapus';


        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $messageIdNotFound = service('translation')->translateCached($messageIdNotFound, 'id', 'en');
            $messageDokumenTidakDitemukan = service('translation')->translateCached($messageDokumenTidakDitemukan, 'id', 'en');
            $messageBerhasil = service('translation')->translateCached($messageBerhasil, 'id', 'en');
        }
        if (!$id) return redirect()->to('/dokumen')->with('pesan', $message);

        $dokumen = $this->dokumenModel->find($id);
        if (!$dokumen) return redirect()->to('/dokumen')->with('pesan', $messageDokumenTidakDitemukan);

        $this->dokumenModel->delete($id); // soft delete
        return redirect()->to('/dokumen')->with('pesan', $messageBerhasil);
    }

    // Tampilkan dokumen trash
    public function trash()
    {
        $baseTitle = 'Edit Dokumen'; // kalau mau ID lebih "indo": 'Profil Staff'
        $title = $baseTitle;

        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }
        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $title = service('translation')->translateCached($baseTitle, 'id', 'en'); // V2 translate :contentReference[oaicite:0]{index=0}
        }
        $data = [
            'title'     => $title,
            'title_tab' => $title . ' &mdash; LPM UG',
            'dokumen'   => $this->dokumenModel->onlyDeleted()->findAll() ?? [],
            'pesan'     => session()->getFlashdata('pesan') ?? []
        ];

        return view('dokumen/trash', $data);
    }

    // Restore dokumen dari trash
    public function restore($id = null)
    {
        if (!$id) return redirect()->to('/dokumen/trash')->with('pesan', 'ID dokumen tidak valid');

        $dokumen = $this->dokumenModel->onlyDeleted()->find($id);
        if (!$dokumen) return redirect()->to('/dokumen/trash')->with('pesan', 'Dokumen tidak ditemukan');

        $this->dokumenModel->update($id, ['deleted_at' => null]);
        return redirect()->to('/dokumen/trash')->with('pesan', 'Dokumen berhasil dipulihkan');
    }
}

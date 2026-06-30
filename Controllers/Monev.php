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
use Google\Cloud\Translate\V2\TranslateClient;

class Monev extends ResourceController
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
    }

    public function index()
    {
        helper('auth');
        if (!in_array(userLogin()->role_id, [1, 2, 3, 4])) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $keyword = $this->request->getGet('keyword');
        $data = $this->abdimas->getPaginated(10, $keyword);
        $data['keyword'] = $keyword;
        $data['tags'] = $this->tags->getAnggota();
        $data['pesan'] = $this->pesan->getPesan();
        $data['mitra'] = $this->abdimas->getMitra();
        $data['anggota'] = $this->tags->getAnggota();
        $data['laporan'] = $this->abdimas->getAll();
        $data['mahasiswa'] = $this->mahasiswa->findAll();

        return view('abdimas/index_monev', $data);
    }

    public function edit($id = null)
    {
        helper('auth');
        if (!in_array(userLogin()->role_id, [1, 2, 3, 4, 6])) {
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

        $data['title_tab'] = 'Monitoring dan Evaluasi &mdash; LPM UG';
        $data['title'] = 'Monitoring dan Evaluasi';
        $data['pesan'] = $this->pesan->getPesan();

        $abdimas = $this->abdimas->getAbdimasWithBidangIlmu($id);

        if (!$abdimas) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Ambil mitra_id & periode_id dari data abdimas
        $mitraId   = $abdimas->mitra_id;
        $periodeId = $abdimas->periode_id;

        // Check if SKM file exists for the selected mitra and period
        if (!$this->checkSkmExists($mitraId, $periodeId)) {
            $mitraModel = new \App\Models\MitraModel();
            $mitra = $mitraModel->find($mitraId);
            $mitraName = $mitra ? esc($mitra->user_name) : ($lang === 'en' ? 'Partner' : 'Mitra');

            // Array terjemahan untuk pesan SKM
            $translations = [
                'id' => [
                    'skmNotUploaded' => 'SKM Belum Diupload!',
                    'skmMessage' => 'Mitra <strong>%s</strong> belum mengupload file SKM untuk periode yang dipilih.',
                    'solution' => 'Solusi:',
                    'solution1' => '1. Silakan hubungi mitra untuk mengupload file SKM terlebih dahulu',
                    'solution2' => '2. Atau pilih mitra lain yang sudah mengupload SKM',
                    'solution3' => '3. Pastikan periode yang dipilih sesuai dengan periode upload SKM',
                    'cannotProceed' => 'Pengusulan tidak dapat dilanjutkan sampai SKM tersedia.',
                ],
                'en' => [
                    'skmNotUploaded' => 'SKM Not Uploaded Yet!',
                    'skmMessage' => 'Partner <strong>%s</strong> has not uploaded the SKM file for the selected period.',
                    'solution' => 'Solution:',
                    'solution1' => '1. Please contact the partner to upload the SKM file first',
                    'solution2' => '2. Or select another partner who has uploaded SKM',
                    'solution3' => '3. Make sure the selected period matches the SKM upload period',
                    'cannotProceed' => 'Proposal cannot proceed until SKM is available.',
                ],
            ];

            // Helper function untuk translate
            $t = function (string $key, ...$args) use ($translations, $lang) {
                $text = $translations[$lang][$key] ?? $translations['id'][$key] ?? $key;
                return $args ? sprintf($text, ...$args) : $text;
            };

            $errorMessage =
                "<strong>{$t('skmNotUploaded')}</strong><br><br>" .
                sprintf($t('skmMessage'), $mitraName) . "<br><br>" .
                "<strong>{$t('solution')}</strong><br>" .
                "{$t('solution1')}<br>" .
                "{$t('solution2')}<br>" .
                "{$t('solution3')}<br><br>" .
                "<em>{$t('cannotProceed')}</em>";

            return redirect()->back()->withInput()->with('error', $errorMessage);
        }

        // Data untuk view
        $data['abdimas']    = $abdimas;
        $data['rekapan']    = $abdimas; // supaya view bisa akses $rekapan
        $data['dosen']      = $this->dosen->findAll();
        $data['anggota']    = $this->abdimas->getAnggota();
        $data['tags']       = $this->tags->getAnggota($id);
        $data['tagluaran']  = $this->tagluaran->getLuaran($id);
        $data['mitra']      = $this->mitra->getAll();
        $data['subprogram'] = $this->subprogram->getAll();
        $data['luaran']     = $this->luaran->findAll();
        $data['periode']    = $this->periode->findAll();
        $data['laporan']    = $this->abdimas->getAll();

        return view('abdimas/edit_monev', $data);
    }


    public function editAdmin($id = null)
    {
        if (!in_array(userLogin()->role_id, [1, 2, 3, 4, 6])) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $data['title_tab'] = 'Monitoring dan Evaluasi &mdash; LPM UG';
        $data['title'] = 'Monitoring dan Evaluasi';
        $data['pesan'] = $this->pesan->getPesan();

        $abdimas = $this->abdimas->getAbdimasWithBidangIlmu($id);

        if (!$abdimas) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // 🗓️ Parsing tanggal kegiatan
        $tanggalMulai = null;
        $tanggalSelesai = null;

        if (!empty($abdimas->tanggal_kegiatan)) {
            $tanggalArray = explode(' - ', $abdimas->tanggal_kegiatan);
            $tanggalMulai = trim($tanggalArray[0] ?? '');
            $tanggalSelesai = trim($tanggalArray[1] ?? '');
        }

        $data['abdimas']         = $abdimas;
        $data['rekapan']         = $abdimas;
        $data['dosen']           = $this->dosen->findAll();
        $data['anggota']         = $this->abdimas->getAnggota();
        $data['tags']            = $this->tags->getAnggota($id);
        $data['tagluaran']       = $this->tagluaran->getLuaran($id);
        $data['mitra']           = $this->mitra->getAll();
        $data['subprogram']      = $this->subprogram->getAll();
        $data['luaran']          = $this->luaran->findAll();
        $data['periode']         = $this->periode->findAll();
        $data['laporan']         = $this->abdimas->getAll();
        $data['tanggal_mulai']   = $tanggalMulai;
        $data['tanggal_selesai'] = $tanggalSelesai;

        return view('abdimas/edit_monev_admin', $data);
    }

    public function update($id = null)
    {
        $abdimas = $this->abdimas->getAbdimasWithBidangIlmu($id);

        $data = [
            'nt1'   => $this->request->getVar('nt1'),
            'nt2'   => $this->request->getVar('nt2'),
            'nt3'   => $this->request->getVar('nt3'),
            'nt4'   => $this->request->getVar('nt4'),
            'nt5'   => $this->request->getVar('nt5'),
            'nt6'   => $this->request->getVar('nt6'),
            'nt7'   => $this->request->getVar('nt7'),
            'nt8'   => $this->request->getVar('nt8'),
            'nt9'   => $this->request->getVar('nt9'),
            'nlpm1' => $this->request->getVar('nlpm1'),
            'nlpm2' => $this->request->getVar('nlpm2'),
            'nlpm3' => $this->request->getVar('nlpm3'),
            'nlpm4' => $this->request->getVar('nlpm4'),
            'nlpm5' => $this->request->getVar('nlpm5'),
            'nlpm6' => $this->request->getVar('nlpm6'),
            'nlpm7' => $this->request->getVar('nlpm7'),
            'nlpm8' => $this->request->getVar('nlpm8'),
            'nlpm9' => $this->request->getVar('nlpm9'),
            'saran' => $this->request->getVar('saran'),
        ];

        // Handle upload file SKM
        $file = $this->request->getFile('skm');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Validate size (max 10MB)
            if ($file->getSize() > 10 * 1024 * 1024) {
                return redirect()->back()->withInput()->with('error', 'Ukuran file SKM maksimal 10MB.');
            }
            // Validate extension and MIME type
            if (strtolower($file->getExtension()) !== 'pdf' || $file->getMimeType() !== 'application/pdf') {
                return redirect()->back()->withInput()->with('error', 'File SKM harus berformat PDF.');
            }

            $newName = $file->getRandomName();
            $uploadPath = WRITEPATH . 'berkas/skm/';

            // bikin folder kalau belum ada
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $file->move($uploadPath, $newName);
            $data['skm'] = $newName;

            // hapus file lama jika ada
            if (!empty($abdimas->skm) && file_exists($uploadPath . $abdimas->skm)) {
                unlink($uploadPath . $abdimas->skm);
            }
        }

        $this->abdimas->update($id, $data);

        return redirect()->to(site_url('monev'))->with('success', 'Data berhasil diupdate.');
    }

    private function checkSkmExists($mitraId, $periodeId)
    {
        $db = \Config\Database::connect();

        // 1. Cek di tbl_dokumen_mitra (data baru yang diupload mitra)
        $builderDoc = $db->table('tbl_dokumen_mitra');
        $builderDoc->where('mitra_id', $mitraId);
        $builderDoc->where('periode_id', $periodeId);
        $builderDoc->where('doc_type', 'skm');
        $builderDoc->where('file_path IS NOT NULL');
        $builderDoc->where('file_path !=', '');
        if ($builderDoc->countAllResults() > 0) {
            return true;
        }

        // 2. Cek di tbl_laporan (data lama/fallback)
        $builderLap = $db->table('tbl_laporan');
        $builderLap->where('mitra_id', $mitraId);
        $builderLap->where('periode_id', $periodeId);
        $builderLap->where('skm IS NOT NULL');
        $builderLap->where('skm !=', '');
        if ($builderLap->countAllResults() > 0) {
            return true;
        }

        return false;
    }


    public function delete($id = null)
    {
        $abdimas = $this->abdimas->getAbdimasWithBidangIlmu($id);
        if ($abdimas) {
            $uploadPath = WRITEPATH . 'berkas/skm/';

            // hapus file skm juga
            if (!empty($abdimas->skm) && file_exists($uploadPath . $abdimas->skm)) {
                unlink($uploadPath . $abdimas->skm);
            }

            $this->abdimas->delete($id);
        }
        return redirect()->to(site_url('monev'))->with('success', 'Data berhasil dihapus.');
    }
}

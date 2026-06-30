<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

use App\Models\HakAksesModel;
use App\Models\PenggunaModel;
use App\Models\PesanModel;
use App\Models\JurusanModel;
use App\Models\FungsionalModel;
use Google\Cloud\Translate\V2\TranslateClient;

class Pengguna extends ResourceController
{
    function __construct()
    {
        $this->hak_akses    = new HakAksesModel();
        $this->pengguna     = new PenggunaModel();
        $this->pesan        = new PesanModel();
        $this->jurusan      = new JurusanModel();
        $this->fungsional   = new FungsionalModel();
    }
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        helper('auth');
        helper('cookie');

        $user = \userLogin();
        if (!$user) {
            return redirect()->to(site_url('login'));
        }
        if (!in_array((int) $user->role_id, [1, 2], true)) {
            return redirect()->to(site_url('dashboard'));
        }

        $keyword = $this->request->getGet('keyword');

        $data = $this->pengguna->getPaginated(100000, $keyword);
        $data['keyword'] = $keyword;
        $data['pesan']   = $this->pesan->getPesan();
        $data['dosen']   = $data['pengguna'];

        $baseTitle = 'Semua Pengguna'; // kalau mau ID lebih "indo": 'Profil Staff'
        $title = $baseTitle;

        $allowed = ['id', 'en'];

        $lang = $this->request->getCookie('lang') ?? 'id';

        if (!in_array($lang, $allowed, true)) {
            $lang = 'id';
        }

        $reqLang = $this->request->getGet('lang');
        if ($reqLang && in_array($reqLang, $allowed, true)) {
            set_cookie('lang', $reqLang, 60 * 60 * 24 * 30);
            $lang = $reqLang;
        }

        if ($lang === 'en') {
            $title = service('translation')->translateCached($baseTitle, 'id', 'en');
        }
        $data['title'] = $title;
        $data['title_tab'] = $title . ' &mdash; LPM UG';
        $data['lang']      = $lang; // opsional kalau view butuh

        return view('pengguna/index', $data);
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
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }
        // session();
        $baseTitle = 'Tambah Pengguna'; // kalau mau ID lebih "indo": 'Profil Staff'
        $title = $baseTitle;

        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }
        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $title = service('translation')->translateCached($baseTitle, 'id', 'en'); // V2 translate :contentReference[oaicite:0]{index=0}
        }
        $data['title'] = $title;
        $data['title_tab'] = $title . ' &mdash; LPM UG';
        $data['pesan'] = $this->pesan->getPesan();
        $data['validation'] = \Config\Services::validation();

        $data['hak_akses'] = $this->hak_akses->findAll();
        $data['jurusan'] = $this->jurusan->getAll();
        $data['fungsional'] = $this->fungsional->findAll();

        return view('pengguna/new', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        // $data = $this->request->getPost();

        if (!$this->validate([
            'user_name' => [
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'Nama user tidak boleh kosong.',
                    'min_length' => 'Nama minimal 3 huruf'
                ],
            ],
            'nidn' => [
                'rules' => 'required|is_unique[tbl_users.nidn]',
                'errors' => [
                    'required' => 'NIDN tidak boleh kosong.',
                    'is_unique' => 'NIDN sudah terdaftar, silahkan masukan NIDN yang berbeda.'
                ],
            ],
        ])) {
            $validation = \Config\Services::validation();
            // dd($validation);
            return redirect()->back()->withInput()->with('validation', $validation);
        }

        $data = [
            'user_name' => $this->request->getVar('user_name'),
            'kontak' => $this->request->getVar('kontak'),
            'nidn' => $this->request->getVar('nidn'),
            'password' => password_hash($this->request->getVar('password'), PASSWORD_BCRYPT),
            'role_id' => $this->request->getVar('role_id'),
            'status' => 1,
        ];
        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $message = 'Data anda berhasil diupdate';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $message = service('translation')->translateCached('Data anda berhasil diupdate', 'id', 'en');
        }


        $this->pengguna->insert($data);
        return redirect()->to(site_url('pengguna'))->with('success', $message);
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

        $baseTitle = 'Edit Pengguna'; // kalau mau ID lebih "indo": 'Profil Staff'
        $title = $baseTitle;

        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }
        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $title = service('translation')->translateCached($baseTitle, 'id', 'en'); // V2 translate :contentReference[oaicite:0]{index=0}
        }
        $data['title'] = $title;
        $data['title_tab'] = $title . ' &mdash; LPM UG';
        $data['pesan'] = $this->pesan->getPesan();

        $pengguna =  $this->pengguna->find($id);
        if (is_object($pengguna)) {
            $data['pengguna'] = $pengguna;
            $data['dosen']    = $pengguna;
            $data['hak_akses'] = $this->hak_akses->findAll();
            $data['jurusan'] = $this->jurusan->getAll();
            $data['fungsional'] = $this->fungsional->findAll();
            return view('pengguna/edit', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('pengguna/edit', $data);
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $data = [
            'user_name' => $this->request->getVar('user_name'),
            'kontak'    => $this->request->getVar('kontak'),
            'nidn'      => $this->request->getVar('nidn'),
            'role_id'   => $this->request->getVar('role_id'),
            'status'    => $this->request->getVar('status'),
        ];
        $this->pengguna->update($id, $data);
        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $message = 'Data anda berhasil diupdate';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $message = service('translation')->translateCached('Data anda berhasil diupdate', 'id', 'en');
        }
        return redirect()->to(site_url('pengguna'))->with('success', $message);
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $this->pengguna->delete($id);
        $lang = $this->request->getCookie('lang') ?? 'id';
        if (! in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $message = 'Data anda berhasil diupdate';

        // ===== Translate jika EN =====
        if ($lang === 'en') {
            $message = service('translation')->translateCached('Data anda berhasil diupdate', 'id', 'en');
        }
        return redirect()->to(site_url('pengguna'))->with('success', $message);
    }

    /**
     * Update password pengguna (dipanggil dari modal di halaman list)
     */
    public function updatePassword($id = null)
    {
        helper('auth');
        if (!in_array((int) userLogin()->role_id, [1, 2], true)) {
            return redirect()->to(site_url('pengguna'))->with('error', 'Anda tidak memiliki akses untuk mengubah password.');
        }

        $pengguna = $this->pengguna->find($id);
        if (!$pengguna) {
            return redirect()->to(site_url('pengguna'))->with('error', 'Data pengguna tidak ditemukan.');
        }

        $newPassword     = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        if (empty($newPassword) || strlen($newPassword) < 6) {
            return redirect()->back()->with('error', 'Password minimal 6 karakter.');
        }

        if ($newPassword !== $confirmPassword) {
            return redirect()->back()->with('error', 'Password dan konfirmasi tidak cocok.');
        }

        $this->pengguna->update($id, [
            'password' => password_hash($newPassword, PASSWORD_BCRYPT),
        ]);

        $lang = $this->request->getCookie('lang') ?? 'id';
        $name = esc($pengguna->user_name);
        $message = ($lang === 'en')
            ? 'Password for "' . $name . '" has been updated successfully.'
            : 'Password pengguna "' . $name . '" berhasil diperbarui.';

        return redirect()->to(site_url('pengguna'))->with('success', $message);
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

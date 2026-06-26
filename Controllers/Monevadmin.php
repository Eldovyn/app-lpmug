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
use App\Models\RekapanModel;
use App\Models\MahasiswaModel;

class Monevadmin extends ResourceController
{
    protected $abdimas;
    protected $tags;
    protected $tagluaran;
    protected $dosen;
    protected $mitra;
    protected $subprogram;
    protected $luaran;
    protected $periode;
    protected $pesan;
    protected $rekapan; // Tambahan
    protected $mahasiswa;

    public function __construct()
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
        $this->rekapan      = new RekapanModel();
        $this->mahasiswa    = new MahasiswaModel();
    }

    public function index()
    {
        if (!in_array(userLogin()->role_id, [1, 2, 3, 4, 6])) {
            return redirect()->to(site_url('dashboard'));
        } elseif (empty(userLogin()->role_id)) {
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

        return view('abdimas/index_monev', $data);
    }

    public function update($id = null)
    {
        helper('auth');

        if (!in_array(userLogin()->role_id, [1, 2, 3, 4, 6])) {
            return redirect()->to(site_url('dashboard'));
        }

        $laporan_id = $id;
        $post = $this->request->getPost();

        $max = [
            'nlpm1' => 30,
            'nlpm2' => 20,
            'nlpm3' => 10,
            'nlpm4' => 20,
            'nlpm5' => 20,
            'nlpm6' => 40,
            'nlpm7' => 60,
            'nlpm8' => 40,
            'nlpm9' => 60,
        ];

        // VALIDASI
        $rules = [];
        foreach ($max as $field => $mx) {
            // hanya buat rules kalau field ada di POST
            if (!isset($post[$field])) continue;

            $rules[$field] = "permit_empty|integer|greater_than_equal_to[1]|less_than_equal_to[$mx]";
        }

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // ======================
        // BUILD DATA UPDATE
        // ======================
        $dataAbdimas = [];

        // prepare NLPM1..9
        foreach ($max as $field => $mx) {
            if (!isset($post[$field])) continue;

            $val = trim((string)$post[$field]);
            if ($val === '') continue;

            $n = (int)$val;
            $n = max(1, min($n, $mx)); // clamp

            // simpan ke array yang akan di-update ke abdimas
            $dataAbdimas[$field] = $n;
        }

        // isi saran_masukan kalau ada
        if (isset($post['saran_masukan'])) {
            $text = trim((string)$post['saran_masukan']);
            if ($text !== '') {
                $dataAbdimas['saran_masukan'] = $text;
            }
        }

        // ======================
        // SIMPAN KE DATABASE
        // ======================

        // hanya simpan ke abdimas kalau ada NLPM atau saran_masukan
        if (!empty($dataAbdimas)) {
            $this->abdimas->update($laporan_id, $dataAbdimas);
        }

        return redirect()->to(site_url('rekapan'))
            ->with('success', 'Nilai MONEV berhasil disimpan.');
    }


    public function edit($id = null)
    {
        helper('auth');
        if (!in_array(userLogin()->role_id, [1, 2, 3, 4, 6])) {
            return redirect()->to(site_url('dashboard'));
        } elseif (empty(userLogin()->role_id)) {
            return redirect()->to(site_url('login'));
        }

        $abdimas = $this->abdimas->find($id);
        $rekapan = $this->rekapan->find($id);

        if (!$abdimas) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title_tab' => 'Monitoring dan Evaluasi &mdash; LPM UG',
            'title'     => 'Monitoring dan Evaluasi',
            'pesan'     => $this->pesan->getPesan(),
            'abdimas'   => $abdimas,
            'rekapan'   => $rekapan, // Pastikan dikirim ke view
            'dosen'     => $this->dosen->findAll(),
            'anggota'   => $this->abdimas->getAnggota(),
            'tags'      => $this->tags->getAnggota($id),
            'tagluaran' => $this->tagluaran->getLuaran($id),
            'mahasiswa' => $this->mahasiswa->getByLaporan($id),
            'mitra'     => $this->mitra->getAll(),
            'subprogram' => $this->subprogram->getAll(),
            'luaran'    => $this->luaran->findAll(),
            'periode'   => $this->periode->findAll(),
            'laporan'   => $this->abdimas->getAll()
        ];

        return view('abdimas/edit_monev_admin', $data);
    }
}

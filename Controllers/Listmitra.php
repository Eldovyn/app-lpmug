<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

use App\Models\MitraModel;
use App\Models\PesanModel;
use App\Models\ProvinsiModel;
use App\Models\KotaModel;

class Listmitra extends ResourceController
{
    function __construct()
    {
        $this->mitra        = new MitraModel();
        $this->pesan        = new PesanModel();
        $this->provinsi     = new ProvinsiModel();
        $this->kota         = new KotaModel();
    }
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        if (userLogin()->role_id != 4) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $keyword = $this->request->getGet('keyword');

        $data = $this->mitra->getPaginated(10, $keyword);
        $data['keyword'] = $keyword;
        // dd($data);
        return view('listmitra/index', $data);
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
            $lang = $this->request->getCookie('lang') ?? 'id';
        }
        if (!in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        // Set cookie if lang from query param
        if ($this->request->getGet('lang')) {
            set_cookie('lang', $lang, 60 * 60 * 24 * 30);
        }

        $titleDict = [
            'id' => 'Daftarkan Mitra',
            'en' => 'Register Partner'
        ];
        $title = $titleDict[$lang] ?? 'Daftarkan Mitra';

        $data['title_tab'] = $title . ' — LPM UG';
        $data['title'] = $title;
        $data['pesan'] = $this->pesan->getPesan();
        $data['validation'] = \Config\Services::validation();

        // $data['hak_akses'] = $this->hak_akses->findAll();
        $data['kota'] = $this->kota->getAll();

        return view('listmitra/new', $data);
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
            'password'      => password_hash($this->request->getVar('password'), PASSWORD_BCRYPT),
            'role_id'       => 5,
            'status'        => 1,
        ];


        $this->mitra->insert($data);

        // Language support for flash message - check query param first, then cookie, default to id
        $lang = $this->request->getGet('lang');
        if (! $lang) {
            $lang = $this->request->getCookie('lang') ?? 'id';
        }
        if (!in_array($lang, ['id', 'en'], true)) {
            $lang = 'id';
        }

        $successMsg = ($lang === 'en') ? 'New data has been successfully saved.' : 'Data baru anda berhasil disimpan.';

        return redirect()->to(site_url('listmitra'))->with('success', $successMsg);
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        //
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        //
    }
}

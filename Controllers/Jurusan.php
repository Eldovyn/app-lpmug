<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

use App\Models\FakultasModel;
use App\Models\JurusanModel;
use App\Models\PesanModel;

class Jurusan extends ResourceController
{
    function __construct()
    {
        $this->fakultas     = new FakultasModel();
        $this->jurusan      = new JurusanModel();
        $this->pesan        = new PesanModel();
    }
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        helper('auth');
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2 && userLogin()->role_id != 3) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $keyword = $this->request->getGet('keyword');

        $data = $this->jurusan->getPaginated(10, $keyword);
        $data['keyword'] = $keyword;
        $data['pesan'] = $this->pesan->getPesan();
        return view('jurusan/index', $data);
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
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2 && userLogin()->role_id != 3) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $data['title_tab'] = 'Tambah jurusan &mdash; LPM UG';
        $data['title'] = 'Tambah jurusan';
        $data['pesan'] = $this->pesan->getPesan();

        $data['fakultas'] = $this->fakultas->findAll();

        return view('jurusan/new', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $data = $this->request->getPost();
        $this->jurusan->insert($data);
        return redirect()->to(site_url('jurusan'))->with('success', 'Data baru anda berhasil disimpan.');
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2 && userLogin()->role_id != 3) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $data['title_tab'] = 'Edit jurusan &mdash; LPM UG';
        $data['title'] = 'Edit jurusan';
        $data['pesan'] = $this->pesan->getPesan();

        $jurusan =  $this->jurusan->find($id);
        if (is_object($jurusan)) {
            $data['jurusan'] = $jurusan;
            $data['fakultas'] = $this->fakultas->findAll();
            return view('jurusan/edit', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('jurusan/edit', $data);
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $data = $this->request->getPost();
        $this->jurusan->update($id, $data);
        return redirect()->to(site_url('jurusan'))->with('success', 'Data anda berhasil diupdate.');
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2 && userLogin()->role_id != 3) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $this->jurusan->delete($id);
        return redirect()->to(site_url('jurusan'))->with('success', 'Data anda berhasil dihapus.');
    }
}

<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

use App\Models\PesanModel;

class Pesan extends ResourceController
{
    function __construct() {
        $this->pesan = new PesanModel();
    }
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        if(userLogin()->role_id != 1 && userLogin()->role_id != 2){
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $keyword = $this->request->getGet('keyword');
        $data = $this->pesan->getPaginated(10, $keyword);
        $data['keyword'] = $keyword;
        $data['pesan'] = $this->pesan->getPesan();
        return view('pesan/index', $data);
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
        if(userLogin()->role_id != 1 && userLogin()->role_id != 2){
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $data['title_tab'] = 'Tambah pesan &mdash; LPM UG';
        $data['title'] = 'Tambah pesan';
        $data['pesan'] = $this->pesan->getPesan();

        return view('pesan/new',$data);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $data = $this->request->getPost();
        $this->pesan->insert($data);
        return redirect()->to(site_url('pesan'))->with('success', 'Data baru anda berhasil disimpan.');
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        if(userLogin()->role_id != 1 && userLogin()->role_id != 2){
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $data['title_tab'] = 'Edit pesan &mdash; LPM UG';
        $data['title'] = 'Edit pesan';
        $data['pesan'] = $this->pesan->getPesan();

        $pesan = $this->pesan->where('pesan_id', $id)->first();
        if(is_object($pesan)) {
            $data['message'] = $pesan;
            return view('pesan/edit', $data);
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
        $data = $this->request->getPost();
        $this->pesan->update($id, $data);
        return redirect()->to(site_url('pesan'))->with('success', 'Data anda berhasil diupdate.');
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        if(userLogin()->role_id != 1 && userLogin()->role_id != 2){
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }
        
        $this->pesan->delete($id);
        return redirect()->to(site_url('pesan'))->with('success', 'Data anda berhasil dihapus.');
    }
}

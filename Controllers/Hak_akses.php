<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

use App\Models\HakAksesModel;
use App\Models\PesanModel;

class Hak_akses extends ResourceController
{
    function __construct() {
        $this->hak_akses = new HakAksesModel();
        $this->pesan        = new PesanModel();
    }
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        if(userLogin()->role_id != 1){
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $keyword = $this->request->getGet('keyword');
        $data = $this->hak_akses->getPaginated(10, $keyword);
        $data['keyword'] = $keyword;
        $data['pesan'] = $this->pesan->getPesan();
        return view('hak_akses/index', $data);
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
        if(userLogin()->role_id != 1){
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $data['title_tab'] = 'Tambah hak akses &mdash; LPM UG';
        $data['title'] = 'Tambah hak akses';
        $data['pesan'] = $this->pesan->getPesan();

        return view('hak_akses/new',$data);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $data = $this->request->getPost();
        $this->hak_akses->insert($data);
        return redirect()->to(site_url('hak_akses'))->with('success', 'Data baru anda berhasil disimpan.');
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        if(userLogin()->role_id != 1){
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $data['title_tab'] = 'Edit hak_akses &mdash; LPM UG';
        $data['title'] = 'Edit hak_akses';
        $data['pesan'] = $this->pesan->getPesan();

        $hak_akses = $this->hak_akses->where('role_id', $id)->first();
        if(is_object($hak_akses)) {
            $data['hak_akses'] = $hak_akses;
            return view('hak_akses/edit', $data);
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
        $this->hak_akses->update($id, $data);
        return redirect()->to(site_url('hak_akses'))->with('success', 'Data anda berhasil diupdate.');
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        if(userLogin()->role_id != 1){
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }
        
        $this->hak_akses->delete($id);
        return redirect()->to(site_url('hak_akses'))->with('success', 'Data anda berhasil dihapus.');
    }
}

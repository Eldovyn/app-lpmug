<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

use App\Models\StrukturModel;
use App\Models\PesanModel;

class Struktur extends ResourceController
{
    function __construct() {
        $this->struktur = new StrukturModel();
        $this->pesan        = new PesanModel();
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
        $data = $this->struktur->getPaginated(10, $keyword);
        $data['keyword'] = $keyword;
        $data['pesan'] = $this->pesan->getPesan();
        return view('struktur/index', $data);
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
        helper('form');
        if(userLogin()->role_id != 1 && userLogin()->role_id != 2){
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }
        $data['title_tab'] = 'Tambah profile &mdash; LPM UG';
        $data['title'] = 'Tambah profile';
        $data['pesan'] = $this->pesan->getPesan();

        // $data['validation'] = \Config\Services::validation();

        $data['struktur'] = $this->struktur->findAll();

        return view('struktur/new',$data);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        helper('form');
        // if(!$this->validate($this->struktur->getValidationRules())) {
        //     $validation = \Config\Services::validation();
        //     // dd($validation);
        //     return redirect()->back()->withInput()->with('validation', $validation);
        // }

        $file = $this->request->getFile('gambar');
        $namaGambar = '';
        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $ext = strtolower($file->getExtension());
            $mime = $file->getMimeType();
            $allowedExts = ['png', 'jpg', 'jpeg', 'gif', 'webp'];
            if (!in_array($ext, $allowedExts, true) || strpos($mime, 'image/') !== 0) {
                return redirect()->back()->withInput()->with('error', 'File harus berupa gambar (png, jpg, jpeg, gif, webp).');
            }
            if ($file->getSize() > 5 * 1024 * 1024) {
                return redirect()->back()->withInput()->with('error', 'Ukuran gambar maksimal 5MB.');
            }

            $namaGambar = $file->getRandomName();
            $file->move('img/upload/struktur', $namaGambar);
        }

        $data = [
            'judul'     => $this->request->getPost('judul'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'gambar'    => $namaGambar,
        ];
        // dd($data);

        $this->struktur->save($data);
        return redirect()->to(site_url('struktur'))->with('success', 'Data baru anda berhasil disimpan.');
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

        $data['title_tab'] = 'Edit Profile &mdash; LPM UG';
        $data['title'] = 'Edit Profile';
        $data['pesan'] = $this->pesan->getPesan();

        $struktur =  $this->struktur->find($id);
        if(is_object($struktur)) {
            $data['struktur'] = $struktur;
            return view('struktur/edit', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('struktur/edit', $data);
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $struktur = $this->struktur->find($id);
        $old_img_name = $struktur->gambar;

        $file = $this->request->getFile('gambar');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $ext = strtolower($file->getExtension());
            $mime = $file->getMimeType();
            $allowedExts = ['png', 'jpg', 'jpeg', 'gif', 'webp'];
            if (!in_array($ext, $allowedExts, true) || strpos($mime, 'image/') !== 0) {
                return redirect()->back()->withInput()->with('error', 'File harus berupa gambar (png, jpg, jpeg, gif, webp).');
            }
            if ($file->getSize() > 5 * 1024 * 1024) {
                return redirect()->back()->withInput()->with('error', 'Ukuran gambar maksimal 5MB.');
            }

            if (!empty($old_img_name) && file_exists('img/upload/struktur/' . $old_img_name)) {
                unlink('img/upload/struktur/' . $old_img_name);
            }

            $namaGambar = $file->getRandomName();
            $file->move('img/upload/struktur', $namaGambar);
        } else {
            $namaGambar = $old_img_name;
        }

        $data = [
            'judul'     => $this->request->getPost('judul'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'gambar'    => $namaGambar,
        ];

        $this->struktur->update($id, $data);
        return redirect()->to(site_url('struktur'))->with('success', 'Data anda berhasil diupdate.');
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        if(userLogin()->role_id != 1 && userLogin()->role_id != 2 && userLogin()->role_id != 3){
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $struktur = $this->struktur->find($id);
        $namaGambar = $struktur->gambar;
        if (!empty($namaGambar) && file_exists('img/upload/struktur/' . $namaGambar)) {
            unlink('img/upload/struktur/' . $namaGambar);
        }
        
        $this->struktur->delete($id);
        return redirect()->to(site_url('struktur'))->with('success', 'Data anda berhasil dihapus.');
    }
}

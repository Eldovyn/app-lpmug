<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourcePresenter;
use App\Models\ProvinsiModel;
use App\Models\PesanModel;

class Provinsi extends ResourcePresenter
{
    function __construct()
    {
        $this->pesan        = new PesanModel();
        $this->provinsi     = new ProvinsiModel();
    }

    protected $modelName = 'App\Models\ProvinsiModel';

    // protected $helpers = ['custom'];
    /**
     * Present a view of resource objects
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
        $data = $this->provinsi->getPaginated(10, $keyword);
        $data['keyword'] = $keyword;
        $data['pesan'] = $this->pesan->getPesan();

        // $data['provinsi'] = $this->provinsi->findAll();
        // $data['provinsi'] = $this->model->findAll();

        return view('provinsi/index', $data);
    }

    /**
     * Present a view to present a specific resource object
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function show($id = null)
    {
        // UNTUK DETAIL
    }

    /**
     * Present a view to present a new single resource object
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

        $data['title_tab'] = 'Provinsi &mdash; LPM UG';
        $data['title'] = 'Provinsi';
        $data['pesan'] = $this->pesan->getPesan();

        return view('provinsi/new', $data);
    }

    /**
     * Process the creation/insertion of a new resource object.
     * This should be a POST.
     *
     * @return mixed
     */
    public function create()
    {
        $data = $this->request->getPost();
        $this->model->insert($data);
        return redirect()->to(site_url('provinsi'))->with('success', 'Data baru anda berhasil disimpan.');
    }

    /**
     * Present a view to edit the properties of a specific resource object
     *
     * @param mixed $id
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

        $data['title_tab'] = 'Edit Provinsi &mdash; LPM UG';
        $data['title'] = 'Edit Provinsi';
        $data['pesan'] = $this->pesan->getPesan();


        $provinsi = $this->model->where('provinsi_id', $id)->first();
        if (is_object($provinsi)) {
            $data['provinsi'] = $provinsi;
            return view('provinsi/edit', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    /**
     * Process the updating, full or partial, of a specific resource object.
     * This should be a POST.
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $data = $this->request->getPost();
        $this->model->update($id, $data);
        return redirect()->to(site_url('provinsi'))->with('success', 'Data anda berhasil diupdate.');
    }

    /**
     * Present a view to confirm the deletion of a specific resource object
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function remove($id = null)
    {
        //
    }

    /**
     * Process the deletion of a specific resource object
     *
     * @param mixed $id
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
        // $this->model->where('provinsi_id', $id)->delete();
        $this->model->delete($id);
        return redirect()->to(site_url('provinsi'))->with('success', 'Data anda berhasil dihapus.');
    }

    public function trash()
    {
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2 && userLogin()->role_id != 3) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $data['title_tab'] = 'Sampah &mdash; LPM UG';
        $data['title'] = 'Data yang dihapus';
        $data['pesan'] = $this->pesan->getPesan();

        // $data['provinsi'] = $this->provinsi->findAll();
        $data['provinsi'] = $this->model->onlyDeleted()->findAll();

        return view('provinsi/trash', $data);
    }

    public function restore($id = null)
    {
        $this->db = \Config\Database::connect();
        if ($id != null) {
            // $this->model->update($id, ['deleted_at' => null]);
            $this->db->table('tbl_provinsi')
                ->set('deleted_at', null, true)
                ->where(['provinsi_id' => $id])
                ->update();
        } else {
            $this->db->table('tbl_provinsi')
                ->set('deleted_at', null, true)
                ->where('deleted_at is NOT NULL', NULL, FALSE)
                ->update();
        }
        if ($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('provinsi'))->with('success', 'Data anda berhasil direstore.');
        }
    }

    public function delete2($id = null)
    {
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2 && userLogin()->role_id != 3) {
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        if ($id != null) {
            $this->model->delete($id, true);
            return redirect()->to(site_url('provinsi/trash'))->with('success', 'Data anda berhasil dihapus permanent.');
        } else {
            $this->model->purgeDeleted();
            return redirect()->to(site_url('provinsi/trash'))->with('success', 'Data trash berhasil dihapus permanent.');
        }
    }
}

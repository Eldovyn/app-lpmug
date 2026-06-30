<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

use App\Models\AbdimasModel;
use App\Models\UndanganModel;
use App\Models\TagsModel;
use App\Models\TagluaranModel;
use App\Models\DosenModel;
use App\Models\MitraModel;
use App\Models\SubprogramModel;
use App\Models\LuaranModel;
use App\Models\PeriodeModel;
use App\Models\PesanModel;

class Undangan extends ResourceController
{
    function __construct() {
        $this->abdimas      = new AbdimasModel();
        $this->undangan     = new UndanganModel();
        $this->tags         = new TagsModel();
        $this->tagluaran    = new TagluaranModel();
        $this->dosen        = new DosenModel();
        $this->mitra        = new MitraModel();
        $this->subprogram   = new SubprogramModel();
        $this->luaran       = new LuaranModel();
        $this->periode      = new PeriodeModel();
        $this->pesan        = new PesanModel();
    }
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        if(userLogin()->role_id != 1 && userLogin()->role_id != 2 && userLogin()->role_id != 3 && userLogin()->role_id != 4){
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $keyword = $this->request->getGet('keyword');
        $data = $this->undangan->getPaginated(10, $keyword);
        $data['keyword'] = $keyword;
        $data['tags'] = $this->tags->getAnggota();
        $data['pesan'] = $this->pesan->getPesan();
        $data['mitra'] = $this->abdimas->getMitra();
        $data['anggota'] = $this->tags->getAnggota();
        $data['laporan'] = $this->abdimas->getAll();
        
        return view('abdimas/undangan', $data);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        if(userLogin()->role_id != 1 && userLogin()->role_id != 2 && userLogin()->role_id != 3 && userLogin()->role_id != 4){
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $data['title_tab'] = 'Detail Laporan &mdash; LPM UG';
        $data['title'] = 'Detail Laporan';
        $data['pesan'] = $this->pesan->getPesan();

        $abdimas =  $this->abdimas->find($id);
        if(is_object($abdimas)) {
            $data['abdimas']    = $abdimas;
            $data['dosen']      = $this->dosen->findAll();
            $data['anggota']    = $this->abdimas->getAnggota();
            $data['tags']       = $this->tags->getAnggota($id);
            $data['tagluaran']  = $this->tagluaran->getLuaran($id);
            $data['mitra']      = $this->mitra->getAll();
            $data['subprogram'] = $this->subprogram->getAll();
            $data['luaran']     = $this->luaran->findAll();
            $data['periode']    = $this->periode->findAll();
            $data['pesan']      = $this->pesan->getPesan();
            $data['laporan'] = $this->abdimas->getAll();
            return view('abdimas/show', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('abdimas/show', $data);
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        if(userLogin()->role_id != 1 && userLogin()->role_id != 2 && userLogin()->role_id != 3 && userLogin()->role_id != 4){
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $data['title_tab'] = 'Registrasi Abdimas &mdash; LPM UG';
        $data['title'] = 'Registrasi Abdimas';
        $data['validation'] = \Config\Services::validation();
        
        // $data['dosen']      = $this->dosen->findAll();
        $data['dosen']      = $this->dosen->getDosen();
        $data['mitra']      = $this->mitra->getAll();
        $data['subprogram'] = $this->subprogram->getAll();
        $data['luaran']     = $this->luaran->findAll();
        $data['periode']    = $this->periode->findAll();
        $data['pesan']      = $this->pesan->getPesan();

        return view('abdimas/new',$data);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $db      = \Config\Database::connect();
        // $builder = $db->table('tbl_laporan');

        $rules = [
            'syarat' => [
                'rules' => 'required',
                'errors'=> [
                    'required'   => 'Silahkan menyutujui syarat dan ketentuan yang berlaku.'
                ],
            ],
        ];

         if(!$this->validate($rules)) {
            $validation = \Config\Services::validation();
            // dd($validation);
            return redirect()->back()->withInput()->with('validation', $validation);
        }

        $data = [
            'ketua_id'      => userLogin()->user_id,
            'mitra_id'      => $this->request->getVar('mitra_id'),
            'subprogram_id' => $this->request->getVar('subprogram_id'),
            // 'luaran_id'     => $this->request->getVar('luaran_id'),
            'periode_id'    => $this->request->getVar('periode_id'),
            'tipe_kegiatan' => $this->request->getVar('tipe_kegiatan'),
            'range_dana'    => $this->request->getVar('range_dana'),
            'verifikasi'    => 0,
        ];

        $this->abdimas->insert($data);

        $laporan_id = $db->insertID();

        $anggota_id = count($this->request->getVar('anggota_id'));
        $luaran_id = count($this->request->getVar('luaran_id'));

        for($i = 0; $i < $anggota_id; $i++){
            $datas[$i] = [
                'laporan_id' => $laporan_id,
                'anggota_id' => $this->request->getVar('anggota_id['.$i.']'),
            ];
            $this->tags->insert($datas[$i]);
        }

        for($j = 0; $j < $luaran_id; $j++){
            $datas[$j] = [
                'laporan_id' => $laporan_id,
                'luaran_id' => $this->request->getVar('luaran_id['.$j.']'),
            ];
            $this->tagluaran->insert($datas[$j]);
        }
        return redirect()->to(site_url('abdimas'))->with('success', 'Data baru anda berhasil disimpan.');
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        if(userLogin()->role_id != 1 && userLogin()->role_id != 2 && userLogin()->role_id != 3 && userLogin()->role_id != 4){
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $data['title_tab'] = 'Undangan Laporan &mdash; LPM UG';
        $data['title'] = 'Undangan Laporan';
        $data['pesan'] = $this->pesan->getPesan();

        $undangan =  $this->undangan->find($id);
        if(is_object($undangan)) {
            $data['undangan']    = $undangan;
            $data['dosen']      = $this->dosen->findAll();
            $data['anggota']    = $this->undangan->getAnggota();
            $data['tags']       = $this->tags->getAnggota($id);
            $data['tagluaran']  = $this->tagluaran->getLuaran($id);
            $data['mitra']      = $this->mitra->getAll();
            $data['subprogram'] = $this->subprogram->getAll();
            $data['luaran']     = $this->luaran->findAll();
            $data['periode']    = $this->periode->findAll();
            $data['pesan']      = $this->pesan->getPesan();
            $data['laporan'] = $this->undangan->getAll();
            return view('undangan/edit', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('undangan/edit', $data);
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $db         = \Config\Database::connect();
        $undangan   =  $this->undangan->find($id);

        $data = [
            'status'      => 'Disetujui',
        ];

        if(!empty($this->tags->where('laporan_id', $id))){
            $this->tags->where('users_id', $id)->delete();
        } 

        $this->undangan->update($id, $data);

        return redirect()->to(site_url('undangan'))->with('success', 'Data anda berhasil diupdate.');
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        if(userLogin()->role_id != 1 && userLogin()->role_id != 2 && userLogin()->role_id != 3 && userLogin()->role_id != 4){
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }
        
        $this->abdimas->delete($id);
        return redirect()->to(site_url('abdimas'))->with('success', 'Data anda berhasil dihapus.');
    }

    public function uploadProposal($id = null)
    {
        if(userLogin()->role_id != 1 && userLogin()->role_id != 2 && userLogin()->role_id != 3 && userLogin()->role_id != 4){
            return redirect()->to(site_url('dashboard'));
        } elseif (userLogin()->role_id == '') {
            return redirect()->to(site_url('login'));
        }

        $data['title_tab'] = 'Update Laporan &mdash; LPM UG';
        $data['title'] = 'Update Laporan';
        $data['pesan'] = $this->pesan->getPesan();

        $abdimas =  $this->abdimas->find($id);
        if(is_object($abdimas)) {
            $data['abdimas']    = $abdimas;
            return view('abdimas/upload_proposal', $data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('abdimas/upload_proposal', $data);
    }

    public function updateProposal($id = null)
    {
        $abdimas = $this->abdimas->find($id);
        $old_pdf_name = $abdimas->proposal;

        $file = $this->request->getFile('proposal');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Validate size (max 10MB)
            if ($file->getSize() > 10 * 1024 * 1024) {
                return redirect()->back()->with('error', 'Ukuran proposal maksimal 10MB.');
            }
            // Validate extension and MIME type
            if (strtolower($file->getExtension()) !== 'pdf' || $file->getMimeType() !== 'application/pdf') {
                return redirect()->back()->with('error', 'File proposal harus berformat PDF.');
            }

            if (!empty($old_pdf_name) && file_exists('berkas/proposal/' . $old_pdf_name)) {
                unlink('berkas/proposal/' . $old_pdf_name);
            }

            $namaBerkas = $file->getRandomName();
            $file->move('berkas/proposal', $namaBerkas);
        } else {
            $namaBerkas = $old_pdf_name;
        }

        $data = [
            'proposal'    => $namaBerkas,
        ];

        $this->abdimas->update($id, $data);
        return redirect()->to(site_url('abdimas'))->with('success', 'Proposal anda berhasil diupload.');
    }
}

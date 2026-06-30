<?php

namespace App\Controllers;

use App\Models\MahasiswaModel;
use App\Models\JurusanModel;
use App\Models\AbdimasModel;

class Mahasiswa extends BaseController
{
    protected $mahasiswa;
    protected $jurusan;
    protected $abdimas;

    public function __construct()
    {
        $this->mahasiswa = new MahasiswaModel();
        $this->jurusan   = new JurusanModel(); // tbl_jurusan
        $this->abdimas   = new AbdimasModel(); // tbl_laporan
    }

    // List semua mahasiswa
    public function index()
    {
        $data['title'] = "Data Mahasiswa";
        $data['mahasiswa'] = $this->mahasiswa->getMahasiswaWithRelations();

        return view('mahasiswa/index', $data);
    }

    // Form tambah mahasiswa
    public function create()
    {
        $data['title'] = "Tambah Mahasiswa";
        $data['jurusan'] = $this->jurusan->findAll();
        $data['laporan'] = $this->abdimas->findAll();

        return view('mahasiswa/create', $data);
    }

    // Simpan data mahasiswa
    public function store()
    {
        $rules = [
            'mahasiswa_name' => 'required|min_length[3]',
            'mahasiswa_npm'  => 'required|is_unique[tbl_mahasiswa.mahasiswa_npm]',
            'jurusan_id'     => 'required|integer',
            'laporan_id'     => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $this->mahasiswa->save([
            'mahasiswa_name' => $this->request->getVar('mahasiswa_name'),
            'mahasiswa_npm'  => $this->request->getVar('mahasiswa_npm'),
            'jurusan_id'     => $this->request->getVar('jurusan_id'),
            'laporan_id'     => $this->request->getVar('laporan_id'),
        ]);

        return redirect()->to(site_url('mahasiswa'))->with('success', 'Data mahasiswa berhasil ditambahkan.');
    }

    // Form edit
    public function edit($id = null)
    {
        $data['title'] = "Edit Mahasiswa";
        $data['mahasiswa'] = $this->mahasiswa->find($id);
        $data['jurusan']   = $this->jurusan->findAll();
        $data['laporan']   = $this->abdimas->findAll();

        if (!$data['mahasiswa']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Mahasiswa tidak ditemukan");
        }

        return view('mahasiswa/edit', $data);
    }

    // Update mahasiswa
    public function update($id = null)
    {
        $rules = [
            'mahasiswa_name' => 'required|min_length[3]',
            'mahasiswa_npm'  => "required|is_unique[tbl_mahasiswa.mahasiswa_npm,mahasiswa_id,{$id}]",
            'jurusan_id'     => 'required|integer',
            'laporan_id'     => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $this->mahasiswa->update($id, [
            'mahasiswa_name' => $this->request->getVar('mahasiswa_name'),
            'mahasiswa_npm'  => $this->request->getVar('mahasiswa_npm'),
            'jurusan_id'     => $this->request->getVar('jurusan_id'),
            'laporan_id'     => $this->request->getVar('laporan_id'),
        ]);

        return redirect()->to(site_url('mahasiswa'))->with('success', 'Data mahasiswa berhasil diupdate.');
    }

    // Hapus mahasiswa
    public function delete($id = null)
    {
        $this->mahasiswa->delete($id);
        return redirect()->to(site_url('mahasiswa'))->with('success', 'Data mahasiswa berhasil dihapus.');
    }
}

<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Pengabdian extends BaseController
{
    public function suratPengabdian($id = null)
    {
        // Ambil data dari database atau array contoh
        // Ganti dengan query database sesuai kebutuhan
        
        // Contoh data static (ganti dengan query database)
        $data = [
            'nomor_urut' => 1, // Nomor urutan berdasarkan upload
            'tanggal_surat' => date('Y-m-d'), // Tanggal surat
            'nama_kegiatan' => 'Pelatihan Kesehatan dan Kebugaran Fisik untuk Masyarakat', // Nama kegiatan/judul
            'lokasi_mitra' => 'LSM Komunitas Orang Orang Depok (OOD) Bethesdasya, Jl. Kecamatan Rp Sawah Depok No 18 Rangkaian Jaya, Jawa Barat', // Lokasi dan nama mitra
            'jangka_waktu' => 'September 2024 - Februari 2025 (1 Semester)', // Jangka waktu pelaksanaan
            'bidang_ilmu' => 'Kesehatan dan Kebugaran Fisik', // Bidang ilmu
            'ketua' => 'Dr. Ahmad Dahlan, M.Kom' // Ketua
        ];
        
        // Jika menggunakan database, gunakan kode seperti ini:
        /*
        $pengabdianModel = new \App\Models\PengabdianModel();
        
        if ($id) {
            $pengabdian = $pengabdianModel->find($id);
            if (!$pengabdian) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Data pengabdian tidak ditemukan');
            }
            
            $data = [
                'nomor_urut' => $pengabdian['nomor_urut'],
                'tanggal_surat' => $pengabdian['tanggal_surat'],
                'nama_kegiatan' => $pengabdian['nama_kegiatan'],
                'lokasi_mitra' => $pengabdian['lokasi_mitra'],
                'jangka_waktu' => $pengabdian['jangka_waktu'],
                'bidang_ilmu' => $pengabdian['bidang_ilmu'],
                'ketua' => $pengabdian['ketua']
            ];
        }
        */
        
        return view('surat_pengabdian', $data);
    }
    
    public function index()
    {
        // Menampilkan daftar pengabdian
        $search = $this->request->getGet('search');
        $tahun = $this->request->getGet('tahun');
        $status = $this->request->getGet('status');
        
        // Contoh data untuk daftar (ganti dengan query database)
        $pengabdian_list = [
            [
                'id' => 1,
                'nomor_urut' => 1,
                'tanggal_surat' => '2024-10-15',
                'nama_kegiatan' => 'Pelatihan Kesehatan dan Kebugaran Fisik untuk Masyarakat',
                'lokasi_mitra' => 'LSM Komunitas Orang Orang Depok (OOD) Bethesdasya',
                'jangka_waktu' => 'September 2024 - Februari 2025',
                'bidang_ilmu' => 'Kesehatan dan Kebugaran',
                'ketua' => 'Dr. Ahmad Dahlan, M.Kom',
                'status' => 'aktif'
            ],
            [
                'id' => 2,
                'nomor_urut' => 2,
                'tanggal_surat' => '2024-09-20',
                'nama_kegiatan' => 'Workshop Teknologi Informasi untuk UMKM',
                'lokasi_mitra' => 'Koperasi Usaha Mikro Depok Sejahtera',
                'jangka_waktu' => 'Oktober 2024 - Januari 2025',
                'bidang_ilmu' => 'Teknologi Informasi',
                'ketua' => 'Prof. Dr. Budi Santoso, M.T',
                'status' => 'aktif'
            ],
            [
                'id' => 3,
                'nomor_urut' => 3,
                'tanggal_surat' => '2024-08-10',
                'nama_kegiatan' => 'Pelatihan Kewirausahaan Digital',
                'lokasi_mitra' => 'Kelompok Tani Makmur Jaya',
                'jangka_waktu' => 'Agustus - November 2024',
                'bidang_ilmu' => 'Manajemen dan Kewirausahaan',
                'ketua' => 'Dr. Sari Indah, S.E., M.M',
                'status' => 'selesai'
            ]
        ];
        
        // Filter data berdasarkan parameter
        if ($search) {
            $pengabdian_list = array_filter($pengabdian_list, function($item) use ($search) {
                return stripos($item['nama_kegiatan'], $search) !== false ||
                       stripos($item['lokasi_mitra'], $search) !== false ||
                       stripos($item['ketua'], $search) !== false;
            });
        }
        
        if ($tahun) {
            $pengabdian_list = array_filter($pengabdian_list, function($item) use ($tahun) {
                return date('Y', strtotime($item['tanggal_surat'])) == $tahun;
            });
        }
        
        if ($status) {
            $pengabdian_list = array_filter($pengabdian_list, function($item) use ($status) {
                return $item['status'] == $status;
            });
        }
        
        // Statistik
        $total = count($pengabdian_list);
        $aktif = count(array_filter($pengabdian_list, fn($item) => $item['status'] == 'aktif'));
        $selesai = count(array_filter($pengabdian_list, fn($item) => $item['status'] == 'selesai'));
        
        $data = [
            'pengabdian_list' => array_values($pengabdian_list), // Re-index array
            'statistik' => [
                'total' => $total,
                'aktif' => $aktif,
                'selesai' => $selesai
            ]
        ];
        
        return view('daftar_pengabdian', $data);
    }
    
    public function cetakSurat($id)
    {
        // Ambil data yang sama seperti suratPengabdian tapi untuk mode cetak
        $data = [
            'nomor_urut' => 1,
            'tanggal_surat' => date('Y-m-d'),
            'nama_kegiatan' => 'Pelatihan Kesehatan dan Kebugaran Fisik untuk Masyarakat',
            'lokasi_mitra' => 'LSM Komunitas Orang Orang Depok (OOD) Bethesdasya, Jl. Kecamatan Rp Sawah Depok No 18 Rangkaian Jaya, Jawa Barat',
            'jangka_waktu' => 'September 2024 - Februari 2025 (1 Semester)',
            'bidang_ilmu' => 'Kesehatan dan Kebugaran Fisik',
            'ketua' => 'Dr. Ahmad Dahlan, M.Kom',
            'print_mode' => true
        ];
        
        return view('surat_pengabdian', $data);
    }
    
    public function tambah()
    {
        return view('form_pengabdian');
    }
    
    public function edit($id)
    {
        // Load data untuk edit
        $data = [
            'id' => $id,
            'nomor_urut' => 1,
            'tanggal_surat' => date('Y-m-d'),
            'nama_kegiatan' => 'Pelatihan Kesehatan dan Kebugaran Fisik untuk Masyarakat',
            'lokasi_mitra' => 'LSM Komunitas Orang Orang Depok (OOD) Bethesdasya',
            'jangka_waktu' => 'September 2024 - Februari 2025 (1 Semester)',
            'bidang_ilmu' => 'Kesehatan dan Kebugaran Fisik',
            'ketua' => 'Dr. Ahmad Dahlan, M.Kom'
        ];
        
        return view('form_pengabdian', $data);
    }
    
    public function simpan()
    {
        // Validasi dan simpan data
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama_kegiatan' => 'required|min_length[5]',
            'lokasi_mitra' => 'required|min_length[5]',
            'ketua' => 'required|min_length[3]',
            'tanggal_surat' => 'required|valid_date'
        ]);
        
        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        
        // Proses simpan data ke database di sini
        // ...
        
        return redirect()->to(base_url('pengabdian'))->with('success', 'Data berhasil disimpan');
    }
    
    public function hapus($id)
    {
        // Proses hapus data dari database
        // ...
        
        return redirect()->to(base_url('pengabdian'))->with('success', 'Data berhasil dihapus');
    }
}
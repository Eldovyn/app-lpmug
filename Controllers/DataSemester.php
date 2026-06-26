<?php

namespace App\Controllers;

use App\Models\HakAksesModel;
use App\Models\ProfileModel;
use App\Models\DosenModel;
use App\Models\UniversitasModel;
use App\Models\FakultasModel;
use App\Models\JurusanModel;
use App\Models\FungsionalModel;
use App\Models\MitraModel;
use App\Models\ProvinsiModel;
use App\Models\KotaModel;
use App\Models\PesanModel;
use App\Models\AbdimasModel;
use App\Models\PeriodeModel;
use Google\Cloud\Translate\V2\TranslateClient;

class DataSemester extends BaseController
{
    protected $hak_akses;
    protected $dosen;
    protected $universitas;
    protected $fakultas;
    protected $jurusan;
    protected $fungsional;
    protected $mitra;
    protected $provinsi;
    protected $kota;
    protected $profile;
    protected $pesan;
    protected $abdimas;

    public function __construct()
    {
        $this->hak_akses    = new HakAksesModel();
        $this->dosen        = new DosenModel();
        $this->universitas  = new UniversitasModel();
        $this->fakultas     = new FakultasModel();
        $this->jurusan      = new JurusanModel();
        $this->fungsional   = new FungsionalModel();
        $this->mitra        = new MitraModel();
        $this->provinsi     = new ProvinsiModel();
        $this->kota         = new KotaModel();
        $this->profile      = new ProfileModel();
        $this->pesan        = new PesanModel();
        $this->abdimas      = new AbdimasModel();
    }

    public function index(): string
    {
        helper(['cookie', 'url']);
        $request = service('request');

        $allowed = ['id', 'en'];
        $lang = get_cookie('lang') ?: 'id';
        if (! in_array($lang, $allowed, true)) {
            $lang = 'id';
        }

        $reqLang = $request->getGet('lang');
        if ($reqLang && in_array($reqLang, $allowed, true)) {
            set_cookie('lang', $reqLang, 60 * 60 * 24 * 30);
            $lang = $reqLang;
        }

        $baseTitle = 'Rekapan Semester';
        $title = $baseTitle;
        if ($lang === 'en') {
            $title = service('translation')->translateCached($baseTitle, 'id', 'en');
        }

        $data['title'] = $title;
        $data['title_tab'] = $title . ' &mdash; LPM UG';

        // Load periode data untuk filter dropdown
        $periodeModel = new \App\Models\PeriodeModel();
        $data['periodes'] = $periodeModel->findAll();

        // Get selected periode from query parameter
        $selected_semester = $request->getGet('semester') ?? '';

        // Convert to integer if not empty
        if (!empty($selected_semester)) {
            $selected_semester = (int)$selected_semester;
        }

        $data['selected_semester'] = $selected_semester;

        // Load laporan data berdasarkan periode
        $abdimasModel = new \App\Models\AbdimasModel();

        // OPTIMASI: Hanya mengambil kolom nilai (nt1-nt9) untuk meringankan query database
        $abdimasModel->select('nt1, nt2, nt3, nt4, nt5, nt6, nt7, nt8, nt9');

        if (!empty($selected_semester)) {
            // Ambil data berdasarkan periode_id yang dipilih
            $laporan_data = $abdimasModel->where('periode_id', $selected_semester)->findAll();
            $data['laporan_data'] = $laporan_data;
        } else {
            // Ambil semua data laporan jika "Semua Semester" dipilih
            $laporan_data = $abdimasModel->findAll();
            $data['laporan_data'] = $laporan_data;
        }

        return view('data_semester/index', $data);
    }
}

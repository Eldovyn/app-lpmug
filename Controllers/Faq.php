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
use Google\Cloud\Translate\V2\TranslateClient;

class Faq extends BaseController
{
    function __construct()
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
        return view('faq/index');
    }
}

<?php

namespace App\Controllers;


use App\Models\ProfilestaffModel;
use App\Models\GaleriModel;
use App\Models\StrukturModel;
use App\Models\MitraModel;
use App\Models\KalenderModel;
use App\Models\PesanModel;
use Google\Cloud\Translate\V2\TranslateClient;

class Home extends BaseController
{
    protected $profilestaff;
    protected $galeri;
    protected $struktur;
    protected $mitra;
    protected $kalender;
    protected $pesan;

    function __construct()
    {
        $this->profilestaff = new ProfilestaffModel();
        $this->galeri       = new GaleriModel();
        $this->struktur     = new StrukturModel();
        $this->mitra        = new mitraModel();
        $this->kalender     = new KalenderModel();
        $this->pesan       = new PesanModel();
    }

    public function index(): string
    {
        $lang = $this->getLang();

        $profilestaff = $this->profilestaff->findAll();
        $galeri       = $this->galeri->getGaleri();

        if ($lang === 'en') {
            foreach ($profilestaff as $item) {
                $item->deskripsi = service('translation')->translateCached($item->deskripsi, 'id', 'en');
            }
            foreach ($galeri as $g) {
                $g->judul = service('translation')->translateCached($g->judul, 'id', 'en');
            }
        }

        $data['title_tab']     = 'ISeeMonevIn &mdash; LPM UG';
        $data['profilestaff']  = $profilestaff;
        $data['galeri']        = $galeri;
        $data['lang']          = $lang;

        return view('homepage/index', $data);
    }

    // Uses getLang() from BaseController

    public function galeri()
    {
        $lang = $this->getLang();

        $data['title_tab'] = 'Galeri &mdash; LPM UG';
        $data['title'] = 'Galeri';
        $data['lang'] = $lang;
        $data['galeri'] = $this->galeri->findAll();

        return view('homepage/galeri', $data);
    }

    public function struktur()
    {
        $lang = $this->getLang();

        $data['title_tab'] = 'Struktur &mdash; LPM UG';
        $data['title'] = 'Struktur';
        $data['lang'] = $lang;
        $data['struktur'] = $this->struktur->findAll();

        return view('homepage/struktur', $data);
    }

    public function mitra()
    {
        $lang = $this->getLang();
        $keyword = $this->request->getGet('keyword');

        $data = $this->mitra->getPaginated(100000, $keyword);

        // Translate data from DB if language is English (only first page, max 10 items)
        if ($lang === 'en' && !empty($data['mitra'])) {
            $count = 0;
            foreach ($data['mitra'] as $m) {
                if ($count >= 10) break; // Limit translate to 10 items

                if (!empty($m->user_name)) {
                    $m->user_name = service('translation')->translateCached($m->user_name, 'id', 'en');
                }
                if (!empty($m->alamat)) {
                    $m->alamat = service('translation')->translateCached($m->alamat, 'id', 'en');
                }
                if (!empty($m->provinsi_name)) {
                    $m->provinsi_name = service('translation')->translateCached($m->provinsi_name, 'id', 'en');
                }
                if (!empty($m->kota_name)) {
                    $m->kota_name = service('translation')->translateCached($m->kota_name, 'id', 'en');
                }
                $count++;
            }
        }

        $data['keyword'] = $keyword;
        $data['lang'] = $lang;

        return view('homepage/mitra', $data);
    }

    public function kalender()
    {
        $lang = $this->getLang();

        $data['title_tab'] = 'Kalender &mdash; LPM UG';
        $data['title'] = 'Kalender Kegiatan';
        $data['lang'] = $lang;
        $data['kalender'] = $this->kalender->findAll();

        return view('homepage/kalender', $data);
    }

    public function kontak()
    {
        $lang = $this->getLang();

        $baseTitle = 'Hubungi kami';
        $title = $baseTitle;

        if ($lang === 'en') {
            $title = service('translation')->translateCached($baseTitle, 'id', 'en');
        }

        $data['title_tab'] = $title . ' &mdash; LPM UG';
        $data['title'] = $title;
        $data['lang'] = $lang;

        return view('homepage/kontak', $data);
    }

    public function kontakProcess()
    {
        $data = $this->request->getPost();
        $this->pesan->insert($data);
        return redirect()->to(site_url('home/kontak'))->with('success', 'Pesan anda berhasil dikirim. Silahkan menunggu balasan dari kami melalui Email atau No.whatsapp yang telah anda masukan. Terima kasih');
    }
}

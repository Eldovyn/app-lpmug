<<<<<<< HEAD
<?php

namespace App\Models;

use CodeIgniter\Model;

class MitraModel extends Model
{
    protected $table            = 'tbl_users';
    protected $primaryKey       = 'user_id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'user_name',
        'nidn',
        'password',
        'email',
        'kontak',
        'status',
        'role_id',
        'kota_id',
        'alamat',
        'kebutuhan',
        'spm',
        'skm'
    ];
    protected $useTimestamps = true;

    protected $validationRules = [
        'user_name' => [
            'rules' => 'required',
            'errors'=> [
                'required' => 'Nama user tidak boleh kosong.',
            ],
        ],
        'nidn' => [
            'rules' => 'required|is_unique[tbl_users.nidn]|min_length[10]',
            'errors'=> [
                'required' => 'Username tidak boleh kosong.',
                'is_unique' => 'Username sudah terdaftar, silahkan masukan Username yang berbeda.',
                'min_length' => 'Username harus 10 karakter.'
            ],
        ],
        'password' => [
            'rules' => 'required|min_length[6]',
            'errors'=> [
                'required' => 'Password tidak boleh kosong.',
                'min_length' => 'Password harus 6 karakter.'
            ],
        ],
    ];

    function getAll() {
        $builder = $this->builder()->orderBy('user_name', 'ASC');
        $builder->select('tbl_users.user_id, tbl_users.user_name, tbl_users.nidn, tbl_users.email, tbl_users.kontak, tbl_users.status, tbl_users.role_id, tbl_users.kota_id, tbl_users.alamat, tbl_users.kebutuhan, tbl_users.spm, tbl_users.skm, tbl_users.flag_status, tbl_kota.kota_name, tbl_provinsi.provinsi_name');
        $builder->join('tbl_kota', 'tbl_kota.kota_id = tbl_users.kota_id', 'left');
        $builder->join('tbl_provinsi', 'tbl_provinsi.provinsi_id = tbl_kota.provinsi_id', 'left');
        $query = $builder->get();
        return $query->getResult();
    }

    // ✅ Get mitra dengan laporan (SPM/SKM dari tbl_laporan)
    public function getAllWithLaporan($periodeId = null)
    {
        $builder = $this->builder()
            ->select('u.user_id, u.user_name, u.nidn, u.email, u.kontak, u.status, u.role_id, u.kota_id, u.alamat, u.kebutuhan, u.spm, u.skm, u.flag_status, k.kota_name, p.provinsi_name, l.laporan_id, l.spm as lap_spm, l.skm as lap_skm, l.verifikasi, per.periode_name')
            ->from('tbl_users u')
            ->join('tbl_kota k', 'k.kota_id = u.kota_id', 'left')
            ->join('tbl_provinsi p', 'p.provinsi_id = k.provinsi_id', 'left')
            ->join('tbl_laporan l', 'l.mitra_id = u.user_id', 'left')
            ->join('tbl_periode per', 'per.periode_id = l.periode_id', 'left')
            ->where('u.role_id', 5) // Asumsi role_id 5 = mitra
            ->orderBy('u.user_name', 'ASC');

        if ($periodeId) {
            $builder->where('l.periode_id', $periodeId);
        }

        return $builder->get()->getResult();
    }

    public function getPaginated($num, $keyword = null)
    {
        $builder = $this->builder()
            ->select('tbl_users.user_id, tbl_users.user_name, tbl_users.nidn, tbl_users.email, tbl_users.kontak, tbl_users.status, tbl_users.role_id, tbl_users.kota_id, tbl_users.alamat, tbl_users.kebutuhan, tbl_users.spm, tbl_users.skm, tbl_users.flag_status, tbl_role.role_name, tbl_kota.kota_name, tbl_provinsi.provinsi_name')
            ->join('tbl_role', 'tbl_role.role_id = tbl_users.role_id')
            ->join('tbl_kota', 'tbl_kota.kota_id = tbl_users.kota_id', 'left')
            ->join('tbl_provinsi', 'tbl_provinsi.provinsi_id = tbl_kota.provinsi_id', 'left')
            ->orderBy('tbl_users.user_name', 'ASC');

        if (!empty($keyword)) {
            $builder->groupStart()
                ->like('tbl_users.user_name', $keyword)
                ->orLike('tbl_users.nidn', $keyword)
                ->orLike('tbl_role.role_name', $keyword)
                ->orLike('tbl_kota.kota_name', $keyword)
                ->orLike('tbl_provinsi.provinsi_name', $keyword)
            ->groupEnd();
        }

        return [
            'title_tab' => 'Mitra &mdash; LPM UG',
            'title'     => 'Mitra',
            'mitra'     => $this->paginate($num),
            'pager'     => $this->pager,
        ];
    }
}
=======
<?php

namespace App\Models;

use CodeIgniter\Model;

class MitraModel extends Model
{
    protected $table            = 'tbl_users';
    protected $primaryKey       = 'user_id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'user_name',  
        'nidn',
        'password',
        'email',
        'kontak',
        'status',
        'role_id',
        'kota_id',
        'alamat',
        'kebutuhan',
    ];
    protected $useTimestamps    = true;

    protected $validationRules  = [
        'user_name' => [
            'rules' => 'required',
            'errors'=> [
                'required' => 'Nama user tidak boleh kosong.',
            ],
        ],
        'nidn' => [
            'rules' => 'required|is_unique[tbl_users.nidn]|min_length[10]',
            'errors'=> [
                'required' => 'Username tidak boleh kosong.',
                'is_unique' => 'Username sudah terdaftar, silahkan masukan Username yang berbeda.',
                'min_length' => 'Username harus 10 karakter.'
            ],
        ],
        'password' => [
            'rules' => 'required|min_length[6]',
            'errors'=> [
                'required' => 'Password tidak boleh kosong.',
                'min_length' => 'Password harus 6 karakter.'
            ],
        ],
    ];

    function getAll() {
        $builder = $this->builder()->orderBy('user_name', 'ASC');
        $builder->join('tbl_kota', 'tbl_kota.kota_id = tbl_users.kota_id');
        $builder->join('tbl_provinsi', 'tbl_provinsi.provinsi_id = tbl_kota.provinsi_id');
        $query   = $builder->get();
        return $query->getResult();
    }

    public function getAllWithSPM()
{
    $builder = $this->builder()
        ->select('tbl_users.*, tbl_kota.kota_name, tbl_provinsi.provinsi_name, l.spm')
        ->join('tbl_kota', 'tbl_kota.kota_id = tbl_users.kota_id')
        ->join('tbl_provinsi', 'tbl_provinsi.provinsi_id = tbl_kota.provinsi_id')
        // Ambil SPM terbaru per mitra
        ->join('(SELECT mitra_id, spm FROM tbl_laporan GROUP BY mitra_id) l', 'l.mitra_id = tbl_users.user_id', 'left')
        ->orderBy('tbl_users.user_name', 'ASC');

    $query = $builder->get();
    return $query->getResult();
}

public function getPaginatedWithSPM($num, $keyword = null)
{
    $builder = $this->builder()
        ->select('tbl_users.*, tbl_role.role_name, tbl_kota.kota_name, tbl_provinsi.provinsi_name, l.spm')
        ->join('tbl_role', 'tbl_role.role_id = tbl_users.role_id')
        ->join('tbl_kota', 'tbl_kota.kota_id = tbl_users.kota_id')
        ->join('tbl_provinsi', 'tbl_provinsi.provinsi_id = tbl_kota.provinsi_id')
        ->join('(SELECT mitra_id, spm FROM tbl_laporan GROUP BY mitra_id) l', 'l.mitra_id = tbl_users.user_id', 'left')
        ->orderBy('tbl_users.user_name', 'ASC');

    if ($keyword != null && $keyword != '') {
        $builder->groupStart()
            ->like('tbl_users.user_name', $keyword)
            ->orLike('tbl_users.nidn', $keyword)
            ->orLike('tbl_role.role_name', $keyword)
            ->orLike('tbl_kota.kota_name', $keyword)
            ->orLike('tbl_provinsi.provinsi_name', $keyword)
        ->groupEnd();
    }

    return [
        'title_tab' => 'Mitra UMKM &mdash; LPM UG',
        'title'     => 'Mitra UMKM',
        'mitra'     => $this->paginate($num),
        'pager'     => $this->pager,
    ];
}

}
>>>>>>> 55c0835 (refactor: update code)

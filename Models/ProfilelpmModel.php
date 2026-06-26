<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfilelpmModel extends Model
{
    protected $table            = 'tbl_profilelpm';
    protected $primaryKey       = 'profilelpm_id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['judul', 'deskripsi', 'gambar'];
    protected $useTimestamps    = true;

    // protected $validationRules  = [
    //     'judul' => [
    //         'rules' => 'required|min_length[3]',
    //         'errors'=> [
    //             'required'   => 'Judul tidak boleh kosong.',
    //             'min_length' => 'Judul tidak boleh kurang dari 3 karakter.',
    //         ],
    //     ],
    //     'gambar' => [
    //         'rules' => 'uploaded[gambar]|max_size[gambar,1024]|is_image[gambar]',
    //         'errors'=> [
    //             'uploaded' => 'Pilih gambar terlebih dahulu.',
    //             'max_size' => 'Ukuran terlalu besar, max 10 MB.',
    //             'is_image' => 'Yang anda masukan bukan file gambar, silahkan masukan ulang.',
    //         ],
    //     ],
    // ];
    function getPaginated($num, $keyword = null) {
        $builder = $this->builder();
        if($keyword != '') {
            $builder->like('judul', $keyword);
        }
        return [
            'title_tab' => 'Profile &mdash; LPM UG',
            'title'     => 'Profile LPM',
            'profilelpm'=> $this->paginate($num),
            'pager'     => $this->pager,
        ];
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class PesanModel extends Model
{
    protected $table            = 'tbl_pesan';
    protected $primaryKey       = 'pesan_id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    // protected $allowedFields    = [
    //     'pesan_name',
    //     'email',
    //     'phone',
    //     'subject',
    //     'pesan',
    // ];
    protected $useTimestamps    = true;

    function getPesan() {
        $builder = $this->db->table('tbl_pesan');
        $builder->orderBy('pesan_id', 'DESC');
        $builder->limit(5);
        $query = $builder->get();
        return $query->getResult();
    }

    function getPaginated($num, $keyword = null) {
        $builder = $this->builder()->orderBy('pesan_id', 'DESC');
        if($keyword != '') {
            $builder->like('pesan_name', $keyword);
            $builder->orLike('subject', $keyword);
            $builder->orLike('email', $keyword);
        }
        return [
            'title_tab'     => 'Kirim Pesan &mdash; LPM UG',
            'title'         => 'Kirim Pesan',
            'message'       => $this->paginate($num),
            'pager'         => $this->pager,
        ];
    }
}

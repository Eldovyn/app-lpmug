<?php

namespace App\Models;

use CodeIgniter\Model;

class BidangIlmuModel extends Model
{
    protected $table            = 'tbl_bidang_ilmu';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = ['nama', 'slug'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';

    public function getAllActive()
    {
        return $this->where('deleted_at', null)->orderBy('nama')->findAll();
    }

    /**
     * Get bidang_ilmu ID by slug (used by AbdimasController)
     */
    public function getBidangIlmuId($slug)
    {
        if (empty($slug)) return null;
        
        $record = $this->where('slug', $slug)
                      ->where('deleted_at', null)
                      ->first();
        
        return $record ? (int)$record->id : null;
    }
}


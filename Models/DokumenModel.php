<?php
namespace App\Models;

use CodeIgniter\Model;

class DokumenModel extends Model
{
    protected $table            = 'tbl_dokumen';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['nama_file', 'file_path', 'created_at', 'updated_at', 'deleted_at'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true; // aktifin soft delete
    protected $returnType = 'array'; // atau 'object'

}

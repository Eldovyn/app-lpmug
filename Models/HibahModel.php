<?php

namespace App\Models;

use CodeIgniter\Model;

class HibahModel extends Model
{
    protected $table            = 'tbl_hibah';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'judul',
        'ketua_id',
        'user_id',
        'status',
        'tanggal_mulai',
        'tanggal_selesai',
        'anggaran',
        'deskripsi',
        'pesan',
        'proposal_file',
        'posisi_dosen',
        'verification_status',
        'verified_at',
        'verified_by',
        'verification_notes'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'judul' => 'required|max_length[255]',
        'ketua_id' => 'permit_empty|is_natural_no_zero',
        'user_id' => 'required|is_natural_no_zero',
        'status' => 'required|in_list[draft,submitted,approved,rejected]',
        'verification_status' => 'required|in_list[draft,submitted,approved,rejected]',
        'posisi_dosen' => 'required|in_list[ketua,anggota]',
        'anggaran' => 'permit_empty|numeric|greater_than_equal_to[0]',
        'tanggal_mulai' => 'permit_empty|valid_date[Y-m-d]',
        'tanggal_selesai' => 'permit_empty|valid_date[Y-m-d]|validate_date_range[tanggal_mulai]',
        'proposal_file' => 'permit_empty|max_length[255]',
    ];
    protected $validationMessages   = [
        'judul' => [
            'required' => 'Judul hibah wajib diisi.',
            'max_length' => 'Judul hibah maksimal 255 karakter.'
        ],
        'ketua_id' => [
            'required' => 'Ketua hibah wajib dipilih.',
            'is_natural_no_zero' => 'Ketua hibah tidak valid.'
        ],
        'user_id' => [
            'required' => 'User ID wajib diisi.',
            'is_natural_no_zero' => 'User ID tidak valid.'
        ],
        'status' => [
            'required' => 'Status wajib dipilih.',
            'in_list' => 'Status tidak valid.'
        ],
        'verification_status' => [
            'required' => 'Status verifikasi wajib dipilih.',
            'in_list' => 'Status verifikasi tidak valid.'
        ],
        'posisi_dosen' => [
            'required' => 'Posisi dosen wajib dipilih.',
            'in_list' => 'Posisi dosen harus ketua atau anggota.'
        ],
        'anggaran' => [
            'numeric' => 'Anggaran harus berupa angka.',
            'greater_than_equal_to' => 'Anggaran harus >= 0.'
        ],
        'tanggal_mulai' => [
            'valid_date' => 'Format tanggal mulai tidak valid.'
        ],
        'tanggal_selesai' => [
            'valid_date' => 'Format tanggal selesai tidak valid.'
        ],
        'proposal_file' => [
            'max_length' => 'Nama file proposal maksimal 255 karakter.'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['validateDateRange'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['validateDateRange'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get hibah data with ketua information
     * Joins with tbl_users table to get ketua name
     *
     * @param int|null $id Specific hibah ID, null for all
     * @return array
     */
    public function getHibahWithKetua($id = null)
    {
        $builder = $this->db->table($this->table . ' h')
            ->select('h.*, u.user_name as ketua_nama')
            ->join('tbl_users u', 'u.user_id = h.ketua_id', 'left');

        if ($id !== null) {
            $builder->where('h.id', $id);
            return $builder->get()->getRowArray();
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Update verification status for a hibah
     *
     * @param int $id Hibah ID
     * @param string $status New verification status
     * @param string|null $notes Verification notes
     * @param int $verifierId ID of the verifier
     * @return bool
     */
    public function updateVerification($id, $status, $notes, $verifierId)
    {
        return $this->update($id, [
            'verification_status' => $status,
            'verification_notes' => $notes,
            'verified_at' => date('Y-m-d H:i:s'),
            'verified_by' => $verifierId
        ]);
    }

    /**
     * Count hibah records by status
     *
     * @param string $status Status to count
     * @return int
     */
    public function countByStatus($status)
    {
        return $this->where('status', $status)->countAllResults();
    }

    /**
     * Get hibah with user information by ID
     *
     * @param int $id Hibah ID
     * @return array|null
     */
    public function getWithUserById($id)
    {
        return $this->select('tbl_hibah.*, tbl_users.user_name, tbl_users.nidn')
                    ->join('tbl_users', 'tbl_users.user_id = tbl_hibah.user_id', 'left')
                    ->find($id);
    }

    /**
     * Validate date range - tanggal_selesai must be >= tanggal_mulai if both are provided
     *
     * @param array $data
     * @return array
     */
    protected function validateDateRange(array $data)
    {
        if (isset($data['data']['tanggal_mulai']) && isset($data['data']['tanggal_selesai'])) {
            $start = $data['data']['tanggal_mulai'];
            $end = $data['data']['tanggal_selesai'];

            if (!empty($start) && !empty($end) && $end < $start) {
                $this->validationMessages['tanggal_selesai']['validate_date_range'] = 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.';
                return false;
            }
        }

        return $data;
    }
}

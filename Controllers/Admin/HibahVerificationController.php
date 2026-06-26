<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\HibahModel;
use App\Models\PesanModel;
use App\Models\UsersModel;
use App\Libraries\HibahService;

class HibahVerificationController extends BaseController
{
    protected $hibahModel;
    protected $hibahService;
    protected $pesanModel;
    protected $usersModel;

    public function __construct()
    {
        $this->hibahModel = new HibahModel();
        $this->hibahService = new HibahService();
        $this->pesanModel = new PesanModel();
        $this->usersModel = new UsersModel();
    }

    /**
     * Helper function to convert object to array
     */
    private function toArray($data)
    {
        if (is_object($data)) {
            return (array)$data;
        }
        return $data;
    }

    /**
     * List semua hibah untuk verifikasi
     */
    public function index()
    {
        // Check role admin (allow role 1 = Super Admin and role 2 = Admin)
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2) {
            return redirect()->to(site_url('dashboard'));
        }

        $keyword = $this->request->getGet('keyword');
        $status = $this->request->getGet('status');

        $builder = $this->hibahModel->select('tbl_hibah.*, tbl_users.user_name')
                                    ->join('tbl_users', 'tbl_users.user_id = tbl_hibah.user_id', 'left');

        if ($status) {
            $builder->where('verification_status', $status);
        } else {
            $builder->whereIn('verification_status', ['draft', 'submitted', 'approved', 'rejected']);
        }

        if ($keyword) {
            $builder->like('judul', $keyword)
                   ->orLike('tbl_users.user_name', $keyword);
        }

        // Get all hibah
        $allHibah = $builder->findAll();

        // Convert all hibah to array format for consistent access
        $hibahList = [];
        foreach ($allHibah as $hibah) {
            $hibahList[] = $this->toArray($hibah);
        }

        // Get approved hibah with user flag info
        $approvedHibah = [];
        $userFlagsModel = new \App\Models\UserFlagsModel();
        
        foreach ($hibahList as $hibah) {
            if (isset($hibah['verification_status']) && $hibah['verification_status'] === 'approved') {
                // Check if user has active flag
                $flags = $userFlagsModel->where('user_id', $hibah['user_id'])
                    ->where('flag_type', 'hibah_approved')
                    ->where('hibah_id', $hibah['id'])
                    ->findAll();
                
                $hibah['has_active_flag'] = !empty($flags);
                
                // Handle both array and object results
                if (!empty($flags)) {
                    $firstFlag = $this->toArray($flags[0]);
                    $hibah['flag_created_at'] = isset($firstFlag['created_at']) ? $firstFlag['created_at'] : null;
                } else {
                    $hibah['flag_created_at'] = null;
                }
                
                $approvedHibah[] = $hibah;
            }
        }

        $data = [
            'title_tab' => 'Verifikasi Hibah &mdash; LPM UG',
            'title' => 'Verifikasi Hibah',
            'hibah' => $hibahList,
            'approved_hibah' => $approvedHibah,
            'keyword' => $keyword,
            'status' => $status,
            'draft_count' => $this->hibahModel->where('verification_status', 'draft')->countAllResults(),
            'pending_count' => $this->hibahModel->where('verification_status', 'submitted')->countAllResults(),
            'approved_count' => $this->hibahModel->where('verification_status', 'approved')->countAllResults(),
            'rejected_count' => $this->hibahModel->where('verification_status', 'rejected')->countAllResults(),
            'total_approved_with_flags' => count($approvedHibah),
            'pesan' => $this->pesanModel->getPesan()
        ];

        return view('admin/hibah/index', $data);
    }

    /**
     * Detail hibah untuk verifikasi
     */
    public function show($id = null)
    {
        // Check role admin (allow role 1 = Super Admin and role 2 = Admin)
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2) {
            return redirect()->to(site_url('dashboard'));
        }

        $hibah = $this->hibahModel->getWithUserById($id);

        if (!$hibah) {
            return redirect()->to(site_url('hibah/verification-list'))->with('error', 'Hibah tidak ditemukan');
        }

        $data = [
            'title_tab' => 'Detail Hibah &mdash; LPM UG',
            'title' => 'Detail Hibah',
            'hibah' => $hibah,
            'validation' => \Config\Services::validation(),
            'pesan' => $this->pesanModel->getPesan()
        ];

        return view('admin/hibah/detail', $data);
    }

    /**
     * Approve hibah
     */
    public function approve($id = null)
    {
        // Check role admin (allow role 1 = Super Admin and role 2 = Admin)
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2) {
            return redirect()->to(site_url('dashboard'));
        }

        $hibah = $this->hibahModel->find($id);

        if (!$hibah) {
            return redirect()->to(site_url('hibah/verification-list'))
                             ->with('error', 'Hibah tidak ditemukan');
        }

        // Convert to array for consistent access
        $hibah = $this->toArray($hibah);

        if (!in_array($hibah['verification_status'] ?? '', ['submitted', 'draft'])) {
            return redirect()->to(site_url('hibah/verification-list'))
                             ->with('error', 'Hibah tidak dapat diapprove karena sudah ' . ($hibah['verification_status'] ?? 'unknown'));
        }

        $notes = $this->request->getPost('verification_notes');

        try {
            $this->hibahService->approveHibah($id, userLogin()->user_id, $notes);

            // Set flag aktif pada user dosen
            $this->_setUserFlag((int)$hibah['user_id'], (int)$id, 'hibah_approved');

            return redirect()->to(site_url('hibah/verification-list'))
                             ->with('success', 'Hibah berhasil diapprove dan flag akun dosen diaktifkan');
        } catch (\Exception $e) {
            log_message('error', 'Approve hibah error: ' . $e->getMessage());
            return redirect()->to(site_url('hibah/verification-list'))
                             ->with('error', 'Gagal approve hibah: ' . $e->getMessage());
        }
    }

    /**
     * Reject hibah
     */
    public function reject($id = null)
    {
        // Check role admin (allow role 1 = Super Admin and role 2 = Admin)
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2) {
            return redirect()->to(site_url('dashboard'));
        }

        $hibah = $this->hibahModel->find($id);

        if (!$hibah) {
            return redirect()->to(site_url('hibah/verification-list'))
                             ->with('error', 'Hibah tidak ditemukan');
        }

        // Convert to array for consistent access
        $hibah = $this->toArray($hibah);

        if (!in_array($hibah['verification_status'] ?? '', ['submitted', 'draft'])) {
            return redirect()->to(site_url('hibah/verification-list'))
                             ->with('error', 'Hibah tidak dapat direject karena sudah ' . ($hibah['verification_status'] ?? 'unknown'));
        }

        $notes = $this->request->getPost('verification_notes');

        if (empty($notes)) {
            return redirect()->back()->with('error', 'Catatan penolakan wajib diisi');
        }

        try {
            $this->hibahService->rejectHibah($id, userLogin()->user_id, $notes);

            // Hapus flag jika ada (hibah ditolak, flag tidak berlaku)
            $this->_removeUserFlag((int)$hibah['user_id'], (int)$id, 'hibah_approved');

            return redirect()->to(site_url('hibah/verification-list'))
                             ->with('success', 'Hibah berhasil direject');
        } catch (\Exception $e) {
            log_message('error', 'Reject hibah error: ' . $e->getMessage());
            return redirect()->to(site_url('hibah/verification-list'))
                             ->with('error', 'Gagal reject hibah: ' . $e->getMessage());
        }
    }

    /**
     * Set flag aktif pada user (dosen) setelah hibah diapprove
     */
    private function _setUserFlag(int $userId, int $hibahId, string $flagType = 'hibah_approved'): void
    {
        try {
            $userFlagsModel = new \App\Models\UserFlagsModel();

            // Cek apakah flag sudah ada agar tidak duplikat
            $existing = $userFlagsModel
                ->where('user_id', $userId)
                ->where('hibah_id', $hibahId)
                ->where('flag_type', $flagType)
                ->first();

            if (!$existing) {
                $userFlagsModel->insert([
                    'user_id'    => $userId,
                    'hibah_id'   => $hibahId,
                    'flag_type'  => $flagType,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }

            // Update kolom flag_status di tbl_users
            $this->usersModel->update($userId, [
                'flag_status'      => 'active',
                'flag_verified_at' => date('Y-m-d H:i:s'),
            ]);
        } catch (\Exception $e) {
            log_message('error', '_setUserFlag error: ' . $e->getMessage());
        }
    }

    /**
     * Hapus flag user ketika hibah direject
     */
    private function _removeUserFlag(int $userId, int $hibahId, string $flagType = 'hibah_approved'): void
    {
        try {
            $userFlagsModel = new \App\Models\UserFlagsModel();

            $userFlagsModel
                ->where('user_id', $userId)
                ->where('hibah_id', $hibahId)
                ->where('flag_type', $flagType)
                ->delete();

            // Cek apakah user masih punya flag aktif lain
            $remainingFlags = $userFlagsModel
                ->where('user_id', $userId)
                ->where('flag_type', $flagType)
                ->countAllResults();

            if ($remainingFlags === 0) {
                // Tidak ada flag aktif lain → reset flag_status
                $this->usersModel->update($userId, [
                    'flag_status'      => null,
                    'flag_verified_at' => null,
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', '_removeUserFlag error: ' . $e->getMessage());
        }
    }

    /**
     * Delete hibah
     */
    public function delete($id = null)
    {
        // Check role admin (allow role 1 = Super Admin and role 2 = Admin)
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2) {
            return redirect()->to(site_url('dashboard'))
                            ->with('error', 'Anda tidak memiliki akses');
        }

        // Validate request method
        if (!$this->request->is('delete') && $this->request->getMethod() !== 'post') {
            return redirect()->to(site_url('hibah/verification-list'))
                            ->with('error', 'Invalid request method');
        }

        $hibah = $this->hibahModel->find($id);

        if (!$hibah) {
            return redirect()->to(site_url('hibah/verification-list'))
                            ->with('error', 'Hibah tidak ditemukan');
        }

        $hibah = $this->toArray($hibah);

        try {
            // Delete proposal file if exists
            if (!empty($hibah['proposal_file'])) {
                $filePath = WRITEPATH . 'uploads/hibah/' . $hibah['proposal_file'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            // Delete from database
            $this->hibahModel->delete($id);

            log_message('info', 'Hibah deleted: ID=' . $id . ' by user_id=' . userLogin()->user_id);

            return redirect()->to(site_url('hibah/verification-list'))
                            ->with('success', 'Hibah "' . $hibah['judul'] . '" berhasil dihapus');

        } catch (\Exception $e) {
            log_message('error', 'Delete hibah error: ' . $e->getMessage());
            return redirect()->to(site_url('hibah/verification-list'))
                            ->with('error', 'Terjadi kesalahan saat menghapus hibah: ' . $e->getMessage());
        }
    }

    /**
     * Download proposal file
     */
    public function downloadProposal($id = null)
    {
        // Check role admin (allow role 1 = Super Admin and role 2 = Admin)
        if (userLogin()->role_id != 1 && userLogin()->role_id != 2) {
            return redirect()->to(site_url('dashboard'));
        }

        $hibah = $this->hibahModel->find($id);

        if (!$hibah) {
            return redirect()->to(site_url('hibah/verification-list'))->with('error', 'File proposal tidak ditemukan');
        }

        $hibah = $this->toArray($hibah);

        if (empty($hibah['proposal_file'])) {
            return redirect()->to(site_url('hibah/verification-list'))->with('error', 'File proposal tidak ditemukan');
        }

        $filePath = WRITEPATH . 'uploads/hibah/' . $hibah['proposal_file'];

        if (!file_exists($filePath)) {
            return redirect()->to(site_url('hibah/verification-list'))->with('error', 'File proposal tidak ditemukan di server');
        }

        return $this->response->download($filePath, null)->setFileName('Proposal_' . $hibah['judul'] . '.pdf');
    }

    /**
     * List akun dosen dengan flag aktif
     * Menampilkan semua akun dosen yang memiliki flag hibah aktif
     */
    public function activeFlags()
    {
        // Check if user is logged in
        if (!session()->user_id) {
            return redirect()->to(site_url('login'));
        }
        
        // Get user login info
        $user = userLogin();
        
        // Check if user object is valid
        if (!$user || !isset($user->role_id)) {
            return redirect()->to(site_url('login'));
        }
        
        // Check role admin (allow role 1 = Super Admin and role 2 = Admin)
        if ($user->role_id != 1 && $user->role_id != 2) {
            return redirect()->to(site_url('dashboard'));
        }

        try {
            $keyword = $this->request->getGet('keyword');

            // Get dosen with active flag status - simplified query
            $db = \Config\Database::connect();
            
            $builder = $db->table('tbl_users u');
            $builder->select([
                'u.user_id',
                'u.user_name', 
                'u.email',
                'u.nidn',
                'u.flag_status',
                'u.flag_verified_at',
                'u.jurusan_id',
                'j.jurusan_name',
                'f.fakultas_name'
            ]);
            $builder->join('tbl_jurusan j', 'j.jurusan_id = u.jurusan_id', 'left');
            $builder->join('tbl_fakultas f', 'f.fakultas_id = j.fakultas_id', 'left');
            $builder->where('u.role_id', 4); // Role ID 4 = Dosen
            $builder->where('u.flag_status', 'active');
            $builder->where('u.deleted_at', null);

            // Search keyword
            if ($keyword) {
                $builder->groupStart()
                    ->like('u.user_name', $keyword)
                    ->orLike('u.email', $keyword)
                    ->orLike('u.nidn', $keyword)
                    ->groupEnd();
            }

            $builder->orderBy('u.user_name', 'ASC');
            $dosenAktif = $builder->get()->getResult();

            // Get hibah count for each user
            $userFlagsModel = new \App\Models\UserFlagsModel();
            foreach ($dosenAktif as &$dosen) {
                $flags = $userFlagsModel->where('user_id', $dosen->user_id)
                    ->where('flag_type', 'hibah_approved')
                    ->findAll();
                $dosen->hibah_approved_count = count($flags);
            }

            $data = [
                'title_tab' => 'Akun Flag Aktif &mdash; LPM UG',
                'title' => 'Akun Dosen dengan Flag Aktif',
                'dosen_aktif' => $dosenAktif,
                'keyword' => $keyword,
                'total_aktif' => count($dosenAktif),
                'pesan' => $this->pesanModel->getPesan()
            ];

            return view('admin/hibah/active_flags', $data);
        } catch (\Exception $e) {
            log_message('error', 'activeFlags error: ' . $e->getMessage());
            return redirect()->to(site_url('dashboard'))->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
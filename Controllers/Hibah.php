<?php

namespace App\Controllers;

use App\Models\HibahModel;
use App\Models\UsersModel;
use App\Models\UserFlagsModel;

class Hibah extends BaseController
{
    protected $hibahModel;
    protected $usersModel;
    protected $userFlagsModel;

    public function __construct()
    {
        $this->hibahModel = new HibahModel();
        $this->usersModel = new UsersModel();
        $this->userFlagsModel = new UserFlagsModel();
    }

    public function upload()
    {
        if (userLogin()->role_id != 4) {
            return redirect()->to(site_url('dashboard'));
        }

        $data['title_tab'] = 'Upload Hibah &mdash; LPM UG';
        $data['title'] = 'Upload Hibah';
        $data['validation'] = \Config\Services::validation();

        return view('dosen/hibah/create', $data);
    }

    public function doUpload()
    {
        if (userLogin()->role_id != 4) {
            return redirect()->to(site_url('dashboard'));
        }

        // Manual validation for all fields
        $errors = [];

        // Validate judul
        $judul = trim($this->request->getPost('judul'));
        if (empty($judul)) {
            $errors['judul'] = 'Judul hibah wajib diisi.';
        } elseif (strlen($judul) > 255) {
            $errors['judul'] = 'Judul hibah maksimal 255 karakter.';
        }

        // Validate posisi_dosen
        $posisi_dosen = $this->request->getPost('posisi_dosen');
        if (empty($posisi_dosen)) {
            $errors['posisi_dosen'] = 'Posisi dosen wajib dipilih (Ketua atau Anggota).';
        } elseif (!in_array($posisi_dosen, ['ketua', 'anggota'], true)) {
            $errors['posisi_dosen'] = 'Posisi dosen tidak valid. Pilih Ketua atau Anggota.';
        }

        // Validate anggaran (optional but must be numeric if provided)
        $anggaran = $this->request->getPost('anggaran');
        if (!empty($anggaran) && !is_numeric($anggaran)) {
            $errors['anggaran'] = 'Anggaran harus berupa angka.';
        } elseif (!empty($anggaran) && $anggaran < 0) {
            $errors['anggaran'] = 'Anggaran tidak boleh negatif.';
        }

        // Validate tanggal_mulai (optional)
        $tanggal_mulai = $this->request->getPost('tanggal_mulai');
        if (!empty($tanggal_mulai)) {
            $date_mulai = \DateTime::createFromFormat('Y-m-d', $tanggal_mulai);
            if (!$date_mulai || $date_mulai->format('Y-m-d') !== $tanggal_mulai) {
                $errors['tanggal_mulai'] = 'Format tanggal mulai tidak valid.';
            }
        }

        // Validate tanggal_selesai (optional)
        $tanggal_selesai = $this->request->getPost('tanggal_selesai');
        if (!empty($tanggal_selesai)) {
            $date_selesai = \DateTime::createFromFormat('Y-m-d', $tanggal_selesai);
            if (!$date_selesai || $date_selesai->format('Y-m-d') !== $tanggal_selesai) {
                $errors['tanggal_selesai'] = 'Format tanggal selesai tidak valid.';
            }
        }

        // Validate date range
        if (!empty($tanggal_mulai) && !empty($tanggal_selesai)) {
            if (strtotime($tanggal_selesai) < strtotime($tanggal_mulai)) {
                $errors['tanggal_selesai'] = 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.';
            }
        }

        // Manual file validation
        $file = $this->request->getFile('proposal_file');
        if (!$file || !$file->isValid()) {
            $errors['proposal_file'] = 'File proposal wajib diupload.';
        } else {
            // Check file size (5MB = 5120 KB = 5242880 bytes)
            if ($file->getSize() > 5242880) {
                $errors['proposal_file'] = 'Ukuran file maksimal 5MB.';
            }
            
            // Check mime type
            $mimeType = $file->getMimeType();
            if ($mimeType !== 'application/pdf') {
                $errors['proposal_file'] = 'Format file harus PDF. File yang diupload: ' . $mimeType;
            }
            
            // Check file extension
            $extension = strtolower($file->getClientExtension());
            if ($extension !== 'pdf') {
                $errors['proposal_file'] = 'Ekstensi file harus .pdf';
            }
        }

        // If there are validation errors, redirect back with errors
        if (!empty($errors)) {
            return redirect()->back()->withInput()->with('validation_errors', $errors);
        }

        // Handle file upload
        $uploadPath = WRITEPATH . 'uploads/hibah';
        
        // Create directory if not exists
        if (!is_dir($uploadPath)) {
            if (!mkdir($uploadPath, 0755, true)) {
                return redirect()->back()->withInput()->with('error', 'Gagal membuat direktori upload.');
            }
        }

        // Generate unique filename
        $fileName = $file->getRandomName();
        
        // Move file to upload directory
        try {
            if (!$file->move($uploadPath, $fileName)) {
                return redirect()->back()->withInput()->with('error', 'Gagal mengupload file.');
            }
        } catch (\Exception $e) {
            log_message('error', 'File upload error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat mengupload file.');
        }

        // Start database transaction
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Determine ketua_id based on posisi_dosen
            $ketuaId = null;
            if ($posisi_dosen === 'ketua') {
                $ketuaId = userLogin()->user_id;
            }

            // Prepare data for insertion
            $data = [
                'user_id' => userLogin()->user_id,
                'ketua_id' => $ketuaId,
                'judul' => $judul,
                'anggaran' => !empty($anggaran) ? $anggaran : 0,
                'tanggal_mulai' => !empty($tanggal_mulai) ? $tanggal_mulai : null,
                'tanggal_selesai' => !empty($tanggal_selesai) ? $tanggal_selesai : null,
                'deskripsi' => $this->request->getPost('deskripsi'),
                'proposal_file' => $fileName,
                'posisi_dosen' => $posisi_dosen,
                'status' => 'draft',
                'verification_status' => 'draft'
            ];

            // Insert into tbl_hibah
            $insertId = $this->hibahModel->insert($data);

            if ($insertId) {
                // Complete transaction
                $db->transComplete();

                // Check transaction status
                if ($db->transStatus() === false) {
                    // Transaction failed, delete uploaded file
                    $filePath = $uploadPath . '/' . $fileName;
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                    return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data hibah. Transaksi database gagal.');
                }

                // Success message based on position
                $posisiText = ($posisi_dosen === 'ketua') ? 'Ketua' : 'Anggota';
                $successMessage = "Hibah berhasil diupload dengan posisi <strong>{$posisiText}</strong>. Status: Menunggu verifikasi.";

                return redirect()->to(site_url('hibah/myHibah'))->with('success', $successMessage);
            } else {
                throw new \Exception('Gagal menyimpan data ke database.');
            }
        } catch (\Exception $e) {
            // Rollback transaction
            $db->transRollback();
            
            // Delete uploaded file on error
            $filePath = $uploadPath . '/' . $fileName;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            
            // Log error
            log_message('error', 'Hibah upload error: ' . $e->getMessage());
            
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function myHibah()
    {
        if (userLogin()->role_id != 4) {
            return redirect()->to(site_url('dashboard'));
        }

        $data['title_tab'] = 'Hibah Saya &mdash; LPM UG';
        $data['title'] = 'Hibah Saya';

        $userId = userLogin()->user_id;

        // Get all hibah where user is the uploader
        $data['hibah_list'] = $this->hibahModel
            ->where('tbl_hibah.user_id', $userId)
            ->orderBy('tbl_hibah.created_at', 'DESC')
            ->findAll();

        // Check if user has hibah flag
        $data['user_has_hibah_flag'] = hasHibahFlag($userId);

        return view('dosen/hibah/index', $data);
    }

    public function detail($id)
    {
        if (userLogin()->role_id != 4 && userLogin()->role_id != 1 && userLogin()->role_id != 2) {
            return redirect()->to(site_url('dashboard'));
        }

        $hibah = $this->hibahModel
            ->select('tbl_hibah.*, tbl_users.user_name, tbl_users.nidn')
            ->join('tbl_users', 'tbl_users.user_id = tbl_hibah.user_id', 'left')
            ->find($id);

        if (!$hibah) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Hibah tidak ditemukan.');
        }

        // Check if user has permission to view this hibah
        if (userLogin()->role_id == 4 && $hibah['user_id'] != userLogin()->user_id) {
            return redirect()->to(site_url('hibah/myHibah'))
                ->with('error', 'Anda tidak memiliki akses ke hibah ini.');
        }

        // Since we removed hibah_anggota table, anggota list is just the uploader
        $anggotaList = [
            [
                'user_id' => $hibah['user_id'],
                'user_name' => $hibah['user_name'],
                'nidn' => $hibah['nidn'],
                'posisi' => $hibah['posisi_dosen']
            ]
        ];

        $data['title_tab'] = 'Detail Hibah &mdash; LPM UG';
        $data['title'] = 'Detail Hibah';
        $data['hibah'] = $hibah;
        $data['anggota_list'] = $anggotaList;

        return view('dosen/hibah/detail', $data);
    }

    public function downloadProposal($id)
    {
        $hibah = $this->hibahModel->find($id);
        
        if (!$hibah || empty($hibah['proposal_file'])) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('File tidak ditemukan.');
        }

        // Check if user has permission to view this file
        $hasAccess = false;
        
        if (userLogin()->role_id == 1 || userLogin()->role_id == 2) {
            // Admin and verifier can access all files
            $hasAccess = true;
        } elseif (userLogin()->role_id == 4 && $hibah['user_id'] == userLogin()->user_id) {
            // Dosen can access their own files
            $hasAccess = true;
        }

        if (!$hasAccess) {
            return redirect()->to(site_url('hibah/myHibah'))
                ->with('error', 'Anda tidak memiliki akses ke file ini.');
        }

        $filePath = WRITEPATH . 'uploads/hibah/' . $hibah['proposal_file'];
        
        if (!file_exists($filePath)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('File tidak ditemukan di server.');
        }

        // Check if it's a download request or inline view
        $isDownload = $this->request->getGet('download') == '1';
        $disposition = $isDownload ? 'attachment' : 'inline';

        // Get file info
        $fileSize = filesize($filePath);
        $fileName = basename($filePath);
        
        // Set headers
        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', $disposition . '; filename="' . $fileName . '"')
            ->setHeader('Content-Length', $fileSize)
            ->setHeader('Cache-Control', 'private, max-age=0, must-revalidate')
            ->setHeader('Pragma', 'public')
            ->setBody(file_get_contents($filePath));
    }

    public function submit($id)
    {
        if (userLogin()->role_id != 4) {
            return redirect()->to(site_url('dashboard'));
        }

        $hibah = $this->hibahModel->find($id);
        
        if (!$hibah) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Hibah tidak ditemukan.');
        }

        // Check if user owns this hibah
        if ($hibah['user_id'] != userLogin()->user_id) {
            return redirect()->to(site_url('hibah/myHibah'))
                ->with('error', 'Anda tidak memiliki akses ke hibah ini.');
        }

        // Only allow submit if status is draft
        if ($hibah['status'] != 'draft') {
            return redirect()->to(site_url('hibah/detail/' . $id))
                ->with('error', 'Hibah sudah disubmit atau tidak dapat disubmit lagi.');
        }

        // Update status
        $updated = $this->hibahModel->update($id, [
            'status' => 'submitted',
            'verification_status' => 'submitted',
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if ($updated) {
            return redirect()->to(site_url('hibah/myHibah'))
                ->with('success', 'Hibah berhasil disubmit untuk verifikasi.');
        } else {
            return redirect()->to(site_url('hibah/detail/' . $id))
                ->with('error', 'Gagal mensubmit hibah. Silakan coba lagi.');
        }
    }

    public function delete($id)
    {
        if (userLogin()->role_id != 4) {
            return redirect()->to(site_url('dashboard'));
        }

        $hibah = $this->hibahModel->find($id);
        
        if (!$hibah) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Hibah tidak ditemukan.');
        }

        // Check if user owns this hibah
        if ($hibah['user_id'] != userLogin()->user_id) {
            return redirect()->to(site_url('hibah/myHibah'))
                ->with('error', 'Anda tidak memiliki akses ke hibah ini.');
        }

        // Only allow deletion if status is draft
        if ($hibah['status'] != 'draft') {
            return redirect()->to(site_url('hibah/detail/' . $id))
                ->with('error', 'Hibah yang sudah disubmit tidak dapat dihapus.');
        }

        // Start transaction
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Delete the hibah record
            $deleted = $this->hibahModel->delete($id);

            if ($deleted) {
                // Delete file if exists
                if (!empty($hibah['proposal_file'])) {
                    $filePath = WRITEPATH . 'uploads/hibah/' . $hibah['proposal_file'];
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }

                $db->transComplete();

                if ($db->transStatus() === false) {
                    throw new \Exception('Transaksi database gagal.');
                }

                return redirect()->to(site_url('hibah/myHibah'))
                    ->with('success', 'Hibah berhasil dihapus.');
            } else {
                throw new \Exception('Gagal menghapus data hibah.');
            }
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Delete hibah error: ' . $e->getMessage());
            
            return redirect()->to(site_url('hibah/detail/' . $id))
                ->with('error', 'Gagal menghapus hibah: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        if (userLogin()->role_id != 4) {
            return redirect()->to(site_url('dashboard'));
        }

        $hibah = $this->hibahModel->find($id);
        
        if (!$hibah) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Hibah tidak ditemukan.');
        }

        // Check if user owns this hibah
        if ($hibah['user_id'] != userLogin()->user_id) {
            return redirect()->to(site_url('hibah/myHibah'))
                ->with('error', 'Anda tidak memiliki akses ke hibah ini.');
        }

        // Only allow edit if status is draft
        if ($hibah['status'] != 'draft') {
            return redirect()->to(site_url('hibah/detail/' . $id))
                ->with('error', 'Hibah yang sudah disubmit tidak dapat diedit.');
        }

        $data['title_tab'] = 'Edit Hibah &mdash; LPM UG';
        $data['title'] = 'Edit Hibah';
        $data['hibah'] = $hibah;
        $data['validation'] = \Config\Services::validation();

        return view('dosen/hibah/edit', $data);
    }

    public function doEdit($id)
    {
        if (userLogin()->role_id != 4) {
            return redirect()->to(site_url('dashboard'));
        }

        $hibah = $this->hibahModel->find($id);
        
        if (!$hibah) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Hibah tidak ditemukan.');
        }

        // Check if user owns this hibah
        if ($hibah['user_id'] != userLogin()->user_id) {
            return redirect()->to(site_url('hibah/myHibah'))
                ->with('error', 'Anda tidak memiliki akses ke hibah ini.');
        }

        // Only allow edit if status is draft
        if ($hibah['status'] != 'draft') {
            return redirect()->to(site_url('hibah/detail/' . $id))
                ->with('error', 'Hibah yang sudah disubmit tidak dapat diedit.');
        }

        // Validation
        $errors = [];

        $judul = trim($this->request->getPost('judul'));
        if (empty($judul)) {
            $errors['judul'] = 'Judul hibah wajib diisi.';
        }

        $posisi_dosen = $this->request->getPost('posisi_dosen');
        if (empty($posisi_dosen)) {
            $errors['posisi_dosen'] = 'Posisi dosen wajib dipilih.';
        } elseif (!in_array($posisi_dosen, ['ketua', 'anggota'], true)) {
            $errors['posisi_dosen'] = 'Posisi dosen tidak valid.';
        }

        // Check if new file is uploaded
        $file = $this->request->getFile('proposal_file');
        $newFileName = null;
        
        if ($file && $file->isValid() && $file->getSize() > 0) {
            if ($file->getSize() > 5242880) {
                $errors['proposal_file'] = 'Ukuran file maksimal 5MB.';
            } elseif ($file->getMimeType() !== 'application/pdf') {
                $errors['proposal_file'] = 'Format file harus PDF.';
            }
        }

        if (!empty($errors)) {
            return redirect()->back()->withInput()->with('validation_errors', $errors);
        }

        // Handle file upload if new file provided
        if ($file && $file->isValid() && $file->getSize() > 0) {
            $uploadPath = WRITEPATH . 'uploads/hibah';
            $newFileName = $file->getRandomName();
            
            try {
                $file->move($uploadPath, $newFileName);
                
                // Delete old file
                if (!empty($hibah['proposal_file'])) {
                    $oldFilePath = $uploadPath . '/' . $hibah['proposal_file'];
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }
            } catch (\Exception $e) {
                return redirect()->back()->withInput()
                    ->with('error', 'Gagal mengupload file baru.');
            }
        }

        // Update data
        $ketuaId = ($posisi_dosen === 'ketua') ? userLogin()->user_id : null;
        
        $updateData = [
            'ketua_id' => $ketuaId,
            'judul' => $judul,
            'anggaran' => $this->request->getPost('anggaran') ?: 0,
            'tanggal_mulai' => $this->request->getPost('tanggal_mulai'),
            'tanggal_selesai' => $this->request->getPost('tanggal_selesai'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'posisi_dosen' => $posisi_dosen,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($newFileName) {
            $updateData['proposal_file'] = $newFileName;
        }

        // Start transaction
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $this->hibahModel->update($id, $updateData);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Transaksi database gagal.');
            }

            return redirect()->to(site_url('hibah/detail/' . $id))
                ->with('success', 'Hibah berhasil diupdate.');
        } catch (\Exception $e) {
            $db->transRollback();
            
            // Delete new file if uploaded
            if ($newFileName) {
                $filePath = WRITEPATH . 'uploads/hibah/' . $newFileName;
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            
            return redirect()->back()->withInput()
                ->with('error', 'Gagal mengupdate hibah.');
        }
    }
}

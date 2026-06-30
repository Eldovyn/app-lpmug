<?= $this->extend('layouts/default') ?>

<?php
// Language Support - ID/EN with Cookies
$lang = $_COOKIE['lang'] ?? 'id';

$translations = [
    'id' => [
        'title_tab' => 'Detail Hibah',
        'title' => 'Detail Hibah',
        'dashboard' => 'Dashboard',
        'hibah_verification' => 'Verifikasi Hibah',
        'detail' => 'Detail',
        'hibah_details' => 'Detail Hibah',
        'judul_hibah' => 'Judul Hibah',
        'pengusul' => 'Pengusul',
        'posisi_dosen' => 'Posisi Dosen',
        'deskripsi' => 'Deskripsi',
        'status_verifikasi' => 'Status Verifikasi',
        'tanggal_upload' => 'Tanggal Upload',
        'tanggal_verifikasi' => 'Tanggal Verifikasi',
        'catatan_verifikasi' => 'Catatan Verifikasi',
        'file_proposal' => 'File Proposal',
        'download_proposal' => 'Download Proposal',
        'file_not_available' => 'File proposal tidak tersedia',
        'aksi_verifikasi' => 'Aksi Verifikasi',
        'catatan_opsional' => 'Catatan (Opsional)',
        'catatan_placeholder' => 'Catatan persetujuan...',
        'approve_hibah' => 'Approve Hibah',
        'catatan_penolakan' => 'Catatan Penolakan',
        'alasan_penolakan' => 'Alasan penolakan...',
        'reject_hibah' => 'Reject Hibah',
        'submitted' => 'Submitted',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'draft' => 'Draft',
        'confirm_approve' => 'Approve hibah ini?',
        'confirm_reject' => 'Reject hibah ini?',
        'language' => 'Bahasa',
    ],
    'en' => [
        'title_tab' => 'Hibah Detail',
        'title' => 'Hibah Detail',
        'dashboard' => 'Dashboard',
        'hibah_verification' => 'Hibah Verification',
        'detail' => 'Detail',
        'hibah_details' => 'Hibah Details',
        'judul_hibah' => 'Hibah Title',
        'pengusul' => 'Proposer',
        'posisi_dosen' => 'Lecturer Position',
        'deskripsi' => 'Description',
        'status_verifikasi' => 'Verification Status',
        'tanggal_upload' => 'Upload Date',
        'tanggal_verifikasi' => 'Verification Date',
        'catatan_verifikasi' => 'Verification Notes',
        'file_proposal' => 'Proposal File',
        'download_proposal' => 'Download Proposal',
        'file_not_available' => 'Proposal file not available',
        'aksi_verifikasi' => 'Verification Action',
        'catatan_opsional' => 'Notes (Optional)',
        'catatan_placeholder' => 'Approval notes...',
        'approve_hibah' => 'Approve Hibah',
        'catatan_penolakan' => 'Rejection Notes',
        'alasan_penolakan' => 'Rejection reason...',
        'reject_hibah' => 'Reject Hibah',
        'submitted' => 'Submitted',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'draft' => 'Draft',
        'confirm_approve' => 'Approve this hibah?',
        'confirm_reject' => 'Reject this hibah?',
        'language' => 'Language',
    ]
];

function t($key, $lang = 'id')
{
    global $translations;
    return $translations[$lang][$key] ?? $key;
}

// Handle language switch
if (isset($_GET['lang'])) {
    $newLang = $_GET['lang'];
    if (in_array($newLang, ['id', 'en'])) {
        setcookie('lang', $newLang, time() + (86400 * 30), '/');
        $lang = $newLang;
    }
}
?>

<?= $this->section('title') ?>
<title><?= t('title_tab', $lang); ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <a href="<?= site_url('hibah/verification-list'); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
        <h1><?= t('title', $lang); ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>"><?= t('dashboard', $lang); ?></a></div>
            <div class="breadcrumb-item"><a href="<?= site_url('hibah/verification-list'); ?>"><?= t('hibah_verification', $lang); ?></a></div>
            <div class="breadcrumb-item"><?= t('detail', $lang); ?></div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4><?= t('hibah_details', $lang); ?></h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <table class="table table-striped">
                                    <tr>
                                        <th width="200"><?= t('judul_hibah', $lang); ?></th>
                                        <td>: <?= esc($hibah['judul']); ?></td>
                                    </tr>
                                    <tr>
                                        <th><?= t('pengusul', $lang); ?></th>
                                        <td>: <?= esc($hibah['user_name']); ?> (ID: <?= esc($hibah['user_id']); ?>)</td>
                                    </tr>
                                    <tr>
                                        <th><?= t('posisi_dosen', $lang); ?></th>
                                        <td>: <?= ucfirst(esc($hibah['posisi_dosen'])); ?></td>
                                    </tr>
                                    <tr>
                                        <th><?= t('deskripsi', $lang); ?></th>
                                        <td>: <?= esc($hibah['deskripsi']); ?></td>
                                    </tr>
                                    <tr>
                                        <th><?= t('status_verifikasi', $lang); ?></th>
                                        <td>:
                                            <?php
                                            $statusClass = '';
                                            switch ($hibah['verification_status']) {
                                                case 'submitted':
                                                    $statusClass = 'warning';
                                                    $statusText = t('submitted', $lang);
                                                    break;
                                                case 'approved':
                                                    $statusClass = 'success';
                                                    $statusText = t('approved', $lang);
                                                    break;
                                                case 'rejected':
                                                    $statusClass = 'danger';
                                                    $statusText = t('rejected', $lang);
                                                    break;
                                                default:
                                                    $statusClass = 'secondary';
                                                    $statusText = t('draft', $lang);
                                                    break;
                                            }
                                            ?>
                                            <span class="badge badge-<?= $statusClass; ?>"><?= $statusText; ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><?= t('tanggal_upload', $lang); ?></th>
                                        <td>: <?= date('d M Y H:i', strtotime($hibah['created_at'])); ?></td>
                                    </tr>
                                    <?php if (!empty($hibah['verified_at'])): ?>
                                        <tr>
                                            <th><?= t('tanggal_verifikasi', $lang); ?></th>
                                            <td>: <?= date('d M Y H:i', strtotime($hibah['verified_at'])); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if (!empty($hibah['verification_notes'])): ?>
                                        <tr>
                                            <th><?= t('catatan_verifikasi', $lang); ?></th>
                                            <td>: <?= esc($hibah['verification_notes']); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h6><?= t('file_proposal', $lang); ?></h6>
                                    </div>
                                    <div class="card-body">
                                        <?php if (!empty($hibah['proposal_file'])): ?>
                                            <p><strong>File:</strong> <?= esc($hibah['proposal_file']); ?></p>
                                            <a href="<?= site_url('hibah/download-proposal/' . $hibah['id']); ?>" class="btn btn-primary btn-block" target="_blank">
                                                <i class="fas fa-download"></i> <?= t('download_proposal', $lang); ?>
                                            </a>
                                        <?php else: ?>
                                            <p class="text-muted"><?= t('file_not_available', $lang); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <?php if ($hibah['verification_status'] == 'submitted'): ?>
                                    <div class="card">
                                        <div class="card-header">
                                            <h6><?= t('aksi_verifikasi', $lang); ?></h6>
                                        </div>
                                        <div class="card-body">
                                            <form action="<?= site_url('hibah/approve/' . $hibah['id']); ?>" method="POST" class="mb-2">
                                                <?= csrf_field(); ?>
                                                <div class="form-group">
                                                    <label for="verification_notes_approve"><?= t('catatan_opsional', $lang); ?></label>
                                                    <textarea name="verification_notes" id="verification_notes_approve" class="form-control" rows="3" placeholder="<?= t('catatan_placeholder', $lang); ?>"></textarea>
                                                </div>
                                                <button type="submit" class="btn btn-success btn-block" onclick="return confirm('<?= t('confirm_approve', $lang); ?>')">
                                                    <i class="fas fa-check"></i> <?= t('approve_hibah', $lang); ?>
                                                </button>
                                            </form>

                                            <form action="<?= site_url('hibah/reject/' . $hibah['id']); ?>" method="POST">
                                                <?= csrf_field(); ?>
                                                <div class="form-group">
                                                    <label for="verification_notes_reject"><?= t('catatan_penolakan', $lang); ?> <span class="text-danger">*</span></label>
                                                    <textarea name="verification_notes" id="verification_notes_reject" class="form-control" rows="3" placeholder="<?= t('alasan_penolakan', $lang); ?>" required></textarea>
                                                </div>
                                                <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('<?= t('confirm_reject', $lang); ?>')">
                                                    <i class="fas fa-times"></i> <?= t('reject_hibah', $lang); ?>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>

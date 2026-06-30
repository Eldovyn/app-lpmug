<?= $this->extend('layouts/default') ?>

<?php
helper(['cookie', 'url']);

$request = service('request');

// bahasa aktif
$allowed = ['id', 'en'];
$lang    = get_cookie('lang') ?: 'id';
if (! in_array($lang, $allowed, true)) {
    $lang = 'id';
}

$reqLang = $request->getGet('lang');
if ($reqLang && in_array($reqLang, $allowed, true)) {
    set_cookie('lang', $reqLang, 60 * 60 * 24 * 30);
    $lang = $reqLang;
}

// dictionary
$dict = [
    'id' => [
        'hibah' => 'Hibah',
        'dashboard' => 'Dashboard',
        'detail' => 'Detail',
        'detail_hibah' => 'Detail Hibah',
        'back' => 'Kembali',
        'judul_hibah' => 'Judul Hibah',
        'pengusul' => 'Pengusul',
        'posisi_dosen' => 'Posisi Dosen',
        'status' => 'Status',
        'status_verifikasi' => 'Status Verifikasi',
        'diverifikasi_pada' => 'Diverifikasi Pada',
        'dibuat_pada' => 'Dibuat Pada',
        'diupdate_pada' => 'Diupdate Pada',
        'deskripsi' => 'Deskripsi',
        'catatan_verifikasi' => 'Catatan Verifikasi',
        'alasan_penolakan' => 'Alasan Penolakan',
        'file_proposal' => 'File Proposal',
        'lihat_file' => 'Lihat File',
        'download' => 'Download',
        'aksi' => 'Aksi',
        'edit_hibah' => 'Edit Hibah',
        'submit_verifikasi' => 'Submit untuk Verifikasi',
        'hapus_hibah' => 'Hapus Hibah',
        'edit_submit_ulang' => 'Edit & Submit Ulang',
        'submit_confirm' => 'Apakah Anda yakin ingin submit hibah ini untuk verifikasi?',
        'delete_confirm' => 'Apakah Anda yakin ingin menghapus hibah ini?',
        'status_draft' => 'Draft',
        'status_submitted' => 'Diajukan',
        'status_approved' => 'Disetujui',
        'status_rejected' => 'Ditolak',
        'ketua' => 'Ketua',
        'anggota' => 'Anggota',
    ],
    'en' => [
        'hibah' => 'Grant',
        'dashboard' => 'Dashboard',
        'detail' => 'Detail',
        'detail_hibah' => 'Grant Detail',
        'back' => 'Back',
        'judul_hibah' => 'Grant Title',
        'pengusul' => 'Proposer',
        'posisi_dosen' => 'Lecturer Position',
        'status' => 'Status',
        'status_verifikasi' => 'Verification Status',
        'diverifikasi_pada' => 'Verified At',
        'dibuat_pada' => 'Created At',
        'diupdate_pada' => 'Updated At',
        'deskripsi' => 'Description',
        'catatan_verifikasi' => 'Verification Notes',
        'alasan_penolakan' => 'Rejection Reason',
        'file_proposal' => 'Proposal File',
        'lihat_file' => 'View File',
        'download' => 'Download',
        'aksi' => 'Actions',
        'edit_hibah' => 'Edit Grant',
        'submit_verifikasi' => 'Submit for Verification',
        'hapus_hibah' => 'Delete Grant',
        'edit_submit_ulang' => 'Edit & Resubmit',
        'submit_confirm' => 'Are you sure you want to submit this grant for verification?',
        'delete_confirm' => 'Are you sure you want to delete this grant?',
        'status_draft' => 'Draft',
        'status_submitted' => 'Submitted',
        'status_approved' => 'Approved',
        'status_rejected' => 'Rejected',
        'ketua' => 'Chair',
        'anggota' => 'Member',
    ],
];

$t = $dict[$lang] ?? $dict['id'];

function getStatusBadgeClass($status)
{
    $classes = [
        'draft' => 'warning',
        'submitted' => 'info',
        'approved' => 'success',
        'rejected' => 'danger'
    ];
    return $classes[$status] ?? 'secondary';
}

function getStatusLabel($status, $t)
{
    $labels = [
        'draft' => $t['status_draft'] ?? 'Draft',
        'submitted' => $t['status_submitted'] ?? 'Submitted',
        'approved' => $t['status_approved'] ?? 'Approved',
        'rejected' => $t['status_rejected'] ?? 'Rejected'
    ];
    return $labels[$status] ?? ucfirst($status);
}
?>

<?= $this->section('title') ?>
<title><?= esc($title_tab); ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="section">
    <div class="section-header">
        <a href="<?= site_url('hibah/myHibah'); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
        <h1><?= esc($title); ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>"><?= esc($t['dashboard']); ?></a></div>
            <div class="breadcrumb-item active"><a href="<?= site_url('hibah/myHibah'); ?>"><?= esc($t['hibah']); ?></a></div>
            <div class="breadcrumb-item"><?= esc($t['detail']); ?></div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4><?= esc($t['detail_hibah']); ?></h4>
                        <div class="card-header-action">
                            <a href="<?= site_url('hibah/myHibah'); ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> <?= esc($t['back']); ?>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <tbody>
                                            <tr>
                                                <th width="200"><?= esc($t['judul_hibah']); ?></th>
                                                <td><?= esc($hibah['judul']); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?= esc($t['pengusul']); ?></th>
                                                <td><?= esc($hibah['user_name']); ?> (NIDN: <?= esc($hibah['nidn']); ?>)</td>
                                            </tr>
                                            <tr>
                                                <th><?= esc($t['posisi_dosen']); ?></th>
                                                <td><?= ucfirst(esc($hibah['posisi_dosen'] == 'ketua' ? $t['ketua'] : $t['anggota'])); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?= esc($t['status']); ?></th>
                                                <td>
                                                    <span class="badge badge-<?= getStatusBadgeClass($hibah['status']); ?>">
                                                        <?= getStatusLabel($hibah['status'], $t); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th><?= esc($t['status_verifikasi']); ?></th>
                                                <td>
                                                    <span class="badge badge-<?= getStatusBadgeClass($hibah['verification_status']); ?>">
                                                        <?= getStatusLabel($hibah['verification_status'], $t); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <?php if (!empty($hibah['verified_at'])): ?>
                                                <tr>
                                                    <th><?= esc($t['diverifikasi_pada']); ?></th>
                                                    <td><?= date('d M Y H:i', strtotime($hibah['verified_at'])); ?></td>
                                                </tr>
                                            <?php endif; ?>
                                            <tr>
                                                <th><?= esc($t['dibuat_pada']); ?></th>
                                                <td><?= date('d M Y H:i', strtotime($hibah['created_at'])); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?= esc($t['diupdate_pada']); ?></th>
                                                <td><?= date('d M Y H:i', strtotime($hibah['updated_at'])); ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <?php if (!empty($hibah['deskripsi'])): ?>
                                    <div class="mt-4">
                                        <h6><?= esc($t['deskripsi']); ?></h6>
                                        <div class="border p-3 rounded bg-light">
                                            <?= nl2br(esc($hibah['deskripsi'])); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if ($hibah['verification_status'] == 'rejected' && !empty($hibah['verification_notes'])): ?>
                                    <div class="mt-4">
                                        <h6><?= esc($t['catatan_verifikasi']); ?></h6>
                                        <div class="alert alert-danger">
                                            <strong><?= esc($t['alasan_penolakan']); ?>:</strong><br>
                                            <?= nl2br(esc($hibah['verification_notes'])); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-4">
                                <?php if (!empty($hibah['proposal_file'])): ?>
                                    <div class="card mb-3">
                                        <div class="card-header">
                                            <h6><i class="fas fa-file-pdf"></i> <?= esc($t['file_proposal']); ?></h6>
                                        </div>
                                        <div class="card-body text-center">
                                            <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                                            <p class="mb-2"><strong><?= $hibah['proposal_file']; ?></strong></p>
                                            <div class="btn-group-vertical w-100">
                                                <a href="<?= site_url('hibah/download-proposal/' . $hibah['id']); ?>"
                                                    target="_blank"
                                                    class="btn btn-primary btn-sm mb-2">
                                                    <i class="fas fa-eye"></i> <?= esc($t['lihat_file']); ?>
                                                </a>
                                                <a href="<?= site_url('hibah/download-proposal/' . $hibah['id']) . '?download=1'; ?>"
                                                    class="btn btn-success btn-sm">
                                                    <i class="fas fa-download"></i> <?= esc($t['download']); ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="card">
                                    <div class="card-header">
                                        <h6><i class="fas fa-cogs"></i> <?= esc($t['aksi']); ?></h6>
                                    </div>
                                    <div class="card-body">
                                        <?php if ($hibah['status'] == 'draft'): ?>
                                            <a href="<?= site_url('hibah/edit/' . $hibah['id']); ?>"
                                                class="btn btn-warning btn-block mb-2">
                                                <i class="fas fa-edit"></i> <?= esc($t['edit_hibah']); ?>
                                            </a>
                                            <button onclick="submitHibah(<?= $hibah['id']; ?>)"
                                                class="btn btn-info btn-block mb-2">
                                                <i class="fas fa-paper-plane"></i> <?= esc($t['submit_verifikasi']); ?>
                                            </button>
                                            <button onclick="deleteHibah(<?= $hibah['id']; ?>)"
                                                class="btn btn-danger btn-block">
                                                <i class="fas fa-trash"></i> <?= esc($t['hapus_hibah']); ?>
                                            </button>
                                        <?php elseif ($hibah['status'] == 'rejected'): ?>
                                            <a href="<?= site_url('hibah/edit/' . $hibah['id']); ?>"
                                                class="btn btn-warning btn-block">
                                                <i class="fas fa-edit"></i> <?= esc($t['edit_submit_ulang']); ?>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function submitHibah(id) {
        if (confirm('<?= $t['submit_confirm']; ?>')) {
            window.location.href = '<?= site_url('hibah/submit/'); ?>' + id;
        }
    }

    function deleteHibah(id) {
        if (confirm('<?= $t['delete_confirm']; ?>')) {
            window.location.href = '<?= site_url('hibah/delete/'); ?>' + id;
        }
    }
</script>

<?= $this->endSection(); ?>

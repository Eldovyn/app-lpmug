<?php
helper(['cookie', 'url']);

$request = service('request');

// bahasa aktif - check query param first, then cookie, default to id
$allowed = ['id', 'en'];
$lang = $request->getGet('lang');
if (! $lang) {
    $lang = get_cookie('lang') ?: 'id';
}
if (! in_array($lang, $allowed, true)) {
    $lang = 'id';
}

// Set cookie if lang from query param
if ($request->getGet('lang')) {
    set_cookie('lang', $lang, 60 * 60 * 24 * 30);
}

// dictionary
$dict = [
    'id' => [
        'page_title' => 'Upload Surat Undangan',
        'upload_title' => 'Upload Surat Undangan',
        'select_file' => 'Pilih File Surat Undangan (PDF max 5MB)',
        'file_format' => 'Format file harus PDF dengan ukuran maksimal 5MB',
        'upload_btn' => 'Upload Surat Undangan',
        'back' => 'Kembali',
        'uploaded_file' => 'File Surat Undangan yang sudah diupload',
        'filename' => 'Nama File',
        'preview' => 'Preview',
        'download' => 'Download',
        'no_file' => 'Belum ada file Surat Undangan',
        'no_file_desc' => 'Upload file surat undangan untuk kegiatan ini.',
    ],
    'en' => [
        'page_title' => 'Upload Invitation Letter',
        'upload_title' => 'Upload Invitation Letter',
        'select_file' => 'Select Invitation Letter File (PDF max 5MB)',
        'file_format' => 'File format must be PDF with maximum size 5MB',
        'upload_btn' => 'Upload Invitation Letter',
        'back' => 'Back',
        'uploaded_file' => 'Uploaded Invitation Letter File',
        'filename' => 'File Name',
        'preview' => 'Preview',
        'download' => 'Download',
        'no_file' => 'No Invitation Letter File',
        'no_file_desc' => 'Upload the invitation letter file for this activity.',
    ],
];

$t = $dict[$lang] ?? $dict['id'];
?>

<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php helper('form'); ?>

<section class="section">
    <div class="section-body">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h4><?= esc($t['upload_title']); ?></h4>
            </div>
            <div class="card-body">
                <form action="<?= site_url('pelaksanaan/update-undangan/' . $abdimas->laporan_id) ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <!-- File Upload -->
                    <div class="form-group">
                        <label for="surat_undangan"><?= esc($t['select_file']); ?></label>
                        <input type="file" name="surat_undangan" id="surat_undangan" class="form-control" accept=".pdf" required>
                        <small class="form-text text-muted"><?= esc($t['file_format']); ?></small>
                    </div>

                    <button type="submit" class="btn btn-primary"><?= esc($t['upload_btn']); ?></button>
                    <a href="<?= site_url('pelaksanaan') ?>" class="btn btn-secondary"><?= esc($t['back']); ?></a>
                </form>

                <!-- File yang Sudah Diupload -->
                <?php if (!empty($abdimas->surat_undangan)): ?>
                    <div class="mt-4">
                        <h5><?= esc($t['uploaded_file']); ?>:</h5>
                        <div class="card">
                            <div class="card-body">
                                <p><strong><?= esc($t['filename']); ?>:</strong> <?= esc(basename($abdimas->surat_undangan)) ?></p>
                                <div class="btn-group">
                                    <a href="<?= site_url('berkas/undangan/' . $abdimas->surat_undangan) ?>"
                                        target="_blank" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> <?= esc($t['preview']); ?>
                                    </a>
                                    <a href="<?= site_url('berkas/undangan/' . $abdimas->surat_undangan) ?>"
                                        download class="btn btn-sm btn-success">
                                        <i class="fas fa-download"></i> <?= esc($t['download']); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="mt-4">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong><?= esc($t['no_file']); ?></strong><br>
                            <?= esc($t['no_file_desc']); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
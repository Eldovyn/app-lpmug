<?php
helper(['cookie', 'url']);

$request = service('request');

$allowed = ['id', 'en'];
$lang = get_cookie('lang') ?: 'id';
if (! in_array($lang, $allowed, true)) {
    $lang = 'id';
}

$reqLang = $request->getGet('lang');
if ($reqLang && in_array($reqLang, $allowed, true)) {
    set_cookie('lang', $reqLang, 60 * 60 * 24 * 30);
    $lang = $reqLang;
}

// Array terjemahan
$I18N = [
    'id' => [
        'dashboard' => 'Dashboard',
        'dataAbdimas' => 'Data abdimas',

        'uploadReport' => 'Upload Laporan',
        'noteRequired' => 'Note:',
        'maxFileSize' => 'Max File Size: 5 MB',
        'compressNote' => 'Jika file anda melebihi Max Size silahkan di kompres terlebih dahulu. bisa menggunakan:',
        'compressLink' => 'Compress PDF Online',
        'currentFile' => 'File saat ini:',
        'required' => '*',

        'uploadEvidence' => 'Upload Bukti Kegiatan',

        'linkOutput' => 'Link Luaran',
        'linkExample' => 'Contoh:',
        'linkPlaceholder' => 'Masukan Link Luaran',

        'btnViewReport' => 'Lihat Laporan',
        'btnViewEvidence' => 'Lihat Bukti Kegiatan',
        'btnUploadReport' => 'Silahkan upload laporan',
        'btnUploadEvidence' => 'Silahkan upload bukti kegiatan',

        'btnBack' => 'kembali',
        'btnSave' => 'Simpan',

        'activityDate' => 'Tanggal Kegiatan',
        'startDate' => 'Tanggal Mulai',
        'endDate' => 'Tanggal Selesai',
        'startDatePlaceholder' => 'Pilih tanggal mulai kegiatan',
        'endDatePlaceholder' => 'Pilih tanggal selesai kegiatan',

        'activityTitle' => 'Judul Kegiatan',
        'activityTitlePlaceholder' => 'Masukan Judul Kegiatan',
    ],
    'en' => [
        'dashboard' => 'Dashboard',
        'dataAbdimas' => 'Abdimas Data',

        'uploadReport' => 'Upload Report',
        'noteRequired' => 'Note:',
        'maxFileSize' => 'Max File Size: 5 MB',
        'compressNote' => 'If your file exceeds the Max Size, please compress it first. You can use:',
        'compressLink' => 'Compress PDF Online',
        'currentFile' => 'Current file:',
        'required' => '*',

        'uploadEvidence' => 'Upload Activity Evidence',

        'linkOutput' => 'Output Link',
        'linkExample' => 'Example:',
        'linkPlaceholder' => 'Enter Output Link',

        'btnViewReport' => 'View Report',
        'btnViewEvidence' => 'View Activity Evidence',
        'btnUploadReport' => 'Please upload report',
        'btnUploadEvidence' => 'Please upload activity evidence',

        'btnBack' => 'back',
        'btnSave' => 'Save',

        'activityDate' => 'Activity Date',
        'startDate' => 'Start Date',
        'endDate' => 'End Date',
        'startDatePlaceholder' => 'Select activity start date',
        'endDatePlaceholder' => 'Select activity end date',

        'activityTitle' => 'Activity Title',
        'activityTitlePlaceholder' => 'Enter Activity Title',
    ],
];

// Helper function untuk translate
$t = function (string $key, ...$args) use ($I18N, $lang) {
    $text = $I18N[$lang][$key] ?? $I18N['id'][$key] ?? $key;
    return $args ? vsprintf($text, $args) : $text;
};
?>

<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <a href="<?= site_url('pelaporan'); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
        <h1><?= $title; ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>"><?= $t('dashboard'); ?></a></div>
            <div class="breadcrumb-item active"><a href="<?= site_url('pelaporan'); ?>"><?= $t('dataAbdimas'); ?></a></div>
            <div class="breadcrumb-item"><?= $title; ?></div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= site_url('pelaporan/' . $abdimas->laporan_id); ?>" method="POST" autocomplete="off" enctype="multipart/form-data">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="_method" value="PATCH">

                            <div class="form-group">
                                <label><?= $t('activityDate'); ?></label>
                                <div class="row">
                                    <?php
                                    // Ambil tanggal dari database (format: "YYYY-MM-DD - YYYY-MM-DD")
                                    $tanggal = explode(' - ', $abdimas->tanggal_kegiatan ?? '');
                                    $tanggalMulai = $tanggal[0] ?? '';
                                    $tanggalSelesai = $tanggal[1] ?? '';
                                    ?>
                                    <div class="col">
                                        <label for="tanggal_mulai" class="form-label"><?= $t('startDate'); ?></label>
                                        <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                                            class="form-control"
                                            value="<?= $tanggalMulai; ?>"
                                            placeholder="<?= $t('startDatePlaceholder'); ?>" required>
                                    </div>
                                    <div class="col">
                                        <label for="tanggal_selesai" class="form-label"><?= $t('endDate'); ?></label>
                                        <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                                            class="form-control"
                                            value="<?= $tanggalSelesai; ?>"
                                            placeholder="<?= $t('endDatePlaceholder'); ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?= $t('activityTitle'); ?></label>
                                <input type="text" name="judul_kegiatan" value="<?= esc($abdimas->judul_kegiatan); ?>" id="judul_kegiatan" class="form-control" placeholder="<?= $t('activityTitlePlaceholder'); ?>" autofocus>
                            </div>

                            <div class="form-group">
                                <label><?= $t('uploadReport'); ?></label><span class="text-danger"><?= $t('required'); ?></span> <span class="text-primary"><b><?= $t('noteRequired'); ?></b> <?= $t('maxFileSize'); ?></span> | <span><?= $t('compressNote'); ?> <a class="badge badge-primary mb-1" target="_blank" href="https://www.ilovepdf.com/compress_pdf"><?= $t('compressLink'); ?></a></span>
                                <?php if ($abdimas->laporan): ?>
                                    <p class="text-muted"><?= $t('currentFile'); ?> <strong><?= esc($abdimas->laporan); ?></strong></p>
                                <?php endif; ?>
                                <input type="file" name="laporan" id="laporan" class="form-control <?= (session('validation')) ? 'is-invalid' : ''; ?>" autofocus required>
                                <div class="invalid-feedback">
                                    <?php if (session('validation') && session('validation')->hasError('laporan')): ?>
                                        <?= session('validation')->getError('laporan'); ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?= $t('uploadEvidence'); ?></label><span class="text-danger"><?= $t('required'); ?></span> <span class="text-primary"><b><?= $t('noteRequired'); ?></b> <?= $t('maxFileSize'); ?></span> | <span><?= $t('compressNote'); ?> <a class="badge badge-primary mb-1" target="_blank" href="https://www.ilovepdf.com/compress_pdf"><?= $t('compressLink'); ?></a></span>
                                <?php if ($abdimas->bukti_kegiatan): ?>
                                    <p class="text-muted"><?= $t('currentFile'); ?> <strong><?= esc($abdimas->bukti_kegiatan); ?></strong></p>
                                <?php endif; ?>
                                <input type="file" name="bukti_kegiatan" id="bukti_kegiatan" class="form-control <?= (session('validation')) ? 'is-invalid' : ''; ?>" autofocus required>
                                <div class="invalid-feedback">
                                    <?php if (session('validation') && session('validation')->hasError('bukti_kegiatan')): ?>
                                        <?= session('validation')->getError('bukti_kegiatan'); ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?= $t('linkOutput'); ?> <span class="text-primary"><b><?= $t('linkExample'); ?></b> [http://link.com](http://link.com) atau https://link.com</span></label>
                                <input type="text" name="link_luaran" value="<?= esc($abdimas->link_luaran); ?>" id="link_luaran" class="form-control" placeholder="<?= $t('linkPlaceholder'); ?>" autofocus required>
                            </div>

                            <div class="float-left">
                                <?php if ($abdimas->laporan): ?>
                                    <a href="<?= site_url('berkas/laporan/' . $abdimas->laporan); ?>" class="btn btn-info text-dark" target="_blank"><?= $t('btnViewReport'); ?></a>
                                <?php else: ?>
                                    <span class="btn btn-dark"><?= $t('btnUploadReport'); ?></span>
                                <?php endif; ?>

                                <?php if ($abdimas->bukti_kegiatan): ?>
                                    <a href="<?= site_url('berkas/kegiatan/' . $abdimas->bukti_kegiatan); ?>" class="btn btn-info text-dark" target="_blank"><?= $t('btnViewEvidence'); ?></a>
                                <?php else: ?>
                                    <span class="btn btn-dark"><?= $t('btnUploadEvidence'); ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="float-right">
                                <a href="<?= site_url('abdimas'); ?>" class="btn btn-dark"><?= $t('btnBack'); ?></a>
                                <button type="submit" class="btn btn-primary"><?= $t('btnSave'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
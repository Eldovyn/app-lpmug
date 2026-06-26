<?php
helper(['cookie', 'url']);

$request = service('request');

$allowed = ['id', 'en'];
$lang = get_cookie('lang') ?: 'id';
if (!in_array($lang, $allowed, true)) {
    $lang = 'id';
}

$reqLang = $request->getGet('lang');
if ($reqLang && in_array($reqLang, $allowed, true)) {
    set_cookie('lang', $reqLang, 60 * 60 * 24 * 30);
    $lang = $reqLang;
}

$DICT = [
    'id' => [
        'dashboard'        => 'Dashboard',
        'profilestaff'     => 'profilestaff',
        'staffName'        => 'Nama Staff',
        'divisionPosition' => 'Divisi / Jabatan',
        'uploadPhoto'      => 'Upload photo',
        'reset'            => 'Reset',
        'save'             => 'Simpan',
        'notes'            => 'Keterangan',
        'fullName'         => 'Nama Lengkap',
    ],
    'en' => [
        'dashboard'        => 'Dashboard',
        'profilestaff'     => 'profilestaff',
        'staffName'        => 'Staff Name',
        'divisionPosition' => 'Division / Position',
        'uploadPhoto'      => 'Upload photo',
        'reset'            => 'Reset',
        'save'             => 'Save',
        'notes'            => 'Notes',
        'fullName'         => 'Full Name',
    ],
];

$t = function (string $key) use ($DICT, $lang) {
    return $DICT[$lang][$key] ?? $key;
};
?>

<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <a href="<?= site_url('profilestaff'); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
        <h1><?= $title; ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>"><?= esc($t('dashboard')); ?></a></div>
            <div class="breadcrumb-item active"><a href="<?= site_url('profilestaff'); ?>"><?= esc($t('profilestaff')); ?></a></div>
            <div class="breadcrumb-item"><?= $title; ?></div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= site_url('profilestaff'); ?>" method="POST" enctype="multipart/form-data" autocomplete="off">
                            <?= csrf_field(); ?>
                            <div class="form-group">
                                <label><?= esc($t('staffName')); ?><small class="text-danger">*</small></label>
                                <input type="text" name="judul" class="form-control <?= (session('validation')) ? 'is-invalid' : ''; ?>" autofocus>
                                <div class="invalid-feedback">
                                    <?php if (session('validation') && session('validation')->hasError('judul')): ?>
                                        <?= session('validation')->getError('judul'); ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?= esc($t('divisionPosition')); ?><small class="text-danger">*</small></label>
                                <textarea name="deskripsi" class="summernote" style="min-height:150px;"></textarea>
                                <div class="invalid-feedback">
                                    <?php if (session('validation') && session('validation')->hasError('deskripsi')): ?>
                                        <?= session('validation')->getError('deskripsi'); ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?= esc($t('uploadPhoto')); ?><small class="text-danger">*</small></label>
                                <input type="file" name="gambar" id="gambar" class="form-control <?= (session('validation')) ? 'is-invalid' : ''; ?>" autofocus>
                                <div class="invalid-feedback">
                                    <?php if (session('validation') && session('validation')->hasError('gambar')): ?>
                                        <?= session('validation')->getError('gambar'); ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="text-right">
                                <button type="reset" class="btn btn-danger"><?= esc($t('reset')); ?></button>
                                <button type="submit" class="btn btn-primary"><?= esc($t('save')); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h4><?= esc($t('notes')); ?></h4>
                    </div>
                    <div class="card-body">
                        <div class="section-title mt-0"><?= esc($t('fullName')); ?></div>
                        <div></div>

                        <div class="section-title">Select 2</div>
                        <div class="section-title">jQuery Selectric</div>
                        <div class="section-title">Select Group Button</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
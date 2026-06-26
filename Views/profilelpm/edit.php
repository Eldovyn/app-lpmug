<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>
<?= $this->endSection() ?>

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

$tr = [
    'id' => [
        'dashboard' => 'Dashboard',
        'profile_lpm_data' => 'Data profile lpm',
        'profile_title' => 'Judul profile',
        'content' => 'Isi content',
        'upload_image' => 'Upload gambar',
        'back' => 'kembali',
        'save' => 'Simpan',
    ],
    'en' => [
        'dashboard' => 'Dashboard',
        'profile_lpm_data' => 'LPM profile data',
        'profile_title' => 'Profile title',
        'content' => 'Content',
        'upload_image' => 'Upload image',
        'back' => 'back',
        'save' => 'Save',
    ],
];

$t = function (string $key) use ($tr, $lang) {
    return $tr[$lang][$key] ?? $tr['id'][$key] ?? $key;
};
?>

<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <a href="<?= site_url('profilelpm'); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
        <h1><?= $title; ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>"><?= esc($t('dashboard')); ?></a></div>
            <div class="breadcrumb-item active"><a href="<?= site_url('profilelpm'); ?>"><?= esc($t('profile_lpm_data')); ?></a></div>
            <div class="breadcrumb-item"><?= $title; ?></div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= site_url('profilelpm/' . $profilelpm->profilelpm_id); ?>" method="POST" enctype="multipart/form-data" autocomplete="off">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="_method" value="PATCH">

                            <div class="form-group">
                                <label><?= esc($t('profile_title')); ?><small class="text-danger">*</small></label>
                                <input type="text" name="judul" value="<?= $profilelpm->judul; ?>" class="form-control <?= (session('validation')) ? 'is-invalid' : ''; ?>" autofocus>
                                <div class="invalid-feedback">
                                    <?php if (session('validation') && session('validation')->hasError('judul')): ?>
                                        <?= session('validation')->getError('judul'); ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?= esc($t('content')); ?><small class="text-danger">*</small></label>
                                <textarea name="deskripsi" class="summernote" style="min-height:150px;"><?= $profilelpm->deskripsi; ?></textarea>
                                <div class="invalid-feedback">
                                    <?php if (session('validation') && session('validation')->hasError('deskripsi')): ?>
                                        <?= session('validation')->getError('deskripsi'); ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?= esc($t('upload_image')); ?><small class="text-danger">*</small></label>
                                <input type="file" name="gambar" id="gambar" class="form-control <?= (session('validation')) ? 'is-invalid' : ''; ?>" autofocus>
                                <div class="invalid-feedback">
                                    <?php if (session('validation') && session('validation')->hasError('gambar')): ?>
                                        <?= session('validation')->getError('gambar'); ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="text-right">
                                <a href="<?= site_url('dashboard'); ?>" class="btn btn-dark"><?= esc($t('back')); ?></a>
                                <!-- <button type="reset" class="btn btn-danger">Reset</button> -->
                                <button type="submit" class="btn btn-primary"><?= esc($t('save')); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-5">
                <div class="card">
                    <div class="card-body">
                        <img src="<?= base_url('/img/upload/profilelpm/' . $profilelpm->gambar); ?>" alt="profilelpm" class="w-100">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
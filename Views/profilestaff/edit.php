<?php
helper(['cookie', 'url']);

// ambil lang dari cookie (request)
$lang = get_cookie('lang') ?: 'id';
$lang = strtolower(trim((string) $lang));
if (!in_array($lang, ['id', 'en'], true)) $lang = 'id';

// bikin benar-benar global supaya bisa dipakai di dalam function t()
$GLOBALS['lang'] = $lang;

$dict = [
    'id' => [
        'dashboard' => 'Dashboard',
        'dataprofile' => 'Data profile Staff',
        'namastaff' => 'Nama Staff',
        'divisijabatan' => 'Divisi / Jabatan',
        'uploadphoto' => 'Upload photo',
        'kembali' => 'kembali',
        'simpan' => 'Simpan',
    ],
    'en' => [
        'dashboard' => 'Dashboard',
        'dataprofile' => 'Staff profile data',
        'namastaff' => 'Staff name',
        'divisijabatan' => 'Division / Position',
        'uploadphoto' => 'Upload photo',
        'kembali' => 'Back',
        'simpan' => 'Save',
    ],
];
$GLOBALS['dict'] = $dict;

if (!function_exists('t')) {
    function t(string $key): string
    {
        $lang = $GLOBALS['lang'] ?? 'id';
        $dict = $GLOBALS['dict'] ?? [];

        return $dict[$lang][$key]
            ?? ($dict['id'][$key] ?? $key);
    }
}
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
            <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>"><?= t('dashboard'); ?></a></div>
            <div class="breadcrumb-item active"><a href="<?= site_url('profilestaff'); ?>"><?= t('dataprofile'); ?></a></div>
            <div class="breadcrumb-item"><?= $title; ?></div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= site_url('profilestaff/' . $profilestaff->profilestaff_id); ?>" method="POST" enctype="multipart/form-data" autocomplete="off">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="_method" value="PATCH">

                            <div class="form-group">
                                <label><?= t('namastaff'); ?><small class="text-danger">*</small></label>
                                <input type="text" name="judul" value="<?= $profilestaff->judul; ?>" class="form-control <?= (session('validation')) ? 'is-invalid' : ''; ?>" autofocus>
                                <div class="invalid-feedback">
                                    <?php if (session('validation') && session('validation')->hasError('judul')): ?>
                                        <?= session('validation')->getError('judul'); ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?= t('divisijabatan'); ?><small class="text-danger">*</small></label>
                                <textarea name="deskripsi" class="summernote" style="min-height:150px;"><?= $profilestaff->deskripsi; ?></textarea>
                                <div class="invalid-feedback">
                                    <?php if (session('validation') && session('validation')->hasError('deskripsi')): ?>
                                        <?= session('validation')->getError('deskripsi'); ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?= t('uploadphoto'); ?><small class="text-danger">*</small></label>
                                <input type="file" name="gambar" id="gambar" class="form-control <?= (session('validation')) ? 'is-invalid' : ''; ?>" autofocus>
                                <div class="invalid-feedback">
                                    <?php if (session('validation') && session('validation')->hasError('gambar')): ?>
                                        <?= session('validation')->getError('gambar'); ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="text-right">
                                <a href="<?= site_url('dashboard'); ?>" class="btn btn-dark"><?= t('kembali'); ?></a>
                                <button type="submit" class="btn btn-primary"><?= t('simpan'); ?></button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-5">
                <div class="card">
                    <div class="card-body">
                        <img src="<?= base_url('/img/upload/profilestaff/' . $profilestaff->gambar); ?>" alt="profilestaff" class="w-100">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
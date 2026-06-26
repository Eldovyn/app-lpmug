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

if (! function_exists('t')) {
    function t(string $key): string
    {
        global $dict, $lang;

        return $dict[$lang][$key]
            ?? $dict['id'][$key]
            ?? $key;
    }
}

if (! function_exists('lang_url')) {
    function lang_url(string $locale): string
    {
        $request = service('request');
        $base = current_url();
        $q = $request->getGet();
        $q['lang'] = $locale;

        return $base . '?' . http_build_query($q);
    }
}

$I18N = [
    'id' => [
        'dashboard'      => 'Dashboard',
        'struktur'       => 'struktur',
        'title'          => 'Judul',
        'description'    => 'Deskripsi',
        'uploadStruktur' => 'Upload struktur',
        'reset'          => 'Reset',
        'save'           => 'Simpan',
        'info'           => 'Keterangan',
        'fullName'       => 'Nama Lengkap',
        'select2'        => 'Select 2',
        'selectric'      => 'jQuery Selectric',
        'selectGroup'    => 'Select Group Button',
    ],
    'en' => [
        'dashboard'      => 'Dashboard',
        'struktur'       => 'structure',
        'title'          => 'Title',
        'description'    => 'Description',
        'uploadStruktur' => 'Upload structure',
        'reset'          => 'Reset',
        'save'           => 'Save',
        'info'           => 'Information',
        'fullName'       => 'Full Name',
        'select2'        => 'Select 2',
        'selectric'      => 'jQuery Selectric',
        'selectGroup'    => 'Select Group Button',
    ],
];

$__ = function (string $key, ...$args) use ($I18N, $lang) {
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
        <a href="<?= site_url('struktur'); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
        <h1><?= $title; ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>"><?= $__('dashboard'); ?></a></div>
            <div class="breadcrumb-item active"><a href="<?= site_url('struktur'); ?>"><?= $__('struktur'); ?></a></div>
            <div class="breadcrumb-item"><?= $title; ?></div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= site_url('struktur'); ?>" method="POST" enctype="multipart/form-data" autocomplete="off">
                            <?= csrf_field(); ?>
                            <div class="form-group">
                                <label><?= $__('title'); ?><small class="text-danger">*</small></label>
                                <input type="text" name="judul" class="form-control <?= (session('validation')) ? 'is-invalid' : ''; ?>" autofocus>
                                <div class="invalid-feedback">
                                    <?php if (session('validation') && session('validation')->hasError('judul')): ?>
                                        <?= session('validation')->getError('judul'); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label><?= $__('description'); ?><small class="text-danger">*</small></label>
                                <textarea name="deskripsi" class="summernote" style="min-height:150px;"></textarea>
                                <div class="invalid-feedback">
                                    <?php if (session('validation') && session('validation')->hasError('deskripsi')): ?>
                                        <?= session('validation')->getError('deskripsi'); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label><?= $__('uploadStruktur'); ?><small class="text-danger">*</small></label>
                                <input type="file" name="gambar" id="gambar" class="form-control <?= (session('validation')) ? 'is-invalid' : ''; ?>" autofocus>
                                <div class="invalid-feedback">
                                    <?php if (session('validation') && session('validation')->hasError('gambar')): ?>
                                        <?= session('validation')->getError('gambar'); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="text-right">
                                <button type="reset" class="btn btn-danger"><?= $__('reset'); ?></button>
                                <button type="submit" class="btn btn-primary"><?= $__('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h4><?= $__('info'); ?></h4>
                    </div>
                    <div class="card-body">
                        <div class="section-title mt-0"><?= $__('fullName'); ?></div>
                        <div>

                        </div>
                        <div class="section-title"><?= $__('select2'); ?></div>

                        <div class="section-title"><?= $__('selectric'); ?></div>

                        <div class="section-title"><?= $__('selectGroup'); ?></div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
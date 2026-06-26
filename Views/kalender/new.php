<?php
// ===== MULAI: Logic Bahasa (ID/EN) =====
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

// Kamus terjemahan
$dict = [
    'id' => [
        'dashboard'  => 'Dashboard',
        'kalender'   => 'Kalender',
        'kegiatan'   => 'Kegiatan',
        'waktu'      => 'Waktu',
        'keterangan' => 'Keterangan',
        'reset'      => 'Reset',
        'simpan'     => 'Simpan',
    ],
    'en' => [
        'dashboard'  => 'Dashboard',
        'kalender'   => 'Calendar',
        'kegiatan'   => 'Activity',
        'waktu'      => 'Time',
        'keterangan' => 'Notes',
        'reset'      => 'Reset',
        'simpan'     => 'Save',
    ],
];

if (!function_exists('t')) {
    function t(string $key): string
    {
        global $dict, $lang;
        return $dict[$lang][$key]
            ?? $dict['id'][$key]
            ?? $key;
    }
}

if (!function_exists('lang_url')) {
    function lang_url(string $locale): string
    {
        $request = service('request');
        $base = current_url();
        $q = $request->getGet();
        $q['lang'] = $locale;
        return $base . '?' . http_build_query($q);
    }
}
// ===== SELESAI: Logic Bahasa =====
?>

<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <a href="<?= site_url('kalender'); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
        <h1><?= $title; ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>"><?= t('dashboard'); ?></a></div>
            <div class="breadcrumb-item active"><a href="<?= site_url('kalender'); ?>"><?= t('kalender'); ?></a></div>
            <div class="breadcrumb-item"><?= $title; ?></div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= site_url('kalender'); ?>" method="POST" autocomplete="off">
                            <?= csrf_field(); ?>
                            <div class="form-group">
                                <label><?= t('kegiatan'); ?><small class="text-danger">*</small></label>
                                <textarea name="kegiatan" class="summernote"></textarea>
                            </div>
                            <div class="form-group">
                                <label><?= t('waktu'); ?><small class="text-danger">*</small></label>
                                <textarea name="waktu" class="summernote"></textarea>
                            </div>
                            <div class="form-group">
                                <label><?= t('keterangan'); ?><small class="text-danger">*</small></label>
                                <textarea name="keterangan" class="summernote"></textarea>
                            </div>
                            <div class="text-right">
                                <button type="reset" class="btn btn-danger"><?= t('reset'); ?></button>
                                <button type="submit" class="btn btn-primary"><?= t('simpan'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
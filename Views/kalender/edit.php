<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>
<?= $this->endSection() ?>

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

$tr = [
    'id' => [
        'dashboard'   => 'Dashboard',
        'calendar'    => 'kalender',
        'activity'    => 'Kegiatan',
        'time'        => 'Waktu',
        'notes'       => 'Keterangan',
        'back'        => 'kembali',
        'save'        => 'Simpan',
    ],
    'en' => [
        'dashboard'   => 'Dashboard',
        'calendar'    => 'calendar',
        'activity'    => 'Activity',
        'time'        => 'Time',
        'notes'       => 'Description',
        'back'        => 'back',
        'save'        => 'Save',
    ],
];

$t = function (string $key) use ($tr, $lang) {
    return $tr[$lang][$key] ?? $tr['id'][$key] ?? $key;
};
?>

<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <a href="<?= site_url('kalender'); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
        <h1><?= esc($title); ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item">
                <a href="<?= site_url('dashboard'); ?>"><?= esc($t('dashboard')); ?></a>
            </div>
            <div class="breadcrumb-item active">
                <a href="<?= site_url('kalender'); ?>"><?= esc($t('calendar')); ?></a>
            </div>
            <div class="breadcrumb-item"><?= esc($title); ?></div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= site_url('kalender/' . $kalender->kalender_id); ?>" method="POST" autocomplete="off">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="_method" value="PATCH">

                            <div class="form-group">
                                <label><?= esc($t('activity')); ?><small class="text-danger">*</small></label>
                                <textarea name="kegiatan" class="summernote"><?= esc($kalender->kegiatan); ?></textarea>
                            </div>

                            <div class="form-group">
                                <label><?= esc($t('time')); ?><small class="text-danger">*</small></label>
                                <textarea name="waktu" class="summernote"><?= esc($kalender->waktu); ?></textarea>
                            </div>

                            <div class="form-group">
                                <label><?= esc($t('notes')); ?><small class="text-danger">*</small></label>
                                <textarea name="keterangan" class="summernote"><?= esc($kalender->keterangan); ?></textarea>
                            </div>

                            <div class="text-right">
                                <a href="<?= site_url('kalender'); ?>" class="btn btn-dark"><?= esc($t('back')); ?></a>
                                <button type="submit" class="btn btn-primary"><?= esc($t('save')); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
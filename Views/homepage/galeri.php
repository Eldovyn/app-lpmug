<?php
// Logika bahasa: query param > cookie > default
helper('cookie');
$request = service('request');
$langParam = $request->getGet('lang');
$langCookie = get_cookie('lang');

if ($langParam && in_array($langParam, ['id', 'en'], true)) {
    $lang = $langParam;
    set_cookie('lang', $lang, 60 * 60 * 24 * 30);
} elseif ($langCookie && in_array($langCookie, ['id', 'en'], true)) {
    $lang = $langCookie;
} else {
    $lang = 'id';
}

// Translation
$tr = [
    'id' => ['heading' => 'Dokumentasi Kegiatan ABDIMAS.'],
    'en' => ['heading' => 'Documentation of ABDIMAS Activities.'],
];
$t = fn(string $key): string => $tr[$lang][$key] ?? ($tr['id'][$key] ?? $key);
?>

<?= $this->extend('layouts/default_section') ?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>
<?= $this->endSection() ?>

<!-- Render Content untuk Galeri Section -->
<?= $this->section('content') ?>
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center mx-auto" style="max-width: 500px">
            <h1 class="display-6 mb-5" data-wow-delay="0.5s"><?= $t('heading'); ?></h1>
        </div>
        <div class="row g-4">
            <?php foreach($galeri as $pr => $v_galeri): ?>
                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="team-item rounded" style="height:330px;">
                        <img class="img-fluid" src="<?=base_url('/img/upload/galeri/'. $v_galeri->gambar)?>" alt="galeri - <?php $v_galeri->judul; ?>" />
                        <div class="text-center p-4">
                            <span><b><?= $v_galeri->judul; ?></b></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<!-- END Render Content untuk Galeri Section -->
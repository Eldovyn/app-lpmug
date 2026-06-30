<?= $this->extend('layouts/default') ?>

<?php
// ===== i18n via cookie lang (default id, selain en => id) =====
$request = service('request'); // CI4 request service :contentReference[oaicite:2]{index=2}

$lang = $lang ?? ($request->getCookie('lang') ?? 'id');
$lang = strtolower(trim((string) $lang));
$lang = ($lang === 'en') ? 'en' : 'id';

$TR = [
    'id' => [
        'dashboard'        => 'Dashboard',
        'breadcrumb_luaran' => 'luaran',

        'label_name'       => 'Nama luaran penelitian',
        'example'          => 'contoh: Teknologi Industri',

        'btn_reset'        => 'Reset',
        'btn_save'         => 'Simpan',

        'hint_title'       => 'Keterangan',
        'hint_fullname'    => 'Nama Lengkap',
        'hint_select2'     => 'Select 2',
        'hint_selectric'   => 'jQuery Selectric',
        'hint_selectgroup' => 'Select Group Button',
    ],
    'en' => [
        'dashboard'        => 'Dashboard',
        'breadcrumb_luaran' => 'outputs',

        'label_name'       => 'Research output name',
        'example'          => 'example: Industrial Technology',

        'btn_reset'        => 'Reset',
        'btn_save'         => 'Save',

        'hint_title'       => 'Notes',
        'hint_fullname'    => 'Full Name',
        'hint_select2'     => 'Select 2',
        'hint_selectric'   => 'jQuery Selectric',
        'hint_selectgroup' => 'Select Group Button',
    ],
];

$t = static function (string $key) use ($TR, $lang): string {
    return $TR[$lang][$key] ?? $TR['id'][$key] ?? $key;
};
?>

<?= $this->section('title') ?>
<title><?= esc($title_tab); ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <a href="<?= site_url('luaran'); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
        <h1><?= esc($title); ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>"><?= esc($t('dashboard')) ?></a></div>
            <div class="breadcrumb-item active"><a href="<?= site_url('luaran'); ?>"><?= esc($t('breadcrumb_luaran')) ?></a></div>
            <div class="breadcrumb-item"><?= esc($title); ?></div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= site_url('luaran'); ?>" method="POST" autocomplete="off">
                            <?= csrf_field(); ?>
                            <div class="form-group">
                                <label><?= esc($t('label_name')) ?><small class="text-danger">*</small> (<?= esc($t('example')) ?>)</label>
                                <input type="text" name="luaran_name" class="form-control" required autofocus>
                            </div>
                            <div class="text-right">
                                <button type="reset" class="btn btn-danger"><?= esc($t('btn_reset')) ?></button>
                                <button type="submit" class="btn btn-primary"><?= esc($t('btn_save')) ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h4><?= esc($t('hint_title')) ?></h4>
                    </div>
                    <div class="card-body">
                        <div class="section-title mt-0"><?= esc($t('hint_fullname')) ?></div>
                        <div></div>

                        <div class="section-title"><?= esc($t('hint_select2')) ?></div>
                        <div class="section-title"><?= esc($t('hint_selectric')) ?></div>
                        <div class="section-title"><?= esc($t('hint_selectgroup')) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
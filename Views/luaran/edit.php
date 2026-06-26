<?= $this->extend('layouts/default') ?>

<?php
// ====== I18N (SINGLE FILE) ======
$request = service('request');

// lang cookie: default id, selain en => id
$lang = $lang ?? ($request->getCookie('lang') ?? 'id'); // getCookie() pulls from $_COOKIE :contentReference[oaicite:2]{index=2}
$lang = strtolower(trim((string) $lang));
$lang = ($lang === 'en') ? 'en' : 'id';

$TR = [
    'id' => [
        'dashboard'        => 'Dashboard',
        'outputs'          => 'luaran',

        'label_output'     => 'luaran Penelitian',
        'example'          => 'Contoh: Teknologi Informasi dan Komputer (TIK)',

        'btn_back'         => 'kembali',
        'btn_save'         => 'Simpan',
    ],
    'en' => [
        'dashboard'        => 'Dashboard',
        'outputs'          => 'Outputs',

        'label_output'     => 'Research Output',
        'example'          => 'Example: Information and Computer Technology (ICT)',

        'btn_back'         => 'Back',
        'btn_save'         => 'Save',
    ],
];

$t = static function (string $key) use ($TR, $lang): string {
    return $TR[$lang][$key] ?? $TR['id'][$key] ?? $key;
};
?>

<?= $this->section('title') ?>
<title><?= esc($title_tab ?? ($lang === 'en' ? 'Edit Output' : 'Edit luaran')); ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <a href="<?= site_url('luaran'); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
        <h1><?= esc($title ?? ($lang === 'en' ? 'Edit Output' : 'Edit luaran')); ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>"><?= esc($t('dashboard')) ?></a></div>
            <div class="breadcrumb-item active"><a href="<?= site_url('luaran'); ?>"><?= esc($t('outputs')) ?></a></div>
            <div class="breadcrumb-item"><?= esc($title ?? ($lang === 'en' ? 'Edit Output' : 'Edit luaran')); ?></div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= site_url('luaran/' . $luaran->luaran_id); ?>" method="POST" autocomplete="off">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="_method" value="PATCH">
                            <div class="form-group">
                                <label>
                                    <?= esc($t('label_output')) ?>
                                    <small class="text-danger">*</small>
                                    (<?= esc($t('example')) ?>)
                                </label>
                                <input type="text" name="luaran_name" value="<?= esc($luaran->luaran_name); ?>" class="form-control" required autofocus>
                            </div>
                            <div class="text-right">
                                <a href="<?= site_url('luaran'); ?>" class="btn btn-dark"><?= esc($t('btn_back')) ?></a>
                                <button type="submit" class="btn btn-primary"><?= esc($t('btn_save')) ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h4>Keterangan</h4>
                    </div>
                    <div class="card-body">
                        <div class="section-title mt-0">Nama Lengkap</div>
                        <div>

                        </div>
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
<?= $this->extend('layouts/default') ?>

<?php
// ====== I18N (SINGLE FILE) ======
$request = service('request');

$lang = $lang ?? ($request->getCookie('lang') ?? 'id'); // getCookie() :contentReference[oaicite:2]{index=2}
$lang = strtolower(trim((string) $lang));
$lang = ($lang === 'en') ? 'en' : 'id'; // default id, selain en => id

$TR = [
    'id' => [
        'dashboard'   => 'Dashboard',
        'faculty'     => 'fakultas',
        'label_name'  => 'Jabatan fakultas',
        'example'     => 'contoh: Teknologi Industri',
        'back'        => 'kembali',
        'save'        => 'Simpan',
        'note'        => 'Keterangan',
        'full_name'   => 'Nama Lengkap',
        'select2'     => 'Select 2',
        'selectric'   => 'jQuery Selectric',
        'select_group' => 'Select Group Button',
    ],
    'en' => [
        'dashboard'   => 'Dashboard',
        'faculty'     => 'faculties',
        'label_name'  => 'Faculty name',
        'example'     => 'e.g. Industrial Technology',
        'back'        => 'back',
        'save'        => 'Save',
        'note'        => 'Notes',
        'full_name'   => 'Full Name',
        'select2'     => 'Select 2',
        'selectric'   => 'jQuery Selectric',
        'select_group' => 'Select Group Button',
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
        <a href="<?= site_url('fakultas'); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
        <h1><?= esc($title); ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>"><?= esc($t('dashboard')) ?></a></div>
            <div class="breadcrumb-item active"><a href="<?= site_url('fakultas'); ?>"><?= esc($t('faculty')) ?></a></div>
            <div class="breadcrumb-item"><?= esc($title); ?></div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= site_url('fakultas/' . $fakultas->fakultas_id); ?>" method="POST" autocomplete="off">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="_method" value="PATCH">
                            <div class="form-group">
                                <label><?= esc($t('label_name')) ?><small class="text-danger">*</small> (<?= esc($t('example')) ?>)</label>
                                <input type="text" name="fakultas_name" value="<?= esc($fakultas->fakultas_name); ?>" class="form-control" required autofocus>
                            </div>
                            <div class="text-right">
                                <a href="<?= site_url('fakultas'); ?>" class="btn btn-dark"><?= esc($t('back')) ?></a>
                                <button type="submit" class="btn btn-primary"><?= esc($t('save')) ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h4><?= esc($t('note')) ?></h4>
                    </div>
                    <div class="card-body">
                        <div class="section-title mt-0"><?= esc($t('full_name')) ?></div>
                        <div></div>
                        <div class="section-title"><?= esc($t('select2')) ?></div>
                        <div class="section-title"><?= esc($t('selectric')) ?></div>
                        <div class="section-title"><?= esc($t('select_group')) ?></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
<?= $this->endSection() ?>
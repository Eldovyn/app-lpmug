<?= $this->extend('layouts/default') ?>

<?php
// ====== I18N (SINGLE FILE) ======
$request = service('request'); // :contentReference[oaicite:2]{index=2}

$lang = $lang ?? ($request->getCookie('lang') ?? 'id'); // :contentReference[oaicite:3]{index=3}
$lang = strtolower(trim((string) $lang));
$lang = ($lang === 'en') ? 'en' : 'id';

$TR = [
    'id' => [
        'dashboard'      => 'Dashboard',
        'topic'          => 'topik',

        'label_topic'    => 'Nama topik penelitian',
        'example'        => 'contoh: Teknologi Industri',

        'reset'          => 'Reset',
        'save'           => 'Simpan',

        'note_title'     => 'Keterangan',
        'note_fullname'  => 'Nama Lengkap',
        'note_select2'   => 'Select 2',
        'note_selectric' => 'jQuery Selectric',
        'note_group'     => 'Select Group Button',
    ],
    'en' => [
        'dashboard'      => 'Dashboard',
        'topic'          => 'topic',

        'label_topic'    => 'Research topic name',
        'example'        => 'example: Industrial Technology',

        'reset'          => 'Reset',
        'save'           => 'Save',

        'note_title'     => 'Notes',
        'note_fullname'  => 'Full Name',
        'note_select2'   => 'Select2',
        'note_selectric' => 'jQuery Selectric',
        'note_group'     => 'Select Group Button',
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
        <a href="<?= site_url('topik'); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
        <h1><?= esc($title); ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>"><?= esc($t('dashboard')) ?></a></div>
            <div class="breadcrumb-item active"><a href="<?= site_url('topik'); ?>"><?= esc($t('topic')) ?></a></div>
            <div class="breadcrumb-item"><?= esc($title); ?></div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= site_url('topik'); ?>" method="POST" autocomplete="off">
                            <?= csrf_field(); ?>
                            <div class="form-group">
                                <label><?= esc($t('label_topic')) ?><small class="text-danger">*</small> (<?= esc($t('example')) ?>)</label>
                                <input type="text" name="topik_name" class="form-control" required autofocus>
                            </div>
                            <div class="text-right">
                                <button type="reset" class="btn btn-danger"><?= esc($t('reset')) ?></button>
                                <button type="submit" class="btn btn-primary"><?= esc($t('save')) ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h4><?= esc($t('note_title')) ?></h4>
                    </div>
                    <div class="card-body">
                        <div class="section-title mt-0"><?= esc($t('note_fullname')) ?></div>
                        <div></div>
                        <div class="section-title"><?= esc($t('note_select2')) ?></div>
                        <div class="section-title"><?= esc($t('note_selectric')) ?></div>
                        <div class="section-title"><?= esc($t('note_group')) ?></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
<?= $this->endSection() ?>
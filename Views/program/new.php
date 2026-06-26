<?= $this->extend('layouts/default') ?>

<?php
$request = service('request');
$lang = $lang ?? ($request->getCookie('lang') ?? 'id');
$lang = strtolower(trim((string) $lang));
$lang = ($lang === 'en') ? 'en' : 'id';

$TR = [
    'id' => [
        'dashboard' => 'Dashboard',
        'program'   => 'program',
        'topic'     => 'Topik penelitian',
        'name'      => 'Nama program',
        'reset'     => 'Reset',
        'save'      => 'Simpan',
        'choose'    => 'Silahkan Pilih',
    ],
    'en' => [
        'dashboard' => 'Dashboard',
        'program'   => 'program',
        'topic'     => 'Research topic',
        'name'      => 'Program name',
        'reset'     => 'Reset',
        'save'      => 'Save',
        'choose'    => 'Please choose',
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
        <a href="<?= site_url('program'); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
        <h1><?= esc($title); ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>"><?= esc($t('dashboard')) ?></a></div>
            <div class="breadcrumb-item active"><a href="<?= site_url('program'); ?>"><?= esc($t('program')) ?></a></div>
            <div class="breadcrumb-item"><?= esc($title); ?></div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= site_url('program'); ?>" method="POST" autocomplete="off">
                            <?= csrf_field(); ?>

                            <div class="form-group">
                                <label><?= esc($t('topic')) ?><small class="text-danger">*</small></label>
                                <select name="topik_id" class="form-control select2" required>
                                    <option selected disabled>&mdash;<?= esc($t('choose')) ?>&mdash;</option>
                                    <?php foreach ($topik as $tp => $v_topik): ?>
                                        <option value="<?= (int) $v_topik->topik_id; ?>"><?= esc($v_topik->topik_name); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label><?= esc($t('name')) ?><small class="text-danger">*</small></label>
                                <input type="text" name="program_name" class="form-control" required autofocus>
                            </div>

                            <div class="text-right">
                                <button type="reset" class="btn btn-danger"><?= esc($t('reset')) ?></button>
                                <button type="submit" class="btn btn-primary"><?= esc($t('save')) ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- kolom kanan (Keterangan) tetap -->
            <div class="col-12 col-md-6 col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h4>Keterangan</h4>
                    </div>
                    <div class="card-body">
                        <div class="section-title mt-0">Nama Lengkap</div>
                        <div></div>
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
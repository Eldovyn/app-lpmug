<?php
// === I18N inline (1 file view, tanpa folder Language, tanpa ubah UI/UX) ===
$lang = strtolower(trim((string) (service('request')->getCookie('lang') ?? 'id')));
$lang = ($lang === 'en') ? 'en' : 'id';

$I18N = [
    'id' => [
        'dashboard'          => 'Dashboard',
        'periode'            => 'periode',

        'labelPeriode'       => 'Periode',
        'labelTahunAjaran'   => 'Tahun Ajaran',
        'example'            => 'contoh',
        'labelStatus'        => 'Status',

        'choose'             => 'Silahkan Pilih',
        'inactive'           => 'Tidak Aktif',
        'active'             => 'Aktif',

        'reset'              => 'Reset',
        'save'               => 'Simpan',

        'infoTitle'          => 'Keterangan',
    ],
    'en' => [
        'dashboard'          => 'Dashboard',
        'periode'            => 'period',

        'labelPeriode'       => 'Period',
        'labelTahunAjaran'   => 'Academic Year',
        'example'            => 'example',
        'labelStatus'        => 'Status',

        'choose'             => 'Please Select',
        'inactive'           => 'Inactive',
        'active'             => 'Active',

        'reset'              => 'Reset',
        'save'               => 'Save',

        'infoTitle'          => 'Info',
    ],
];

$t = function (string $key, ...$args) use ($I18N, $lang) {
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
        <a href="<?= site_url('periode'); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
        <h1><?= $title; ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>"><?= $t('dashboard'); ?></a></div>
            <div class="breadcrumb-item active"><a href="<?= site_url('periode'); ?>"><?= $t('periode'); ?></a></div>
            <div class="breadcrumb-item"><?= $title; ?></div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= site_url('periode'); ?>" method="POST" autocomplete="off">
                            <?= csrf_field(); ?>

                            <div class="form-group">
                                <label><?= $t('labelPeriode'); ?><small class="text-danger">*</small></label>
                                <select name="periode_name" class="form-control select2" required>
                                    <option selected disabled>&mdash;<?= $t('choose'); ?>&mdash;</option>
                                    <option value="PTA">PTA</option>
                                    <option value="ATA">ATA</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label><?= $t('labelTahunAjaran'); ?><small class="text-danger">*</small> (<?= $t('example'); ?>: 2023/2024)</label>
                                <input type="text" name="tahun_ajaran" class="form-control" required autofocus>
                            </div>

                            <div class="form-group">
                                <label><?= $t('labelStatus'); ?><small class="text-danger">*</small></label>
                                <select name="status" class="form-control select2" required>
                                    <option selected disabled>&mdash;<?= $t('choose'); ?>&mdash;</option>
                                    <option value="0"><?= $t('inactive'); ?></option>
                                    <option value="1"><?= $t('active'); ?></option>
                                </select>
                            </div>

                            <div class="text-right">
                                <button type="reset" class="btn btn-danger"><?= $t('reset'); ?></button>
                                <button type="submit" class="btn btn-primary"><?= $t('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h4><?= $t('infoTitle'); ?></h4>
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
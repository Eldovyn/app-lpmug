<?= $this->extend('layouts/default') ?>

<?php
// ====== I18N (SINGLE FILE) ======
$request = service('request'); // :contentReference[oaicite:1]{index=1}

$lang = $lang ?? ($request->getCookie('lang') ?? 'id'); // :contentReference[oaicite:2]{index=2}
$lang = strtolower(trim((string) $lang));
$lang = ($lang === 'en') ? 'en' : 'id';

$TR = [
    'id' => [
        'dashboard'     => 'Dashboard',
        'city'          => 'kota',

        'province'      => 'Provinsi',
        'choose'        => 'Silahkan Pilih',

        'city_name'     => 'Nama kota / Kabupaten',

        'back'          => 'kembali',
        'save'          => 'Simpan',

        'note_title'    => 'Keterangan',
        'note_fullname' => 'Nama Lengkap',
        'note_select2'  => 'Select 2',
        'note_selectric' => 'jQuery Selectric',
        'note_group'    => 'Select Group Button',
    ],
    'en' => [
        'dashboard'     => 'Dashboard',
        'city'          => 'City',

        'province'      => 'Province',
        'choose'        => 'Please Choose',

        'city_name'     => 'City / Regency Name',

        'back'          => 'Back',
        'save'          => 'Save',

        'note_title'    => 'Notes',
        'note_fullname' => 'Full Name',
        'note_select2'  => 'Select2',
        'note_selectric' => 'jQuery Selectric',
        'note_group'    => 'Select Group Button',
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
        <a href="<?= site_url('kota'); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
        <h1><?= esc($title); ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>"><?= esc($t('dashboard')) ?></a></div>
            <div class="breadcrumb-item active"><a href="<?= site_url('kota'); ?>"><?= esc($t('city')) ?></a></div>
            <div class="breadcrumb-item"><?= esc($title); ?></div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= site_url('kota/' . $kota->kota_id); ?>" method="POST" autocomplete="off">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="_method" value="PATCH">

                            <div class="form-group">
                                <label><?= esc($t('province')) ?><small class="text-danger">*</small></label>
                                <select name="provinsi_id" class="form-control select2" required>
                                    <option selected disabled>&mdash;<?= esc($t('choose')) ?>&mdash;</option>
                                    <?php foreach ($provinsi as $pro => $v_provinsi): ?>
                                        <option value="<?= esc($v_provinsi->provinsi_id); ?>" <?= $kota->provinsi_id == $v_provinsi->provinsi_id ? 'selected' : null; ?>>
                                            <?= esc($v_provinsi->provinsi_name); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label><?= esc($t('city_name')) ?><small class="text-danger">*</small></label>
                                <input type="text" name="kota_name" value="<?= esc($kota->kota_name); ?>" class="form-control" required autofocus>
                            </div>

                            <div class="text-right">
                                <a href="<?= site_url('kota'); ?>" class="btn btn-dark"><?= esc($t('back')) ?></a>
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
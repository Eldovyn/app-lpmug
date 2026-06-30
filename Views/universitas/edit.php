<?= $this->extend('layouts/default') ?>

<?php
// ====== I18N (SINGLE FILE) ======
$request = service('request');

$lang = $lang ?? ($request->getCookie('lang') ?? 'id');
$lang = strtolower(trim((string) $lang));
$lang = ($lang === 'en') ? 'en' : 'id';

$TR = [
    'id' => [
        'dashboard'     => 'Dashboard',
        'universitas'   => 'Universitas',
        'back'          => 'kembali',
        'save'          => 'Simpan',
        'note'          => 'Keterangan',

        'name_label'    => 'Nama Universitas / Instansi',
        'name_example'  => 'contoh: Universitas Gunadarma',

        'contact_label' => 'Kontak/No. Telp',
        'contact_ex'    => 'Contoh: 622178881112',

        'address_label' => 'Alamat',

        // side card (dummy)
        'full_name'     => 'Nama Lengkap',
        'select2'       => 'Select 2',
        'selectric'     => 'jQuery Selectric',
        'select_group'  => 'Select Group Button',
    ],
    'en' => [
        'dashboard'     => 'Dashboard',
        'universitas'   => 'Universities',
        'back'          => 'Back',
        'save'          => 'Save',
        'note'          => 'Notes',

        'name_label'    => 'University / Institution Name',
        'name_example'  => 'example: Gunadarma University',

        'contact_label' => 'Contact/Phone',
        'contact_ex'    => 'Example: 622178881112',

        'address_label' => 'Address',

        // side card (dummy)
        'full_name'     => 'Full Name',
        'select2'       => 'Select 2',
        'selectric'     => 'jQuery Selectric',
        'select_group'  => 'Select Group Button',
    ],
];

$t = static fn(string $key): string => $TR[$lang][$key] ?? $TR['id'][$key] ?? $key;
?>

<?= $this->section('title') ?>
<title><?= esc($title_tab); ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <a href="<?= site_url('universitas'); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
        <h1><?= esc($title); ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>"><?= esc($t('dashboard')) ?></a></div>
            <div class="breadcrumb-item active"><a href="<?= site_url('universitas'); ?>"><?= esc($t('universitas')) ?></a></div>
            <div class="breadcrumb-item"><?= esc($title); ?></div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= site_url('universitas/' . $universitas->universitas_id); ?>" method="POST" autocomplete="off">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="_method" value="PATCH">

                            <div class="form-group">
                                <label><?= esc($t('name_label')) ?><small class="text-danger">*</small>
                                    (<?= esc($t('name_example')) ?>)
                                </label>
                                <input type="text" name="universitas_name" value="<?= esc($universitas->universitas_name); ?>" class="form-control" required autofocus>
                            </div>

                            <div class="form-group">
                                <label><?= esc($t('contact_label')) ?> (<small class="text-primary"><?= esc($t('contact_ex')) ?></small>)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-phone"></i></div>
                                    </div>
                                    <input type="number" name="kontak" value="<?= esc($universitas->kontak); ?>" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?= esc($t('address_label')) ?></label>
                                <textarea name="alamat" class="form-control" style="height: 150px"><?= esc($universitas->alamat); ?></textarea>
                            </div>

                            <div class="text-right">
                                <a href="<?= site_url('universitas'); ?>" class="btn btn-dark"><?= esc($t('back')) ?></a>
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
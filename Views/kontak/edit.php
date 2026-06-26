<?php
helper(['cookie', 'url']);

$request = service('request');

$allowed = ['id', 'en'];
$lang = get_cookie('lang') ?: 'id';
if (!in_array($lang, $allowed, true)) {
    $lang = 'id';
}

$reqLang = $request->getGet('lang');
if ($reqLang && in_array($reqLang, $allowed, true)) {
    set_cookie('lang', $reqLang, 60 * 60 * 24 * 30);
    $lang = $reqLang;
}

$I18N = [
    'id' => [
        'dashboard'      => 'Dashboard',
        'kontak'         => 'kontak',
        'labelName'      => 'Nama kontak',
        'labelContact'   => 'Kontak',
        'labelNote'      => 'Nama kontak', // mengikuti UI kamu (meski ini textarea)
        'exampleName'    => '(contoh: Wenday)',
        'exampleContact' => '(contoh: 628123677722)',
        'back'           => 'kembali',
        'save'           => 'Simpan',
        'info'           => 'Keterangan',
        'fullName'       => 'Nama Lengkap',
        'select2'        => 'Select 2',
        'selectric'      => 'jQuery Selectric',
        'selectGroup'    => 'Select Group Button',
    ],
    'en' => [
        'dashboard'      => 'Dashboard',
        'kontak'         => 'contact',
        'labelName'      => 'Contact name',
        'labelContact'   => 'Contact',
        'labelNote'      => 'Notes',
        'exampleName'    => '(example: Wenday)',
        'exampleContact' => '(example: 628123677722)',
        'back'           => 'back',
        'save'           => 'Save',
        'info'           => 'Information',
        'fullName'       => 'Full Name',
        'select2'        => 'Select 2',
        'selectric'      => 'jQuery Selectric',
        'selectGroup'    => 'Select Group Button',
    ],
];

$__ = function (string $key) use ($I18N, $lang) {
    return $I18N[$lang][$key] ?? $I18N['id'][$key] ?? $key;
};
?>

<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <a href="<?= site_url('kontak'); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
        <h1><?= $title; ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>"><?= $__('dashboard'); ?></a></div>
            <div class="breadcrumb-item active"><a href="<?= site_url('kontak'); ?>"><?= $__('kontak'); ?></a></div>
            <div class="breadcrumb-item"><?= $title; ?></div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= site_url('kontak/' . $kontak->kontak_id); ?>" method="POST" autocomplete="off">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="_method" value="PATCH">
                            <div class="form-group">
                                <label><?= $__('labelName'); ?><small class="text-danger">*</small> <?= $__('exampleName'); ?></label>
                                <input type="text" name="kontak_name" value="<?= $kontak->kontak_name; ?>" class="form-control" required autofocus>
                            </div>
                            <div class="form-group">
                                <label><?= $__('labelContact'); ?><small class="text-danger">*</small> <?= $__('exampleContact'); ?></label>
                                <input type="number" name="kontak" value="<?= $kontak->kontak; ?>" class="form-control" required autofocus>
                            </div>
                            <div class="form-group">
                                <label><?= $__('labelNote'); ?><small class="text-danger">*</small></label>
                                <textarea name="keterangan" class="form-control" style="min-height:150px;"><?= $kontak->keterangan; ?></textarea>
                            </div>
                            <div class="text-right">
                                <a href="<?= site_url('kontak'); ?>" class="btn btn-dark"><?= $__('back'); ?></a>
                                <button type="submit" class="btn btn-primary"><?= $__('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h4><?= $__('info'); ?></h4>
                    </div>
                    <div class="card-body">
                        <div class="section-title mt-0"><?= $__('fullName'); ?></div>
                        <div>

                        </div>
                        <div class="section-title"><?= $__('select2'); ?></div>

                        <div class="section-title"><?= $__('selectric'); ?></div>

                        <div class="section-title"><?= $__('selectGroup'); ?></div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
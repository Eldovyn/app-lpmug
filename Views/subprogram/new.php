<?= $this->extend('layouts/default') ?>

<?php
// ====== I18N (SINGLE FILE) ======
$request = service('request');

$lang = $lang ?? ($request->getCookie('lang') ?? 'id');
$lang = strtolower(trim((string) $lang));
$lang = ($lang === 'en') ? 'en' : 'id'; // default id, selain en => id

$TR = [
    'id' => [
        'dashboard'        => 'Dashboard',
        'subprogram'       => 'subprogram',

        'lbl_topic_program' => 'Topik & Program',
        'opt_select'       => 'Silahkan Pilih',
        'lbl_subprogram'   => 'Sub program',

        'btn_reset'        => 'Reset',
        'btn_save'         => 'Simpan',

        'note'             => 'Keterangan',
        'full_name'        => 'Nama Lengkap',
        'select2'          => 'Select 2',
        'selectric'        => 'jQuery Selectric',
        'select_group_btn' => 'Select Group Button',
    ],
    'en' => [
        'dashboard'        => 'Dashboard',
        'subprogram'       => 'subprogram',

        'lbl_topic_program' => 'Topic & Program',
        'opt_select'       => 'Please Select',
        'lbl_subprogram'   => 'Sub program',

        'btn_reset'        => 'Reset',
        'btn_save'         => 'Save',

        'note'             => 'Notes',
        'full_name'        => 'Full Name',
        'select2'          => 'Select 2',
        'selectric'        => 'jQuery Selectric',
        'select_group_btn' => 'Select Group Button',
    ],
];

$t = static function (string $key) use ($TR, $lang): string {
    return $TR[$lang][$key] ?? $TR['id'][$key] ?? $key;
};
?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <a href="<?= site_url('subprogram'); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
        <h1><?= $title; ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>"><?= esc($t('dashboard')) ?></a></div>
            <div class="breadcrumb-item active"><a href="<?= site_url('subprogram'); ?>"><?= esc($t('subprogram')) ?></a></div>
            <div class="breadcrumb-item"><?= $title; ?></div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= site_url('subprogram'); ?>" method="POST" autocomplete="off">
                            <?= csrf_field(); ?>
                            <div class="form-group">
                                <label><?= esc($t('lbl_topic_program')) ?><small class="text-danger">*</small></label>
                                <select name="program_id" class="form-control select2" required autofocus>
                                    <option selected disabled>&mdash;<?= esc($t('opt_select')) ?>&mdash;</option>
                                    <?php foreach ($program as $prog => $v_program): ?>
                                        <option value="<?= $v_program->program_id; ?>"><?= $v_program->topik_name; ?> - <?= $v_program->program_name; ?> </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label><?= esc($t('lbl_subprogram')) ?><small class="text-danger">*</small></label>
                                <input type="text" name="subprogram_name" class="form-control" required autofocus>
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
                        <h4><?= esc($t('note')) ?></h4>
                    </div>
                    <div class="card-body">
                        <div class="section-title mt-0"><?= esc($t('full_name')) ?></div>
                        <div>

                        </div>
                        <div class="section-title"><?= esc($t('select2')) ?></div>

                        <div class="section-title"><?= esc($t('selectric')) ?></div>

                        <div class="section-title"><?= esc($t('select_group_btn')) ?></div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
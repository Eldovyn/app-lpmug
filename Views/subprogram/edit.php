<?= $this->extend('layouts/default') ?>

<?php
// ====== I18N (SINGLE FILE) ======
$request = service('request'); // CI4 service request :contentReference[oaicite:1]{index=1}

$lang = $lang ?? ($request->getCookie('lang') ?? 'id');
$lang = strtolower(trim((string) $lang));
$lang = ($lang === 'en') ? 'en' : 'id'; // default id, selain en => id

$TR = [
    'id' => [
        'dashboard'        => 'Dashboard',
        'breadcrumb_page'  => 'subprogram',

        'label_topic_prog' => 'Topik & Program',
        'opt_select'       => 'Silahkan Pilih',

        'label_subprog'    => 'Nama sub program',

        'btn_back'         => 'kembali',
        'btn_save'         => 'Simpan',

        'note_title'       => 'Keterangan',
        'note_fullname'    => 'Nama Lengkap',

        // (opsional) kalau nanti mau ditranslate juga
        'note_select2'     => 'Select 2',
        'note_selectric'   => 'jQuery Selectric',
        'note_group_btn'   => 'Select Group Button',
    ],
    'en' => [
        'dashboard'        => 'Dashboard',
        'breadcrumb_page'  => 'subprogram',

        'label_topic_prog' => 'Topic & Program',
        'opt_select'       => 'Please Select',

        'label_subprog'    => 'Sub program name',

        'btn_back'         => 'Back',
        'btn_save'         => 'Save',

        'note_title'       => 'Notes',
        'note_fullname'    => 'Full Name',

        // (opsional)
        'note_select2'     => 'Select 2',
        'note_selectric'   => 'jQuery Selectric',
        'note_group_btn'   => 'Select Group Button',
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
        <a href="<?= site_url('subprogram'); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
        <h1><?= esc($title); ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>"><?= esc($t('dashboard')) ?></a></div>
            <div class="breadcrumb-item active"><a href="<?= site_url('subprogram'); ?>"><?= esc($t('breadcrumb_page')) ?></a></div>
            <div class="breadcrumb-item"><?= esc($title); ?></div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= site_url('subprogram/' . $subprogram->subprogram_id); ?>" method="POST" autocomplete="off">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="_method" value="PATCH">

                            <div class="form-group">
                                <label><?= esc($t('label_topic_prog')) ?><small class="text-danger">*</small></label>
                                <select name="program_id" class="form-control select2" required>
                                    <option selected disabled>&mdash;<?= esc($t('opt_select')) ?>&mdash;</option>
                                    <?php foreach ($program as $prog => $v_program): ?>
                                        <option value="<?= esc($v_program->program_id); ?>" <?= ((int)$subprogram->program_id === (int)$v_program->program_id) ? 'selected' : null; ?>>
                                            <?= esc($v_program->topik_name); ?> - <?= esc($v_program->program_name); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label><?= esc($t('label_subprog')) ?><small class="text-danger">*</small></label>
                                <input type="text" name="subprogram_name" value="<?= esc($subprogram->subprogram_name); ?>" class="form-control" required autofocus>
                            </div>

                            <div class="text-right">
                                <a href="<?= site_url('subprogram'); ?>" class="btn btn-dark"><?= esc($t('btn_back')) ?></a>
                                <button type="submit" class="btn btn-primary"><?= esc($t('btn_save')) ?></button>
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
                        <div class="section-title"><?= esc($t('note_group_btn')) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
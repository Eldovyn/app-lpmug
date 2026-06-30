<?= $this->extend('layouts/default') ?>

<?php
// ====== I18N (SINGLE FILE) ======
$request = service('request'); // CI4: service('request') :contentReference[oaicite:1]{index=1}

// cookie lang: default id, selain en => id
$lang = $lang ?? ($request->getCookie('lang') ?? 'id'); // CI4 getCookie() :contentReference[oaicite:2]{index=2}
$lang = strtolower(trim((string) $lang));
$lang = ($lang === 'en') ? 'en' : 'id';

$TR = [
    'id' => [
        'dashboard'            => 'Dashboard',
        'program_breadcrumb'   => 'program',

        'label_topic'          => 'Topik penelitian',
        'opt_select'           => '—Silahkan Pilih—',
        'label_program'        => 'Nama program',

        'btn_back'             => 'kembali',
        'btn_save'             => 'Simpan',

        'note'                 => 'Keterangan',
        'full_name'            => 'Nama Lengkap',
        'select2'              => 'Select 2',
        'jquery_selectric'     => 'jQuery Selectric',
        'select_group_button'  => 'Select Group Button',
    ],
    'en' => [
        'dashboard'            => 'Dashboard',
        'program_breadcrumb'   => 'program',

        'label_topic'          => 'Research topic',
        'opt_select'           => '—Please Select—',
        'label_program'        => 'Program name',

        'btn_back'             => 'back',
        'btn_save'             => 'Save',

        'note'                 => 'Notes',
        'full_name'            => 'Full Name',
        'select2'              => 'Select 2',
        'jquery_selectric'     => 'jQuery Selectric',
        'select_group_button'  => 'Select Group Button',
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
            <div class="breadcrumb-item active"><a href="<?= site_url('program'); ?>"><?= esc($t('program_breadcrumb')) ?></a></div>
            <div class="breadcrumb-item"><?= esc($title); ?></div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= site_url('program/' . $program->program_id); ?>" method="POST" autocomplete="off">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="_method" value="PATCH">

                            <div class="form-group">
                                <label><?= esc($t('label_topic')) ?><small class="text-danger">*</small></label>
                                <select name="topik_id" class="form-control select2" required>
                                    <option selected disabled>&mdash;<?= esc($t('opt_select')) ?>&mdash;</option>
                                    <?php foreach ($topik as $tpk => $v_topik): ?>
                                        <option value="<?= $v_topik->topik_id; ?>" <?= $program->topik_id == $v_topik->topik_id ? 'selected' : null; ?>>
                                            <?= esc($v_topik->topik_name); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label><?= esc($t('label_program')) ?><small class="text-danger">*</small></label>
                                <input type="text" name="program_name" value="<?= esc($program->program_name); ?>" class="form-control" required autofocus>
                            </div>

                            <div class="text-right">
                                <a href="<?= site_url('program'); ?>" class="btn btn-dark"><?= esc($t('btn_back')) ?></a>
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
                        <div></div>

                        <div class="section-title"><?= esc($t('select2')) ?></div>
                        <div class="section-title"><?= esc($t('jquery_selectric')) ?></div>
                        <div class="section-title"><?= esc($t('select_group_button')) ?></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
<?= $this->endSection() ?>
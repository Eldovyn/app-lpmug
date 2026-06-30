<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>
<?= $this->endSection() ?>

<?php
helper(['cookie', 'url']);

$request = service('request');

$allowed = ['id', 'en'];
$lang = get_cookie('lang') ?: 'id';
if (! in_array($lang, $allowed, true)) {
    $lang = 'id';
}

$reqLang = $request->getGet('lang');
if ($reqLang && in_array($reqLang, $allowed, true)) {
    set_cookie('lang', $reqLang, 60 * 60 * 24 * 30);
    $lang = $reqLang;
}

if (! function_exists('t')) {
    function t(string $key): string
    {
        global $dict, $lang;

        return $dict[$lang][$key]
            ?? $dict['id'][$key]
            ?? $key;
    }
}

if (! function_exists('lang_url')) {
    function lang_url(string $locale): string
    {
        $request = service('request');
        $base = current_url();
        $q = $request->getGet();
        $q['lang'] = $locale;

        return $base . '?' . http_build_query($q);
    }
}

$tr = [
    'id' => [
        'dashboard' => 'Dashboard',
        'congrats' => 'Selamat!',
        'warning_error' => 'Warning Error!',

        'add_data' => 'Tambah data',
        'search_placeholder' => 'Pencarian...',

        'calendar' => 'kalender',

        'activity' => 'Kegiatan',
        'time' => 'Waktu',
        'description' => 'Keterangan',
        'action' => 'Action',

        'back' => 'kembali',
        'save' => 'Simpan',

        'showing' => 'Menampilkan',
        'to' => 'sampai',
        'of' => 'dari',
        'entries' => 'entri',

        'confirm_delete' => 'Hapus data? | Apakah anda yakin?',
    ],
    'en' => [
        'dashboard' => 'Dashboard',
        'congrats' => 'Congratulations!',
        'warning_error' => 'Warning!',

        'add_data' => 'Add data',
        'search_placeholder' => 'Search...',

        'calendar' => 'calendar',

        'activity' => 'Activity',
        'time' => 'Time',
        'description' => 'Description',
        'action' => 'Action',

        'back' => 'back',
        'save' => 'Save',

        'showing' => 'Showing',
        'to' => 'to',
        'of' => 'of',
        'entries' => 'entries',

        'confirm_delete' => 'Delete data? | Are you sure?',
    ],
];

$t = function (string $key) use ($tr, $lang) {
    return $tr[$lang][$key] ?? $tr['id'][$key] ?? $key;
};

// angka footer tetap sama (UI/UX tidak berubah)
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$from = 1 + (10 * ($page - 1));
?>

<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <h1><?= $title; ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active">
                <a href="<?= site_url('dashboard'); ?>"><?= esc($t('dashboard')); ?></a>
            </div>
            <div class="breadcrumb-item"><?= $title; ?></div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b><?= esc($t('congrats')); ?></b>
                <?= session()->getFlashdata('success'); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b><?= esc($t('warning_error')); ?></b>
                <?= session()->getFlashdata('error'); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4 class="text-sm">
                    <a href="<?= site_url('kalender/new'); ?>" class="btn btn-primary">
                        <i class="fas fa-plus-circle mr-1"></i><?= esc($t('add_data')); ?>
                    </a>
                    <!-- <a href="<?= site_url('kalender/trash'); ?>" class="btn btn-danger"><i class="fas fa-trash mr-1"></i>Data yang dihapus</a> -->
                </h4>
                <div class="card-header-form">
                    <form action="" method="GET" autocomplete="off">
                        <div class="input-group">
                            <input name="keyword" value="<?= $keyword; ?>" type="text" class="form-control"
                                placeholder="<?= esc($t('search_placeholder')); ?>">
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?= esc($t('activity')); ?></th>
                                <th><?= esc($t('time')); ?></th>
                                <th><?= esc($t('description')); ?></th>
                                <th class="text-center"><?= esc($t('action')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = $from;
                            foreach ($kalender as $fgsi => $v_kalender):
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td class="text-wrap" style="max-width:200px;"><?= $v_kalender->kegiatan; ?></td>
                                    <td class="text-wrap" style="max-width:200px;"><?= $v_kalender->waktu; ?></td>
                                    <td class="text-wrap" style="max-width:200px;"><?= $v_kalender->keterangan; ?></td>
                                    <td class="text-center">
                                        <a href="<?= site_url('kalender/' . $v_kalender->kalender_id . '/edit'); ?>" class="btn btn-warning btn-sm">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <form action="<?= site_url('kalender/' . $v_kalender->kalender_id); ?>" method="POST" class="d-inline" id="del-<?= $v_kalender->kalender_id; ?>">
                                            <?= csrf_field(); ?>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button class="btn btn-danger btn-sm"
                                                data-confirm="<?= esc($t('confirm_delete')); ?>"
                                                data-confirm-yes="submitDel(<?= $v_kalender->kalender_id; ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="float-left">
                        <i>
                            <?= esc($t('showing')); ?> <?= $from; ?>
                            <?= esc($t('to')); ?> <?= $no - 1; ?>
                            <?= esc($t('of')); ?> <?= $pager->getTotal(); ?>
                            <?= esc($t('entries')); ?>
                        </i>
                    </div>
                    <div class="float-right">
                        <?= $pager->links('default', 'pagination'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
<?= $this->extend('layouts/default') ?>

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

$TR = [
    'id' => [
        'dashboard'     => 'Dashboard',
        'success_title' => 'Selamat!',
        'error_title'   => 'Warning Error!',
        'add_data'      => 'Tambah data',
        'search_ph'     => 'Pencarian...',

        'th_topic'      => 'Topik penelitian',
        'th_program'    => 'Program',
        'th_subprogram' => 'Sub program',
        'th_action'     => 'Aksi',

        'delete_title'  => 'Hapus data?',
        'delete_msg'    => 'Apakah anda yakin?',

        'showing'       => 'Showing',
        'to'            => 'to',
        'of'            => 'of',
        'entries'       => 'entries',
    ],
    'en' => [
        'dashboard'     => 'Dashboard',
        'success_title' => 'Success!',
        'error_title'   => 'Warning!',
        'add_data'      => 'Add data',
        'search_ph'     => 'Search...',

        'th_topic'      => 'Research topic',
        'th_program'    => 'Program',
        'th_subprogram' => 'Sub program',
        'th_action'     => 'Action',

        'delete_title'  => 'Delete data?',
        'delete_msg'    => 'Are you sure?',

        'showing'       => 'Showing',
        'to'            => 'to',
        'of'            => 'of',
        'entries'       => 'entries',
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
        <h1><?= esc($title); ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="<?= site_url('dashboard'); ?>"><?= esc($t('dashboard')) ?></a></div>
            <div class="breadcrumb-item"><?= esc($title); ?></div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b><?= esc($t('success_title')) ?></b>
                <?= esc(session()->getFlashdata('success')); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b><?= esc($t('error_title')) ?></b>
                <?= esc(session()->getFlashdata('error')); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4 class="text-sm">
                    <a href="<?= site_url('subprogram/new'); ?>" class="btn btn-primary">
                        <i class="fas fa-plus-circle mr-1"></i><?= esc($t('add_data')) ?>
                    </a>
                    <!-- <a href="<?= site_url('subprogram/trash'); ?>" class="btn btn-danger"><i class="fas fa-trash mr-1"></i>Data yang dihapus</a> -->
                </h4>
                <div class="card-header-form">
                    <form action="" method="GET" autocomplete="off">
                        <div class="input-group">
                            <input name="keyword" value="<?= esc($keyword); ?>" type="text" class="form-control" placeholder="<?= esc($t('search_ph')) ?>">
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
                                <th><?= esc($t('th_topic')) ?></th>
                                <th><?= esc($t('th_program')) ?></th>
                                <th><?= esc($t('th_subprogram')) ?></th>
                                <th class="text-center"><?= esc($t('th_action')) ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
                            $no = 1 + (10 * ($page - 1));
                            foreach ($subprogram as $kt => $v_subprogram): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td class="text_wrap" style="max-width: 300px;"><?= esc($v_subprogram->topik_name); ?></td>
                                    <td class="text_wrap" style="max-width: 300px;"><?= esc($v_subprogram->program_name); ?></td>
                                    <td class="text_wrap" style="max-width: 300px;"><?= esc($v_subprogram->subprogram_name); ?></td>
                                    <td class="text-center">
                                        <a href="<?= site_url('subprogram/' . $v_subprogram->subprogram_id . '/edit'); ?>" class="btn btn-warning btn-sm">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <form action="<?= site_url('subprogram/' . $v_subprogram->subprogram_id); ?>" method="POST" class="d-inline" id="del-<?= (int) $v_subprogram->subprogram_id; ?>">
                                            <?= csrf_field(); ?>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button class="btn btn-danger btn-sm"
                                                data-confirm="<?= esc($t('delete_title')) ?> | <?= esc($t('delete_msg')) ?>"
                                                data-confirm-yes="submitDel(<?= (int) $v_subprogram->subprogram_id; ?>)">
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
                            <?= esc($t('showing')) ?>
                            <?= 1 + (10 * ($page - 1)); ?>
                            <?= esc($t('to')) ?>
                            <?= $no - 1; ?>
                            <?= esc($t('of')) ?>
                            <?= (int) $pager->getTotal(); ?>
                            <?= esc($t('entries')) ?>
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
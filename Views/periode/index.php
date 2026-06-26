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

$TR = [
    'id' => [
        'dashboard'      => 'Dashboard',
        'congrats'       => 'Congratulation!',
        'warning_error'  => 'Warning Error!',
        'add_data'       => 'Tambah data',
        'search_ph'      => 'Pencarian...',
        'th_period'      => 'Periode',
        'th_year'        => 'Tahun Ajaran',
        'th_info'        => 'Info',
        'th_status'      => 'Status',
        'th_action'      => 'Action',

        'info_open'      => 'Pendaftaran dibuka',
        'info_running'   => 'Abdimas sedang berlangsung',
        'info_report'    => 'Pengumpulan Laporan',
        'info_closed'    => 'Pendaftaran ditutup',
        'info_done'      => 'Selesai',

        'active'         => 'Aktif',
        'inactive'       => 'Tidak Aktif',

        'delete_title'   => 'Hapus data?',
        'delete_msg'     => 'Apakah anda yakin?',

        'showing'        => 'Showing',
        'to'             => 'to',
        'of'             => 'of',
        'entries'        => 'entries',
    ],
    'en' => [
        'dashboard'      => 'Dashboard',
        'congrats'       => 'Congratulations!',
        'warning_error'  => 'Warning!',
        'add_data'       => 'Add data',
        'search_ph'      => 'Search...',
        'th_period'      => 'Period',
        'th_year'        => 'Academic Year',
        'th_info'        => 'Info',
        'th_status'      => 'Status',
        'th_action'      => 'Action',

        'info_open'      => 'Registration open',
        'info_running'   => 'Community service in progress',
        'info_report'    => 'Report submission',
        'info_closed'    => 'Registration closed',
        'info_done'      => 'Finished',

        'active'         => 'Active',
        'inactive'       => 'Inactive',

        'delete_title'   => 'Delete data?',
        'delete_msg'     => 'Are you sure?',

        'showing'        => 'Showing',
        'to'             => 'to',
        'of'             => 'of',
        'entries'        => 'entries',
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
        <h1><?= $title; ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="<?= site_url('dashboard'); ?>"><?= esc($t('dashboard')) ?></a></div>
            <div class="breadcrumb-item"><?= $title; ?></div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b><?= esc($t('congrats')) ?></b>
                <?= session()->getFlashdata('success'); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b><?= esc($t('warning_error')) ?></b>
                <?= session()->getFlashdata('error'); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4 class="text-sm">
                    <a href="<?= site_url('periode/new'); ?>" class="btn btn-primary">
                        <i class="fas fa-plus-circle mr-1"></i><?= esc($t('add_data')) ?>
                    </a>
                </h4>
                <div class="card-header-form">
                    <form action="" method="GET" autocomplete="off">
                        <div class="input-group">
                            <input name="keyword" value="<?= $keyword; ?>" type="text" class="form-control" placeholder="<?= esc($t('search_ph')) ?>">
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
                                <th><?= esc($t('th_period')) ?></th>
                                <th><?= esc($t('th_year')) ?></th>
                                <th><?= esc($t('th_info')) ?></th>
                                <th><?= esc($t('th_status')) ?></th>
                                <th class="text-center"><?= esc($t('th_action')) ?></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $page = isset($_GET['page']) ? $_GET['page'] : 1;
                            $no = 1 + (10 * ($page - 1));
                            foreach ($periode as $pr => $v_periode): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $v_periode->periode_name; ?></td>
                                    <td><?= $v_periode->tahun_ajaran; ?></td>
                                    <td>
                                        <?php if ($v_periode->info == 1): ?>
                                            <span class="badge badge-success"><?= esc($t('info_open')) ?></span>
                                        <?php elseif ($v_periode->info == 2): ?>
                                            <span class="badge badge-info"><?= esc($t('info_running')) ?></span>
                                        <?php elseif ($v_periode->info == 3): ?>
                                            <span class="badge badge-warning"><?= esc($t('info_report')) ?></span>
                                        <?php elseif ($v_periode->info == 0): ?>
                                            <span class="badge badge-dark"><?= esc($t('info_closed')) ?></span>
                                        <?php else: ?>
                                            <span class="badge badge-danger"><?= esc($t('info_done')) ?></span>
                                        <?php endif ?>
                                    </td>

                                    <td>
                                        <?php if ($v_periode->status == 1): ?>
                                            <span class="badge badge-primary"><?= esc($t('active')) ?></span>
                                        <?php else: ?>
                                            <span class="badge badge-danger"><?= esc($t('inactive')) ?></span>
                                        <?php endif ?>
                                    </td>

                                    <td class="text-center">
                                        <a href="<?= site_url('periode/' . $v_periode->periode_id . '/edit'); ?>" class="btn btn-warning btn-sm">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>

                                        <form action="<?= site_url('periode/' . $v_periode->periode_id); ?>" method="POST" class="d-inline" id="del-<?= $v_periode->periode_id; ?>">
                                            <?= csrf_field(); ?>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button class="btn btn-danger btn-sm"
                                                data-confirm="<?= esc($t('delete_title')) ?> | <?= esc($t('delete_msg')) ?>"
                                                data-confirm-yes="submitDel(<?= $v_periode->periode_id; ?>)">
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
                            <?= esc($t('showing')) ?> <?= 1 + (10 * ($page - 1)); ?>
                            <?= esc($t('to')) ?> <?= $no - 1; ?>
                            <?= esc($t('of')) ?> <?= $pager->getTotal(); ?>
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
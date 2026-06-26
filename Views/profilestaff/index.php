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

// Kamus (1 file view, tidak perlu app/Language)
$DICT = [
    'id' => [
        'dashboard'     => 'Dashboard',
        'congrats'      => 'Selamat!',
        'warning'       => 'Peringatan!',
        'add'           => 'Tambah data',
        'search'        => 'Pencarian...',
        'title'         => 'Judul',
        'description'   => 'Deskripsi',
        'image'         => 'Gambar',
        'action'        => 'Action',
        'deleteConfirm' => 'Hapus data? | Apakah anda yakin?',
        'showing'       => 'Menampilkan {from} sampai {to} dari {total} data',
    ],
    'en' => [
        'dashboard'     => 'Dashboard',
        'congrats'      => 'Congratulations!',
        'warning'       => 'Warning!',
        'add'           => 'Add data',
        'search'        => 'Search...',
        'title'         => 'Title',
        'description'   => 'Description',
        'image'         => 'Image',
        'action'        => 'Action',
        'deleteConfirm' => 'Delete data? | Are you sure?',
        'showing'       => 'Showing {from} to {to} of {total} entries',
    ],
];

$t = function (string $key) use ($DICT, $lang) {
    return $DICT[$lang][$key] ?? $key;
};

$fmt = function (string $key, array $vars) use ($t) {
    $s = $t($key);
    foreach ($vars as $k => $v) {
        $s = str_replace('{' . $k . '}', (string) $v, $s);
    }
    return $s;
};
?>

<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>
<?= $this->endSection() ?>

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
                <b><?= esc($t('warning')); ?></b>
                <?= session()->getFlashdata('error'); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4 class="text-sm">
                    <a href="<?= site_url('profilestaff/new'); ?>" class="btn btn-primary">
                        <i class="fas fa-plus-circle mr-1"></i><?= esc($t('add')); ?>
                    </a>
                    <!-- <a href="<?= site_url('profilestaff/trash'); ?>" class="btn btn-danger"><i class="fas fa-trash mr-1"></i>Data yang dihapus</a> -->
                </h4>
                <div class="card-header-form">
                    <form action="" method="GET" autocomplete="off">
                        <div class="input-group">
                            <input name="keyword" value="<?= $keyword; ?>" type="text" class="form-control" placeholder="<?= esc($t('search')); ?>">
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
                                <th><?= esc($t('title')); ?></th>
                                <th><?= esc($t('description')); ?></th>
                                <th><?= esc($t('image')); ?></th>
                                <th class="text-center"><?= esc($t('action')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $page = isset($_GET['page']) ? $_GET['page'] : 1;
                            $no = 1 + (10 * ($page - 1));
                            foreach ($profilestaff as $pr => $v_profilestaff): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $v_profilestaff->judul; ?></td>
                                    <td class="text-wrap" style="max-width:300px;"><?= $v_profilestaff->deskripsi; ?></td>
                                    <td><img src="<?= base_url('/img/upload/profilestaff/' . $v_profilestaff->gambar) ?>" alt="profilestaff - <?php $v_profilestaff->judul; ?>" class="rounded m-2" style="width:100px; height:100px;"></td>
                                    <td class="text-center">
                                        <a href="<?= site_url('profilestaff/' . $v_profilestaff->profilestaff_id . '/edit'); ?>" class="btn btn-warning btn-sm"><i class="fas fa-pencil-alt"></i></a>
                                        <form action="<?= site_url('profilestaff/' . $v_profilestaff->profilestaff_id); ?>" method="POST" class="d-inline" id="del-<?= $v_profilestaff->profilestaff_id; ?>">
                                            <?= csrf_field(); ?>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button class="btn btn-danger btn-sm"
                                                data-confirm="<?= esc($t('deleteConfirm')); ?>"
                                                data-confirm-yes="submitDel(<?= $v_profilestaff->profilestaff_id; ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="float-left">
                        <?php
                        $from  = 1 + (10 * ($page - 1));
                        $to    = $no - 1;
                        $total = $pager->getTotal();
                        ?>
                        <i><?= esc($fmt('showing', ['from' => $from, 'to' => $to, 'total' => $total])); ?></i>
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
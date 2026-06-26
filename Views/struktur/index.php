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

$I18N = [
    'id' => [
        'dashboard'         => 'Dashboard',
        'congratulation'    => 'Berhasil!',
        'warningError'      => 'Terjadi kesalahan!',
        'addData'           => 'Tambah data',
        'searchPlaceholder' => 'Pencarian...',
        'colNo'             => '#',
        'colTitle'          => 'Judul',
        'colDescription'    => 'Deskripsi',
        'colImage'          => 'Gambar',
        'colAction'         => 'Aksi',
        'deleteConfirm'     => 'Hapus data? | Apakah anda yakin?',
        'showing'           => 'Menampilkan %1$s sampai %2$s dari %3$s data',
    ],
    'en' => [
        'dashboard'         => 'Dashboard',
        'congratulation'    => 'Success!',
        'warningError'      => 'Error!',
        'addData'           => 'Add data',
        'searchPlaceholder' => 'Search...',
        'colNo'             => '#',
        'colTitle'          => 'Title',
        'colDescription'    => 'Description',
        'colImage'          => 'Image',
        'colAction'         => 'Action',
        'deleteConfirm'     => 'Delete data? | Are you sure?',
        'showing'           => 'Showing %1$s to %2$s of %3$s entries',
    ],
];

$__ = function (string $key, ...$args) use ($I18N, $lang) {
    $text = $I18N[$lang][$key] ?? $I18N['id'][$key] ?? $key;
    return $args ? vsprintf($text, $args) : $text;
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
            <div class="breadcrumb-item active"><a href="<?= site_url('dashboard'); ?>"><?= $__('dashboard'); ?></a></div>
            <div class="breadcrumb-item"><?= $title; ?></div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b><?= $__('congratulation'); ?></b>
                <?= session()->getFlashdata('success'); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b><?= $__('warningError'); ?></b>
                <?= session()->getFlashdata('error'); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4 class="text-sm">
                    <a href="<?= site_url('struktur/new'); ?>" class="btn btn-primary"><i class="fas fa-plus-circle mr-1"></i><?= $__('addData'); ?></a>
                    <!-- <a href="<?= site_url('struktur/trash'); ?>" class="btn btn-danger"><i class="fas fa-trash mr-1"></i>Data yang dihapus</a> -->
                </h4>
                <div class="card-header-form">
                    <form action="" method="GET" autocomplete="off">
                        <div class="input-group">
                            <input name="keyword" value="<?= $keyword; ?>" type="text" class="form-control" placeholder="<?= $__('searchPlaceholder'); ?>">
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
                                <th><?= $__('colNo'); ?></th>
                                <th><?= $__('colTitle'); ?></th>
                                <th><?= $__('colDescription'); ?></th>
                                <th><?= $__('colImage'); ?></th>
                                <th class="text-center"><?= $__('colAction'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $page = isset($_GET['page']) ? $_GET['page'] : 1;
                            $no = 1 + (10 * ($page - 1));
                            foreach ($struktur as $pr => $v_struktur): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td class="text-wrap" style="max-width:300px;"><?= $v_struktur->judul; ?></td>
                                    <td class="text-wrap" style="max-width:300px;"><?= $v_struktur->deskripsi; ?></td>
                                    <td><img src="<?= base_url('/img/upload/struktur/' . $v_struktur->gambar) ?>" alt="struktur - <?php $v_struktur->judul; ?>" class="rounded m-2 gambar-item pe-auto" style="width:100px; height:100px; cursor:pointer;"></td>
                                    <td class="text-center">
                                        <!-- <a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-eye"></i></a> -->
                                        <a href="<?= site_url('struktur/' . $v_struktur->struktur_id . '/edit'); ?>" class="btn btn-warning btn-sm"><i class="fas fa-pencil-alt"></i></a>
                                        <form action="<?= site_url('struktur/' . $v_struktur->struktur_id); ?>" method="POST" class="d-inline" id="del-<?= $v_struktur->struktur_id; ?>">
                                            <?= csrf_field(); ?>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button class="btn btn-danger btn-sm" data-confirm="<?= $__('deleteConfirm'); ?>" data-confirm-yes="submitDel(<?= $v_struktur->struktur_id; ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <?php
                    $from  = 1 + (10 * ($page - 1));
                    $to    = $no - 1;
                    $total = $pager->getTotal();
                    ?>

                    <div class="float-left">
                        <i><?= $__('showing', $from, $to, $total); ?></i>
                    </div>
                    <div class="float-right">
                        <?= $pager->links('default', 'pagination'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal -->
<div class="modal fade" id="gambar-popup" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <img src="<?= base_url('/img/upload/struktur/' . $v_struktur->gambar) ?>" alt="struktur - <?php $v_struktur->judul; ?>" class="modal-img w-100">
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
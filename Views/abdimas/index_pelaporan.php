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

$tr = [
    'id' => [
        'laporan' => 'Laporan',
        'dashboard' => 'Dashboard',
        'registrasi_abdimas' => 'Registrasi abdimas',
        'pencarian' => 'Pencarian...',
        'selamat' => 'Selamat!',
        'warning_error' => 'Warning Error!',
        'action' => 'Action',

        'judul_kegiatan' => 'Judul Kegiatan',
        'belum_ada_judul' => 'Belum ada Judul Kegiatan',
        'dana_kegiatan' => 'Dana Kegiatan',
        'belum_ada_dana' => 'Belum ada Dana Kegiatan',
        'mitra' => 'Mitra',
        'periode' => 'Periode',
        'tidak_tersedia' => 'Tidak Tersedia',
        'status' => 'Status',
        'catatan_perbaikan' => 'Catatan Perbaikan',
        'tidak_ada_catatan_revisi' => 'Tidak ada catatan revisi',
        'anggota' => 'Anggota',
        'ketua' => 'Ketua',

        'proses' => 'PROSES',
        'disetujui' => 'DISETUJUI',
        'revisi' => 'REVISI',

        'lihat_laporan' => 'Lihat Laporan',
        'edit_laporan' => 'Edit Laporan',
        'hapus_laporan' => 'Hapus Laporan',

        'lembar_pengesahan' => 'Lembar Pengesahan',
        'arsip_dokumen_abdimas' => 'Arsip Dokumen Abdimas',

        'showing' => 'Showing {from} to {to} of {total} entries',
    ],
    'en' => [
        'laporan' => 'Reports',
        'dashboard' => 'Dashboard',
        'registrasi_abdimas' => 'Register abdimas',
        'pencarian' => 'Search...',
        'selamat' => 'Success!',
        'warning_error' => 'Warning Error!',
        'action' => 'Action',

        'judul_kegiatan' => 'Activity Title',
        'belum_ada_judul' => 'No activity title yet',
        'dana_kegiatan' => 'Activity Fund',
        'belum_ada_dana' => 'No fund yet',
        'mitra' => 'Partner',
        'periode' => 'Period',
        'tidak_tersedia' => 'Not available',
        'status' => 'Status',
        'catatan_perbaikan' => 'Revision Note',
        'tidak_ada_catatan_revisi' => 'No revision notes',
        'anggota' => 'Members',
        'ketua' => 'Leader',

        'proses' => 'IN PROGRESS',
        'disetujui' => 'APPROVED',
        'revisi' => 'REVISION',

        'lihat_laporan' => 'View Report',
        'edit_laporan' => 'Edit Report',
        'hapus_laporan' => 'Delete Report',

        'lembar_pengesahan' => 'Approval Sheet',
        'arsip_dokumen_abdimas' => 'Abdimas Document Archive',

        'showing' => 'Showing {from} to {to} of {total} entries',
    ],
];

$t = function (string $key, array $vars = []) use ($tr, $lang) {
    $text = $tr[$lang][$key] ?? $key;
    foreach ($vars as $k => $v) {
        $text = str_replace('{' . $k . '}', (string)$v, $text);
    }
    return $text;
};
?>

<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <h1><?= esc($t('laporan')); ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="<?= site_url('dashboard'); ?>"><?= esc($t('dashboard')); ?></a></div>
            <div class="breadcrumb-item"><?= esc($t('laporan')); ?></div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b><?= esc($t('selamat')); ?></b>
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
                    <a href="<?= site_url('abdimas/new'); ?>" class="btn btn-success"><i class="fas fa-plus-circle mr-1"></i><?= esc($t('registrasi_abdimas')); ?></a>
                </h4>
                <div class="card-header-form">
                    <form action="" method="GET" autocomplete="off">
                        <div class="input-group">
                            <input name="keyword" value="<?= $keyword; ?>" type="text" class="form-control" placeholder="<?= esc($t('pencarian')); ?>">
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
                                <th><?= esc($t('laporan')); ?></th>
                                <th class="text-center"><?= esc($t('action')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $page = isset($_GET['page']) ? $_GET['page'] : 1;
                            $no = 1 + (10 * ($page - 1));
                            foreach ($laporan as $tag => $v_abdimas) : ?>
                                <?php if ($v_abdimas->anggota_id == userLogin()->user_id): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td class="p-2">

                                            <!-- Informasi Tambahan -->
                                            <div class="row">

                                                <div class="col-md-4">
                                                    <div class="card p-2">
                                                        <b><?= esc($t('judul_kegiatan')); ?>: </b>

                                                        <?php if (!empty($v_abdimas->judul_kegiatan)) : ?>
                                                            <?= $v_abdimas->judul_kegiatan; ?>
                                                        <?php else : ?>
                                                            <span class="text-danger"><?= esc($t('belum_ada_judul')); ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="card p-2">
                                                        <b><?= esc($t('dana_kegiatan')); ?>: </b>

                                                        <?php if (!empty($v_abdimas->range_dana)) : ?>
                                                            <?= 'Rp. ' . number_format($v_abdimas->range_dana, 0, ',', '.'); ?>
                                                        <?php else : ?>
                                                            <span class="text-danger"><?= esc($t('belum_ada_dana')); ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="card p-2">
                                                        <b><?= esc($t('mitra')); ?>:</b>
                                                        <?php $seen = [];
                                                        foreach ($mitra as $mtr => $v_mitra): ?>
                                                            <?php if ($v_mitra->user_id == $v_abdimas->mitra_id && !isset($seen[$v_mitra->user_id])): ?>
                                                                <?= $v_mitra->user_name; ?>
                                                                <?php $seen[$v_mitra->user_id] = true; ?>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="card p-2">
                                                        <b><?= esc($t('periode')); ?>:</b>
                                                        <div>
                                                            <?= !empty($v_abdimas->periode_name) && !empty($v_abdimas->tahun_ajaran) ? esc($v_abdimas->periode_name) . ' ' . esc($v_abdimas->tahun_ajaran) : '<span class="text-danger">' . esc($t('tidak_tersedia')) . '</span>'; ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="card p-2">
                                                        <b><?= esc($t('status')); ?>:</b>
                                                        <?php if ($v_abdimas->verifikasi == 0): ?>
                                                            <span class='badge badge-primary'><?= esc($t('proses')); ?></span>
                                                        <?php elseif ($v_abdimas->verifikasi == 1): ?>
                                                            <span class='badge badge-success'><?= esc($t('disetujui')); ?></span>
                                                        <?php elseif ($v_abdimas->verifikasi == 2): ?>
                                                            <span class='badge badge-warning text-dark'><?= esc($t('revisi')); ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="card p-2">
                                                        <b><?= esc($t('catatan_perbaikan')); ?>: </b>
                                                        <?php if (!empty($v_abdimas->revisi)) : ?>
                                                            <?= $v_abdimas->revisi; ?>
                                                        <?php else : ?>
                                                            <span class="text-danger"><?= esc($t('tidak_ada_catatan_revisi')); ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card p-2">
                                                <b><?= esc($t('anggota')); ?> : </b>
                                                <?php
                                                $seen = [];
                                                $counter = 1;
                                                $output = [];

                                                foreach ($tags as $key => $v_tags):
                                                    if ($v_abdimas->laporan_id == $v_tags->laporan_id && !isset($seen[$v_tags->laporan_id])):
                                                        if ($v_tags->anggota_id == $v_abdimas->ketua_id):
                                                            $output[] = "<div class='item'>{$counter}. " . ucwords(strtolower($v_tags->user_name)) .
                                                                " (<span class='text-danger'>" . $t('ketua') . "</span>)<br>
                                                                <b>NIDN:</b> {$v_tags->nidn} <br>
                                                                <b>SINTA ID:</b> {$v_tags->sinta_id}</div>";
                                                        else:
                                                            $output[] = "<div class='item'>{$counter}. " . ucwords(strtolower($v_tags->user_name)) .
                                                                "<br> <b>NIDN:</b> {$v_tags->nidn} <br>
                                                                <b>SINTA ID:</b> {$v_tags->sinta_id}</div>";
                                                        endif;

                                                        $seen[$v_tags->laporan_id] = true;
                                                        $counter++;
                                                    endif;
                                                endforeach;
                                                ?>

                                                <style>
                                                    .container {
                                                        display: flex;
                                                        flex-wrap: wrap;
                                                        gap: 20px;
                                                    }

                                                    .item {
                                                        width: calc(25% - 20px);
                                                        /* Membagi jadi 2 kolom */
                                                        background: #f8f9fa;
                                                        padding: 10px;
                                                        border: 1px solid #ddd;
                                                        border-radius: 5px;
                                                    }
                                                </style>

                                                <div class="container">
                                                    <?= implode("\n", $output); ?>
                                                </div>
                                            </div>

                                            <br>

                                        </td>
                                        <td class="text-center">
                                            <a href="<?= site_url('abdimas/' . $v_abdimas->laporan_id); ?>" class="btn btn-dark btn-sm m-1 show-item" style="width:150px;"><?= esc($t('lihat_laporan')); ?></a><br>
                                            <a href="<?= site_url('pelaporan/' . $v_abdimas->laporan_id . '/edit'); ?>" class="btn btn-primary btn-sm" style="width:150px;"><?= esc($t('edit_laporan')); ?></a><br>
                                            <form action="<?= site_url('abdimas/' . $v_abdimas->laporan_id); ?>" method="POST" class="d-inline" id="del-<?= $v_abdimas->laporan_id; ?>">
                                                <?= csrf_field(); ?>
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button class="btn btn-danger btn-sm m-1" style="width:150px;" data-confirm="Hapus data? | Apakah anda yakin?" data-confirm-yes="submitDel(<?= $v_abdimas->laporan_id; ?>)">
                                                    <?= esc($t('hapus_laporan')); ?>
                                                </button>
                                            </form><br>
                                            <?php if ($v_abdimas->verifikasi == 1): ?>
                                                <a href="<?= site_url('/abdimas/pdf/' . $v_abdimas->laporan_id); ?>" class="btn btn-success btn-sm" style="width:150px;"><?= esc($t('lembar_pengesahan')); ?></a><br>
                                                <a href="<?= site_url('abdimas/arsip/' . $v_abdimas->laporan_id); ?>" class="btn btn-warning btn-sm" style="width:150px;"><?= esc($t('arsip_dokumen_abdimas')); ?></a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="float-left">
                        <?php
                        $from  = 1 + (10 * ($page - 1));
                        $to    = $no - 1;
                        $total = $pager->getTotal();
                        ?>
                        <i><?= esc($t('showing', ['from' => $from, 'to' => $to, 'total' => $total])); ?></i>
                    </div>
                    <div class="float-right">
                        <?= $pager->links('default', 'pagination'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // Optional helper (tidak menambah UI):
    // panggil dari mana saja: window.setLang('en') / window.setLang('id')
    // Max-Age & SameSite adalah atribut cookie standar. :contentReference[oaicite:1]{index=1}
    window.setLang = function(lang) {
        var maxAge = 60 * 60 * 24 * 365; // 1 tahun
        document.cookie = "lang=" + encodeURIComponent(lang) + "; path=/; max-age=" + maxAge + "; SameSite=Lax";
        location.reload();
    };
</script>

<?= $this->endSection() ?>
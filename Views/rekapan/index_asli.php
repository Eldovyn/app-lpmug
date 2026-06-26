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

        'leader' => 'Ketua',
        'members' => 'Anggota',
        'description' => 'Deskripsi',
        'period' => 'Periode',
        'status' => 'Status',
        'action' => 'Action',

        'field' => 'Bidang Ilmu:',
        'partner' => 'Mitra:',
        'address' => 'Alamat:',

        'process' => 'PROSES',
        'approved' => 'DISETUJUI',
        'revision' => 'REVISI',

        'view' => 'Lihat',
        'delete' => 'Hapus',
        'confirm_delete' => 'Hapus data? | Apakah anda yakin?',
        'leader_tag' => 'Ketua',
    ],
    'en' => [
        'dashboard' => 'Dashboard',
        'congrats' => 'Congratulations!',
        'warning_error' => 'Warning!',

        'leader' => 'Leader',
        'members' => 'Members',
        'description' => 'Description',
        'period' => 'Period',
        'status' => 'Status',
        'action' => 'Action',

        'field' => 'Field:',
        'partner' => 'Partner:',
        'address' => 'Address:',

        'process' => 'IN PROGRESS',
        'approved' => 'APPROVED',
        'revision' => 'REVISION',

        'view' => 'View',
        'delete' => 'Delete',
        'confirm_delete' => 'Delete data? | Are you sure?',
        'leader_tag' => 'Leader',
    ],
];

$t = function (string $key) use ($tr, $lang) {
    return $tr[$lang][$key] ?? $tr['id'][$key] ?? $key;
};
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
            <div class="card-body">
                <div class="table-responsive-md">
                    <table class="table table-striped" id="table-1">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?= esc($t('leader')); ?></th>
                                <th><?= esc($t('members')); ?></th>
                                <th><?= esc($t('description')); ?></th>
                                <th><?= esc($t('period')); ?></th>
                                <th><?= esc($t('status')); ?></th>
                                <th class="text-center"><?= esc($t('action')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $page = isset($_GET['page']) ? $_GET['page'] : 1;
                            $no = 1 + (10 * ($page - 1));
                            foreach ($abdimas as $tag => $v_abdimas) : ?>
                                <tr>
                                    <td><?= $no++; ?></td>

                                    <td class="text-wrap">
                                        <?php foreach ($tags as $key => $v_tags): ?>
                                            <?php if ($v_abdimas->laporan_id == $v_tags->laporan_id): ?>
                                                <?php if ($v_tags->anggota_id == $v_abdimas->ketua_id): ?>
                                                    <?= ucwords(strtolower($v_tags->user_name)); ?>
                                                    (<span class="text-danger"><?= esc($t('leader_tag')); ?></span>)
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        <?php endforeach ?>
                                    </td>

                                    <td class="text-wrap">
                                        <?php $seen = [];
                                        $counter = 1;
                                        foreach ($tags as $key => $v_tags): ?>
                                            <?php if ($v_abdimas->laporan_id == $v_tags->laporan_id && !isset($seen[$v_tags->laporan_id])): ?>
                                                <?= $counter . '. ' . ucwords(strtolower($v_tags->user_name)); ?> ||
                                                <?php $seen[$v_tags->laporan_id] = true;
                                                $counter++; ?>
                                            <?php endif; ?>
                                        <?php endforeach ?>
                                    </td>

                                    <td class="text-wrap">
                                        <b><?= esc($t('field')); ?></b>
                                        <?php foreach ($dosen as $ds => $v_dosen): ?>
                                            <?php if ($v_dosen->user_id == $v_abdimas->ketua_id): ?>
                                                <?= $v_dosen->jurusan_name; ?>
                                            <?php endif; ?>
                                        <?php endforeach ?>
                                        <br><br>

                                        <b><?= esc($t('partner')); ?></b>
                                        <?php $seen = [];
                                        foreach ($mitra as $mtr => $v_mitra): ?>
                                            <?php if ($v_mitra->user_id == $v_abdimas->mitra_id && !isset($seen[$v_mitra->user_id])): ?>
                                                <?= $v_mitra->user_name; ?>
                                                <?php $seen[$v_mitra->user_id] = true; ?>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                        <br><br>

                                        <b><?= esc($t('address')); ?></b>
                                        <?php $seen = [];
                                        foreach ($mitra as $mtr => $alt_mitra): ?>
                                            <?php if ($alt_mitra->user_id == $v_abdimas->mitra_id && !isset($seen[$alt_mitra->user_id])): ?>
                                                <?= $alt_mitra->alamat; ?>
                                                <?php $seen[$alt_mitra->user_id] = true; ?>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                        <br><br>
                                    </td>

                                    <td class="text-wrap">
                                        <?php foreach ($periode as $rr => $v_periode): ?>
                                            <?php if ($v_periode->periode_id == $v_abdimas->periode_id): ?>
                                                <?= $v_periode->periode_name; ?> <?= $v_periode->tahun_ajaran; ?>
                                            <?php endif; ?>
                                        <?php endforeach ?>
                                    </td>

                                    <td class="text-center">
                                        <?php if ($v_abdimas->verifikasi == 0): ?>
                                            <span class="badge badge-primary px-lg-4"><?= esc($t('process')); ?></span>
                                        <?php elseif ($v_abdimas->verifikasi == 1): ?>
                                            <span class="badge badge-success px-lg-4"><?= esc($t('approved')); ?></span>
                                        <?php elseif ($v_abdimas->verifikasi == 2): ?>
                                            <span class="badge badge-warning px-lg-4 text-dark"><?= esc($t('revision')); ?></span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center">
                                        <a href="<?= site_url('rekapan/' . $v_abdimas->laporan_id . '/edit'); ?>" class="btn btn-dark btn-sm p-1" style="width=150px">
                                            <?= esc($t('view')); ?>
                                        </a><br>

                                        <form action="<?= site_url('abdimas/' . $v_abdimas->laporan_id); ?>" method="POST" class="d-inline" id="del-<?= $v_abdimas->laporan_id; ?>">
                                            <?= csrf_field(); ?>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button class="btn btn-danger btn-sm p-1" style="width=150px"
                                                data-confirm="<?= esc($t('confirm_delete')); ?>"
                                                data-confirm-yes="submitDel(<?= $v_abdimas->laporan_id; ?>)">
                                                <?= esc($t('delete')); ?>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</section>
<?= $this->endSection() ?>

<script>
    new DataTable('#table-1', {
        columnDefs: [{
            width: '20%',
            targets: 0
        }]
    });
</script>
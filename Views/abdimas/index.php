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

$I18N = [
    'id' => [
        'dashboard'            => 'Dashboard',
        'submission'           => 'Pengusulan',
        'successTitle'         => 'Selamat!',
        'errorTitle'           => 'Warning Error!',
        'registerAbdimas'      => 'Registrasi abdimas',
        'searchPlaceholder'    => 'Pencarian...',
        'report'               => 'Laporan',
        'action'               => 'Action',

        'activityTitle'        => 'Judul Kegiatan:',
        'noActivityTitle'      => 'Belum ada Judul Kegiatan',
        'activityFund'         => 'Dana Kegiatan:',
        'noActivityFund'       => 'Belum ada Dana Kegiatan',
        'partner'              => 'Mitra:',
        'period'               => 'Periode:',
        'notAvailable'         => 'Tidak Tersedia',
        'status'               => 'Status:',
        'statusProcess'        => 'PROSES',
        'statusApproved'       => 'DISETUJUI',
        'statusRevision'       => 'REVISI',

        'revisionNote'         => 'Catatan Perbaikan:',
        'noRevisionNote'       => 'Tidak ada catatan revisi',

        'members'              => 'Anggota :',
        'leader'               => 'Ketua',
        'nidn'                 => 'NIDN:',
        'sinta'                => 'SINTA ID:',

        'editSubmission'       => 'Edit Pengusulan',
        'viewSpm'              => 'Lihat SPM',

        'showing'              => 'Showing %1$s to %2$s of %3$s entries',
    ],
    'en' => [
        'dashboard'            => 'Dashboard',
        'submission'           => 'Submission',
        'successTitle'         => 'Success!',
        'errorTitle'           => 'Error!',
        'registerAbdimas'      => 'Register abdimas',
        'searchPlaceholder'    => 'Search...',
        'report'               => 'Report',
        'action'               => 'Action',

        'activityTitle'        => 'Activity title:',
        'noActivityTitle'      => 'No activity title yet',
        'activityFund'         => 'Activity fund:',
        'noActivityFund'       => 'No activity fund yet',
        'partner'              => 'Partner:',
        'period'               => 'Period:',
        'notAvailable'         => 'Not available',
        'status'               => 'Status:',
        'statusProcess'        => 'PROCESS',
        'statusApproved'       => 'APPROVED',
        'statusRevision'       => 'REVISION',

        'revisionNote'         => 'Revision notes:',
        'noRevisionNote'       => 'No revision notes',

        'members'              => 'Members :',
        'leader'               => 'Leader',
        'nidn'                 => 'NIDN:',
        'sinta'                => 'SINTA ID:',

        'editSubmission'       => 'Edit Submission',
        'viewSpm'              => 'View SPM',

        'showing'              => 'Showing %1$s to %2$s of %3$s entries',
    ],
];

$__ = function (string $key, ...$args) use ($I18N, $lang) {
    $text = $I18N[$lang][$key] ?? $I18N['id'][$key] ?? $key;
    return $args ? vsprintf($text, $args) : $text;
}; // closure callable via __invoke :contentReference[oaicite:3]{index=3}
?>

<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <h1><?= $__('submission'); ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="<?= site_url('dashboard'); ?>"><?= $__('dashboard'); ?></a></div>
            <div class="breadcrumb-item"><?= $__('submission'); ?></div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b><?= $__('successTitle'); ?></b>
                <?= session()->getFlashdata('success'); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b><?= $__('errorTitle'); ?></b>
                <?= session()->getFlashdata('error'); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4 class="text-sm">
                    <a href="<?= site_url('abdimas/new'); ?>" class="btn btn-success"><i class="fas fa-plus-circle mr-1"></i><?= $__('registerAbdimas'); ?></a>
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
                                <th>#</th>
                                <th><?= $__('report'); ?></th>
                                <th class="text-center"><?= $__('action'); ?></th>
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
                                                        <b><?= $__('activityTitle'); ?> </b>

                                                        <?php if ($v_abdimas->judul_kegiatan == !null) : ?>
                                                            <?= $v_abdimas->judul_kegiatan; ?>
                                                        <?php else : ?>
                                                            <span class="text-danger"><?= $__('noActivityTitle'); ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="card p-2">
                                                        <b><?= $__('activityFund'); ?> </b>

                                                        <?php if ($v_abdimas->range_dana == !null) : ?>
                                                            <?= 'Rp. ' . number_format($v_abdimas->range_dana, 0, ',', '.'); ?>
                                                        <?php else : ?>
                                                            <span class="text-danger"><?= $__('noActivityFund'); ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="card p-2">
                                                        <b><?= $__('partner'); ?></b>
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
                                                        <b><?= $__('period'); ?></b>
                                                        <div>
                                                            <?= !empty($v_abdimas->periode_name) && !empty($v_abdimas->tahun_ajaran)
                                                                ? esc($v_abdimas->periode_name) . ' ' . esc($v_abdimas->tahun_ajaran)
                                                                : '<span class="text-danger">' . $__('notAvailable') . '</span>'; ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="card p-2">
                                                        <b><?= $__('status'); ?></b>
                                                        <?php if ($v_abdimas->verifikasi == 0): ?>
                                                            <span class='badge badge-primary'><?= $__('statusProcess'); ?></span>
                                                        <?php elseif ($v_abdimas->verifikasi == 1): ?>
                                                            <span class='badge badge-success'><?= $__('statusApproved'); ?></span>
                                                        <?php elseif ($v_abdimas->verifikasi == 2): ?>
                                                            <span class='badge badge-warning text-dark'><?= $__('statusRevision'); ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="card p-2">
                                                        <b><?= $__('revisionNote'); ?> </b>
                                                        <?php if ($v_abdimas->revisi == !null) : ?>
                                                            <?= $v_abdimas->revisi; ?>
                                                        <?php else : ?>
                                                            <span class="text-danger"><?= $__('noRevisionNote'); ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card p-2">
                                                <b><?= $__('members'); ?> </b>
                                                <?php
                                                $seen = [];
                                                $counter = 1;
                                                $output = [];

                                                foreach ($tags as $key => $v_tags):
                                                    if ($v_abdimas->laporan_id == $v_tags->laporan_id && !isset($seen[$v_tags->laporan_id])):
                                                        if ($v_tags->anggota_id == $v_abdimas->ketua_id):
                                                            $output[] = "<div class='item'>{$counter}. " . ucwords(strtolower($v_tags->user_name)) .
                                                                " (<span class='text-danger'>{$__('leader')}</span>)<br> 
                                                                <b>{$__('nidn')}</b> {$v_tags->nidn} <br> 
                                                                <b>{$__('sinta')}</b> {$v_tags->sinta_id}</div>";
                                                        else:
                                                            $output[] = "<div class='item'>{$counter}. " . ucwords(strtolower($v_tags->user_name)) .
                                                                "<br> <b>{$__('nidn')}</b> {$v_tags->nidn} <br> 
                                                                <b>{$__('sinta')}</b> {$v_tags->sinta_id}</div>";
                                                        endif;

                                                        $seen[$v_mitra->user_id] = true;
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
                                            <a href="<?= site_url('abdimas/' . $v_abdimas->laporan_id . '/edit'); ?>" class="btn btn-warning btn-sm" style="width:150px;"><?= $__('editSubmission'); ?></a><br>

                                            <?php if (isset($v_abdimas->spm_exists) && $v_abdimas->spm_exists): ?>
                                                <a href="<?= site_url('abdimas/lihatDokumen/spm/' . $v_abdimas->mitra_id . '/' . $v_abdimas->periode_id); ?>"
                                                    class="btn btn-info btn-sm" style="width:150px;" target="_blank">
                                                    <i class="fas fa-file-pdf"></i> <?= $__('viewSpm'); ?>
                                                </a><br>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
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

<?= $this->endSection() ?>
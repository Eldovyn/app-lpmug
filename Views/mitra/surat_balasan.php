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
        'titlePage'            => 'Surat Balasan Pengabdian Masyarakat',
        'dashboard'            => 'Dashboard',
        'breadcrumb'           => 'Surat Balasan',

        'manageTitle'          => 'Kelola Surat Balasan',

        'noteLabel'            => 'Catatan:',
        'noteText'             => 'Surat balasan dapat di-generate untuk laporan yang sudah memiliki data lengkap (Judul Kegiatan dan Ketua Pengusul sudah terdaftar).',

        'colNo'                => 'No',
        'colActivityTitle'     => 'Judul Kegiatan',
        'colPeriod'            => 'Periode',
        'colStatus'            => 'Status',
        'colAction'            => 'Aksi',

        'notFilled'            => 'Belum diisi',
        'readyGenerate'        => 'Siap Generate',
        'incompleteData'       => 'Data Belum Lengkap',

        'btnGeneratePdf'       => 'Generate PDF',
        'btnIncomplete'        => 'Data Belum Lengkap',

        'allAvailableTitle'    => 'Semua Surat Sudah Tersedia',
        'allAvailableDesc'     => 'Semua laporan Anda sudah memiliki surat balasan atau belum memiliki data yang cukup untuk generate surat.',

        'loadingSrOnly'        => 'Loading...',
        'loadingTitle'         => 'Sedang Generate PDF...',
        'loadingDesc'          => 'Mohon tunggu, proses ini membutuhkan waktu beberapa saat.',

        // JS texts
        'confirmPrefix'        => "Generate surat balasan untuk kegiatan:\n\"",
        'confirmSuffix'        => "\"?\n\nProses ini akan membuka PDF di tab baru.",
        'popupBlocked'         => 'Pop-up diblokir! Silakan aktifkan pop-up untuk browser ini.',
    ],
    'en' => [
        'titlePage'            => 'Community Service Reply Letter',
        'dashboard'            => 'Dashboard',
        'breadcrumb'           => 'Reply Letter',

        'manageTitle'          => 'Manage Reply Letter',

        'noteLabel'            => 'Note:',
        'noteText'             => 'A reply letter can be generated for reports that already have complete data (Activity Title and Proposer/Leader are registered).',

        'colNo'                => 'No',
        'colActivityTitle'     => 'Activity Title',
        'colPeriod'            => 'Period',
        'colStatus'            => 'Status',
        'colAction'            => 'Action',

        'notFilled'            => 'Not filled yet',
        'readyGenerate'        => 'Ready to Generate',
        'incompleteData'       => 'Incomplete Data',

        'btnGeneratePdf'       => 'Generate PDF',
        'btnIncomplete'        => 'Incomplete Data',

        'allAvailableTitle'    => 'All Letters Are Available',
        'allAvailableDesc'     => 'All of your reports already have a reply letter, or there is not enough data to generate one.',

        'loadingSrOnly'        => 'Loading...',
        'loadingTitle'         => 'Generating PDF...',
        'loadingDesc'          => 'Please wait, this process may take a moment.',

        // JS texts
        'confirmPrefix'        => "Generate a reply letter for:\n\"",
        'confirmSuffix'        => "\"?\n\nThis will open the PDF in a new tab.",
        'popupBlocked'         => 'Pop-up blocked! Please allow pop-ups for this site.',
    ],
];

$t = function (string $key, ...$args) use ($I18N, $lang) {
    $text = $I18N[$lang][$key] ?? $I18N['id'][$key] ?? $key;
    return $args ? vsprintf($text, $args) : $text;
};
?>

<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php helper('form'); ?>

<section class="section">
    <div class="section-header">
        <h1><?= $t('titlePage'); ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="<?= site_url('dashboard') ?>"><?= $t('dashboard'); ?></a></div>
            <div class="breadcrumb-item"><?= $t('breadcrumb'); ?></div>
        </div>
    </div>

    <div class="section-body">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                    <?= session()->getFlashdata('error') ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                    <?= session()->getFlashdata('success') ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h4><?= $t('manageTitle'); ?></h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <?php if (!empty($laporan_without_surat)): ?>
                            <div class="alert alert-warning">
                                <i class="fas fa-info-circle"></i>
                                <strong><?= $t('noteLabel'); ?></strong> <?= $t('noteText'); ?>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th width="5%"><?= $t('colNo'); ?></th>
                                            <th width="40%"><?= $t('colActivityTitle'); ?></th>
                                            <th width="15%"><?= $t('colPeriod'); ?></th>
                                            <th width="15%"><?= $t('colStatus'); ?></th>
                                            <th width="25%"><?= $t('colAction'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($laporan_without_surat as $index => $laporan): ?>
                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td>
                                                    <strong><?= esc($laporan->judul_kegiatan ?? $t('notFilled')) ?></strong>
                                                    <br><small class="text-muted">ID: <?= $laporan->laporan_id ?></small>
                                                </td>
                                                <td>
                                                    <span class="badge badge-primary">
                                                        <?= esc(($laporan->periode_name ?? '') . ' ' . ($laporan->tahun_ajaran ?? '')) ?: 'N/A' ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if (!empty($laporan->judul_kegiatan) && !empty($laporan->ketua_id)): ?>
                                                        <span class="badge badge-success"><?= $t('readyGenerate'); ?></span>
                                                    <?php else: ?>
                                                        <span class="badge badge-warning"><?= $t('incompleteData'); ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if (!empty($laporan->judul_kegiatan) && !empty($laporan->ketua_id)): ?>
                                                        <a href="<?= site_url('mitra/surat-balasan/generate/' . $laporan->laporan_id) ?>"
                                                            class="btn btn-primary btn-sm generate-pdf-btn"
                                                            data-laporan-id="<?= $laporan->laporan_id ?>"
                                                            data-judul="<?= esc($laporan->judul_kegiatan) ?>">
                                                            <i class="fas fa-magic"></i> <?= $t('btnGeneratePdf'); ?>
                                                        </a>
                                                    <?php else: ?>
                                                        <button class="btn btn-secondary btn-sm" disabled>
                                                            <i class="fas fa-exclamation-circle"></i> <?= $t('btnIncomplete'); ?>
                                                        </button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="empty-state text-center py-5">
                                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                <h5><?= $t('allAvailableTitle'); ?></h5>
                                <p class="text-muted"><?= $t('allAvailableDesc'); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="sr-only"><?= $t('loadingSrOnly'); ?></span>
                </div>
                <h5><?= $t('loadingTitle'); ?></h5>
                <p class="text-muted"><?= $t('loadingDesc'); ?></p>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        const confirmPrefix = <?= json_encode($t('confirmPrefix')); ?>;
        const confirmSuffix = <?= json_encode($t('confirmSuffix')); ?>;
        const popupBlocked = <?= json_encode($t('popupBlocked')); ?>;

        $('.generate-pdf-btn').click(function(e) {
            e.preventDefault();

            const judul = $(this).data('judul');
            const generateUrl = $(this).attr('href');

            if (!confirm(confirmPrefix + judul + confirmSuffix)) {
                return;
            }

            $('#loadingModal').modal('show');

            const newWindow = window.open(generateUrl, '_blank');

            if (newWindow) {
                setTimeout(function() {
                    $('#loadingModal').modal('hide');
                }, 3000);
            } else {
                $('#loadingModal').modal('hide');
                alert(popupBlocked);
            }
        });
    });
</script>

<?= $this->endSection() ?>
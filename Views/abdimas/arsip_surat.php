<?php
// ====== LOGIC TRANSLATION (SINGLE FILE) ======
helper(['cookie', 'url']);

$request = service('request');

// 1. Cek Cookie & URL Parameter untuk Bahasa
$allowed = ['id', 'en'];
$lang = get_cookie('lang') ?: 'id';

// Validasi jika cookie dimanipulasi
if (! in_array($lang, $allowed, true)) {
    $lang = 'id';
}

// Cek parameter URL ?lang=en
$reqLang = $request->getGet('lang');
if ($reqLang && in_array($reqLang, $allowed, true)) {
    set_cookie('lang', $reqLang, 60 * 60 * 24 * 30);
    $lang = $reqLang;
}

// 2. Kamus Kata (Dictionary)
$dict = [
    'id' => [
        'header_title'   => 'Arsip Surat Abdimas',
        'th_doc'         => 'Jenis Dokumen',
        'th_action'      => 'Aksi',
        'td_laporan'     => 'Laporan',
        'td_bukti'       => 'Bukti Kegiatan',
        'td_spm'         => 'SPM',
        'td_skm'         => 'SKM',
        'td_balasan'     => 'Surat Balasan',
        'btn_lihat'      => 'Lihat',
        'btn_balasan'    => 'Surat Balasan',
        'empty_laporan'  => 'Belum ada laporan',
        'empty_bukti'    => 'Belum ada bukti kegiatan',
        'empty_spm'      => 'Belum ada SPM',
        'empty_skm'      => 'Belum ada SKM',
        'footer_text'    => 'Data arsip ini dikelola oleh LPM',
    ],
    'en' => [
        'header_title'   => 'Abdimas Letter Archive',
        'th_doc'         => 'Document Type',
        'th_action'      => 'Action',
        'td_laporan'     => 'Report',
        'td_bukti'       => 'Activity Evidence',
        'td_spm'         => 'SPM',
        'td_skm'         => 'SKM',
        'td_balasan'     => 'Reply Letter',
        'btn_lihat'      => 'View',
        'btn_balasan'    => 'Reply Letter',
        'empty_laporan'  => 'No report available',
        'empty_bukti'    => 'No evidence available',
        'empty_spm'      => 'No SPM available',
        'empty_skm'      => 'No SKM available',
        'footer_text'    => 'This archive data is managed by LPM',
    ],
];

// 3. Fungsi Penerjemah (Closure agar aman di dalam View)
$t = function ($key) use ($dict, $lang) {
    return $dict[$lang][$key] ?? $dict['id'][$key] ?? $key;
};

// 4. Helper URL Switcher (Opsional, jika ingin dipakai manual)
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
?>

<?= $this->extend('layouts/default') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-3">
    <div class="card shadow-sm border-0 rounded-3">
        <!-- Header -->
        <div class="card-header bg-white border-0 d-flex align-items-center">
            <i class="fas fa-folder-open text-primary me-2"></i>
            <h5 class="mb-0 fw-bold text-dark"><?= esc($t('header_title')) ?></h5>
        </div>

        <!-- Body -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="bg-white text-dark text-center">
                        <tr>
                            <th style="width: 30%;"><?= esc($t('th_doc')) ?></th>
                            <th><?= esc($t('th_action')) ?></th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <tr>
                            <td class="text-black bg-white"><?= esc($t('td_laporan')) ?></td>
                            <td>
                                <?php if ($abdimas->laporan): ?>
                                    <a href="<?= site_url('berkas/laporan/' . $abdimas->laporan) ?>"
                                        target="_blank"
                                        class="btn btn-warning text-white btn-sm">
                                        <i class="fas fa-file-alt me-1"></i> <?= esc($t('btn_lihat')) ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-danger fw-semibold"><?= esc($t('empty_laporan')) ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-black bg-white"><?= esc($t('td_bukti')) ?></td>
                            <td>
                                <?php if ($abdimas->bukti_kegiatan): ?>
                                    <a href="<?= site_url('berkas/kegiatan/' . $abdimas->bukti_kegiatan) ?>"
                                        target="_blank"
                                        class="btn btn-warning text-white btn-sm">
                                        <i class="fas fa-image me-1"></i> <?= esc($t('btn_lihat')) ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-danger fw-semibold"><?= esc($t('empty_bukti')) ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-black bg-white"><?= esc($t('td_spm')) ?></td>
                            <td>
                                <?php if ($spm): ?>
                                    <a href="<?= site_url($spm['file_path']) ?>"
                                        target="_blank"
                                        class="btn btn-warning text-white btn-sm">
                                        <i class="fas fa-file me-1"></i> <?= esc($t('btn_lihat')) ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-danger fw-semibold"><?= esc($t('empty_spm')) ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-black bg-white"><?= esc($t('td_skm')) ?></td>
                            <td>
                                <?php if ($skm): ?>
                                    <a href="<?= site_url($skm['file_path']) ?>"
                                        target="_blank"
                                        class="btn btn-warning text-white btn-sm">
                                        <i class="fas fa-file-signature me-1"></i> <?= esc($t('btn_lihat')) ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-danger fw-semibold"><?= esc($t('empty_skm')) ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-black bg-white"><?= esc($t('td_balasan')) ?></td>
                            <td>
                                <a href="<?= site_url('abdimas/surat-balasan-pdf/' . $abdimas->laporan_id); ?>"
                                    class="btn btn-success btn-sm"
                                    style="width:150px;"
                                    target="_blank">
                                    <i class="fas fa-envelope-open-text me-1"></i> <?= esc($t('btn_balasan')) ?>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer -->
        <div class="card-footer bg-white text-center text-muted small border-0">
            <i class="fas fa-lock me-1"></i> <?= esc($t('footer_text')) ?> • <?= date('Y') ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
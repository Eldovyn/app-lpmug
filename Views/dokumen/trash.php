<?= $this->extend('layouts/default') ?>

<?php
$request = service('request');

$lang = $lang ?? ($request->getCookie('lang') ?? 'id');
$lang = strtolower(trim((string) $lang));
$lang = ($lang === 'en') ? 'en' : 'id'; // default id, selain 'en' => id (termasuk 'id/en')

$TR = [
    'id' => [
        'title_tab_default' => 'Trash',
        'title_default'     => 'Dokumen Terhapus',
        'back'              => 'Kembali ke Dokumen',
        'th_doc_name'       => 'Nama Dokumen',
        'th_file'           => 'File',
        'th_action'         => 'Aksi',
        'btn_view'          => 'Lihat',
        'btn_restore'       => 'Pulihkan',
        'confirm_restore'   => 'Yakin restore?',
        'empty'             => 'Trash kosong.',
    ],
    'en' => [
        'title_tab_default' => 'Trash',
        'title_default'     => 'Deleted Documents',
        'back'              => 'Back to Documents',
        'th_doc_name'       => 'Document Name',
        'th_file'           => 'File',
        'th_action'         => 'Action',
        'btn_view'          => 'View',
        'btn_restore'       => 'Restore',
        'confirm_restore'   => 'Are you sure you want to restore?',
        'empty'             => 'Trash is empty.',
    ],
];

$t = static function (string $key) use ($TR, $lang): string {
    return $TR[$lang][$key] ?? $TR['id'][$key] ?? $key;
};
?>

<?= $this->section('title') ?>
<title><?= esc($title_tab ?? $t('title_tab_default')); ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <h1><?= esc($title ?? $t('title_default')); ?></h1>
    </div>

    <!-- Flashdata -->
    <?php if (!empty($pesan)) : ?>
        <?php foreach ((array)$pesan as $p) : ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= esc($p); ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="container-fluid">
        <a href="<?= base_url('dokumen'); ?>" class="btn btn-primary mb-3">⬅ <?= esc($t('back')) ?></a>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th><?= esc($t('th_doc_name')) ?></th>
                        <th><?= esc($t('th_file')) ?></th>
                        <th><?= esc($t('th_action')) ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($dokumen)) : ?>
                        <?php foreach ($dokumen as $d) : ?>
                            <tr>
                                <td><?= esc($d['nama_file']); ?></td>
                                <td>
                                    <a href="<?= base_url($d['file_path']); ?>" target="_blank" class="btn btn-info btn-sm"><?= esc($t('btn_view')) ?></a>
                                </td>
                                <td>
                                    <a href="<?= base_url('dokumen/restore/' . $d['id']); ?>" class="btn btn-success btn-sm" onclick="return confirm('<?= esc($t('confirm_restore')) ?>')">♻ <?= esc($t('btn_restore')) ?></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="3" class="text-center py-3"><?= esc($t('empty')) ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
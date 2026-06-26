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
        'title_tab_default' => 'Dokumen',
        'title_default'     => 'Manajemen Dokumen',
        'ph_doc_name'       => 'Nama dokumen',
        'btn_upload'        => 'Upload',
        'btn_trash'         => 'Lihat Dokumen Terhapus',
        'th_doc_name'       => 'Nama Dokumen',
        'th_file'           => 'File',
        'th_action'         => 'Aksi',
        'btn_view'          => 'Lihat',
        'btn_edit'          => 'Edit',
        'btn_delete'        => 'Hapus',
        'confirm_delete'    => 'Yakin hapus?',
        'empty'             => 'Data dokumen kosong.',
    ],
    'en' => [
        'title_tab_default' => 'Documents',
        'title_default'     => 'Document Management',
        'ph_doc_name'       => 'Document name',
        'btn_upload'        => 'Upload',
        'btn_trash'         => 'View Deleted Documents',
        'th_doc_name'       => 'Document Name',
        'th_file'           => 'File',
        'th_action'         => 'Action',
        'btn_view'          => 'View',
        'btn_edit'          => 'Edit',
        'btn_delete'        => 'Delete',
        'confirm_delete'    => 'Are you sure you want to delete?',
        'empty'             => 'No documents found.',
    ],
];

$t = static function (string $key) use ($TR, $lang): string {
    return $TR[$lang][$key] ?? $TR['id'][$key] ?? $key;
};

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
        <!-- Upload Form -->
        <form class="form-inline mb-3" action="<?= base_url('dokumen/upload') ?>" method="post" enctype="multipart/form-data">
            <input type="text" name="nama_file" class="form-control mr-2" placeholder="<?= esc($t('ph_doc_name')) ?>" required>
            <input type="file" name="file_dokumen" class="form-control mr-2" required>
            <button type="submit" class="btn btn-primary">⬆ <?= esc($t('btn_upload')) ?></button>
        </form>

        <!-- Link Trash -->
        <a href="<?= base_url('dokumen/trash') ?>" class="btn btn-warning mb-3">🗑 <?= esc($t('btn_trash')) ?></a>

        <!-- Table -->
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
                                    <a href="<?= base_url('dokumen/edit/' . $d['id']); ?>" class="btn btn-success btn-sm">✏ <?= esc($t('btn_edit')) ?></a>
                                    <a href="<?= base_url('dokumen/delete/' . $d['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('<?= esc($t('confirm_delete')) ?>')">🗑 <?= esc($t('btn_delete')) ?></a>
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
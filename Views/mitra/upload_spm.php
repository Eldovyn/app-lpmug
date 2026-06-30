<<<<<<< HEAD
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

// ✅ TRANSLATION ARRAY
$TR = [
    'id' => [
        'page_title'           => 'Upload SPM',
        'label_periode'        => 'Pilih Periode',
        'placeholder_periode'  => '-- Pilih Periode --',
        'label_nomor_surat'    => 'Nomor Surat SPM',
        'placeholder_nomor'    => 'Masukkan nomor surat SPM',
        'label_file'           => 'Pilih File SPM (PDF max 5MB)',
        'btn_upload'           => 'Upload SPM',
        'msg_active_periode'   => '(Periode Aktif)',
        'msg_only_active'      => 'Hanya periode aktif yang dapat dipilih untuk upload SPM.',
        'msg_uploaded_files'   => 'File SPM yang sudah diupload:',
        'th_periode'           => 'Periode',
        'th_nomor_surat'       => 'Nomor Surat',
        'th_file'              => 'File SPM',
        'th_action'            => 'Aksi',
        'btn_preview'          => 'Preview',
        'btn_download'         => 'Download',
        'btn_delete'           => 'Hapus',
        'msg_confirm_delete'   => 'Apakah Anda yakin ingin menghapus file SPM ini?',
        'msg_no_files_title'   => 'Belum ada file SPM',
        'msg_no_files_desc'    => 'Upload file SPM untuk periode tertentu untuk melihatnya di sini.',
    ],

    'en' => [
        'page_title'           => 'Upload SPM',
        'label_periode'        => 'Select Period',
        'placeholder_periode'  => '-- Select Period --',
        'label_nomor_surat'    => 'SPM Letter Number',
        'placeholder_nomor'    => 'Enter SPM letter number',
        'label_file'           => 'Choose SPM File (PDF max 5MB)',
        'btn_upload'           => 'Upload SPM',
        'msg_active_periode'   => '(Active Period)',
        'msg_only_active'      => 'Only active periods can be selected for SPM upload.',
        'msg_uploaded_files'   => 'Uploaded SPM files:',
        'th_periode'           => 'Period',
        'th_nomor_surat'       => 'Letter Number',
        'th_file'              => 'SPM File',
        'th_action'            => 'Action',
        'btn_preview'          => 'Preview',
        'btn_download'         => 'Download',
        'btn_delete'           => 'Delete',
        'msg_confirm_delete'   => 'Are you sure you want to delete this SPM file?',
        'msg_no_files_title'   => 'No SPM files yet',
        'msg_no_files_desc'    => 'Upload SPM files for a specific period to see them here.',
    ],
];

$t = static function (string $key) use ($TR, $lang): string {
    return $TR[$lang][$key] ?? $TR['id'][$key] ?? $key;
};
?>

<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php helper('form'); ?>

<section class="section">
    <div class="section-body">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h4><?= esc($t('page_title')) ?></h4>
            </div>
            <div class="card-body">
                <form action="<?= site_url('mitra/uploadSubmit/' . $mitra->user_id) ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input type="hidden" name="doc_type" value="spm">

                    <!-- Dropdown Periode -->
                    <div class="form-group">
                        <label for="periode"><?= esc($t('label_periode')) ?></label>
                        <select name="periode_id" id="periode" class="form-control" required>
                            <option value=""><?= esc($t('placeholder_periode')) ?></option>
                            <?php foreach ($periodes as $periode): ?>
                                <option value="<?= $periode->periode_id ?>" <?= (count($periodes) == 1) ? 'selected' : '' ?>>
                                    <?= $periode->periode_name . ' ' . $periode->tahun_ajaran ?>
                                    <?php if (count($periodes) == 1): ?>
                                        <?= esc($t('msg_active_periode')) ?>
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (count($periodes) == 1): ?>
                            <small class="form-text text-muted"><?= esc($t('msg_only_active')) ?></small>
                        <?php endif; ?>
                    </div>

                    <!-- Input Nomor Surat -->
                    <div class="form-group">
                        <label for="nomor_surat"><?= esc($t('label_nomor_surat')) ?></label>
                        <input type="text" name="nomor_surat" id="nomor_surat"
                            class="form-control" placeholder="<?= esc($t('placeholder_nomor')) ?>" required>
                    </div>

                    <!-- File Upload -->
                    <div class="form-group">
                        <label for="spm"><?= esc($t('label_file')) ?></label>
                        <input type="file" name="spm" id="spm" class="form-control" accept=".pdf" required>
                    </div>

                    <button type="submit" class="btn btn-primary"><?= esc($t('btn_upload')) ?></button>
                </form>

                <!-- Tabel File SPM yang Sudah Diupload -->
                <?php if (!empty($dokumen_mitra)): ?>
                    <div class="mt-4">
                        <h5><?= esc($t('msg_uploaded_files')) ?></h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th><?= esc($t('th_periode')) ?></th>
                                        <th><?= esc($t('th_nomor_surat')) ?></th>
                                        <th><?= esc($t('th_file')) ?></th>
                                        <th><?= esc($t('th_action')) ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($dokumen_mitra as $dokumen): ?>
                                        <?php if ($dokumen->doc_type === 'spm'): ?>
                                            <tr>
                                                <td><?= $dokumen->periode_name . ' ' . $dokumen->tahun_ajaran ?></td>
                                                <td><?= esc($dokumen->nomor_surat ?? '-') ?></td>
                                                <td><?= esc(basename($dokumen->file_path)) ?></td>
                                                <td>
                                                    <a href="<?= site_url('dokumen_mitra/preview/' . $dokumen->dokumen_id) ?>"
                                                        target="_blank" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i> <?= esc($t('btn_preview')) ?>
                                                    </a>
                                                    <a href="<?= site_url('dokumen_mitra/download/' . $dokumen->dokumen_id) ?>"
                                                        class="btn btn-sm btn-success">
                                                        <i class="fas fa-download"></i> <?= esc($t('btn_download')) ?>
                                                    </a>
                                                    <form method="post" action="<?= site_url('dokumen_mitra/delete/' . $dokumen->dokumen_id) ?>"
                                                        style="display: inline;"
                                                        onsubmit="return confirm('<?= esc($t('msg_confirm_delete')) ?>')">
                                                        <?= csrf_field() ?>
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="fas fa-trash"></i> <?= esc($t('btn_delete')) ?>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="mt-4">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong><?= esc($t('msg_no_files_title')) ?></strong><br>
                            <?= esc($t('msg_no_files_desc')) ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
=======
<?= $this->extend('layouts/default') ?>
<?= $this->section('content') ?>

<section class="section">
    <div class="section-header">
        <h1>Upload SPM</h1>
        <a href="<?= site_url('dashboard') ?>" class="btn btn-dark ml-2"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>

    <div class="section-body">

        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <form action="<?= site_url('mitra/upload_spm/' . $mitra->user_id) ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="form-group">
                <label>Upload SPM <span class="text-danger">*</span></label>
                <span class="text-primary"><b>Note:</b> Max File Size: 5 MB</span> |
                <span>Jika file Anda melebihi Max Size, silakan kompres terlebih dahulu:
                    <a class="badge badge-primary mb-1" target="_blank" href="https://www.ilovepdf.com/compress_pdf">Compress PDF Online</a>
                </span>
                <input type="file" name="spm" class="form-control" accept=".pdf" required>
            </div>

            <?php if(!empty($mitra->spm ?? null)): ?>
                <p>File SPM saat ini: 
                    <a href="<?= base_url('writable/berkas/spm/' . $mitra->spm) ?>" target="_blank"><?= $mitra->spm ?></a>
                </p>
            <?php endif; ?>

            <div class="d-flex mt-2">
                <button type="submit" class="btn btn-success me-2">
                    <i class="fas fa-upload"></i> Upload
                </button>
                <a href="<?= site_url('dashboard') ?>" class="btn btn-dark">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

        </form>
    </div>
</section>

<?= $this->endSection() ?>
>>>>>>> 55c0835 (refactor: update code)

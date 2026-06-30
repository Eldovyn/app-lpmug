<?= $this->extend('layouts/default') ?>

<?php
helper(['cookie', 'url']);

$request = service('request');

// bahasa aktif
$allowed = ['id', 'en'];
$lang    = get_cookie('lang') ?: 'id';
if (! in_array($lang, $allowed, true)) {
    $lang = 'id';
}

$reqLang = $request->getGet('lang');
if ($reqLang && in_array($reqLang, $allowed, true)) {
    set_cookie('lang', $reqLang, 60 * 60 * 24 * 30);
    $lang = $reqLang;
}

// dictionary
$dict = [
    'id' => [
        'hibah' => 'Hibah',
        'dashboard' => 'Dashboard',
        'edit_hibah' => 'Edit Hibah',
        'detail' => 'Detail',
        'back' => 'Kembali',
        'position' => 'Posisi Dosen',
        'select_position' => 'Pilih Posisi',
        'ketua' => 'Ketua',
        'anggota' => 'Anggota',
        'deskripsi' => 'Deskripsi Hibah',
        'deskripsi_placeholder' => 'Jelaskan tujuan dan ruang lingkup hibah (opsional)',
        'deskripsi_hint' => 'Opsional: Jelaskan tujuan dan ruang lingkup hibah',
        'proposal_file' => 'File Proposal (PDF, Max 5MB)',
        'select_file' => 'Pilih file baru (opsional)',
        'proposal_hint' => 'Biarkan kosong jika tidak ingin mengubah file. Upload file proposal dalam format PDF dengan ukuran maksimal 5MB',
        'current_file' => 'File saat ini:',
        'cancel' => 'Batal',
        'submit' => 'Update Hibah',
        'judul' => 'Judul Hibah',
        'judul_placeholder' => 'Masukkan judul hibah',
    ],
    'en' => [
        'hibah' => 'Grant',
        'dashboard' => 'Dashboard',
        'edit_hibah' => 'Edit Grant',
        'detail' => 'Detail',
        'back' => 'Back',
        'position' => 'Lecturer Position',
        'select_position' => 'Select Position',
        'ketua' => 'Chair',
        'anggota' => 'Member',
        'deskripsi' => 'Grant Description',
        'deskripsi_placeholder' => 'Explain the purpose and scope of the grant (optional)',
        'deskripsi_hint' => 'Optional: Explain the purpose and scope of the grant',
        'proposal_file' => 'Proposal File (PDF, Max 5MB)',
        'select_file' => 'Select new file (optional)',
        'proposal_hint' => 'Leave empty if you do not want to change the file. Upload proposal file in PDF format with maximum size of 5MB',
        'current_file' => 'Current file:',
        'cancel' => 'Cancel',
        'submit' => 'Update Grant',
        'judul' => 'Grant Title',
        'judul_placeholder' => 'Enter grant title',
    ],
];

$t = $dict[$lang] ?? $dict['id'];
?>

<?= $this->section('title') ?>
<title><?= esc($title_tab); ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="section">
    <div class="section-header">
        <a href="<?= site_url('hibah/detail/' . $hibah['id']); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
        <h1><?= esc($title); ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>"><?= esc($t['dashboard']); ?></a></div>
            <div class="breadcrumb-item"><a href="<?= site_url('hibah/myHibah'); ?>"><?= esc($t['hibah']); ?></a></div>
            <div class="breadcrumb-item"><a href="<?= site_url('hibah/detail/' . $hibah['id']); ?>"><?= esc($t['detail']); ?></a></div>
            <div class="breadcrumb-item"><?= esc($title); ?></div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= site_url('hibah/do-edit/' . $hibah['id']); ?>" method="POST" enctype="multipart/form-data" autocomplete="off">
                            <?= csrf_field(); ?>

                            <?php if (session('error')): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <?= session('error'); ?>
                                </div>
                            <?php endif; ?>

                            <div class="form-group">
                                <label for="judul"><?= esc($t['judul']); ?> <span class="text-danger">*</span></label>
                                <input type="text" name="judul" id="judul" class="form-control <?= (isset(session('validation_errors')['judul'])) ? 'is-invalid' : ''; ?>" value="<?= old('judul', $hibah['judul']); ?>" placeholder="<?= esc($t['judul_placeholder']); ?>" required>
                                <div class="invalid-feedback">
                                    <?= session('validation_errors')['judul'] ?? ''; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="posisi_dosen"><?= esc($t['position']); ?> <span class="text-danger">*</span></label>
                                <select name="posisi_dosen" id="posisi_dosen" class="form-control <?= (isset(session('validation_errors')['posisi_dosen'])) ? 'is-invalid' : ''; ?>" required>
                                    <option value=""><?= esc($t['select_position']); ?></option>
                                    <option value="ketua" <?= old('posisi_dosen', $hibah['posisi_dosen']) == 'ketua' ? 'selected' : ''; ?>><?= esc($t['ketua']); ?></option>
                                    <option value="anggota" <?= old('posisi_dosen', $hibah['posisi_dosen']) == 'anggota' ? 'selected' : ''; ?>><?= esc($t['anggota']); ?></option>
                                </select>
                                <div class="invalid-feedback">
                                    <?= session('validation_errors')['posisi_dosen'] ?? ''; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="deskripsi"><?= esc($t['deskripsi']); ?></label>
                                <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4" placeholder="<?= esc($t['deskripsi_placeholder']); ?>"><?= old('deskripsi', $hibah['deskripsi']); ?></textarea>
                                <small class="form-text text-muted"><?= esc($t['deskripsi_hint']); ?></small>
                            </div>

                            <div class="form-group">
                                <label for="proposal_file"><?= esc($t['proposal_file']); ?></label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input <?= (isset(session('validation_errors')['proposal_file'])) ? 'is-invalid' : ''; ?>" id="proposal_file" name="proposal_file" accept=".pdf">
                                    <label class="custom-file-label" for="proposal_file"><?= esc($t['select_file']); ?></label>
                                    <div class="invalid-feedback">
                                        <?= session('validation_errors')['proposal_file'] ?? ''; ?>
                                    </div>
                                </div>
                                <small class="form-text text-muted">
                                    <?= esc($t['proposal_hint']); ?>
                                    <?php if (!empty($hibah['proposal_file'])): ?>
                                        <br><strong><?= esc($t['current_file']); ?></strong> <?= $hibah['proposal_file']; ?>
                                    <?php endif; ?>
                                </small>
                            </div>

                            <div class="text-right">
                                <a href="<?= site_url('hibah/detail/' . $hibah['id']); ?>" class="btn btn-secondary"><?= esc($t['cancel']); ?></a>
                                <button type="submit" class="btn btn-primary" id="submit_btn">
                                    <i class="fas fa-save"></i> <?= esc($t['submit']); ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // Show selected file name
    document.getElementById('proposal_file').addEventListener('change', function(e) {
        const fileName = e.target.files[0].name;
        document.querySelector('.custom-file-label').textContent = fileName;
    });
</script>

<?= $this->endSection(); ?>

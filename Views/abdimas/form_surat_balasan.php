<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<title>Form Surat Balasan</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
<div class="section-header">
    <a href="<?= site_url('abdimas'); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
    <h1>Form Surat Balasan</h1>
</div>

<div class="section-body">
    <div class="row">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <form action="<?= site_url('abdimas/generate-surat-balasan-pdf-from-form'); ?>" method="POST" autocomplete="off" target="_blank">
                        <?= csrf_field(); ?>
                        <div class="form-group">
                            <label for="judul_kegiatan">Judul Kegiatan <span class="text-danger">*</span></label>
                            <input type="text" name="judul_kegiatan" id="judul_kegiatan" class="form-control" required value="<?= old('judul_kegiatan'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="nama_dosen">Nama Dosen <span class="text-danger">*</span></label>
                            <input type="text" name="nama_dosen" id="nama_dosen" class="form-control" required value="<?= old('nama_dosen'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="bidang_ilmu">Bidang Ilmu <span class="text-danger">*</span></label>
                            <select name="bidang_ilmu" id="bidang_ilmu" class="form-control" required>
                                <option value=""><?= old('bidang_ilmu') ? '' : '—Pilih Bidang Ilmu—'; ?></option>
                                <?php $current_bidang = old('bidang_ilmu', ''); ?>
                                <option value="ipa-matematika" <?= $current_bidang == 'ipa-matematika' ? 'selected' : '' ?>>1. Ilmu Pengetahuan Alam (IPA) & Matematika</option>
                                <option value="teknik-rekayasa" <?= $current_bidang == 'teknik-rekayasa' ? 'selected' : '' ?>>2. Ilmu Teknik & Rekayasa</option>
                                <option value="kesehatan-kedokteran" <?= $current_bidang == 'kesehatan-kedokteran' ? 'selected' : '' ?>>3. Ilmu Kesehatan & Kedokteran</option>
                                <option value="sosial-humaniora-seni" <?= $current_bidang == 'sosial-humaniora-seni' ? 'selected' : '' ?>>4. Ilmu Sosial, Humaniora, & Seni</option>
                                <option value="pertanian-tanaman" <?= $current_bidang == 'pertanian-tanaman' ? 'selected' : '' ?>>5. Ilmu Pertanian & Tanaman</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="ketua">Ketua <span class="text-danger">*</span></label>
                            <input type="text" name="ketua" id="ketua" class="form-control" required value="<?= old('ketua'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="ketua_nidn">NIDN Ketua <span class="text-danger">*</span></label>
                            <input type="text" name="ketua_nidn" id="ketua_nidn" class="form-control" required value="<?= old('ketua_nidn'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="lokasi_mitra">Lokasi Mitra <span class="text-danger">*</span></label>
                            <input type="text" name="lokasi_mitra" id="lokasi_mitra" class="form-control" required value="<?= old('lokasi_mitra'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="tanggal_kegiatan">Tanggal Kegiatan <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_kegiatan" id="tanggal_kegiatan" class="form-control" required value="<?= old('tanggal_kegiatan'); ?>">
                        </div>
                        <div class="form-group">
                            <label>Anggota (opsional)</label>
                            <div id="anggota-container">
                                <div class="input-group mb-2">
                                    <input type="text" name="anggota[]" class="form-control" placeholder="Nama Anggota 1">
                                    <select name="bidang_ilmu_anggota[]" class="form-control ml-2" required>
                                        <option value="" disabled selected>Pilih Bidang Ilmu</option>
                                        <optgroup label="Bidang Ilmu Utama">
                                            <option value="ipa-matematika">1. Ilmu Pengetahuan Alam (IPA) & Matematika</option>
                                            <option value="teknik-rekayasa">2. Ilmu Teknik & Rekayasa</option>
                                            <option value="kesehatan-kedokteran">3. Ilmu Kesehatan & Kedokteran</option>
                                            <option value="sosial-humaniora-seni">4. Ilmu Sosial, Humaniora, & Seni</option>
                                            <option value="pertanian-tanaman">5. Ilmu Pertanian & Tanaman</option>
                                        </optgroup>
                                    </select>
                                    <div class="input-group-append">
                                        <button class="btn btn-danger remove-anggota" type="button">&times;</button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="add-anggota" class="btn btn-secondary btn-sm">Tambah Anggota</button>
                        </div>
                        <div class="text-right">
                            <button type="reset" class="btn btn-danger">Reset</button>
                            <button type="submit" class="btn btn-primary">Generate PDF</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</section>

<script>
document.getElementById('add-anggota').addEventListener('click', function() {
    const container = document.getElementById('anggota-container');
    const count = container.querySelectorAll('input[name="anggota[]"]').length + 1;
    const div = document.createElement('div');
    div.classList.add('input-group', 'mb-2');
    div.innerHTML = `
        <input type="text" name="anggota[]" class="form-control" placeholder="Nama Anggota ${count}">
        <select name="bidang_ilmu_anggota[]" class="form-control ml-2" required>
            <option value="" disabled selected>Pilih Bidang Ilmu</option>
            <option value="Teknologi Informasi">Teknologi Informasi</option>
            <option value="Manajemen">Manajemen</option>
            <option value="Ekonomi">Ekonomi</option>
            <!-- Add more options as needed -->
        </select>
        <div class="input-group-append">
            <button class="btn btn-danger remove-anggota" type="button">&times;</button>
        </div>
    `;
    container.appendChild(div);
});

document.getElementById('anggota-container').addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('remove-anggota')) {
        e.target.closest('.input-group').remove();
    }
});
</script>

<?= $this->endSection() ?>

<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<title><?= $title ?? 'Preview Sertifikat' ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <h1><?= $title ?? 'Preview Sertifikat' ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="<?= site_url('dashboard'); ?>">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="<?= site_url('sertifikat'); ?>">Sertifikat</a></div>
            <div class="breadcrumb-item">Preview</div>
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4 class="text-primary"><?= $title ?? 'Preview Sertifikat' ?></h4>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h5>Data Sertifikat</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th width="200">Judul Kegiatan</th>
                                <td><?= esc($laporan->judul_kegiatan ?? '-') ?></td>
                            </tr>
                            <tr>
                                <th>Periode</th>
                                <td><?= esc($periode->nama_periode ?? '-') ?> <?= esc($periode->tahun ?? '') ?></td>
                            </tr>
                            <tr>
                                <th>Tanggal Kegiatan</th>
                                <td><?= esc($laporan->tanggal_kegiatab ?? '-') ?></td>
                            </tr>
                            <tr>
                                <th>Mitra</th>
                                <td><?= esc($mitra->user_name ?? '-') ?></td>
                            </tr>
                            <tr>
                                <th>Alamat Mitra</th>
                                <td><?= esc($mitra->alamat ?? '-') ?></td>
                            </tr>
                            <tr>
                                <th>Ketua LPM</th>
                                <td><?= esc($ketua_lpm['nama'] ?? '-') ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="mb-4">
                    <h5>Daftar Anggota</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>NIDN/NPM</th>
                                    <th>Peran</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($members)): ?>
                                    <?php $no = 1; ?>
                                    <?php foreach ($members as $member): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= esc($member['member']->user_name ?? ($member['member']->mahasiswa_name ?? '-')) ?></td>
                                            <td><?= esc($member['member']->nidn ?? ($member['member']->npm ?? '-')) ?></td>
                                            <td>
                                                <?php if ($member['role'] === 'ketua'): ?>
                                                    <span class="badge badge-primary">Ketua Tim</span>
                                                <?php else: ?>
                                                    <span class="badge badge-info">Anggota</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada anggota</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mb-4">
                    <a href="<?= site_url('sertifikat/generate-all/' . $laporan->laporan_id) ?>" class="btn btn-primary">
                        <i class="fas fa-file-archive"></i> Download Semua (ZIP)
                    </a>
                    <a href="<?= site_url('sertifikat') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<title><?= esc($title_tab ?? 'Sertifikat — LPM UG') ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <h1><?= esc($title ?? 'Sertifikat Pengabdian Masyarakat') ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="<?= site_url('dashboard') ?>">Dashboard</a></div>
            <div class="breadcrumb-item">Sertifikat</div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">&times;</button>
                <?= session()->getFlashdata('success') ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">&times;</button>
                <?= session()->getFlashdata('error') ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4 class="text-primary">
                    <i class="fas fa-certificate mr-2"></i>
                    Daftar Laporan Pengabdian Masyarakat Anda
                </h4>
            </div>
            <div class="card-body">

                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-1"></i>
                    Sertifikat dihasilkan secara otomatis berdasarkan data akun dan laporan Anda.
                    Klik tombol <strong>Generate Sertifikat</strong> pada laporan yang ingin di-download.
                </div>

                <?php if (!empty($laporan)): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="tblSertifikat">
                            <thead class="thead-light">
                                <tr>
                                    <th width="40">No</th>
                                    <th>Judul Kegiatan</th>
                                    <th width="160">Mitra</th>
                                    <th width="150">Periode</th>
                                    <th width="100">Peran</th>
                                    <th width="80">Status</th>
                                    <th width="140" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($laporan as $lap): ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td>
                                            <?php if (!empty($lap['judul_kegiatan'])): ?>
                                                <?= esc($lap['judul_kegiatan']) ?>
                                            <?php else: ?>
                                                <em class="text-muted">Judul belum diisi</em>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= esc($lap['mitra_name'] ?? '-') ?></td>
                                        <td>
                                            <?= esc($lap['periode_name'] ?? '-') ?>
                                            <?php if (!empty($lap['tahun_ajaran'])): ?>
                                                <br><small class="text-muted"><?= esc($lap['tahun_ajaran']) ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if (($lap['peran'] ?? '') === 'Ketua Tim'): ?>
                                                <span class="badge badge-primary">Ketua Tim</span>
                                            <?php else: ?>
                                                <span class="badge badge-info">Anggota</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                                $verif = (int)($lap['verifikasi'] ?? 0);
                                                if ($verif >= 3):
                                            ?>
                                                <span class="badge badge-success">Disetujui</span>
                                            <?php elseif ($verif >= 1): ?>
                                                <span class="badge badge-warning">Proses</span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary">Pengajuan</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if (!empty($lap['judul_kegiatan'])): ?>
                                                <a href="<?= site_url('sertifikat/generate-pdf/' . $lap['laporan_id']) ?>"
                                                   class="btn btn-sm btn-success"
                                                   title="Generate &amp; Download Sertifikat"
                                                   target="_blank">
                                                    <i class="fas fa-file-pdf"></i> Sertifikat
                                                </a>
                                            <?php else: ?>
                                                <button class="btn btn-sm btn-secondary" disabled title="Judul kegiatan belum tersedia">
                                                    <i class="fas fa-file-pdf"></i> Sertifikat
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-folder-open"></i>
                        </div>
                        <h2>Belum Ada Laporan</h2>
                        <p class="lead">
                            Anda belum memiliki laporan pengabdian masyarakat.<br>
                            Sertifikat akan tersedia setelah laporan terdaftar.
                        </p>
                        <a href="<?= site_url('abdimas/new') ?>" class="btn btn-primary mt-4">
                            <i class="fas fa-plus"></i> Buat Laporan Baru
                        </a>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>

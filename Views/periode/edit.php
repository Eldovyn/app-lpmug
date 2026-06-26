<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <a href="<?= site_url('periode/'); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
        <h1><?= $title; ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>">Dashboard</a></div>
            <div class="breadcrumb-item active"><a href="<?= site_url('periode'); ?>">periode</a></div>
            <div class="breadcrumb-item"><?= $title; ?></div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= site_url('periode/' . $periode->periode_id); ?>" method="POST" autocomplete="off">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="_method" value="PATCH">
                            <div class="form-group">
                                <label>Periode<small class="text-danger">*</small></label>
                                <select name="periode_name" class="form-control select2" required>
                                    <option selected disabled>&mdash;Silahkan Pilih&mdash;</option>
                                    <?php if ($periode->periode_name == 'PTA'): ?>
                                        <option value="<?= $periode->periode_name; ?>" selected><?= $periode->periode_name; ?></option>
                                        <option value="ATA">ATA</option>
                                    <?php elseif ($periode->periode_name == 'ATA'): ?>
                                        <option value="<?= $periode->periode_name; ?>" selected><?= $periode->periode_name; ?></option>
                                        <option value="PTA">PTA</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Tahun Ajaran<small class="text-danger">*</small> (contoh: 2023/2024)</label>
                                <input type="text" name="tahun_ajaran" value="<?= $periode->tahun_ajaran; ?>" class="form-control" required autofocus>
                            </div>
                            <div class="form-group">
                                <label>Info<small class="text-danger">*</small></label>
                                <select name="info" class="form-control select2" required>
                                    <option selected disabled>&mdash;Silahkan Pilih&mdash;</option>
                                    <?php if ($periode->info == 1): ?>
                                        <option value="<?= $periode->info; ?>" selected>Pendaftaran dibuka</option>
                                        <option value="0">Pendaftaran ditutup</option>
                                        <option value="2">Abdimas sedang berlangsung</option>
                                        <option value="3">Pengumpulan Laporan</option>
                                        <option value="4">Selesai</option>
                                    <?php elseif ($periode->info == 2): ?>
                                        <option value="<?= $periode->info; ?>" selected>Abdimas sedang berlangsung</option>
                                        <option value="0">Pendaftaran ditutup</option>
                                        <option value="1">Pendaftaran dibuka</option>
                                        <option value="3">Pengumpulan Laporan</option>
                                        <option value="4">Selesai</option>
                                    <?php elseif ($periode->info == 3): ?>
                                        <option value="<?= $periode->info; ?>" selected>Pengumpulan Laporan</option>
                                        <option value="0">Pendaftaran ditutup</option>
                                        <option value="1">Pendaftaran dibuka</option>
                                        <option value="2">Abdimas sedang berlangsung</option>
                                        <option value="4">Selesai</option>
                                    <?php elseif ($periode->info == 4): ?>
                                        <option value="<?= $periode->info; ?>" selected>Selesai</option>
                                        <option value="0">Pendaftaran ditutup</option>
                                        <option value="1">Pendaftaran dibuka</option>
                                        <option value="2">Abdimas sedang berlangsung</option>
                                        <option value="3">Pengumpulan Laporan</option>
                                    <?php else: ?>
                                        <option value="<?= $periode->info; ?>" selected>Pendaftaran ditutup</option>
                                        <option value="1">Pendaftaran dibuka</option>
                                        <option value="2">Abdimas sedang berlangsung</option>
                                        <option value="3">Pengumpulan Laporan</option>
                                        <option value="4">Selesai</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Status<small class="text-danger">*</small></label>
                                <select name="status" class="form-control select2" required>
                                    <option selected disabled>&mdash;Silahkan Pilih&mdash;</option>
                                    <?php if ($periode->status == 1): ?>
                                        <option value="<?= $periode->status; ?>" selected>Aktif</option>
                                        <option value="0">Tidak Aktif</option>
                                    <?php elseif ($periode->status == 0): ?>
                                        <option value="<?= $periode->status; ?>" selected>Tidak Aktif</option>
                                        <option value="1">Aktif</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="text-right">
                                <a href="<?= site_url('periode'); ?>" class="btn btn-dark">kembali</a>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--<div class="col-12 col-md-6 col-lg-5">-->
            <!--    <div class="card">-->
            <!--        <div class="card-header">-->
            <!--            <h4>Keterangan</h4>-->
            <!--        </div>-->
            <!--        <div class="card-body">-->
            <!--            <div class="section-title mt-0">Nama Lengkap</div>-->
            <!--            <div>-->

            <!--            </div>-->
            <!--            <div class="section-title">Select 2</div>-->

            <!--            <div class="section-title">jQuery Selectric</div>-->

            <!--            <div class="section-title">Select Group Button</div>-->

            <!--        </div>-->
            <!--    </div>-->
            <!--</div>-->
        </div>
    </div>
</section>
<?= $this->endSection() ?>
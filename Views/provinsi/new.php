<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
<div class="section-header">
    <a href="<?= site_url('provinsi'); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
    <h1><?= $title; ?></h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>">Dashboard</a></div>
        <div class="breadcrumb-item active"><a href="<?= site_url('provinsi'); ?>">Provinsi</a></div>
        <div class="breadcrumb-item"><?= $title; ?></div>
    </div>
</div>

<div class="section-body">
    <div class="row">
        <div class="col-12 col-md-6 col-lg-7">
            <div class="card">
                <div class="card-body">
                    <form action="<?= site_url('provinsi'); ?>" method="POST" autocomplete="off">
                        <?= csrf_field(); ?>
                        <div class="form-group">
                            <label>Nama Provinsi<small class="text-danger">*</small></label>
                            <input type="text" name="provinsi_name" value="<?= old('provinsi_name'); ?>" class="form-control" autofocus>
                        </div>
                        <div class="text-right">
                            <button type="reset" class="btn btn-danger">Reset</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h4>Keterangan</h4>
                </div>
                <div class="card-body">
                    <div class="section-title mt-0">Nama Lengkap</div>
                    <div>

                    </div>
                    <div class="section-title">Select 2</div>
                    
                    <div class="section-title">jQuery Selectric</div>
                    
                    <div class="section-title">Select Group Button</div>
                
                </div>
            </div>
        </div>
    </div>
</div>
</section>
<?= $this->endSection() ?>
<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
<div class="section-header">
    <a href="<?= site_url('abdimas'); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
    <h1><?= $title; ?></h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>">Dashboard</a></div>
        <div class="breadcrumb-item active"><a href="<?= site_url('abdimas'); ?>">Upload Proposal</a></div>
        <div class="breadcrumb-item"><?= $title; ?></div>
    </div>
</div>

<div class="section-body">
    <div class="row">
        <div class="col-12 col-md-6 col-lg-7">
            <div class="card">
                <div class="card-body">
                    <form action="<?= site_url('abdimas/proposal/update/'.$abdimas->laporan_id); ?>" method="POST" enctype="multipart/form-data" autocomplete="off">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="_method" value="PATCH">
                        <div class="form-group">
                            <label>Upload proposal<small class="text-danger">*</small></label>
                            <input type="file" name="proposal" id="proposal" class="form-control <?= (session('validation')) ? 'is-invalid' : ''; ?>" autofocus>
                            <div class="invalid-feedback">
                                <?php if(session('validation') && session('validation')->hasError('proposal')):?>
                                    <?= session('validation')->getError('proposal'); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="text-right">
                            <a href="<?= site_url('abdimas'); ?>" class="btn btn-dark">kembali</a>
                            <!-- <button type="reset" class="btn btn-danger">Reset</button> -->
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>   
        <div class="col-12 col-md-6 col-lg-5">
            <div class="card">
                <div class="card-body">
                    <embed 
                        type="application/pdf" 
                        src="<?= base_url('/berkas/proposal/'. $abdimas->proposal); ?>" 
                        width="200px">
                    </embed>
                </div>    
            </div>
        </div>
    </div>
</div>
</section>
<?= $this->endSection() ?>
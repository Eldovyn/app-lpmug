<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>
<?= $this->section('javascript') ?>
<script>
    function togglePassword(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const eyeIcon = document.getElementById(iconId);
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
<div class="section-header">
    <a href="<?= site_url('dashboard'); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
    <h1><?= $title; ?></h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>">Dashboard</a></div>
        <div class="breadcrumb-item"><?= $title; ?></div>
    </div>
</div>

<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible show fade">
        <div class="alert-body">
            <button class="close" data-dismiss="alert">x</button>
            <b>Congratulation!</b>
            <?= session()->getFlashdata('success'); ?>
        </div>
    </div>
<?php endif; ?>

<?php if(session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible show fade">
        <div class="alert-body">
            <button class="close" data-dismiss="alert">x</button>
            <b>Warning Error!</b>
            <?= session()->getFlashdata('error'); ?>
        </div>
    </div>
<?php endif; ?>

<div class="section-body">
    <div class="row">
        <div class="col-12 col-md-6 col-lg-7">
            <div class="card">
                <div class="card-body">
                    <form action="<?= site_url('ubah_password/'.$ubah_password->user_id); ?>" method="POST" autocomplete="off">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="_method" value="PUT">
                        <div class="form-group">
                            <label>Password baru<small class="text-danger">*</small></label>
                            <div class="input-group">
    <div class="input-group-prepend">
        <div class="input-group-text">
            <i class="fas fa-lock"></i>
        </div>
    </div>
    <input type="password" id="input_password" name="password" class="form-control <?= (session('validation')) ? 'is-invalid' : ''; ?>" autocomplete="new-password" autofocus>
    <div class="input-group-append">
        <span class="input-group-text" style="cursor: pointer;" onclick="togglePassword('input_password', 'eye_icon')">
            <i class="fas fa-eye" id="eye_icon"></i>
        </span>
    </div>
</div>
                            <div class="invalid-feedback">
                                <?php if(session('validation') && session('validation')->hasError('password')):?>
                                    <?= session('validation')->getError('password'); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Konfirmasi Password<small class="text-danger">*</small></label>
                            <div class="input-group">
    <div class="input-group-prepend">
        <div class="input-group-text">
            <i class="fas fa-lock"></i>
        </div>
    </div>
    <input type="password" id="input_password_konfirmasi" name="password_konfirmasi" class="form-control <?= (session('validation')) ? 'is-invalid' : ''; ?>" autocomplete="new-password">
    <div class="input-group-append">
        <span class="input-group-text" style="cursor: pointer;" onclick="togglePassword('input_password_konfirmasi', 'eye_icon_konfirm')">
            <i class="fas fa-eye" id="eye_icon_konfirm"></i>
        </span>
    </div>
</div>
                            <div class="invalid-feedback">
                                <?php if(session('validation') && session('validation')->hasError('password_konfirmasi')):?>
                                    <?= session('validation')->getError('password_konfirmasi'); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="text-right">
                            <a href="<?= site_url('dashboard'); ?>" class="btn btn-dark">kembali</a>
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
                    <div class="section-title mt-0">Nama Lengkpa</div>
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
<?= $this->section('javascript') ?>
<script>
    function togglePassword(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const eyeIcon = document.getElementById(iconId);
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>
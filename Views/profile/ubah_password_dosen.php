<?= $this->extend('layouts/default') ?>

<?php
// ===== i18n single-file (ID/EN) =====
$request = service('request'); // :contentReference[oaicite:2]{index=2}

// Prioritas: $lang dari controller -> cookie -> default id
$lang = $lang ?? $request->getCookie('lang') ?? 'id';
$lang = in_array($lang, ['id', 'en'], true) ? $lang : 'id';

$TR = [
    'id' => [
        'dashboard' => 'Dashboard',
        'success_title' => 'Selamat!',
        'error_title' => 'Peringatan!',

        'new_password' => 'Password baru',
        'confirm_password' => 'Konfirmasi Password',

        'btn_back' => 'kembali',
        'btn_save' => 'Simpan',
    ],
    'en' => [
        'dashboard' => 'Dashboard',
        'success_title' => 'Congratulations!',
        'error_title' => 'Warning!',

        'new_password' => 'New password',
        'confirm_password' => 'Confirm password',

        'btn_back' => 'Back',
        'btn_save' => 'Save',
    ],
];

$t = static fn(string $key): string => $TR[$lang][$key] ?? $TR['id'][$key] ?? $key;
?>

<?= $this->section('title') ?>
<title><?= esc($title_tab); ?></title>
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
        <h1><?= esc($title); ?></h1>

        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item">
                <a href="<?= site_url('dashboard'); ?>"><?= esc($t('dashboard')) ?></a>
            </div>
            <div class="breadcrumb-item"><?= esc($title); ?></div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b><?= esc($t('success_title')) ?></b>
                <?= esc(session()->getFlashdata('success')); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b><?= esc($t('error_title')) ?></b>
                <?= esc(session()->getFlashdata('error')); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= site_url('ubah_password_dosen/' . $ubah_password_dosen->user_id); ?>" method="POST" autocomplete="off">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="_method" value="PUT">

                            <div class="form-group">
                                <label><?= esc($t('new_password')) ?><small class="text-danger">*</small></label>
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
                                    <?php if (session('validation') && session('validation')->hasError('password')): ?>
                                        <?= esc(session('validation')->getError('password')); ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?= esc($t('confirm_password')) ?><small class="text-danger">*</small></label>
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
                                    <?php if (session('validation') && session('validation')->hasError('password_konfirmasi')): ?>
                                        <?= esc(session('validation')->getError('password_konfirmasi')); ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="text-right">
                                <a href="<?= site_url('dashboard'); ?>" class="btn btn-dark"><?= esc($t('btn_back')) ?></a>
                                <button type="submit" class="btn btn-primary"><?= esc($t('btn_save')) ?></button>
                            </div>
                        </form>
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
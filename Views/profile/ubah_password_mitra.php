<?php
// === I18N inline (1 file view, tanpa folder Language, tanpa ubah UI/UX) ===
// ambil bahasa dari cookie "lang" (default id)
$lang = strtolower(trim((string) (service('request')->getCookie('lang') ?? 'id')));
$lang = ($lang === 'en') ? 'en' : 'id';

$I18N = [
    'id' => [
        'dashboard'      => 'Dashboard',
        'successTitle'   => 'Congratulation!',
        'errorTitle'     => 'Warning Error!',
        'newPassword'    => 'Password baru',
        'confirmPassword' => 'Konfirmasi Password',
        'back'           => 'kembali',
        'save'           => 'Simpan',
    ],
    'en' => [
        'dashboard'      => 'Dashboard',
        'successTitle'   => 'Success!',
        'errorTitle'     => 'Error!',
        'newPassword'    => 'New password',
        'confirmPassword' => 'Confirm password',
        'back'           => 'back',
        'save'           => 'Save',
    ],
];

$t = function (string $key, ...$args) use ($I18N, $lang) {
    $text = $I18N[$lang][$key] ?? $I18N['id'][$key] ?? $key;
    return $args ? vsprintf($text, $args) : $text;
};
?>

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
            <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>"><?= $t('dashboard'); ?></a></div>
            <div class="breadcrumb-item"><?= $title; ?></div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b><?= $t('successTitle'); ?></b>
                <?= session()->getFlashdata('success'); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b><?= $t('errorTitle'); ?></b>
                <?= session()->getFlashdata('error'); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= site_url('ubah_password_mitra/' . $ubah_password_mitra->user_id); ?>" method="POST" autocomplete="off">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="_method" value="PUT">

                            <div class="form-group">
                                <label><?= $t('newPassword'); ?><small class="text-danger">*</small></label>
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
                                        <?= session('validation')->getError('password'); ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?= $t('confirmPassword'); ?><small class="text-danger">*</small></label>
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
                                        <?= session('validation')->getError('password_konfirmasi'); ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="text-right">
                                <a href="<?= site_url('dashboard'); ?>" class="btn btn-dark"><?= $t('back'); ?></a>
                                <button type="submit" class="btn btn-primary"><?= $t('save'); ?></button>
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
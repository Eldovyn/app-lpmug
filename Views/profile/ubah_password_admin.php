<?= $this->extend('layouts/default') ?>

<?php
helper(['cookie', 'url']);

$request = service('request');

$allowed = ['id', 'en'];
$lang = get_cookie('lang') ?: 'id';
if (! in_array($lang, $allowed, true)) {
    $lang = 'id';
}

$reqLang = $request->getGet('lang');
if ($reqLang && in_array($reqLang, $allowed, true)) {
    set_cookie('lang', $reqLang, 60 * 60 * 24 * 30);
    $lang = $reqLang;
}

$TR = [
    'id' => [
        'dashboard'        => 'Dashboard',
        'congrats'         => 'Congratulation!',
        'warning_error'    => 'Warning Error!',
        'new_password'     => 'Password baru',
        'confirm_password' => 'Konfirmasi Password',
        'back'             => 'kembali',
        'save'             => 'Simpan',
    ],
    'en' => [
        'dashboard'        => 'Dashboard',
        'congrats'         => 'Congratulations!',
        'warning_error'    => 'Warning!',
        'new_password'     => 'New password',
        'confirm_password' => 'Confirm password',
        'back'             => 'Back',
        'save'             => 'Save',
    ],
];

if (! function_exists('t')) {
    function t(string $key): string
    {
        global $dict, $lang;

        return $dict[$lang][$key]
            ?? $dict['id'][$key]
            ?? $key;
    }
}

if (! function_exists('lang_url')) {
    function lang_url(string $locale): string
    {
        $request = service('request');
        $base = current_url();
        $q = $request->getGet();
        $q['lang'] = $locale;

        return $base . '?' . http_build_query($q);
    }
}

$t = static function (string $key) use ($TR, $lang): string {
    return $TR[$lang][$key] ?? $TR['id'][$key] ?? $key;
};
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
            <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>"><?= esc($t('dashboard')) ?></a></div>
            <div class="breadcrumb-item"><?= esc($title); ?></div>
        </div>
    </div>


    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b><?= esc($t('congrats')) ?></b>
                <?= esc(session()->getFlashdata('success')); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b><?= esc($t('warning_error')) ?></b>
                <?= esc(session()->getFlashdata('error')); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= site_url('ubah_password_admin/' . $ubah_password_admin->user_id); ?>" method="POST" autocomplete="off">
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
                                <a href="<?= site_url('dashboard'); ?>" class="btn btn-dark"><?= esc($t('back')) ?></a>
                                <button type="submit" class="btn btn-primary"><?= esc($t('save')) ?></button>
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
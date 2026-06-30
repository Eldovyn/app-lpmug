<?= $this->extend('layouts/default') ?>

<?php
helper(['cookie', 'url']);

$request = service('request');

// bahasa aktif - check query param first, then cookie, default to id
$allowed = ['id', 'en'];
$lang = $request->getGet('lang');
if (! $lang) {
    $lang = get_cookie('lang') ?: 'id';
}
if (! in_array($lang, $allowed, true)) {
    $lang = 'id';
}

// Set cookie if lang from query param
if ($request->getGet('lang')) {
    set_cookie('lang', $lang, 60 * 60 * 24 * 30);
}

// dictionary
$dict = [
    'id' => [
        'dashboard' => 'Dashboard',
        'data_mitra' => 'Data mitra',
        'edit_mitra' => 'Edit Mitra',
        'back' => 'Kembali',
        'label_nama_lengkap' => 'Nama lengkap',
        'label_username' => 'Username',
        'label_email' => 'Email',
        'label_kontak' => 'No.Telp/Whatsapp',
        'label_wilayah' => 'Wilayah',
        'select_option' => '—Silahkan Pilih—',
        'label_alamat' => 'Alamat Lengkap',
        'label_kebutuhan' => 'Kebutuan Mitra kepada Abdimas',
        'label_password' => 'Kata Sandi',
        'password_hint' => 'Kosongkan jika tidak ingin mengubah kata sandi',
        'label_password_confirm' => 'Konfirmasi Kata Sandi',
        'save' => 'Simpan',
    ],
    'en' => [
        'dashboard' => 'Dashboard',
        'data_mitra' => 'Partner Data',
        'edit_mitra' => 'Edit Partner',
        'back' => 'Back',
        'label_nama_lengkap' => 'Full Name',
        'label_username' => 'Username',
        'label_email' => 'Email',
        'label_kontak' => 'Phone/Whatsapp',
        'label_wilayah' => 'Region',
        'select_option' => '—Please Select—',
        'label_alamat' => 'Complete Address',
        'label_kebutuhan' => 'Partner Needs for Abdimas',
        'label_password' => 'Password',
        'password_hint' => 'Leave blank if you don\'t want to change the password',
        'label_password_confirm' => 'Confirm Password',
        'save' => 'Save',
    ],
];

$t = $dict[$lang] ?? $dict['id'];
?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <a href="<?= site_url('mitra'); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
        <h1><?= $title; ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>"><?= esc($t['dashboard']); ?></a></div>
            <div class="breadcrumb-item active"><a href="<?= site_url('mitra'); ?>"><?= esc($t['data_mitra']); ?></a></div>
            <div class="breadcrumb-item"><?= $title; ?></div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= site_url('mitra/' . $mitra->user_id); ?>" method="POST" autocomplete="off">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="_method" value="PATCH">
                            <div class="form-group">
                                <label><?= esc($t['label_nama_lengkap']); ?><small class="text-danger">*</small></label>
                                <input type="text" name="user_name" id="user_name" value="<?= $mitra->user_name; ?>" class="form-control <?= (session('validation')) ? 'is-invalid' : ''; ?>" autofocus>
                                <div class="invalid-feedback">
                                    <?php if (session('validation') && session('validation')->hasError('user_name')): ?>
                                        <?= session('validation')->getError('user_name'); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label><?= esc($t['label_username']); ?><small class="text-danger">*</small></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="nidn" value="<?= $mitra->nidn; ?>" class="form-control" disabled autofocus>
                                </div>
                            </div>
                            <div class="form-group">
                                <label><?= esc($t['label_email']); ?><small class="text-danger">*</small></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-phone"></i>
                                        </div>
                                    </div>
                                    <input type="email" name="email" value="<?= $mitra->email; ?>" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label><?= esc($t['label_kontak']); ?><small class="text-danger">*</small></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-phone"></i>
                                        </div>
                                    </div>
                                    <input type="number" name="kontak" value="<?= $mitra->kontak; ?>" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label><?= esc($t['label_wilayah']); ?><small class="text-danger">*</small></label>
                                <select name="kota_id" class="form-control select2">
                                    <option selected disabled><?= esc($t['select_option']); ?></option>
                                    <?php foreach ($kota as $jrs => $v_kota): ?>
                                        <option value="<?= $v_kota->kota_id; ?>" <?= $mitra->kota_id == $v_kota->kota_id ? 'selected' : null; ?>>
                                            <?= $v_kota->provinsi_name; ?> - <?= $v_kota->kota_name; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label><?= esc($t['label_alamat']); ?><small class="text-danger">*</small></label>
                                <textarea name="alamat" class="form-control" style="height: 150px"><?= $mitra->alamat; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label><?= esc($t['label_kebutuhan']); ?><small class="text-danger">*</small></label>
                                <textarea name="kebutuhan" class="form-control" style="height: 150px"><?= $mitra->kebutuhan; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label><?= esc($t['label_password']); ?> (<small class="text-primary"><?= esc($t['password_hint']); ?></small>)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </div>
                                    </div>
                                    <input type="password" id="input_password" name="password" class="form-control <?= (session('validation') && session('validation')->hasError('password')) ? 'is-invalid' : ''; ?>" autocomplete="new-password">
                                    <div class="input-group-append">
                                        <span class="input-group-text" style="cursor: pointer;" onclick="togglePassword('input_password', 'eye_icon')">
                                            <i class="fas fa-eye" id="eye_icon"></i>
                                        </span>
                                    </div>
                                    <div class="invalid-feedback">
                                        <?php if (session('validation') && session('validation')->hasError('password')): ?>
                                            <?= esc(session('validation')->getError('password')); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label><?= esc($t['label_password_confirm']); ?></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </div>
                                    </div>
                                    <input type="password" id="input_password_confirm" name="password_confirm" class="form-control <?= (session('error_password_confirm') || (session('validation') && session('validation')->hasError('password_confirm'))) ? 'is-invalid' : ''; ?>" autocomplete="new-password">
                                    <div class="input-group-append">
                                        <span class="input-group-text" style="cursor: pointer;" onclick="togglePassword('input_password_confirm', 'eye_icon_confirm')">
                                            <i class="fas fa-eye" id="eye_icon_confirm"></i>
                                        </span>
                                    </div>
                                    <div class="invalid-feedback">
                                        <?php if (session('error_password_confirm')): ?>
                                            <?= esc(session('error_password_confirm')); ?>
                                        <?php elseif (session('validation') && session('validation')->hasError('password_confirm')): ?>
                                            <?= esc(session('validation')->getError('password_confirm')); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <a href="<?= site_url('mitra'); ?>" class="btn btn-dark"><?= esc($t['back']); ?></a>
                                <button type="submit" class="btn btn-primary"><?= esc($t['save']); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function togglePassword(inputId, iconId) {
    var pwInput = document.getElementById(inputId);
    var eyeIcon = document.getElementById(iconId);
    if (pwInput.type === "password") {
        pwInput.type = "text";
        eyeIcon.classList.remove("fa-eye");
        eyeIcon.classList.add("fa-eye-slash");
    } else {
        pwInput.type = "password";
        eyeIcon.classList.remove("fa-eye-slash");
        eyeIcon.classList.add("fa-eye");
    }
}
</script>
<?= $this->endSection() ?>
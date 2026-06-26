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
        'create_mitra' => 'Tambah Mitra',
        'back' => 'Kembali',
        'required_note' => 'Tidak Boleh Kosong',
        'label_nama_lengkap' => 'Nama lengkap Mitra UMKM',
        'label_username' => 'Username',
        'username_note' => 'Contoh: abdimasoke / abdimas_oke',
        'min_char_note' => 'Minimal 10 Karakter',
        'label_email' => 'Email',
        'email_note' => 'Contoh: email@email.com',
        'label_kontak' => 'No.Telp/Whatsapp',
        'kontak_note' => 'Contoh: 628122300932',
        'label_password' => 'Password',
        'password_note' => 'Contoh: password123',
        'pass_min_note' => 'Minimal 6 Karakter',
        'label_provinsi_kota' => 'Provinsi dan Kota/Kabupaten',
        'select_option' => '—Silahkan Pilih—',
        'label_alamat' => 'Alamat Lengkap',
        'label_kebutuhan' => 'Kebutuhan Mitra kepada Abdimas',
        'reset' => 'Reset',
        'save' => 'Simpan',
    ],
    'en' => [
        'dashboard' => 'Dashboard',
        'data_mitra' => 'Partner Data',
        'create_mitra' => 'Add Partner',
        'back' => 'Back',
        'required_note' => 'Required',
        'label_nama_lengkap' => 'Partner Full Name',
        'label_username' => 'Username',
        'username_note' => 'Example: abdimasoke / abdimas_oke',
        'min_char_note' => 'Minimum 10 Characters',
        'label_email' => 'Email',
        'email_note' => 'Example: email@email.com',
        'label_kontak' => 'Phone/Whatsapp',
        'kontak_note' => 'Example: 628122300932',
        'label_password' => 'Password',
        'password_note' => 'Example: password123',
        'pass_min_note' => 'Minimum 6 Characters',
        'label_provinsi_kota' => 'Province and City/Regency',
        'select_option' => '—Please Select—',
        'label_alamat' => 'Complete Address',
        'label_kebutuhan' => 'Partner Needs for Abdimas',
        'reset' => 'Reset',
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
                        <form action="<?= site_url('mitra'); ?>" method="POST" autocomplete="off">
                            <?= csrf_field(); ?>
                            <div class="form-group">
                                <label><?= esc($t['label_nama_lengkap']); ?><small class="text-danger">*</small> <span class="text-primary">Note: <?= esc($t['required_note']); ?></span></label>
                                <input type="text" name="user_name" id="user_name" value="<?= set_value('user_name'); ?>" class="form-control <?= (session('validation')) ? 'is-invalid' : ''; ?>" autofocus>
                                <div class="invalid-feedback">
                                    <?php if (session('validation') && session('validation')->hasError('user_name')): ?>
                                        <?= session('validation')->getError('user_name'); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label><?= esc($t['label_username']); ?><small class="text-danger">*</small> (<b class="text-primary"><?= esc($t['username_note']); ?></b>) <span class="text-primary">Note: <?= esc($t['min_char_note']); ?></span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="nidn" value="<?= set_value('nidn'); ?>" class="form-control <?= (session('validation')) ? 'is-invalid' : ''; ?>" autofocus>
                                    <div class="invalid-feedback">
                                        <?php if (session('validation') && session('validation')->hasError('nidn')): ?>
                                            <?= session('validation')->getError('nidn'); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label><?= esc($t['label_email']); ?><small class="text-danger">*</small> (<b class="text-primary"><?= esc($t['email_note']); ?></b>)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-phone"></i>
                                        </div>
                                    </div>
                                    <input type="email" name="email" value="<?= set_value('email'); ?>" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label><?= esc($t['label_kontak']); ?><small class="text-danger">*</small> (<b class="text-primary"><?= esc($t['kontak_note']); ?></b>)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-phone"></i>
                                        </div>
                                    </div>
                                    <input type="number" name="kontak" value="<?= set_value('kontak'); ?>" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label><?= esc($t['label_password']); ?><small class="text-danger">*</small> (<b class="text-primary"><?= esc($t['password_note']); ?></b>) <span class="text-primary">Note: <?= esc($t['pass_min_note']); ?></span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="password" value="<?= set_value('password'); ?>" class="form-control <?= (session('validation')) ? 'is-invalid' : ''; ?>" autofocus>
                                    <div class="invalid-feedback">
                                        <?php if (session('validation') && session('validation')->hasError('password')): ?>
                                            <?= session('validation')->getError('password'); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label><?= esc($t['label_provinsi_kota']); ?><small class="text-danger">*</small></label>
                                <select name="kota_id" value="<?= set_value('kota_id'); ?>" class="form-control select2">
                                    <option selected disabled><?= esc($t['select_option']); ?></option>
                                    <?php foreach ($kota as $jrs => $v_kota): ?>
                                        <option value="<?= $v_kota->kota_id; ?>"><?= $v_kota->provinsi_name; ?> - <?= $v_kota->kota_name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label><?= esc($t['label_alamat']); ?><small class="text-danger">*</small></label>
                                <textarea name="alamat" class="form-control" style="height: 150px"><?= set_value('alamat'); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label><?= esc($t['label_kebutuhan']); ?><small class="text-danger">*</small></label>
                                <textarea name="kebutuhan" class="form-control" style="height: 150px"><?= set_value('kebutuhan'); ?></textarea>
                            </div>
                            <div class="text-right">
                                <button type="reset" class="btn btn-danger"><?= esc($t['reset']); ?></button>
                                <button type="submit" class="btn btn-primary"><?= esc($t['save']); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
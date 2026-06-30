<?= $this->extend('layouts/default') ?>

<?php
helper(['cookie', 'url']);

$request = service('request');

$allowed = ['id', 'en'];
$lang = get_cookie('lang') ?: 'id';
if (!in_array($lang, $allowed, true)) {
    $lang = 'id';
}

$reqLang = $request->getGet('lang');
if ($reqLang && in_array($reqLang, $allowed, true)) {
    set_cookie('lang', $reqLang, 60 * 60 * 24 * 30);
    $lang = $reqLang;
}

$TR = [
    'id' => [
        'dashboard' => 'Dashboard',
        'breadcrumb_dashboard' => 'Dashboard',

        'success_title' => 'Selamat!',
        'error_title'   => 'Peringatan!',

        'label_gelar_dpn' => 'Gelar depan',
        'label_nama_lengkap' => 'Nama lengkap',
        'label_gelar_blkng' => 'Gelar belakang',

        'label_sinta' => 'SINTA ID',
        'example' => 'Contoh',

        'label_nidn' => 'NIDN',
        'label_email' => 'Email',
        'label_kontak' => 'No.Telp/Whatsapp',

        'label_univ' => 'Universitas',
        'label_fungsional' => 'Jabatan Fungsional',
        'label_bidang' => 'Program Studi',
        'label_prov_kota' => 'Provinsi - Kota',
        'label_alamat' => 'Alamat lengkap',

        'placeholder_update_title' => 'Update Title',
        'placeholder_select' => '— Silahkan Pilih —',

        'btn_back' => 'kembali',
        'btn_change_password' => 'Ubah password',
        'btn_save' => 'Simpan',
    ],
    'en' => [
        'dashboard' => 'Dashboard',
        'breadcrumb_dashboard' => 'Dashboard',

        'success_title' => 'Congratulations!',
        'error_title'   => 'Warning!',

        'label_gelar_dpn' => 'Prefix title',
        'label_nama_lengkap' => 'Full name',
        'label_gelar_blkng' => 'Suffix title',

        'label_sinta' => 'SINTA ID',
        'example' => 'Example',

        'label_nidn' => 'NIDN',
        'label_email' => 'Email',
        'label_kontak' => 'Phone/WhatsApp',

        'label_univ' => 'University',
        'label_fungsional' => 'Functional Position',
        'label_bidang' => 'Study Program',
        'label_prov_kota' => 'Province - City',
        'label_alamat' => 'Full address',

        'placeholder_update_title' => 'Update Title',
        'placeholder_select' => '— Please Select —',

        'btn_back' => 'Back',
        'btn_change_password' => 'Change password',
        'btn_save' => 'Save',
    ],
];

$t = static fn(string $key): string => $TR[$lang][$key] ?? $TR['id'][$key] ?? $key;
?>

<?= $this->section('title') ?>
<title><?= esc($title_tab); ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <a href="<?= site_url('dashboard'); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
        <h1><?= esc($title); ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>"><?= esc($t('breadcrumb_dashboard')) ?></a></div>
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
                        <form action="<?= site_url('profile_user_dosen/' . $profile_user_dosen->user_id); ?>" method="POST" autocomplete="off">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="_method" value="PUT">

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label><?= esc($t('label_gelar_dpn')) ?><small class="text-danger">*</small></label>
                                    <input type="text" name="gelar_dpn" id="gelar_dpn" value="<?= esc($profile_user_dosen->gelar_dpn); ?>" class="form-control" autofocus>
                                </div>

                                <div class="form-group col-md-4">
                                    <label><?= esc($t('label_nama_lengkap')) ?><small class="text-danger">*</small></label>
                                    <input type="text" name="user_name" id="user_name"
                                        value="<?= esc($profile_user_dosen->user_name); ?>"
                                        class="form-control <?= (session('validation')) ? 'is-invalid' : ''; ?>" autofocus>
                                    <div class="invalid-feedback">
                                        <?php if (session('validation') && session('validation')->hasError('user_name')): ?>
                                            <?= esc(session('validation')->getError('user_name')); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="form-group col-md-4">
                                    <label><?= esc($t('label_gelar_blkng')) ?><small class="text-danger">*</small></label>
                                    <input type="text" name="gelar_blkng" id="gelar_blkng" value="<?= esc($profile_user_dosen->gelar_blkng); ?>" class="form-control" autofocus>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?= esc($t('label_sinta')) ?><small class="text-danger">*</small>
                                    (<small class="text-primary"><?= esc($t('example')) ?>: 123456</small>)
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="far fa-user"></i></div>
                                    </div>
                                    <input type="text" name="sinta_id" value="<?= esc($profile_user_dosen->sinta_id); ?>"
                                        class="form-control <?= (session('validation')) ? 'is-invalid' : ''; ?>" autofocus>
                                    <div class="invalid-feedback">
                                        <?php if (session('validation') && session('validation')->hasError('sinta_id')): ?>
                                            <?= esc(session('validation')->getError('sinta_id')); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?= esc($t('label_nidn')) ?><small class="text-danger">*</small>
                                    (<small class="text-primary"><?= esc($t('example')) ?>: 1234567890</small>)
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-user"></i></div>
                                    </div>
                                    <input type="text" name="nidn" value="<?= esc($profile_user_dosen->nidn); ?>"
                                        class="form-control <?= (session('validation')) ? 'is-invalid' : ''; ?>" autofocus>
                                    <div class="invalid-feedback">
                                        <?php if (session('validation') && session('validation')->hasError('nidn')): ?>
                                            <?= esc(session('validation')->getError('nidn')); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?= esc($t('label_email')) ?><small class="text-danger">*</small>
                                    (<small class="text-primary"><?= esc($t('example')) ?>: email@email.ac.id</small>)
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-envelope"></i></div>
                                    </div>
                                    <input type="email" name="email" value="<?= esc($profile_user_dosen->email); ?>" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?= esc($t('label_kontak')) ?><small class="text-danger">*</small>
                                    (<small class="text-primary"><?= esc($t('example')) ?>: 628122300932</small>)
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-phone"></i></div>
                                    </div>
                                    <input type="number" name="kontak" value="<?= esc($profile_user_dosen->kontak); ?>" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?= esc($t('label_univ')) ?><small class="text-danger">*</small></label>
                                <select name="universitas_id" class="form-control select2">
                                    <option selected disabled><?= esc($t('placeholder_select')) ?></option>
                                    <?php foreach ($universitas as $v_universitas): ?>
                                        <option value="<?= esc($v_universitas->universitas_id); ?>"
                                            <?= $profile_user_dosen->universitas_id == $v_universitas->universitas_id ? 'selected' : null; ?>>
                                            <?= esc($v_universitas->universitas_name); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-5">
                                    <label><?= esc($t('label_fungsional')) ?><small class="text-danger">*</small></label>
                                    <select name="fungsional_id" class="form-control select2">
                                        <option selected disabled><?= esc($t('placeholder_select')) ?></option>
                                        <?php foreach ($fungsional as $v_fungsional): ?>
                                            <option value="<?= esc($v_fungsional->fungsional_id); ?>"
                                                <?= $profile_user_dosen->fungsional_id == $v_fungsional->fungsional_id ? 'selected' : null; ?>>
                                                <?= esc($v_fungsional->fungsional_name); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-7">
                                    <label><?= esc($t('label_bidang')) ?><small class="text-danger">*</small></label>
                                    <select name="jurusan_id" class="form-control select2">
                                        <option selected disabled><?= esc($t('placeholder_select')) ?></option>
                                        <?php foreach ($jurusan as $v_jurusan): ?>
                                            <option value="<?= esc($v_jurusan->jurusan_id); ?>"
                                                <?= $profile_user_dosen->jurusan_id == $v_jurusan->jurusan_id ? 'selected' : null; ?>>
                                                <?= esc($v_jurusan->fakultas_name); ?> - <?= esc($v_jurusan->jurusan_name); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?= esc($t('label_prov_kota')) ?><small class="text-danger">*</small></label>
                                <select name="kota_id" class="form-control select2">
                                    <option selected disabled><?= esc($t('placeholder_select')) ?></option>
                                    <?php foreach ($kota as $v_kota): ?>
                                        <option value="<?= esc($v_kota->kota_id); ?>"
                                            <?= $profile_user_dosen->kota_id == $v_kota->kota_id ? 'selected' : null; ?>>
                                            <?= esc($v_kota->provinsi_name); ?> - <?= esc($v_kota->kota_name); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label><?= esc($t('label_alamat')) ?></label>
                                <textarea name="alamat" class="form-control" style="height: 150px"><?= esc($profile_user_dosen->alamat); ?></textarea>
                            </div>

                            <div class="text-right">
                                <a href="<?= site_url('dashboard'); ?>" class="btn btn-dark"><?= esc($t('btn_back')) ?></a>
                                <a href="<?= site_url('ubah_password_dosen/update/' . userLogin()->user_id); ?>" class="btn btn-warning text-dark">
                                    <?= esc($t('btn_change_password')) ?>
                                </a>
                                <button type="submit" class="btn btn-primary"><?= esc($t('btn_save')) ?></button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
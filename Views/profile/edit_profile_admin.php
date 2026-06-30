<?= $this->extend('layouts/default') ?>

<?php
helper(['cookie', 'url']);

$request = service('request');

$allowed = ['id', 'en'];
$lang = get_cookie('lang') ?: 'id';
if (! in_array($lang, $allowed, true)) {
    $lang = 'id';
}

// switch bahasa via query param: ?lang=id / ?lang=en
$reqLang = $request->getGet('lang');
if ($reqLang && in_array($reqLang, $allowed, true)) {
    set_cookie('lang', $reqLang, 60 * 60 * 24 * 30); // 30 hari
    $lang = $reqLang;
}

$TR = [
    'id' => [
        'dashboard'        => 'Dashboard',
        'congrats'         => 'Congratulation!',
        'warning_error'    => 'Warning Error!',
        'example'          => 'Contoh',
        'please_choose'    => 'Silahkan Pilih',
        'front_title'      => 'Gelar depan',
        'full_name'        => 'Nama lengkap',
        'back_title'       => 'Gelar belakang',
        'sinta_id'         => 'SINTA ID',
        'nidn'             => 'NIDN',
        'email'            => 'Email',
        'phone_wa'         => 'No.Telp/Whatsapp',
        'university'       => 'Universitas',
        'functional_role'  => 'Jabatan Fungsional',
        'field'            => 'Bidang Ilmu',
        'province_city'    => 'Provinsi - Kota',
        'full_address'     => 'Alamat lengkap',
        'back'             => 'kembali',
        'change_password'  => 'Ubah password',
        'save'             => 'Simpan',
    ],
    'en' => [
        'dashboard'        => 'Dashboard',
        'congrats'         => 'Congratulations!',
        'warning_error'    => 'Warning!',
        'example'          => 'Example',
        'please_choose'    => 'Please Choose',
        'front_title'      => 'Title (prefix)',
        'full_name'        => 'Full name',
        'back_title'       => 'Title (suffix)',
        'sinta_id'         => 'SINTA ID',
        'nidn'             => 'NIDN',
        'email'            => 'Email',
        'phone_wa'         => 'Phone/WhatsApp',
        'university'       => 'University',
        'functional_role'  => 'Functional Position',
        'field'            => 'Field of Study',
        'province_city'    => 'Province - City',
        'full_address'     => 'Full address',
        'back'             => 'Back',
        'change_password'  => 'Change password',
        'save'             => 'Save',
    ],
];

$t = static function (string $key) use ($TR, $lang): string {
    return $TR[$lang][$key] ?? $TR['id'][$key] ?? $key;
};

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
                        <form action="<?= site_url('profile_user_admin/' . $profile_user_admin->user_id); ?>" method="POST" autocomplete="off">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="_method" value="PUT">

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label><?= esc($t('front_title')) ?><small class="text-danger">*</small></label>
                                    <input type="text" name="gelar_dpn" id="user_name" value="<?= esc($profile_user_admin->gelar_dpn); ?>" class="form-control" autofocus>
                                </div>

                                <div class="form-group col-md-4">
                                    <label><?= esc($t('full_name')) ?><small class="text-danger">*</small></label>
                                    <input type="text" name="user_name" id="user_name" value="<?= esc($profile_user_admin->user_name); ?>" class="form-control <?= (session('validation')) ? 'is-invalid' : ''; ?>" autofocus>
                                    <div class="invalid-feedback">
                                        <?php if (session('validation') && session('validation')->hasError('user_name')): ?>
                                            <?= esc(session('validation')->getError('user_name')); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="form-group col-md-4">
                                    <label><?= esc($t('back_title')) ?><small class="text-danger">*</small></label>
                                    <input type="text" name="gelar_blkng" id="user_name" value="<?= esc($profile_user_admin->gelar_blkng); ?>" class="form-control" autofocus>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>
                                    <?= esc($t('sinta_id')) ?><small class="text-danger">*</small>
                                    (<small class="text-primary"><?= esc($t('example')) ?>: 123456</small>)
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="far fa-user"></i></div>
                                    </div>
                                    <input type="text" name="sinta_id" value="<?= esc($profile_user_admin->sinta_id); ?>" class="form-control <?= (session('validation')) ? 'is-invalid' : ''; ?>" autofocus>
                                    <div class="invalid-feedback">
                                        <?php if (session('validation') && session('validation')->hasError('sinta_id')): ?>
                                            <?= esc(session('validation')->getError('sinta_id')); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>
                                    <?= esc($t('nidn')) ?><small class="text-danger">*</small>
                                    (<small class="text-primary"><?= esc($t('example')) ?>: 1234567890</small>)
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-user"></i></div>
                                    </div>
                                    <input type="text" name="nidn" value="<?= esc($profile_user_admin->nidn); ?>" class="form-control <?= (session('validation')) ? 'is-invalid' : ''; ?>" autofocus>
                                    <div class="invalid-feedback">
                                        <?php if (session('validation') && session('validation')->hasError('nidn')): ?>
                                            <?= esc(session('validation')->getError('nidn')); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>
                                    <?= esc($t('email')) ?><small class="text-danger">*</small>
                                    (<small class="text-primary"><?= esc($t('example')) ?>: email@email.ac.id</small>)
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-envelope"></i></div>
                                    </div>
                                    <input type="email" name="email" value="<?= esc($profile_user_admin->email); ?>" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>
                                    <?= esc($t('phone_wa')) ?><small class="text-danger">*</small>
                                    (<small class="text-primary"><?= esc($t('example')) ?>: 628122300932</small>)
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-phone"></i></div>
                                    </div>
                                    <input type="number" name="kontak" value="<?= esc($profile_user_admin->kontak); ?>" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?= esc($t('university')) ?><small class="text-danger">*</small></label>
                                <select name="universitas_id" class="form-control select2">
                                    <option selected disabled>&mdash;<?= esc($t('please_choose')) ?>&mdash;</option>
                                    <?php foreach ($universitas as $jrs => $v_universitas): ?>
                                        <option value="<?= esc($v_universitas->universitas_id); ?>" <?= $profile_user_admin->universitas_id == $v_universitas->universitas_id ? 'selected' : null; ?>>
                                            <?= esc($v_universitas->universitas_name); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-5">
                                    <label><?= esc($t('functional_role')) ?><small class="text-danger">*</small></label>
                                    <select name="fungsional_id" class="form-control select2">
                                        <option selected disabled>&mdash;<?= esc($t('please_choose')) ?>&mdash;</option>
                                        <?php foreach ($fungsional as $jrs => $v_fungsional): ?>
                                            <option value="<?= esc($v_fungsional->fungsional_id); ?>" <?= $profile_user_admin->fungsional_id == $v_fungsional->fungsional_id ? 'selected' : null; ?>>
                                                <?= esc($v_fungsional->fungsional_name); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-7">
                                    <label><?= esc($t('field')) ?><small class="text-danger">*</small></label>
                                    <select name="jurusan_id" class="form-control select2">
                                        <option selected disabled>&mdash;<?= esc($t('please_choose')) ?>&mdash;</option>
                                        <?php foreach ($jurusan as $jrs => $v_jurusan): ?>
                                            <option value="<?= esc($v_jurusan->jurusan_id); ?>" <?= $profile_user_admin->jurusan_id == $v_jurusan->jurusan_id ? 'selected' : null; ?>>
                                                <?= esc($v_jurusan->fakultas_name); ?> - <?= esc($v_jurusan->jurusan_name); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?= esc($t('province_city')) ?><small class="text-danger">*</small></label>
                                <select name="kota_id" class="form-control select2">
                                    <option selected disabled>&mdash;<?= esc($t('please_choose')) ?>&mdash;</option>
                                    <?php foreach ($kota as $jrs => $v_kota): ?>
                                        <option value="<?= esc($v_kota->kota_id); ?>" <?= $profile_user_admin->kota_id == $v_kota->kota_id ? 'selected' : null; ?>>
                                            <?= esc($v_kota->provinsi_name); ?> - <?= esc($v_kota->kota_name); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label><?= esc($t('full_address')) ?></label>
                                <textarea name="alamat" class="form-control" style="height: 150px"><?= esc($profile_user_admin->alamat); ?></textarea>
                            </div>

                            <div class="text-right">
                                <a href="<?= site_url('dashboard'); ?>" class="btn btn-dark"><?= esc($t('back')) ?></a>
                                <a href="<?= site_url('ubah_password_admin/update/' . userLogin()->user_id); ?>" class="btn btn-warning text-dark"><?= esc($t('change_password')) ?></a>
                                <button type="submit" class="btn btn-primary"><?= esc($t('save')) ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
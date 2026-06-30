<?php
// --- I18N inline (1 file view, tanpa folder Language, tanpa ubah UI/UX) ---
$lang = strtolower(trim((string) (service('request')->getCookie('lang') ?? 'id'))); // CI4 getCookie() :contentReference[oaicite:2]{index=2}
$lang = ($lang === 'en') ? 'en' : 'id';

$I18N = [
    'id' => [
        'dashboard'        => 'Dashboard',
        'congratulation'   => 'Congratulation!',
        'warningError'     => 'Warning Error!',
        'prefixTitle'      => 'Gelar depan',
        'fullName'         => 'Nama lengkap',
        'suffixTitle'      => 'Gelar belakang',
        'sintaId'          => 'SINTA ID',
        'nidn'             => 'NIDN',
        'email'            => 'Email',
        'phone'            => 'No.Telp/Whatsapp',
        'university'       => 'Universitas',
        'functionalPos'    => 'Jabatan Fungsional',
        'fieldStudy'       => 'Program Studi',
        'provinceCity'     => 'Provinsi - Kota',
        'fullAddress'      => 'Alamat lengkap',
        'example'          => 'Contoh: %s',
        'pleaseSelect'     => 'Silahkan Pilih',
        'back'             => 'kembali',
        'changePassword'   => 'Ubah password',
        'save'             => 'Simpan',
    ],
    'en' => [
        'dashboard'        => 'Dashboard',
        'congratulation'   => 'Success!',
        'warningError'     => 'Error!',
        'prefixTitle'      => 'Prefix title',
        'fullName'         => 'Full name',
        'suffixTitle'      => 'Suffix title',
        'sintaId'          => 'SINTA ID',
        'nidn'             => 'NIDN',
        'email'            => 'Email',
        'phone'            => 'Phone/WhatsApp',
        'university'       => 'University',
        'functionalPos'    => 'Functional Position',
        'fieldStudy'       => 'Study Program',
        'provinceCity'     => 'Province - City',
        'fullAddress'      => 'Full address',
        'example'          => 'Example: %s',
        'pleaseSelect'     => 'Please Select',
        'back'             => 'back',
        'changePassword'   => 'Change password',
        'save'             => 'Save',
    ],
];

$__ = function (string $key, ...$args) use ($I18N, $lang) {
    $text = $I18N[$lang][$key] ?? $I18N['id'][$key] ?? $key;
    return $args ? vsprintf($text, $args) : $text;
};
?>

<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <a href="<?= site_url('dashboard'); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
        <h1><?= $title; ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>"><?= $__('dashboard'); ?></a></div>
            <div class="breadcrumb-item"><?= $title; ?></div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b><?= $__('congratulation'); ?></b>
                <?= session()->getFlashdata('success'); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b><?= $__('warningError'); ?></b>
                <?= session()->getFlashdata('error'); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= site_url('profile_user_staff/' . $profile_user_staff->user_id); ?>" method="POST" autocomplete="off">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="_method" value="PUT">

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label><?= $__('prefixTitle'); ?><small class="text-danger">*</small></label>
                                    <input type="text" name="gelar_dpn" id="user_name" value="<?= $profile_user_staff->gelar_dpn; ?>" class="form-control" autofocus>
                                </div>
                                <div class="form-group col-md-4">
                                    <label><?= $__('fullName'); ?><small class="text-danger">*</small></label>
                                    <input type="text" name="user_name" id="user_name" value="<?= $profile_user_staff->user_name; ?>" class="form-control <?= (session('validation')) ? 'is-invalid' : ''; ?>" autofocus>
                                    <div class="invalid-feedback">
                                        <?php if (session('validation') && session('validation')->hasError('user_name')): ?>
                                            <?= session('validation')->getError('user_name'); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label><?= $__('suffixTitle'); ?><small class="text-danger">*</small></label>
                                    <input type="text" name="gelar_blkng" id="user_name" value="<?= $profile_user_staff->gelar_blkng; ?>" class="form-control" autofocus>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?= $__('sintaId'); ?><small class="text-danger">*</small> (<small class="text-primary"><?= $__('example', '123456'); ?></small>)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="far fa-user"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="sinta_id" value="<?= $profile_user_staff->sinta_id; ?>" class="form-control <?= (session('validation')) ? 'is-invalid' : ''; ?>" autofocus>
                                    <div class="invalid-feedback">
                                        <?php if (session('validation') && session('validation')->hasError('sinta_id')): ?>
                                            <?= session('validation')->getError('sinta_id'); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?= $__('nidn'); ?><small class="text-danger">*</small> (<small class="text-primary"><?= $__('example', '1234567890'); ?></small>)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="nidn" value="<?= $profile_user_staff->nidn; ?>" class="form-control <?= (session('validation')) ? 'is-invalid' : ''; ?>" autofocus>
                                    <div class="invalid-feedback">
                                        <?php if (session('validation') && session('validation')->hasError('nidn')): ?>
                                            <?= session('validation')->getError('nidn'); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?= $__('email'); ?><small class="text-danger">*</small> (<small class="text-primary"><?= $__('example', 'email@email.ac.id'); ?></small>)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                    </div>
                                    <input type="email" name="email" value="<?= $profile_user_staff->email; ?>" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?= $__('phone'); ?><small class="text-danger">*</small> (<small class="text-primary"><?= $__('example', '628122300932'); ?></small>)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-phone"></i>
                                        </div>
                                    </div>
                                    <input type="number" name="kontak" value="<?= $profile_user_staff->kontak; ?>" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?= $__('university'); ?><small class="text-danger">*</small></label>
                                <select name="universitas_id" class="form-control select2">
                                    <option selected disabled>&mdash;<?= $__('pleaseSelect'); ?>&mdash;</option>
                                    <?php foreach ($universitas as $jrs => $v_universitas): ?>
                                        <option value="<?= $v_universitas->universitas_id; ?>" <?= $profile_user_staff->universitas_id == $v_universitas->universitas_id ? 'selected' : null; ?>>
                                            <?= $v_universitas->universitas_name; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-5">
                                    <label><?= $__('functionalPos'); ?><small class="text-danger">*</small></label>
                                    <select name="fungsional_id" class="form-control select2">
                                        <option selected disabled>&mdash;<?= $__('pleaseSelect'); ?>&mdash;</option>
                                        <?php foreach ($fungsional as $jrs => $v_fungsional): ?>
                                            <option value="<?= $v_fungsional->fungsional_id; ?>" <?= $profile_user_staff->fungsional_id == $v_fungsional->fungsional_id ? 'selected' : null; ?>>
                                                <?= $v_fungsional->fungsional_name; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-7">
                                    <label><?= $__('fieldStudy'); ?><small class="text-danger">*</small></label>
                                    <select name="jurusan_id" class="form-control select2">
                                        <option selected disabled>&mdash;<?= $__('pleaseSelect'); ?>&mdash;</option>
                                        <?php foreach ($jurusan as $jrs => $v_jurusan): ?>
                                            <option value="<?= $v_jurusan->jurusan_id; ?>" <?= $profile_user_staff->jurusan_id == $v_jurusan->jurusan_id ? 'selected' : null; ?>>
                                                <?= $v_jurusan->fakultas_name; ?> - <?= $v_jurusan->jurusan_name; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?= $__('provinceCity'); ?><small class="text-danger">*</small></label>
                                <select name="kota_id" class="form-control select2">
                                    <option selected disabled>&mdash;<?= $__('pleaseSelect'); ?>&mdash;</option>
                                    <?php foreach ($kota as $jrs => $v_kota): ?>
                                        <option value="<?= $v_kota->kota_id; ?>" <?= $profile_user_staff->kota_id == $v_kota->kota_id ? 'selected' : null; ?>>
                                            <?= $v_kota->provinsi_name; ?> - <?= $v_kota->kota_name; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label><?= $__('fullAddress'); ?></label>
                                <textarea name="alamat" class="form-control" style="height: 150px"><?= $profile_user_staff->alamat; ?></textarea>
                            </div>

                            <div class="text-right">
                                <a href="<?= site_url('dashboard'); ?>" class="btn btn-dark"><?= $__('back'); ?></a>
                                <a href="<?= site_url('ubah_password_staff/update/' . userLogin()->user_id); ?>" class="btn btn-warning text-dark"><?= $__('changePassword'); ?></a>
                                <button type="submit" class="btn btn-primary"><?= $__('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- commented block tetap dibiarkan seperti aslinya -->
        </div>
    </div>
</section>
<?= $this->endSection() ?>
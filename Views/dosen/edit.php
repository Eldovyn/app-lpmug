<?= $this->extend('layouts/default') ?>

<?php
// ====== I18N (SINGLE FILE) ======
$request = service('request'); // :contentReference[oaicite:1]{index=1}

$lang = $lang ?? ($request->getCookie('lang') ?? 'id'); // :contentReference[oaicite:2]{index=2}
$lang = strtolower(trim((string) $lang));
$lang = ($lang === 'en') ? 'en' : 'id';

$TR = [
    'id' => [
        'dashboard'      => 'Dashboard',
        'lecturer_data'  => 'Data dosen',

        'front_title'    => 'Gelar depan',
        'full_name'      => 'Nama lengkap',
        'back_title'     => 'Gelar belakang',

        'example'        => 'Contoh',

        'sinta_id'       => 'SINTA ID',
        'nidn'           => 'NIDN',
        'email'          => 'Email',
        'phone_wa'       => 'No.Telp/Whatsapp',

        'functional'     => 'Jabatan Fungsional',
        'field'          => 'Bidang Ilmu',
        'please_choose'  => 'Silahkan Pilih',
        'password'       => 'Kata Sandi',
        'password_hint'  => 'Kosongkan jika tidak ingin mengubah kata Sandi',
        'password_confirm'=> 'Konfirmasi Kata Sandi',

        'back'           => 'kembali',
        'save'           => 'Simpan',
    ],
    'en' => [
        'dashboard'      => 'Dashboard',
        'lecturer_data'  => 'Lecturer Data',

        'front_title'    => 'Title (prefix)',
        'full_name'      => 'Full name',
        'back_title'     => 'Title (suffix)',

        'example'        => 'Example',

        'sinta_id'       => 'SINTA ID',
        'nidn'           => 'NIDN',
        'email'          => 'Email',
        'phone_wa'       => 'Phone/WhatsApp',

        'functional'     => 'Functional Position',
        'field'          => 'Field of Study',
        'please_choose'  => 'Please Choose',
        'password'       => 'Password',
        'password_hint'  => 'Leave blank if you don\'t want to change the password',
        'password_confirm'=> 'Confirm Password',

        'back'           => 'Back',
        'save'           => 'Save',
    ],
];

$t = static function (string $key) use ($TR, $lang): string {
    return $TR[$lang][$key] ?? $TR['id'][$key] ?? $key;
};
?>

<?= $this->section('title') ?>
<title><?= esc($title_tab); ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <a href="<?= site_url('dosen'); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
        <h1><?= esc($title); ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>"><?= esc($t('dashboard')) ?></a></div>
            <div class="breadcrumb-item active"><a href="<?= site_url('dosen'); ?>"><?= esc($t('lecturer_data')) ?></a></div>
            <div class="breadcrumb-item"><?= esc($title); ?></div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= site_url('dosen/' . $dosen->user_id); ?>" method="POST" autocomplete="off">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="_method" value="PATCH">

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label><?= esc($t('front_title')) ?><small class="text-danger">*</small></label>
                                    <input type="text" name="gelar_dpn" id="user_name" value="<?= esc($dosen->gelar_dpn); ?>" class="form-control" autofocus>
                                </div>

                                <div class="form-group col-md-4">
                                    <label><?= esc($t('full_name')) ?><small class="text-danger">*</small></label>
                                    <input type="text" name="user_name" id="user_name" value="<?= esc($dosen->user_name); ?>" class="form-control <?= (session('validation')) ? 'is-invalid' : ''; ?>" autofocus>
                                    <div class="invalid-feedback">
                                        <?php if (session('validation') && session('validation')->hasError('user_name')): ?>
                                            <?= esc(session('validation')->getError('user_name')); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="form-group col-md-4">
                                    <label><?= esc($t('back_title')) ?><small class="text-danger">*</small></label>
                                    <input type="text" name="gelar_blkng" id="user_name" value="<?= esc($dosen->gelar_blkng); ?>" class="form-control" autofocus>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?= esc($t('sinta_id')) ?><small class="text-danger">*</small> (<small class="text-primary"><?= esc($t('example')) ?>: 123456</small>)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-user"></i></div>
                                    </div>
                                    <input type="text" name="sinta_id" value="<?= esc($dosen->sinta_id); ?>" class="form-control <?= (session('validation')) ? 'is-invalid' : ''; ?>" autofocus>
                                    <div class="invalid-feedback">
                                        <?php if (session('validation') && session('validation')->hasError('sinta_id')): ?>
                                            <?= esc(session('validation')->getError('sinta_id')); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?= esc($t('nidn')) ?><small class="text-danger">*</small> (<small class="text-primary"><?= esc($t('example')) ?>: 1234567890</small>)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-user"></i></div>
                                    </div>
                                    <input type="text" name="nidn" value="<?= esc($dosen->nidn); ?>" class="form-control <?= (session('validation')) ? 'is-invalid' : ''; ?>" disabled autofocus>
                                    <div class="invalid-feedback">
                                        <?php if (session('validation') && session('validation')->hasError('nidn')): ?>
                                            <?= esc(session('validation')->getError('nidn')); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?= esc($t('email')) ?><small class="text-danger">*</small> (<small class="text-primary"><?= esc($t('example')) ?>: email@email.ac.id</small>)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-phone"></i></div>
                                    </div>
                                    <input type="email" name="email" value="<?= esc($dosen->email); ?>" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?= esc($t('phone_wa')) ?><small class="text-danger">*</small> (<small class="text-primary"><?= esc($t('example')) ?>: 628122300932</small>)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-phone"></i></div>
                                    </div>
                                    <input type="number" name="kontak" value="<?= esc($dosen->kontak); ?>" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?= esc($t('functional')) ?><small class="text-danger">*</small></label>
                                <select name="fungsional_id" class="form-control select2">
                                    <option selected disabled>&mdash;<?= esc($t('please_choose')) ?>&mdash;</option>
                                    <?php foreach ($fungsional as $jrs => $v_fungsional): ?>
                                        <option value="<?= esc($v_fungsional->fungsional_id); ?>" <?= $dosen->fungsional_id == $v_fungsional->fungsional_id ? 'selected' : null; ?>>
                                            <?= esc($v_fungsional->fungsional_name); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label><?= esc($t('field')) ?><small class="text-danger">*</small></label>
                                <select name="jurusan_id" class="form-control select2">
                                    <option selected disabled>&mdash;<?= esc($t('please_choose')) ?>&mdash;</option>
                                    <?php foreach ($jurusan as $jrs => $v_jurusan): ?>
                                        <option value="<?= esc($v_jurusan->jurusan_id); ?>" <?= $dosen->jurusan_id == $v_jurusan->jurusan_id ? 'selected' : null; ?>>
                                            <?= esc($v_jurusan->fakultas_name); ?> - <?= esc($v_jurusan->jurusan_name); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label><?= esc($t('password')) ?> (<small class="text-primary"><?= esc($t('password_hint')) ?></small>)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-lock"></i></div>
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
                                <label><?= esc($t('password_confirm')) ?></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-lock"></i></div>
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
                                <a href="<?= site_url('dosen'); ?>" class="btn btn-dark"><?= esc($t('back')) ?></a>
                                <button type="submit" class="btn btn-primary"><?= esc($t('save')) ?></button>
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
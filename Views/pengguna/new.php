<?= $this->extend('layouts/default') ?>

<?php
// Pastikan set_value() tersedia
helper('form'); // Form Helper (set_value) :contentReference[oaicite:2]{index=2}

// ====== I18N (SINGLE FILE) ======
$request = service('request');
$lang = strtolower(trim((string) ($lang ?? $request->getCookie('lang') ?? 'id'))); // cookie dari Request :contentReference[oaicite:3]{index=3}
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
        'sinta_note'     => 'Note: Bagi <b>Dosen</b> yang belum memiliki <b>SINTA ID</b> diisi dengan <b>7 Digit Terakhir No.Telp/Whatsapp</b>',
        'nidn'           => 'NIDN',
        'email'          => 'Email',
        'phone_wa'       => 'No.Telp/Whatsapp',

        'password'       => 'Password',
        'password_note'  => 'Note: isi dengan <b> lpmug2023 </b> sebagai default password',

        'functional'     => 'Jabatan Fungsional',
        'field'          => 'Program Studi',
        'please_choose'  => 'Silahkan Pilih',

        'reset'          => 'Reset',
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
        'sinta_note'     => 'Note: If the <b>Lecturer</b> does not have a <b>SINTA ID</b>, fill it with the <b>last 7 digits of Phone/WhatsApp</b>.',
        'nidn'           => 'NIDN',
        'email'          => 'Email',
        'phone_wa'       => 'Phone/WhatsApp',

        'password'       => 'Password',
        'password_note'  => 'Note: use <b> lpmug2023 </b> as the default password',

        'functional'     => 'Functional Position',
        'field'          => 'Study Program',
        'please_choose'  => 'Please Choose',

        'reset'          => 'Reset',
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
                        <form action="<?= site_url('dosen'); ?>" method="POST" autocomplete="off">
                            <?= csrf_field(); ?>

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label><?= esc($t('front_title')) ?></label>
                                    <input type="text" name="gelar_dpn" id="user_name" value="<?= esc(set_value('gelar_dpn')); ?>" class="form-control" autofocus>
                                </div>

                                <div class="form-group col-md-4">
                                    <label><?= esc($t('full_name')) ?><small class="text-danger">*</small></label>
                                    <input type="text" name="user_name" id="user_name" value="<?= esc(set_value('user_name')); ?>" class="form-control <?= (session('validation')) ? 'is-invalid' : ''; ?>" autofocus>
                                    <div class="invalid-feedback">
                                        <?php if (session('validation') && session('validation')->hasError('user_name')): ?>
                                            <?= esc(session('validation')->getError('user_name')); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="form-group col-md-4">
                                    <label><?= esc($t('back_title')) ?></label>
                                    <input type="text" name="gelar_blkng" id="user_name" value="<?= esc(set_value('gelar_blkng')); ?>" class="form-control" autofocus>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>
                                    <?= esc($t('sinta_id')) ?><small class="text-danger">*</small>
                                    (<small class="text-primary"><?= esc($t('example')) ?>: 123456</small>)
                                    <span class="text-primary"><?= $t('sinta_note') ?></span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-user"></i></div>
                                    </div>
                                    <input type="text" name="sinta_id" value="<?= esc(set_value('sinta_id')); ?>" class="form-control <?= (session('validation')) ? 'is-invalid' : ''; ?>" autofocus>
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
                                    <input type="text" name="nidn" value="<?= esc(set_value('nidn')); ?>" class="form-control <?= (session('validation')) ? 'is-invalid' : ''; ?>" autofocus>
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
                                    <input type="email" name="email" value="<?= esc(set_value('email')); ?>" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?= esc($t('phone_wa')) ?><small class="text-danger">*</small> (<small class="text-primary"><?= esc($t('example')) ?>: 628122300932</small>)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-phone"></i></div>
                                    </div>
                                    <input type="number" name="kontak" value="<?= esc(set_value('kontak')); ?>" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>
                                    <?= esc($t('password')) ?><small class="text-danger">*</small>
                                    <span class="text-primary"><?= $t('password_note') ?></span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-lock"></i></div>
                                    </div>
                                    <input type="text" name="password" value="<?= esc(set_value('password')); ?>" class="form-control <?= (session('validation')) ? 'is-invalid' : ''; ?>" autofocus>
                                    <div class="invalid-feedback">
                                        <?php if (session('validation') && session('validation')->hasError('password')): ?>
                                            <?= esc(session('validation')->getError('password')); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?= esc($t('functional')) ?><small class="text-danger">*</small></label>
                                <select name="fungsional_id" value="<?= esc(set_value('fungsional_id')); ?>" class="form-control select2">
                                    <option selected disabled>&mdash;<?= esc($t('please_choose')) ?>&mdash;</option>
                                    <?php foreach ($fungsional as $fgsl => $v_fungsional): ?>
                                        <option value="<?= esc($v_fungsional->fungsional_id); ?>"><?= esc($v_fungsional->fungsional_name); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label><?= esc($t('field')) ?><small class="text-danger">*</small></label>
                                <select name="jurusan_id" value="<?= esc(set_value('jurusan_id')); ?>" class="form-control select2">
                                    <option selected disabled>&mdash;<?= esc($t('please_choose')) ?>&mdash;</option>
                                    <?php foreach ($jurusan as $jrs => $v_jurusan): ?>
                                        <option value="<?= esc($v_jurusan->jurusan_id); ?>"><?= esc($v_jurusan->fakultas_name); ?> - <?= esc($v_jurusan->jurusan_name); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="text-right">
                                <button type="reset" class="btn btn-danger"><?= esc($t('reset')) ?></button>
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
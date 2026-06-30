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
        'dashboard'         => 'Dashboard',
        'lecturer_data'     => 'Data dosen',

        'success_title'     => 'Selamat!',
        'error_title'       => 'Warning Error!',
        'add_data'          => 'Tambah data',

        'th_name'           => 'Nama',
        'th_field'          => 'Program Studi',
        'th_action'         => 'Action',

        'delete_title'      => 'Hapus data?',
        'delete_msg'        => 'Apakah anda yakin?',

        'edit_password'     => 'Ubah Password',
        'modal_title'       => 'Ganti Password Pengguna',
        'new_password'      => 'Password Baru',
        'confirm_password'  => 'Konfirmasi Password',
        'cancel'            => 'Batal',
        'save'              => 'Simpan',
        'password_min'      => 'Password minimal 6 karakter.',
        'password_mismatch' => 'Password dan konfirmasi tidak cocok.',
    ],
    'en' => [
        'dashboard'         => 'Dashboard',
        'lecturer_data'     => 'Lecturer Data',

        'success_title'     => 'Success!',
        'error_title'       => 'Warning!',
        'add_data'          => 'Add data',

        'th_name'           => 'Name',
        'th_field'          => 'Study Program',
        'th_action'         => 'Action',

        'delete_title'      => 'Delete data?',
        'delete_msg'        => 'Are you sure?',

        'edit_password'     => 'Change Password',
        'modal_title'       => 'Change User Password',
        'new_password'      => 'New Password',
        'confirm_password'  => 'Confirm Password',
        'cancel'            => 'Cancel',
        'save'              => 'Save',
        'password_min'      => 'Password must be at least 6 characters.',
        'password_mismatch' => 'Password and confirmation do not match.',
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
        <h1><?= esc($title); ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="<?= site_url('dashboard'); ?>"><?= esc($t('dashboard')) ?></a></div>
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
        <div class="card">
            <div class="card-header">
                <h4 class="text-sm">
                    <a href="<?= site_url('dosen/new'); ?>" class="btn btn-primary">
                        <i class="fas fa-plus-circle mr-1"></i><?= esc($t('add_data')) ?>
                    </a>
                </h4>
            </div>

            <div class="card-body">
                <div class="table-responsive-md">
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Sinta ID</th>
                                <th><?= esc($t('th_name')) ?></th>
                                <th>NIDN</th>
                                <th><?= esc($t('th_field')) ?></th>
                                <th><?= esc($t('th_action')) ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $page = isset($_GET['page']) ? $_GET['page'] : 1;
                            $no = 1 + (10 * ($page - 1));
                            foreach ($dosen as $users => $v_dosen) : ?>
                                <?php if ($v_dosen->role_id == 4): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= esc($v_dosen->sinta_id); ?></td>
                                        <td><?= esc($v_dosen->gelar_dpn); ?> <?= esc(ucwords(strtolower($v_dosen->user_name))); ?>, <?= esc($v_dosen->gelar_blkng); ?></td>
                                        <td><?= esc($v_dosen->nidn); ?></td>
                                        <td class="text-wrap"><?= esc($v_dosen->fakultas_name); ?> - <?= esc($v_dosen->jurusan_name); ?></td>
                                        <td>
                                            <a href="<?= site_url('dosen/' . $v_dosen->user_id . '/edit'); ?>" class="btn btn-warning btn-sm mb-1">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>

                                            <button type="button"
                                                class="btn btn-info btn-sm mb-1"
                                                onclick="openPasswordModal(<?= (int) $v_dosen->user_id; ?>, '<?= esc(addslashes($v_dosen->user_name)); ?>')"
                                                title="<?= esc($t('edit_password')); ?>">
                                                <i class="fas fa-key"></i>
                                            </button>

                                            <form action="<?= site_url('dosen/' . $v_dosen->user_id); ?>" method="POST" class="d-inline" id="del-<?= esc($v_dosen->user_id); ?>">
                                                <?= csrf_field(); ?>
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button class="btn btn-danger btn-sm mb-1"
                                                    data-confirm="<?= esc($t('delete_title')) ?> | <?= esc($t('delete_msg')) ?>"
                                                    data-confirm-yes="submitDel(<?= (int) $v_dosen->user_id; ?>)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Modal Ubah Password Pengguna -->
<div class="modal fade" id="modalUbahPassword" tabindex="-1" role="dialog" aria-labelledby="modalUbahPasswordLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #17a2b8, #117a8b);">
                <h5 class="modal-title text-white" id="modalUbahPasswordLabel">
                    <i class="fas fa-key mr-2"></i><?= esc($t('modal_title')); ?>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formUbahPassword" method="POST" action="" onsubmit="return validatePwForm()">
                <?= csrf_field(); ?>
                <div class="modal-body">
                    <p class="text-muted mb-3">
                        <i class="fas fa-user-circle mr-1"></i>
                        Pengguna: <strong id="penggunaNameLabel"></strong>
                    </p>
                    <div class="form-group">
                        <label for="pw_new"><?= esc($t('new_password')); ?> <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="pw_new" name="new_password"
                                placeholder="Min. 6 karakter" required minlength="6">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePw('pw_new','icon_pw_new')">
                                    <i class="fas fa-eye" id="icon_pw_new"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="pw_confirm"><?= esc($t('confirm_password')); ?> <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="pw_confirm" name="confirm_password"
                                placeholder="Ulangi password baru" required minlength="6">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePw('pw_confirm','icon_pw_confirm')">
                                    <i class="fas fa-eye" id="icon_pw_confirm"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="pw-error" class="alert alert-danger d-none" role="alert"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i><?= esc($t('cancel')); ?>
                    </button>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-save mr-1"></i><?= esc($t('save')); ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var pgBaseUrl    = '<?= site_url(); ?>';
    var pgMsgMin     = '<?= esc($t('password_min')); ?>';
    var pgMsgMismatch= '<?= esc($t('password_mismatch')); ?>';

    function openPasswordModal(userId, userName) {
        document.getElementById('penggunaNameLabel').textContent = userName;
        document.getElementById('formUbahPassword').action = pgBaseUrl + 'pengguna/update-password/' + userId;
        document.getElementById('pw_new').value = '';
        document.getElementById('pw_confirm').value = '';
        document.getElementById('pw-error').classList.add('d-none');
        document.getElementById('pw-error').textContent = '';
        $('#modalUbahPassword').modal('show');
    }

    function togglePw(inputId, iconId) {
        var input = document.getElementById(inputId);
        var icon  = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    function validatePwForm() {
        var newPwd  = document.getElementById('pw_new').value;
        var confPwd = document.getElementById('pw_confirm').value;
        var errBox  = document.getElementById('pw-error');

        if (newPwd.length < 6) {
            errBox.textContent = pgMsgMin;
            errBox.classList.remove('d-none');
            return false;
        }
        if (newPwd !== confPwd) {
            errBox.textContent = pgMsgMismatch;
            errBox.classList.remove('d-none');
            return false;
        }
        errBox.classList.add('d-none');
        return true;
    }
</script>

<?= $this->endSection() ?>
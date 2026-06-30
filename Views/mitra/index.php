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
        'dashboard'       => 'Dashboard',
        'mitra'           => 'Mitra',
        'congrats'        => 'Selamat!',
        'error'           => 'Warning Error!',
        'register_mitra'  => 'Daftarkan Mitra',
        'th_no'           => '#',
        'th_nama_mitra'   => 'Nama Mitra',
        'th_kontak'       => 'Kontak',
        'th_kebutuhan'    => 'Kebutuhan',
        'th_alamat'       => 'Alamat',
        'th_action'       => 'Action',
        'contact_person'  => 'Contact Person',
        'email'           => 'Email',
        'update'          => 'Perbaharui',
        'delete_title'    => 'Hapus data?',
        'delete_msg'      => 'Apakah anda yakin?',
        'edit_password'   => 'Edit Password',
        'modal_title'     => 'Ganti Password Mitra',
        'new_password'    => 'Password Baru',
        'confirm_password'=> 'Konfirmasi Password',
        'cancel'          => 'Batal',
        'save'            => 'Simpan',
        'password_min'    => 'Password minimal 6 karakter.',
        'password_mismatch' => 'Password dan konfirmasi tidak cocok.',
    ],
    'en' => [
        'dashboard'       => 'Dashboard',
        'mitra'           => 'Partner',
        'congrats'        => 'Congratulations!',
        'error'           => 'Warning Error!',
        'register_mitra'  => 'Register Partner',
        'th_no'           => '#',
        'th_nama_mitra'   => 'Partner Name',
        'th_kontak'       => 'Contact',
        'th_kebutuhan'    => 'Needs',
        'th_alamat'       => 'Address',
        'th_action'       => 'Action',
        'contact_person'  => 'Contact Person',
        'email'           => 'Email',
        'update'          => 'Update',
        'delete_title'    => 'Delete data?',
        'delete_msg'      => 'Are you sure?',
        'edit_password'   => 'Edit Password',
        'modal_title'     => 'Change Partner Password',
        'new_password'    => 'New Password',
        'confirm_password'=> 'Confirm Password',
        'cancel'          => 'Cancel',
        'save'            => 'Save',
        'password_min'    => 'Password must be at least 6 characters.',
        'password_mismatch' => 'Password and confirmation do not match.',
    ],
];

$t = $dict[$lang] ?? $dict['id'];

// Helper function for translation
function translateMitraText(string $text, string $source, string $target): string
{
    $text = trim($text);
    if ($text === '' || $source === $target) {
        return $text;
    }

    $key = $_ENV['apiKeyGoogleTranslateApi'] ?? null;
    if (! $key) {
        return $text;
    }

    $client = new \Google\Cloud\Translate\V2\TranslateClient(['key' => $key]);

    $result = $client->translate($text, [
        'source' => $source,
        'target' => $target,
    ]);

    return $result['text'] ?? $text;
}
?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <h1><?= esc($t['mitra']); ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="<?= site_url('dashboard'); ?>"><?= esc($t['dashboard']); ?></a></div>
            <div class="breadcrumb-item"><?= esc($t['mitra']); ?></div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b><?= esc($t['congrats']); ?></b>
                <?= session()->getFlashdata('success'); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b><?= esc($t['error']); ?></b>
                <?= session()->getFlashdata('error'); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4 class="text-sm">
                    <a href="<?= site_url('mitra/new'); ?>" class="btn btn-primary"><i class="fas fa-plus-circle mr-1"></i><?= esc($t['register_mitra']); ?></a>
                </h4>
            </div>
            <div class="card-body">
                <div class="table-responsive-md">
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th><?= esc($t['th_no']); ?></th>
                                <th><?= esc($t['th_nama_mitra']); ?></th>
                                <th><?= esc($t['th_kontak']); ?></th>
                                <th><?= esc($t['th_kebutuhan']); ?></th>
                                <th><?= esc($t['th_alamat']); ?></th>
                                <th class="text-center"><?= esc($t['th_action']); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $page = isset($_GET['page']) ? $_GET['page'] : 1;
                            $no = 1 + (10 * ($page - 1));

                            // Translate fields if EN
                            $translatedFields = [];
                            if ($lang === 'en') {
                                foreach ($mitra as $v_mitra) {
                                    if ($v_mitra->role_id == 5) {
                                        $keyProv = 'prov_mitra_' . md5($v_mitra->provinsi_name);
                                        $keyKota = 'kota_mitra_' . md5($v_mitra->kota_name);
                                        $keyKebutuhan = 'keb_mitra_' . md5($v_mitra->kebutuhan);
                                        $keyAlamat = 'alm_mitra_' . md5($v_mitra->alamat);

                                        // Translate provinsi
                                        $translatedProv = cache()->get($keyProv);
                                        if (!$translatedProv && $v_mitra->provinsi_name) {
                                            $translatedProv = translateMitraText($v_mitra->provinsi_name, 'id', 'en');
                                            cache()->save($keyProv, $translatedProv, 86400 * 30);
                                        }

                                        // Translate kota
                                        $translatedKota = cache()->get($keyKota);
                                        if (!$translatedKota && $v_mitra->kota_name) {
                                            $translatedKota = translateMitraText($v_mitra->kota_name, 'id', 'en');
                                            cache()->save($keyKota, $translatedKota, 86400 * 30);
                                        }

                                        // Translate kebutuhan
                                        $translatedKebutuhan = cache()->get($keyKebutuhan);
                                        if (!$translatedKebutuhan && $v_mitra->kebutuhan) {
                                            $translatedKebutuhan = translateMitraText($v_mitra->kebutuhan, 'id', 'en');
                                            cache()->save($keyKebutuhan, $translatedKebutuhan, 86400 * 30);
                                        }

                                        // Translate alamat
                                        $translatedAlamat = cache()->get($keyAlamat);
                                        if (!$translatedAlamat && $v_mitra->alamat) {
                                            $translatedAlamat = translateMitraText($v_mitra->alamat, 'id', 'en');
                                            cache()->save($keyAlamat, $translatedAlamat, 86400 * 30);
                                        }

                                        $translatedFields[$v_mitra->user_id] = [
                                            'provinsi' => $translatedProv ?: $v_mitra->provinsi_name,
                                            'kota' => $translatedKota ?: $v_mitra->kota_name,
                                            'kebutuhan' => $translatedKebutuhan ?: $v_mitra->kebutuhan,
                                            'alamat' => $translatedAlamat ?: $v_mitra->alamat,
                                        ];
                                    }
                                }
                            }

                            foreach ($mitra as $users => $v_mitra) : ?>
                                <?php if ($v_mitra->role_id == 5): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td class="text-wrap" style="max-width:200px;"><?= $v_mitra->user_name; ?></td>
                                        <td>
                                            <?= esc($t['contact_person']); ?>: +<?= $v_mitra->kontak; ?> <br>
                                            <?= esc($t['email']); ?>: <?= $v_mitra->email; ?>
                                        </td>
                                        <td class="text-wrap" style="max-width:200px;">
                                            <?php if ($lang === 'en' && isset($translatedFields[$v_mitra->user_id])): ?>
                                                <?= $translatedFields[$v_mitra->user_id]['kebutuhan']; ?>
                                            <?php else: ?>
                                                <?= $v_mitra->kebutuhan; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-wrap" style="max-width:200px;">
                                            <?php if ($lang === 'en' && isset($translatedFields[$v_mitra->user_id])): ?>
                                                <?= $translatedFields[$v_mitra->user_id]['provinsi']; ?> - <?= $translatedFields[$v_mitra->user_id]['kota']; ?> <br>
                                                <?= $translatedFields[$v_mitra->user_id]['alamat']; ?>
                                            <?php else: ?>
                                                <?= $v_mitra->provinsi_name; ?> - <?= $v_mitra->kota_name; ?> <br>
                                                <?= $v_mitra->alamat; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?= site_url('mitra/' . $v_mitra->user_id . '/edit'); ?>" class="btn btn-warning btn-sm m-1" style="width:100px;"><i class="fas fa-pencil-alt"></i> <?= esc($t['update']); ?></a><br>
                                            <button type="button"
                                                class="btn btn-info btn-sm m-1"
                                                style="width:100px;"
                                                onclick="openPasswordModal(<?= $v_mitra->user_id; ?>, '<?= esc(addslashes($v_mitra->user_name)); ?>')"
                                            >
                                                <i class="fas fa-key"></i> <?= esc($t['edit_password']); ?>
                                            </button><br>
                                            <?php if (userLogin()->role_id == 4): ?>
                                                <form action="<?= site_url('mitra/' . $v_mitra->user_id); ?>" method="POST" class="d-inline" id="del-<?= $v_mitra->user_id; ?>">
                                                    <?= csrf_field(); ?>
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button class="btn btn-danger btn-sm m-1" hidden style="width:100px;" data-confirm="<?= esc($t['delete_title']); ?> | <?= esc($t['delete_msg']); ?>" data-confirm-yes="submitDel(<?= $v_mitra->user_id; ?>)">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            <?php elseif (userLogin()->role_id != 4): ?>
                                                <form action="<?= site_url('mitra/' . $v_mitra->user_id); ?>" method="POST" class="d-inline" id="del-<?= $v_mitra->user_id; ?>">
                                                    <?= csrf_field(); ?>
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button class="btn btn-danger btn-sm m-1" style="width:100px;" data-confirm="<?= esc($t['delete_title']); ?> | <?= esc($t['delete_msg']); ?>" data-confirm-yes="submitDel(<?= $v_mitra->user_id; ?>)">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            <?php endif; ?>
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

<!-- Modal Edit Password Mitra -->
<div class="modal fade" id="modalEditPassword" tabindex="-1" role="dialog" aria-labelledby="modalEditPasswordLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #17a2b8, #117a8b);">
                <h5 class="modal-title text-white" id="modalEditPasswordLabel">
                    <i class="fas fa-key mr-2"></i><?= esc($t['modal_title']); ?>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEditPassword" method="POST" action="" onsubmit="return validatePasswordForm()">
                <?= csrf_field(); ?>
                <div class="modal-body">
                    <p class="text-muted mb-3">
                        <i class="fas fa-user-circle mr-1"></i>
                        Mitra: <strong id="mitraNameLabel"></strong>
                    </p>
                    <div class="form-group">
                        <label for="new_password"><?= esc($t['new_password']); ?> <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="new_password" name="new_password"
                                placeholder="Min. 6 karakter" required minlength="6">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility('new_password', 'icon_new')">
                                    <i class="fas fa-eye" id="icon_new"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password"><?= esc($t['confirm_password']); ?> <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                                placeholder="Ulangi password baru" required minlength="6">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility('confirm_password', 'icon_confirm')">
                                    <i class="fas fa-eye" id="icon_confirm"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="password-error" class="alert alert-danger d-none" role="alert"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i><?= esc($t['cancel']); ?>
                    </button>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-save mr-1"></i><?= esc($t['save']); ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var baseUrl = '<?= site_url(); ?>';
    var msgMin  = '<?= esc($t['password_min']); ?>';
    var msgMismatch = '<?= esc($t['password_mismatch']); ?>';

    function openPasswordModal(mitraId, mitraName) {
        document.getElementById('mitraNameLabel').textContent = mitraName;
        document.getElementById('formEditPassword').action = baseUrl + 'mitra/update-password/' + mitraId;
        document.getElementById('new_password').value = '';
        document.getElementById('confirm_password').value = '';
        document.getElementById('password-error').classList.add('d-none');
        document.getElementById('password-error').textContent = '';
        $('#modalEditPassword').modal('show');
    }

    function togglePasswordVisibility(inputId, iconId) {
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

    function validatePasswordForm() {
        var newPwd  = document.getElementById('new_password').value;
        var confPwd = document.getElementById('confirm_password').value;
        var errBox  = document.getElementById('password-error');

        if (newPwd.length < 6) {
            errBox.textContent = msgMin;
            errBox.classList.remove('d-none');
            return false;
        }
        if (newPwd !== confPwd) {
            errBox.textContent = msgMismatch;
            errBox.classList.remove('d-none');
            return false;
        }
        errBox.classList.add('d-none');
        return true;
    }
</script>

<?= $this->endSection() ?>
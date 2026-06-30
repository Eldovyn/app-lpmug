<?php
// =====================
// I18N (cookie-based)
// =====================
$request = service('request'); // CI4 request service :contentReference[oaicite:2]{index=2}
$lang = $request->getCookie('lang') ?? 'id'; // IncomingRequest::getCookie() :contentReference[oaicite:3]{index=3}
if (!in_array($lang, ['id', 'en'], true)) {
    $lang = 'id';
}

// Optional fallback title_tab kalau controller belum set
if (!isset($title_tab) || $title_tab === null || $title_tab === '') {
    $title_tab = ($lang === 'en') ? 'Registration &mdash; LPM UG' : 'Registrasi &mdash; LPM UG';
}

$T = [
    'id' => [
        'error_title' => 'Peringatan Error!',
        'page_title'  => 'Silahkan daftarkan diri anda',
        'important_badge' => 'Keterangan Penting',

        // Instruction items (HTML allowed)
        'inst_1' => '<strong>▶</strong> Daftar sebagai <strong class="text-primary-gradient">Dosen</strong> gunakan <strong>NIDN / NUPTK</strong>.',
        'inst_2' => '<strong>▶</strong> Daftar sebagai <strong class="text-primary-gradient">Mitra</strong> gunakan <strong>Username</strong>.',
        'inst_3' => '<strong>▶</strong> Untuk <strong>SINTA ID</strong> hanya untuk <strong class="text-primary-gradient">Dosen</strong>.',
        'inst_4' => '<strong>▶</strong> Bagi <strong class="text-primary-gradient">Dosen</strong> yang belum memiliki <strong>SINTA ID</strong> diisi dengan <strong>7 Digit Terakhir No.Telp/WhatsApp</strong>.',
        'inst_5' => '<strong>▶</strong> Untuk <strong class="text-primary-gradient">Mitra</strong> pada bagian <strong>SINTA ID</strong> diisi dengan <strong>7 Digit Terakhir No.Telp/WhatsApp</strong>.',
        'inst_6' => '<strong>▶</strong> Gunakan <strong>Password</strong> yang mudah diingat, terdiri dari <strong>6 Karakter</strong> boleh menggunakan kombinasi <strong>Huruf, Angka, Simbol</strong>, disarankan <strong>tidak menggunakan huruf kapital</strong>.',
        'inst_7' => '<strong>▶</strong> <strong>Username</strong> tidak boleh menggunakan huruf Kapital.',

        // Form labels/placeholders
        'label_nidn' => 'Username / NIDN (NUPTK)',
        'badge_mitra' => 'Mitra: Username',
        'badge_dosen' => 'Dosen: NIDN/NUPTK',
        'ph_nidn' => 'Masukkan Username/NIDN',

        'label_sinta' => 'SINTA ID',
        'ph_sinta' => 'SINTA ID atau 7 digit terakhir No.Telp',
        'sinta_help' => 'Jika belum punya SINTA ID, isi dengan 7 digit terakhir No.Telp/WhatsApp',

        'label_name' => 'Nama Lengkap',
        'ph_name' => 'Masukkan nama lengkap Anda',

        'label_email' => 'Email',
        'ph_email' => 'contoh@email.com',

        'label_pass' => 'Password',
        'pass_rule' => 'Min. 6 Karakter (Huruf+Angka+Simbol)',
        'ph_pass' => 'Masukkan password',

        'label_pass_conf' => 'Konfirmasi Password',
        'ph_pass_conf' => 'Ulangi password',

        'label_role' => 'Mendaftar sebagai?',
        'role_dosen' => 'Dosen',
        'role_mitra' => 'Mitra',

        'label_field' => 'Bidang Ilmu',
        'opt_field' => 'Pilih Bidang Ilmu',

        'label_contact' => 'Kontak',
        'ph_contact' => 'Nomor telepon/WhatsApp',

        'label_city' => 'Kota',
        'opt_city' => 'Pilih Kota',

        'label_address' => 'Alamat',
        'ph_address' => 'Alamat lengkap',

        'agree_terms' => 'Saya setuju dengan syarat dan ketentuan yang berlaku',

        'btn_register' => 'Daftar Sekarang',

        'already_account' => 'Sudah punya akun?',
        'login_here' => 'Login disini',
        'back_home' => 'Kembali ke Home',

        'processing' => 'Memproses...',
    ],
    'en' => [
        'error_title' => 'Error Warning!',
        'page_title'  => 'Please register yourself',
        'important_badge' => 'Important Notes',

        // Instruction items (HTML allowed)
        'inst_1' => '<strong>▶</strong> Register as <strong class="text-primary-gradient">Lecturer</strong> using <strong>NIDN / NUPTK</strong>.',
        'inst_2' => '<strong>▶</strong> Register as <strong class="text-primary-gradient">Partner</strong> using <strong>Username</strong>.',
        'inst_3' => '<strong>▶</strong> <strong>SINTA ID</strong> is only for <strong class="text-primary-gradient">Lecturers</strong>.',
        'inst_4' => '<strong>▶</strong> For <strong class="text-primary-gradient">Lecturers</strong> without a <strong>SINTA ID</strong>, fill it with the <strong>last 7 digits of your phone/WhatsApp number</strong>.',
        'inst_5' => '<strong>▶</strong> For <strong class="text-primary-gradient">Partners</strong>, in the <strong>SINTA ID</strong> field, fill it with the <strong>last 7 digits of your phone/WhatsApp number</strong>.',
        'inst_6' => '<strong>▶</strong> Use an easy-to-remember <strong>Password</strong>, at least <strong>6 characters</strong>. You may use a combination of <strong>letters, numbers, symbols</strong>. It is recommended <strong>not to use uppercase letters</strong>.',
        'inst_7' => '<strong>▶</strong> <strong>Username</strong> must not contain uppercase letters.',

        // Form labels/placeholders
        'label_nidn' => 'Username / NIDN (NUPTK)',
        'badge_mitra' => 'Partner: Username',
        'badge_dosen' => 'Lecturer: NIDN/NUPTK',
        'ph_nidn' => 'Enter Username/NIDN',

        'label_sinta' => 'SINTA ID',
        'ph_sinta' => 'SINTA ID or last 7 digits of phone',
        'sinta_help' => "If you don't have a SINTA ID, fill with the last 7 digits of your phone/WhatsApp number",

        'label_name' => 'Full Name',
        'ph_name' => 'Enter your full name',

        'label_email' => 'Email',
        'ph_email' => 'example@email.com',

        'label_pass' => 'Password',
        'pass_rule' => 'Min. 6 chars (Letters+Numbers+Symbols)',
        'ph_pass' => 'Enter password',

        'label_pass_conf' => 'Confirm Password',
        'ph_pass_conf' => 'Re-enter password',

        'label_role' => 'Register as?',
        'role_dosen' => 'Lecturer',
        'role_mitra' => 'Partner',

        'label_field' => 'Field of Study',
        'opt_field' => 'Select Field of Study',

        'label_contact' => 'Contact',
        'ph_contact' => 'Phone/WhatsApp number',

        'label_city' => 'City',
        'opt_city' => 'Select City',

        'label_address' => 'Address',
        'ph_address' => 'Full address',

        'agree_terms' => 'I agree to the applicable terms and conditions',

        'btn_register' => 'Register Now',

        'already_account' => 'Already have an account?',
        'login_here' => 'Login here',
        'back_home' => 'Back to Home',

        'processing' => 'Processing...',
    ],
];

// FIX scope view CI4: pakai $GLOBALS supaya fungsi L() selalu bisa akses variabel :contentReference[oaicite:4]{index=4}
$GLOBALS['lang'] = $lang;
$GLOBALS['T'] = $T;

function L(string $key): string
{
    $lang = $GLOBALS['lang'] ?? 'id';
    $T = $GLOBALS['T'] ?? [];
    if (isset($T[$lang]) && array_key_exists($key, $T[$lang])) {
        return $T[$lang][$key];
    }
    return $key;
}
?>

<?= $this->extend('layouts/default_auth') ?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Custom CSS for Enhanced Registration Page -->
<style>
    .registration-container {
        animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .card-primary {
        border: none;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        border-radius: 15px;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card-primary:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.15);
    }

    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border: none;
    }

    .card-header h4 {
        margin: 0;
        font-weight: 600;
        font-size: 1.5rem;
        color: #ffffff !important;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .info-badge {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        padding: 3px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 10px;
        box-shadow: 0 2px 10px rgba(240, 147, 251, 0.3);
    }

    .alert-warning {
        background: linear-gradient(135deg, #fff3cd 0%, #ffe5a1 100%);
        border: none;
        border-radius: 12px;
        border-left: 4px solid #ffc107;
        animation: slideIn 0.5s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .alert-danger {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c2c7 100%);
        border: none;
        border-radius: 12px;
        border-left: 4px solid #dc3545;
        animation: shake 0.5s ease-out;
    }

    @keyframes shake {

        0%,
        100% {
            transform: translateX(0);
        }

        25% {
            transform: translateX(-10px);
        }

        75% {
            transform: translateX(10px);
        }
    }

    .form-control {
        border-radius: 10px;
        border: 2px solid #e0e6ed;
        padding: 12px 15px;
        transition: all 0.3s ease;
        font-size: 0.95rem;
        height: 48px;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        transform: translateY(-2px);
    }

    .form-control.is-invalid {
        border-color: #dc3545;
    }

    .form-control.is-invalid:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }

    .input-group-append .btn {
        height: 48px;
        border: 2px solid #e0e6ed;
        border-left: none;
    }

    .input-group .form-control {
        border-right: none;
        border-radius: 10px 0 0 10px !important;
    }

    .input-group-append .btn:hover {
        background-color: #f8f9fa;
        border-color: #667eea;
    }

    .form-group label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 8px;
        font-size: 0.9rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
        animation: fadeIn 0.5s ease-out backwards;
    }

    .form-group:nth-child(1) {
        animation-delay: 0.1s;
    }

    .form-group:nth-child(2) {
        animation-delay: 0.2s;
    }

    .form-group:nth-child(3) {
        animation-delay: 0.3s;
    }

    .form-group:nth-child(4) {
        animation-delay: 0.4s;
    }

    .form-group:nth-child(5) {
        animation-delay: 0.5s;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .selectgroup-item {
        flex: 1;
        margin-bottom: 0;
    }

    .selectgroup-button {
        border-radius: 10px;
        padding: 15px 20px;
        border: 2px solid #e0e6ed;
        transition: all 0.3s ease;
        font-weight: 600;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 56px;
        font-size: 1rem;
    }

    .selectgroup-button i {
        margin-right: 8px;
    }

    .selectgroup-input:checked+.selectgroup-button {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: #667eea;
        color: white;
        transform: scale(1.02);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }

    .selectgroup-button:hover {
        border-color: #667eea;
        transform: translateY(-2px);
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 10px;
        padding: 15px;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .btn-primary:before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .btn-primary:hover:before {
        width: 300px;
        height: 300px;
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
    }

    .btn-primary:active {
        transform: translateY(-1px);
    }

    .custom-control-label {
        cursor: pointer;
        user-select: none;
        padding-left: 5px;
    }

    .custom-control-input:checked~.custom-control-label::before {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: #667eea;
    }

    .instruction-item {
        padding: 8px 0;
        border-left: 3px solid transparent;
        padding-left: 10px;
        margin-left: -10px;
        transition: all 0.3s ease;
    }

    .instruction-item:hover {
        border-left-color: #667eea;
        padding-left: 15px;
        background: rgba(102, 126, 234, 0.05);
    }

    .password-strength-indicator {
        margin-top: 10px;
    }

    .footer-copyright {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        padding: 20px;
        border-radius: 10px;
        margin-top: 20px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }

    .text-primary-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 700;
    }

    /* Tooltip styling */
    .info-icon {
        display: inline-block;
        width: 18px;
        height: 18px;
        background: #667eea;
        color: white;
        border-radius: 50%;
        text-align: center;
        line-height: 18px;
        font-size: 12px;
        cursor: help;
        margin-left: 5px;
        transition: transform 0.3s ease;
    }

    .info-icon:hover {
        transform: scale(1.2) rotate(180deg);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .card-header h4 {
            font-size: 1.2rem;
        }

        .btn-primary {
            font-size: 1rem;
            padding: 12px;
        }
    }
</style>

<section class="section">
    <div class="container mt-5 registration-container">
        <div class="row">
            <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-8 offset-xl-2">

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible show fade">
                        <div class="alert-body">
                            <button class="close" data-dismiss="alert">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <strong><i class="fas fa-exclamation-triangle"></i> <?= L('error_title') ?></strong>
                            <p class="mb-0 mt-2"><?= session()->getFlashdata('error'); ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="card card-primary">
                    <div class="card-header mb-0">
                        <h4><i class="fas fa-user-plus"></i> <?= L('page_title') ?></h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="alert alert-warning">
                            <div class="info-badge">
                                <i class="fas fa-info-circle"></i> <?= L('important_badge') ?>
                            </div>
                            <p class="text-small text-dark mb-2" style="line-height: 1.8;">
                                <span class="instruction-item d-block"><?= L('inst_1') ?></span>
                                <span class="instruction-item d-block"><?= L('inst_2') ?></span>
                                <span class="instruction-item d-block"><?= L('inst_3') ?></span>
                                <span class="instruction-item d-block"><?= L('inst_4') ?></span>
                                <span class="instruction-item d-block"><?= L('inst_5') ?></span>
                                <span class="instruction-item d-block"><?= L('inst_6') ?></span>
                                <span class="instruction-item d-block"><?= L('inst_7') ?></span>
                            </p>
                        </div>

                        <form method="POST" action="<?= site_url('auth/registrasiProcess'); ?>" autocomplete="off" id="registrationForm">
                            <?= csrf_field(); ?>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="nidn" class="d-block">
                                        <i class="fas fa-id-card"></i> <?= L('label_nidn') ?>
                                        <small class="text-danger">*</small>
                                    </label>
                                    <div class="mb-2">
                                        <span class="badge badge-info badge-pill"><?= L('badge_mitra') ?></span>
                                        <span class="badge badge-primary badge-pill ml-1"><?= L('badge_dosen') ?></span>
                                    </div>
                                    <input id="nidn" type="text" name="nidn" value="<?= set_value('nidn'); ?>"
                                        class="form-control <?= (session('validation') && session('validation')->hasError('nidn')) ? 'is-invalid' : ''; ?>"
                                        placeholder="<?= L('ph_nidn') ?>" autofocus>
                                    <div class="invalid-feedback">
                                        <?php if (session('validation') && session('validation')->hasError('nidn')): ?>
                                            <?= session('validation')->getError('nidn'); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="sinta_id" class="d-block">
                                        <i class="fas fa-fingerprint"></i> <?= L('label_sinta') ?> <small class="text-danger">*</small>
                                    </label>
                                    <input id="sinta_id" type="text" value="<?= set_value('sinta_id'); ?>"
                                        class="form-control <?= (session('validation') && session('validation')->hasError('sinta_id')) ? 'is-invalid' : ''; ?>"
                                        name="sinta_id" placeholder="<?= L('ph_sinta') ?>">
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> <?= L('sinta_help') ?>
                                    </small>
                                    <div class="invalid-feedback">
                                        <?php if (session('validation') && session('validation')->hasError('sinta_id')): ?>
                                            <?= session('validation')->getError('sinta_id'); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="user_name">
                                    <i class="fas fa-user"></i> <?= L('label_name') ?> <small class="text-danger">*</small>
                                </label>
                                <input id="user_name" type="text" value="<?= set_value('user_name'); ?>"
                                    class="form-control <?= (session('validation') && session('validation')->hasError('user_name')) ? 'is-invalid' : ''; ?>"
                                    name="user_name" placeholder="<?= L('ph_name') ?>">
                                <div class="invalid-feedback">
                                    <?php if (session('validation') && session('validation')->hasError('user_name')): ?>
                                        <?= session('validation')->getError('user_name'); ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email">
                                    <i class="fas fa-envelope"></i> <?= L('label_email') ?> <small class="text-danger">*</small>
                                </label>
                                <input id="email" type="email" value="<?= set_value('email'); ?>"
                                    class="form-control <?= (session('validation') && session('validation')->hasError('email')) ? 'is-invalid' : ''; ?>"
                                    name="email" placeholder="<?= L('ph_email') ?>">
                                <div class="invalid-feedback">
                                    <?php if (session('validation') && session('validation')->hasError('email')): ?>
                                        <?= session('validation')->getError('email'); ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="password" class="d-block">
                                        <i class="fas fa-lock"></i> <?= L('label_pass') ?> <small class="text-danger">*</small>
                                    </label>
                                    <small class="badge badge-primary mb-2 d-block" style="width: fit-content;">
                                        <?= L('pass_rule') ?>
                                    </small>
                                    <div class="input-group">
                                        <input id="password" type="password"
                                            class="form-control pwstrength <?= (session('validation') && session('validation')->hasError('password')) ? 'is-invalid' : ''; ?>"
                                            data-indicator="pwindicator" name="password" placeholder="<?= L('ph_pass') ?>">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" id="togglePassword" style="border-radius: 0 10px 10px 0;">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        <div class="invalid-feedback">
                                            <?php if (session('validation') && session('validation')->hasError('password')): ?>
                                                <?= session('validation')->getError('password'); ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div id="pwindicator" class="pwindicator">
                                        <div class="bar"></div>
                                        <div class="label"></div>
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="password_konfirmasi" class="d-block">
                                        <i class="fas fa-lock"></i> <?= L('label_pass_conf') ?> <small class="text-danger">*</small>
                                    </label>
                                    <small class="badge badge-transparent mb-2 d-block" style="visibility: hidden;">Min. 6 Karakter (Huruf+Angka+Simbol)</small>
                                    <div class="input-group">
                                        <input id="password_konfirmasi" type="password"
                                            class="form-control <?= (session('validation') && session('validation')->hasError('password_konfirmasi')) ? 'is-invalid' : ''; ?>"
                                            name="password_konfirmasi" placeholder="<?= L('ph_pass_conf') ?>">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm" style="border-radius: 0 10px 10px 0;">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        <div class="invalid-feedback">
                                            <?php if (session('validation') && session('validation')->hasError('password_konfirmasi')): ?>
                                                <?= session('validation')->getError('password_konfirmasi'); ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label d-block mb-3">
                                    <i class="fas fa-user-tag"></i> <?= L('label_role') ?> <small class="text-danger">*</small>
                                </label>
                                <div class="selectgroup w-100">
                                    <label class="selectgroup-item mb-0">
                                        <input type="radio" name="role_id" value="4" class="selectgroup-input" id="role_dosen">
                                        <span class="selectgroup-button">
                                            <i class="fas fa-chalkboard-teacher"></i> <?= L('role_dosen') ?>
                                        </span>
                                    </label>
                                    <label class="selectgroup-item mb-0">
                                        <input type="radio" name="role_id" value="5" class="selectgroup-input" id="role_mitra">
                                        <span class="selectgroup-button">
                                            <i class="fas fa-handshake"></i> <?= L('role_mitra') ?>
                                        </span>
                                    </label>
                                </div>
                                <?php if (session('validation') && session('validation')->hasError('role_id')): ?>
                                    <small class="text-danger d-block mt-2"><?= session('validation')->getError('role_id'); ?></small>
                                <?php endif; ?>
                            </div>

                            <!-- Role-specific fields -->
                            <div id="dosen-fields" class="role-fields" style="display: none;">
                                <div class="form-group">
                                    <label for="jurusan_id">
                                        <i class="fas fa-graduation-cap"></i> <?= L('label_field') ?> <small class="text-danger">*</small>
                                    </label>
                                    <select id="jurusan_id" name="jurusan_id" class="form-control <?= (session('validation') && session('validation')->hasError('jurusan_id')) ? 'is-invalid' : ''; ?>">
                                        <option value=""><?= L('opt_field') ?></option>
                                        <?php foreach ($jurusan as $j): ?>
                                            <option value="<?= $j->jurusan_id; ?>" <?= set_value('jurusan_id') == $j->jurusan_id ? 'selected' : ''; ?>>
                                                <?= $j->fakultas_name; ?> - <?= $j->jurusan_name; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        <?php if (session('validation') && session('validation')->hasError('jurusan_id')): ?>
                                            <?= session('validation')->getError('jurusan_id'); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div id="mitra-fields" class="role-fields" style="display: none;">
                                <div class="form-group">
                                    <label for="kontak">
                                        <i class="fas fa-phone"></i> <?= L('label_contact') ?> <small class="text-danger">*</small>
                                    </label>
                                    <input id="kontak" type="text" value="<?= set_value('kontak'); ?>"
                                        class="form-control <?= (session('validation') && session('validation')->hasError('kontak')) ? 'is-invalid' : ''; ?>"
                                        name="kontak" placeholder="<?= L('ph_contact') ?>">
                                    <div class="invalid-feedback">
                                        <?php if (session('validation') && session('validation')->hasError('kontak')): ?>
                                            <?= session('validation')->getError('kontak'); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="kota_id">
                                        <i class="fas fa-map-marker-alt"></i> <?= L('label_city') ?> <small class="text-danger">*</small>
                                    </label>
                                    <select id="kota_id" name="kota_id" class="form-control <?= (session('validation') && session('validation')->hasError('kota_id')) ? 'is-invalid' : ''; ?>">
                                        <option value=""><?= L('opt_city') ?></option>
                                        <?php foreach ($kota as $k): ?>
                                            <option value="<?= $k->kota_id; ?>" <?= set_value('kota_id') == $k->kota_id ? 'selected' : ''; ?>>
                                                <?= $k->provinsi_name; ?> - <?= $k->kota_name; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        <?php if (session('validation') && session('validation')->hasError('kota_id')): ?>
                                            <?= session('validation')->getError('kota_id'); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="alamat">
                                        <i class="fas fa-home"></i> <?= L('label_address') ?> <small class="text-danger">*</small>
                                    </label>
                                    <textarea id="alamat" class="form-control <?= (session('validation') && session('validation')->hasError('alamat')) ? 'is-invalid' : ''; ?>"
                                        name="alamat" rows="3" placeholder="<?= L('ph_address') ?>"><?= set_value('alamat'); ?></textarea>
                                    <div class="invalid-feedback">
                                        <?php if (session('validation') && session('validation')->hasError('alamat')): ?>
                                            <?= session('validation')->getError('alamat'); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="syarat"
                                        class="custom-control-input <?= (session('validation') && session('validation')->hasError('syarat')) ? 'is-invalid' : ''; ?>"
                                        id="syarat">
                                    <label class="custom-control-label" for="syarat">
                                        <?= L('agree_terms') ?>
                                    </label>
                                    <div class="invalid-feedback">
                                        <?php if (session('validation') && session('validation')->hasError('syarat')): ?>
                                            <?= session('validation')->getError('syarat'); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                    <i class="fas fa-user-plus"></i> <?= L('btn_register') ?>
                                </button>
                            </div>

                            <div class="text-center mt-4">
                                <p class="text-muted">
                                    <?= L('already_account') ?> <a href="<?= site_url('login'); ?>" class="font-weight-bold text-primary-gradient"><?= L('login_here') ?></a>
                                </p>
                                <p class="text-muted">
                                    <a href="<?= site_url(); ?>" class="font-weight-bold text-primary-gradient">
                                        <i class="fas fa-home"></i> <?= L('back_home') ?>
                                    </a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="footer-copyright text-center">
                    <p class="mb-0 text-muted">
                        Copyright &copy; <?php echo date("Y"); ?> &mdash;
                        <a href="#" class="font-weight-bold text-primary-gradient">LPM UG</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Enhanced JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');

        if (togglePassword) {
            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
        }

        const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
        const passwordConfirm = document.getElementById('password_konfirmasi');

        if (togglePasswordConfirm) {
            togglePasswordConfirm.addEventListener('click', function() {
                const type = passwordConfirm.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordConfirm.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
        }

        // Role selection toggle
        const roleDosen = document.getElementById('role_dosen');
        const roleMitra = document.getElementById('role_mitra');
        const dosenFields = document.getElementById('dosen-fields');
        const mitraFields = document.getElementById('mitra-fields');

        function toggleFields() {
            if (roleDosen.checked) {
                dosenFields.style.display = 'block';
                mitraFields.style.display = 'none';
            } else if (roleMitra.checked) {
                dosenFields.style.display = 'none';
                mitraFields.style.display = 'block';
            } else {
                dosenFields.style.display = 'none';
                mitraFields.style.display = 'none';
            }
        }

        roleDosen.addEventListener('change', toggleFields);
        roleMitra.addEventListener('change', toggleFields);

        // Form submission animation
        const form = document.getElementById('registrationForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <?= L('processing') ?>';
                submitBtn.disabled = true;
            });
        }

        // Smooth scroll to error
        const invalidInputs = document.querySelectorAll('.is-invalid');
        if (invalidInputs.length > 0) {
            invalidInputs[0].scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
            invalidInputs[0].focus();
        }
    });
</script>

<?= $this->endSection() ?>
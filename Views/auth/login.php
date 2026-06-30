<?php
// Ambil bahasa dari cookie (default: id)
$request = service('request');
$lang = $request->getCookie('lang') ?? 'id';
if (!in_array($lang, ['id', 'en'], true)) {
    $lang = 'id';
}

// Optional fallback kalau controller belum set title_tab
if (!isset($title_tab) || $title_tab === null || $title_tab === '') {
    $title_tab = ($lang === 'en') ? 'Login &mdash; LPM UG' : 'Login &mdash; LPM UG';
}

// Kamus teks (ID/EN)
$T = [
    'id' => [
        'success_title' => 'Selamat!',
        'error_title'   => 'Peringatan Error!',
        'login_title'   => 'Silahkan Login',
        'info_title'    => 'Keterangan!',
        'info_text'     => 'Gunakan <strong>NIDN</strong> untuk login sebagai <strong>Dosen</strong> dan <strong>Username</strong> untuk login sebagai <strong>Mitra</strong>.',
        'label_user'    => 'NIDN / Username',
        'ph_user'       => 'Masukkan NIDN atau Username',
        'inv_user'      => 'Mohon isi NIDN (Dosen) / Username (Mitra)',
        'label_pass'    => 'Password',
        'ph_pass'       => 'Masukkan password Anda',
        'inv_pass'      => 'Mohon isi password anda',
        'btn_login'     => 'Masuk Sekarang',
        'no_account'    => 'Belum punya akun?',
        'register_here' => 'Buat disini',
        'back_home'     => 'Kembali ke Home',
        'processing'    => 'Memproses...',
    ],
    'en' => [
        'success_title' => 'Success!',
        'error_title'   => 'Error Warning!',
        'login_title'   => 'Please Login',
        'info_title'    => 'Information!',
        'info_text'     => 'Use <strong>NIDN</strong> to login as <strong>Lecturer</strong> and <strong>Username</strong> to login as <strong>Partner</strong>.',
        'label_user'    => 'NIDN / Username',
        'ph_user'       => 'Enter NIDN or Username',
        'inv_user'      => 'Please enter NIDN (Lecturer) / Username (Partner)',
        'label_pass'    => 'Password',
        'ph_pass'       => 'Enter your password',
        'inv_pass'      => 'Please enter your password',
        'btn_login'     => 'Login Now',
        'no_account'    => "Don't have an account?",
        'register_here' => 'Create here',
        'back_home'     => 'Back to Home',
        'processing'    => 'Processing...',
    ],
];

// FIX: karena view CI4 tidak berada di global scope, simpan ke $GLOBALS
$GLOBALS['lang'] = $lang;
$GLOBALS['T']    = $T;

function L(string $key): string
{
    $lang = $GLOBALS['lang'] ?? 'id';
    $T    = $GLOBALS['T'] ?? [];

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

<!-- Custom CSS for Enhanced Login Page -->
<style>
    .login-container {
        animation: fadeInUp 0.6s ease-out;
        min-height: 100vh;
        display: flex;
        align-items: center;
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
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.12);
        border-radius: 20px;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background: white;
    }

    .card-primary:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.18);
    }

    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 35px 30px;
        border: none;
        position: relative;
        overflow: hidden;
    }

    .card-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        animation: pulse 3s ease-in-out infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            transform: scale(1);
            opacity: 0.5;
        }

        50% {
            transform: scale(1.1);
            opacity: 0.8;
        }
    }

    .card-header h4 {
        margin: 0;
        font-weight: 700;
        font-size: 1.8rem;
        color: #ffffff !important;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .card-header h4 i {
        margin-right: 12px;
        font-size: 2rem;
        animation: bounce 2s ease-in-out infinite;
    }

    @keyframes bounce {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-10px);
        }
    }

    .card-body {
        padding: 40px 35px;
    }

    .alert {
        border: none;
        border-radius: 15px;
        padding: 18px 22px;
        animation: slideInDown 0.5s ease-out;
        margin-bottom: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-success {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        border-left: 5px solid #28a745;
        color: #155724;
    }

    .alert-danger {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c2c7 100%);
        border-left: 5px solid #dc3545;
        color: #721c24;
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

    .alert-warning {
        background: linear-gradient(135deg, #fff3cd 0%, #ffe5a1 100%);
        border: none;
        border-radius: 15px;
        border-left: 5px solid #ffc107;
        padding: 20px;
        margin-bottom: 30px;
    }

    .alert-warning b {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 1.05rem;
        color: #856404;
    }

    .alert-warning p {
        margin-top: 10px;
        margin-bottom: 0;
        line-height: 1.7;
        color: #856404;
    }

    .form-group {
        margin-bottom: 25px;
        animation: fadeIn 0.5s ease-out backwards;
        position: relative;
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

    .form-group label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 10px;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-group label i {
        color: #667eea;
        font-size: 1.1rem;
    }

    .form-control {
        border-radius: 12px;
        border: 2px solid #e0e6ed;
        padding: 14px 18px;
        transition: all 0.3s ease;
        font-size: 1rem;
        height: 52px;
        background: #f8f9fa;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
        transform: translateY(-2px);
        background: white;
    }

    .form-control:valid {
        border-color: #28a745;
    }

    .form-control.is-invalid,
    .form-control:invalid {
        border-color: #dc3545;
    }

    .invalid-feedback {
        display: block;
        margin-top: 8px;
        color: #dc3545;
        font-size: 0.875rem;
        font-weight: 500;
        animation: fadeIn 0.3s ease-out;
    }

    .input-icon-wrapper {
        position: relative;
    }

    .input-icon-wrapper .form-control {
        padding-right: 50px;
    }

    .toggle-password {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #667eea;
        cursor: pointer;
        font-size: 1.2rem;
        transition: all 0.3s ease;
        padding: 8px;
        z-index: 10;
    }

    .toggle-password:hover {
        color: #764ba2;
        transform: translateY(-50%) scale(1.2);
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 12px;
        padding: 16px;
        font-weight: 700;
        font-size: 1.15rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
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
        width: 400px;
        height: 400px;
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(102, 126, 234, 0.5);
    }

    .btn-primary:active {
        transform: translateY(-1px);
        box-shadow: 0 6px 15px rgba(102, 126, 234, 0.4);
    }

    .btn-primary i {
        margin-right: 10px;
        font-size: 1.2rem;
    }

    .text-muted a {
        color: #667eea;
        font-weight: 600;
        text-decoration: none;
        position: relative;
        transition: all 0.3s ease;
    }

    .text-muted a:hover {
        color: #764ba2;
    }

    .text-muted a::after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        bottom: -2px;
        left: 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        transition: width 0.3s ease;
    }

    .text-muted a:hover::after {
        width: 100%;
    }

    .footer-copyright {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        padding: 20px;
        border-radius: 15px;
        margin-top: 25px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        animation: fadeIn 0.8s ease-out;
    }

    .footer-copyright a {
        color: #667eea;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .footer-copyright a:hover {
        color: #764ba2;
    }

    /* Loading Spinner */
    .btn-loading {
        pointer-events: none;
        opacity: 0.7;
    }

    .spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid rgba(255, 255, 255, .3);
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .card-header h4 {
            font-size: 1.4rem;
        }

        .card-body {
            padding: 30px 25px;
        }

        .btn-primary {
            font-size: 1rem;
            padding: 14px;
        }
    }

    /* Focus ring animation */
    @keyframes focusRing {
        0% {
            box-shadow: 0 0 0 0 rgba(102, 126, 234, 0.4);
        }

        100% {
            box-shadow: 0 0 0 8px rgba(102, 126, 234, 0);
        }
    }

    .form-control:focus {
        animation: focusRing 0.6s ease-out;
    }
</style>

<section class="section">
    <div class="container login-container">
        <div class="row w-100">
            <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible show fade">
                        <div class="alert-body">
                            <button class="close" data-dismiss="alert">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <strong><i class="fas fa-check-circle"></i> <?= L('success_title') ?></strong>
                            <p class="mb-0 mt-2"><?= session()->getFlashdata('success'); ?></p>
                        </div>
                    </div>
                <?php endif; ?>

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
                        <h4><i class="fas fa-sign-in-alt"></i> <?= L('login_title') ?></h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <b class="text-dark">
                                <i class="fas fa-info-circle"></i> <?= L('info_title') ?>
                            </b>
                            <p class="text-small text-dark">
                                <?= L('info_text') ?>
                            </p>
                        </div>

                        <form method="POST" action="<?= site_url('auth/loginProcess'); ?>" class="needs-validation" novalidate="" id="loginForm">
                            <?= csrf_field(); ?>

                            <div class="form-group mb-3">
                                <label for="nidn">
                                    <i class="fas fa-user"></i> <?= L('label_user') ?>
                                </label>
                                <input id="nidn" type="text" class="form-control" name="nidn" value="<?= set_value('nidn'); ?>"
                                    placeholder="<?= L('ph_user') ?>" tabindex="1" required autofocus>
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle"></i> <?= L('inv_user') ?>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="password" class="control-label">
                                    <i class="fas fa-lock"></i> <?= L('label_pass') ?>
                                </label>
                                <div class="input-icon-wrapper">
                                    <input id="password" type="password" class="form-control" name="password"
                                        placeholder="<?= L('ph_pass') ?>" tabindex="2" required>
                                    <button type="button" class="toggle-password" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle"></i> <?= L('inv_pass') ?>
                                </div>
                            </div>

                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                    <i class="fas fa-sign-in-alt"></i> <?= L('btn_login') ?>
                                </button>
                            </div>

                            <div class="text-center mt-4">
                                <p class="text-muted mb-2">
                                    <?= L('no_account') ?> <a href="<?= site_url('registrasi'); ?>"><?= L('register_here') ?></a>
                                </p>
                                <p class="text-muted">
                                    <a href="<?= site_url(); ?>">
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
                        <a href="#">LPM UG</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Enhanced JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form validation
        const form = document.getElementById('loginForm');
        const nidnInput = document.getElementById('nidn');
        const passwordInput = document.getElementById('password');

        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        if (togglePassword) {
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                const icon = this.querySelector('i');
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');

                // Add pulse animation
                this.style.transform = 'translateY(-50%) scale(1.3)';
                setTimeout(() => {
                    this.style.transform = 'translateY(-50%) scale(1)';
                }, 200);
            });
        }

        // Form submission with loading state
        if (form) {
            form.addEventListener('submit', function(e) {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();

                    // Shake animation on invalid
                    form.classList.add('was-validated');

                    // Focus first invalid field
                    const invalidField = form.querySelector(':invalid');
                    if (invalidField) {
                        invalidField.focus();
                        invalidField.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
                } else {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    submitBtn.innerHTML = '<span class="spinner"></span> <?= L('processing') ?>';
                    submitBtn.classList.add('btn-loading');
                }
            });
        }

        // Auto-dismiss alerts after 5 seconds
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                const closeBtn = alert.querySelector('.close');
                if (closeBtn) {
                    closeBtn.click();
                }
            }, 5000);
        });

        // Input focus effects
        const inputs = document.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.01)';
            });

            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });

            // Real-time validation feedback
            input.addEventListener('input', function() {
                if (this.value.length > 0) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                } else {
                    this.classList.remove('is-valid');
                }
            });
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Alt + L to focus on login field
            if (e.altKey && e.key === 'l') {
                e.preventDefault();
                nidnInput.focus();
            }

            // Alt + P to focus on password field
            if (e.altKey && e.key === 'p') {
                e.preventDefault();
                passwordInput.focus();
            }
        });

        // Add ripple effect to button
        const button = document.querySelector('.btn-primary');
        if (button) {
            button.addEventListener('click', function(e) {
                const rect = this.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;

                const ripple = document.createElement('span');
                ripple.style.cssText = `
                position: absolute;
                width: 20px;
                height: 20px;
                border-radius: 50%;
                background: rgba(255,255,255,0.6);
                left: ${x}px;
                top: ${y}px;
                transform: translate(-50%, -50%);
                animation: ripple 0.6s ease-out;
                pointer-events: none;
            `;

                this.appendChild(ripple);

                setTimeout(() => ripple.remove(), 600);
            });
        }

        // Add CSS for ripple animation
        const style = document.createElement('style');
        style.textContent = `
        @keyframes ripple {
            to {
                transform: translate(-50%, -50%) scale(20);
                opacity: 0;
            }
        }
        .form-control.is-valid {
            border-color: #28a745 !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
    `;
        document.head.appendChild(style);
    });
</script>

<?= $this->endSection() ?>
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
        'label_captcha' => 'Keamanan: Berapa hasil',
        'ph_captcha'    => 'Masukkan hasil penjumlahan',
        'inv_captcha'   => 'Mohon isi jawaban keamanan',
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
        'label_captcha' => 'Security: What is the result of',
        'ph_captcha'    => 'Enter the sum',
        'inv_captcha'   => 'Please enter the security answer',
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

<!-- Tailwind CSS Login Implementation (Preflight Disabled to protect Bootstrap) -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        corePlugins: {
            preflight: false,
        }
    }
</script>

<!-- Toastify CSS & JS -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<style>
    /* Custom utility classes to complement preflight:false */
    @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap');
    
    .font-nunito { font-family: 'Nunito', sans-serif; }
    
    /* Reset input styles since preflight is false */
    .tw-input {
        appearance: none;
        background-color: #f3f4f6;
        border: 1px solid transparent;
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        line-height: 1.25rem;
        color: #374151;
        width: 100%;
        outline: none;
        transition: all 0.2s;
        box-sizing: border-box;
    }
    .tw-input:focus {
        background-color: #ffffff;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
    }
    .tw-btn {
        appearance: none;
        border: none;
        outline: none;
        cursor: pointer;
        font-family: inherit;
    }
</style>

<section class="fixed inset-0 z-[9999] overflow-y-auto bg-gradient-to-br from-blue-50 via-indigo-50 to-slate-200 flex items-center justify-center p-4 font-nunito">
    
    <div class="relative w-full max-w-md mt-12 mx-auto">
        <!-- Floating Logo -->
        <div class="absolute -top-12 left-1/2 transform -translate-x-1/2 z-10">
            <div class="bg-white p-1 rounded-full shadow-md border-4 border-white">
                <img src="<?= base_url('template/assets/img/logo-gunadarma.png') ?>" alt="Logo" class="w-20 h-20 rounded-full object-contain">
            </div>
        </div>

        <!-- Main Card -->
        <div class="bg-[#f8f9fa] rounded-xl shadow-2xl overflow-hidden pt-14 pb-2 relative">
            
            <div class="px-8 pb-6">
                <!-- Header Text -->
                <div class="text-center mb-6 hidden">
                    <h2 class="text-xl font-bold text-gray-800">LPM Gunadarma</h2>
                </div>

                <!-- Alerts removed, handled via Toastify JS below -->

                <!-- Form -->
                <form method="POST" action="<?= site_url('auth/loginProcess'); ?>" id="loginForm">
                    <?= csrf_field(); ?>

                    <!-- Username / NIDN -->
                    <div class="mb-5">
                        <label for="nidn" class="block text-[13px] font-bold text-gray-800 mb-1">
                            <?= L('label_user') ?>
                        </label>
                        <input id="nidn" name="nidn" type="text" class="tw-input" value="<?= set_value('nidn'); ?>" placeholder="<?= L('ph_user') ?>" tabindex="1" required autofocus>
                    </div>

                    <!-- Password -->
                    <div class="mb-5 relative">
                        <label for="password" class="block text-[13px] font-bold text-gray-800 mb-1">
                            <?= L('label_pass') ?>
                        </label>
                        <input id="password" name="password" type="password" class="tw-input pr-10" placeholder="<?= L('ph_pass') ?>" tabindex="2" required>
                        <button type="button" class="tw-btn absolute right-3 top-8 text-gray-400 hover:text-gray-600 bg-transparent" id="togglePassword">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>

                    <!-- Captcha -->
                    <div class="mb-6">
                        <label for="captcha" class="block text-[13px] font-bold text-gray-800 mb-1">
                            <?= L('label_captcha') ?> <?= isset($captcha_num1) ? $captcha_num1 . ' + ' . $captcha_num2 . '?' : '?' ?>
                        </label>
                        <input id="captcha" name="captcha" type="number" class="tw-input" placeholder="<?= L('ph_captcha') ?>" tabindex="3" required>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="tw-btn w-full bg-[#3b82f6] hover:bg-[#2563eb] text-white font-bold py-3 px-4 rounded-lg shadow-sm transition-colors duration-200 text-sm tracking-wide" tabindex="4">
                        <?= L('btn_login') ?>
                    </button>
                    
                    <!-- Info Text -->
                    <div class="text-center mt-6 text-[12px] text-gray-500">
                        <?= L('info_text') ?>
                    </div>
                </form>
            </div>
            
            <!-- Bottom Section (Like reference image) -->
            <div class="border-t border-gray-200 bg-[#f4f5f7] px-8 py-4 flex flex-col items-center gap-2 mt-4">
                <a href="<?= site_url('registrasi'); ?>" class="text-[13px] text-[#3b82f6] font-bold hover:underline">
                    <?= L('no_account') ?> <?= L('register_here') ?>
                </a>
                <a href="<?= site_url(); ?>" class="text-[13px] text-[#3b82f6] font-bold hover:underline">
                    <?= L('back_home') ?>
                </a>
            </div>

        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        
        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                if(type === 'text') {
                    eyeIcon.classList.remove('fa-eye');
                    eyeIcon.classList.add('fa-eye-slash');
                } else {
                    eyeIcon.classList.remove('fa-eye-slash');
                    eyeIcon.classList.add('fa-eye');
                }
            });
        }

        <?php if (session()->getFlashdata('error')): ?>
            Toastify({
                text: <?= json_encode(strip_tags(session()->getFlashdata('error') ?? '')); ?>,
                duration: 4000,
                close: true,
                gravity: "top",
                position: "center",
                style: {
                    background: "linear-gradient(to right, #ef4444, #f87171)",
                    borderRadius: "8px",
                    boxShadow: "0 4px 12px rgba(239, 68, 68, 0.3)",
                    fontFamily: "'Nunito', sans-serif",
                    fontWeight: "bold",
                    color: "white"
                }
            }).showToast();
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            Toastify({
                text: <?= json_encode(strip_tags(session()->getFlashdata('success') ?? '')); ?>,
                duration: 4000,
                close: true,
                gravity: "top",
                position: "center",
                style: {
                    background: "linear-gradient(to right, #10b981, #34d399)",
                    borderRadius: "8px",
                    boxShadow: "0 4px 12px rgba(16, 185, 129, 0.3)",
                    fontFamily: "'Nunito', sans-serif",
                    fontWeight: "bold",
                    color: "white"
                }
            }).showToast();
        <?php endif; ?>
    });
</script>

<?= $this->endSection() ?>
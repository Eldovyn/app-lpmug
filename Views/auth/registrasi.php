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

        'label_captcha' => 'Keamanan: Berapa hasil',
        'ph_captcha'    => 'Masukkan hasil penjumlahan',
        'inv_captcha'   => 'Mohon isi jawaban keamanan',

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

        'label_captcha' => 'Security: What is the result of',
        'ph_captcha'    => 'Enter the sum',
        'inv_captcha'   => 'Please fill in the security answer',

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

<!-- Tailwind CSS Registration Implementation -->
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
    @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap');
    .font-nunito { font-family: 'Nunito', sans-serif; }
    
    .tw-input {
        appearance: none; background-color: #f3f4f6; border: 1px solid transparent; border-radius: 0.5rem;
        padding: 0.75rem 1rem; font-size: 0.875rem; color: #374151; width: 100%; outline: none; transition: all 0.2s; box-sizing: border-box;
    }
    .tw-input:focus { background-color: #ffffff; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3); }
    .tw-input.is-invalid { border-color: #ef4444; background-color: #fef2f2; }
    .tw-input.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.3); }
    
    .tw-btn { appearance: none; border: none; outline: none; cursor: pointer; font-family: inherit; }
    
    /* Custom Radio Buttons for Role */
    .tw-radio-label {
        display: flex; align-items: center; justify-content: center; padding: 1rem; border-radius: 0.5rem;
        background-color: #f3f4f6; border: 2px solid transparent; cursor: pointer; transition: all 0.2s; font-weight: 600; font-size: 0.875rem; color: #4b5563;
    }
    .tw-radio-input:checked + .tw-radio-label {
        background-color: #ebf5ff; border-color: #3b82f6; color: #1d4ed8;
    }
    .tw-radio-input { display: none; }
</style>

<section class="fixed inset-0 z-[9999] overflow-y-auto bg-gradient-to-br from-blue-50 via-indigo-50 to-slate-200 py-10 px-4 font-nunito">
    <div class="relative w-full max-w-2xl mx-auto mt-10 mb-10">
        <!-- Floating Logo -->
        <div class="absolute -top-12 left-1/2 transform -translate-x-1/2 z-10">
            <div class="bg-white p-1 rounded-full shadow-md border-4 border-white">
                <img src="<?= base_url('template/assets/img/logo-gunadarma.png') ?>" alt="Logo" class="w-20 h-20 rounded-full object-contain">
            </div>
        </div>

        <div class="bg-[#f8f9fa] rounded-xl shadow-2xl overflow-hidden pt-14 pb-2 relative">
            <div class="px-6 sm:px-10 pb-6">
                <!-- Alerts handled via Toastify JS below -->

                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded shadow-sm text-sm text-yellow-800">
                    <p class="font-bold mb-2"><i class="fas fa-info-circle mr-1"></i> <?= L('important_badge') ?></p>
                    <div class="space-y-1">
                        <p><?= L('inst_1') ?></p>
                        <p><?= L('inst_2') ?></p>
                        <p><?= L('inst_3') ?></p>
                        <p><?= L('inst_4') ?></p>
                        <p><?= L('inst_5') ?></p>
                        <p><?= L('inst_6') ?></p>
                        <p><?= L('inst_7') ?></p>
                    </div>
                </div>

                <form method="POST" action="<?= site_url('auth/registrasiProcess'); ?>" autocomplete="off" id="registrationForm">
                    <?= csrf_field(); ?>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <!-- NIDN -->
                        <div>
                            <label for="nidn" class="block text-[13px] font-bold text-gray-800 mb-2">
                                <?= L('label_nidn') ?> <span class="text-red-500">*</span>
                            </label>
                            <input id="nidn" type="text" name="nidn" value="<?= set_value('nidn'); ?>" class="tw-input <?= (session('validation') && session('validation')->hasError('nidn')) ? 'is-invalid' : ''; ?>" placeholder="<?= L('ph_nidn') ?>" autofocus>
                            <div class="mt-2 flex gap-2 flex-wrap">
                                <span class="inline-block text-[10px] bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded font-normal"><?= L('badge_mitra') ?></span>
                                <span class="inline-block text-[10px] bg-indigo-100 text-indigo-700 px-1.5 py-0.5 rounded font-normal"><?= L('badge_dosen') ?></span>
                            </div>
                            <?php if (session('validation') && session('validation')->hasError('nidn')): ?>
                                <p class="text-red-500 text-xs mt-1 font-bold"><?= session('validation')->getError('nidn'); ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- SINTA ID -->
                        <div>
                            <label for="sinta_id" class="block text-[13px] font-bold text-gray-800 mb-2">
                                <?= L('label_sinta') ?> <span class="text-red-500">*</span>
                            </label>
                            <input id="sinta_id" type="text" name="sinta_id" value="<?= set_value('sinta_id'); ?>" class="tw-input <?= (session('validation') && session('validation')->hasError('sinta_id')) ? 'is-invalid' : ''; ?>" placeholder="<?= L('ph_sinta') ?>">
                            <p class="text-gray-500 text-[11px] mt-1"><i class="fas fa-info-circle"></i> <?= L('sinta_help') ?></p>
                            <?php if (session('validation') && session('validation')->hasError('sinta_id')): ?>
                                <p class="text-red-500 text-xs mt-1 font-bold"><?= session('validation')->getError('sinta_id'); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label for="user_name" class="block text-[13px] font-bold text-gray-800 mb-1">
                            <?= L('label_name') ?> <span class="text-red-500">*</span>
                        </label>
                        <input id="user_name" type="text" name="user_name" value="<?= set_value('user_name'); ?>" class="tw-input <?= (session('validation') && session('validation')->hasError('user_name')) ? 'is-invalid' : ''; ?>" placeholder="<?= L('ph_name') ?>">
                        <?php if (session('validation') && session('validation')->hasError('user_name')): ?>
                            <p class="text-red-500 text-xs mt-1 font-bold"><?= session('validation')->getError('user_name'); ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="mb-5">
                        <label for="email" class="block text-[13px] font-bold text-gray-800 mb-1">
                            <?= L('label_email') ?> <span class="text-red-500">*</span>
                        </label>
                        <input id="email" type="email" name="email" value="<?= set_value('email'); ?>" class="tw-input <?= (session('validation') && session('validation')->hasError('email')) ? 'is-invalid' : ''; ?>" placeholder="<?= L('ph_email') ?>">
                        <?php if (session('validation') && session('validation')->hasError('email')): ?>
                            <p class="text-red-500 text-xs mt-1 font-bold"><?= session('validation')->getError('email'); ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-[13px] font-bold text-gray-800 mb-2">
                                <?= L('label_pass') ?> <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input id="password" name="password" type="password" class="tw-input pr-10 <?= (session('validation') && session('validation')->hasError('password')) ? 'is-invalid' : ''; ?>" placeholder="<?= L('ph_pass') ?>">
                                <button type="button" class="tw-btn absolute right-3 top-3 text-gray-400 hover:text-gray-600 bg-transparent" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <span class="inline-block text-[10px] bg-indigo-50 text-indigo-700 px-1.5 py-0.5 rounded mt-2 font-normal"><?= L('pass_rule') ?></span>
                            <?php if (session('validation') && session('validation')->hasError('password')): ?>
                                <p class="text-red-500 text-xs mt-1 font-bold"><?= session('validation')->getError('password'); ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_konfirmasi" class="block text-[13px] font-bold text-gray-800 mb-2">
                                <?= L('label_pass_conf') ?> <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input id="password_konfirmasi" name="password_konfirmasi" type="password" class="tw-input pr-10 <?= (session('validation') && session('validation')->hasError('password_konfirmasi')) ? 'is-invalid' : ''; ?>" placeholder="<?= L('ph_pass_conf') ?>">
                                <button type="button" class="tw-btn absolute right-3 top-3 text-gray-400 hover:text-gray-600 bg-transparent" id="togglePasswordConfirm">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <?php if (session('validation') && session('validation')->hasError('password_konfirmasi')): ?>
                                <p class="text-red-500 text-xs mt-1 font-bold"><?= session('validation')->getError('password_konfirmasi'); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Role Selection -->
                    <div class="mb-5">
                        <label class="block text-[13px] font-bold text-gray-800 mb-2">
                            <?= L('label_role') ?> <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="w-full">
                                <input type="radio" name="role_id" value="4" class="tw-radio-input" id="role_dosen" <?= set_value('role_id') == '4' ? 'checked' : '' ?>>
                                <div class="tw-radio-label"><i class="fas fa-chalkboard-teacher mr-2"></i> <?= L('role_dosen') ?></div>
                            </label>
                            <label class="w-full">
                                <input type="radio" name="role_id" value="5" class="tw-radio-input" id="role_mitra" <?= set_value('role_id') == '5' ? 'checked' : '' ?>>
                                <div class="tw-radio-label"><i class="fas fa-handshake mr-2"></i> <?= L('role_mitra') ?></div>
                            </label>
                        </div>
                        <?php if (session('validation') && session('validation')->hasError('role_id')): ?>
                            <p class="text-red-500 text-xs mt-1 font-bold"><?= session('validation')->getError('role_id'); ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Dosen Fields -->
                    <div id="dosen-fields" class="mb-5" style="display: none;">
                        <label for="jurusan_id" class="block text-[13px] font-bold text-gray-800 mb-1">
                            <?= L('label_field') ?> <span class="text-red-500">*</span>
                        </label>
                        <select id="jurusan_id" name="jurusan_id" class="tw-input <?= (session('validation') && session('validation')->hasError('jurusan_id')) ? 'is-invalid' : ''; ?>">
                            <option value=""><?= L('opt_field') ?></option>
                            <?php foreach ($jurusan as $j): ?>
                                <option value="<?= $j->jurusan_id; ?>" <?= set_value('jurusan_id') == $j->jurusan_id ? 'selected' : ''; ?>>
                                    <?= $j->fakultas_name; ?> - <?= $j->jurusan_name; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (session('validation') && session('validation')->hasError('jurusan_id')): ?>
                            <p class="text-red-500 text-xs mt-1 font-bold"><?= session('validation')->getError('jurusan_id'); ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Mitra Fields -->
                    <div id="mitra-fields" class="space-y-5 mb-5" style="display: none;">
                        <div>
                            <label for="kontak" class="block text-[13px] font-bold text-gray-800 mb-1">
                                <?= L('label_contact') ?> <span class="text-red-500">*</span>
                            </label>
                            <input id="kontak" type="text" name="kontak" value="<?= set_value('kontak'); ?>" class="tw-input <?= (session('validation') && session('validation')->hasError('kontak')) ? 'is-invalid' : ''; ?>" placeholder="<?= L('ph_contact') ?>">
                            <?php if (session('validation') && session('validation')->hasError('kontak')): ?>
                                <p class="text-red-500 text-xs mt-1 font-bold"><?= session('validation')->getError('kontak'); ?></p>
                            <?php endif; ?>
                        </div>
                        <div>
                            <label for="kota_id" class="block text-[13px] font-bold text-gray-800 mb-1">
                                <?= L('label_city') ?> <span class="text-red-500">*</span>
                            </label>
                            <select id="kota_id" name="kota_id" class="tw-input <?= (session('validation') && session('validation')->hasError('kota_id')) ? 'is-invalid' : ''; ?>">
                                <option value=""><?= L('opt_city') ?></option>
                                <?php foreach ($kota as $k): ?>
                                    <option value="<?= $k->kota_id; ?>" <?= set_value('kota_id') == $k->kota_id ? 'selected' : ''; ?>>
                                        <?= $k->provinsi_name; ?> - <?= $k->kota_name; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (session('validation') && session('validation')->hasError('kota_id')): ?>
                                <p class="text-red-500 text-xs mt-1 font-bold"><?= session('validation')->getError('kota_id'); ?></p>
                            <?php endif; ?>
                        </div>
                        <div>
                            <label for="alamat" class="block text-[13px] font-bold text-gray-800 mb-1">
                                <?= L('label_address') ?> <span class="text-red-500">*</span>
                            </label>
                            <textarea id="alamat" name="alamat" rows="3" class="tw-input <?= (session('validation') && session('validation')->hasError('alamat')) ? 'is-invalid' : ''; ?>" placeholder="<?= L('ph_address') ?>"><?= set_value('alamat'); ?></textarea>
                            <?php if (session('validation') && session('validation')->hasError('alamat')): ?>
                                <p class="text-red-500 text-xs mt-1 font-bold"><?= session('validation')->getError('alamat'); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Captcha -->
                    <div class="mb-5">
                        <label for="captcha" class="block text-[13px] font-bold text-gray-800 mb-2">
                            <?= L('label_captcha') ?> <?= isset($captcha_num1) ? $captcha_num1 . ' + ' . $captcha_num2 . '?' : '?' ?> <span class="text-red-500">*</span>
                        </label>
                        <input id="captcha" name="captcha" type="number" class="tw-input" placeholder="<?= L('ph_captcha') ?>" required>
                    </div>

                    <!-- Syarat -->
                    <div class="mb-6 flex items-start">
                        <input type="checkbox" id="syarat" name="syarat" class="h-4 w-4 mt-0.5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer">
                        <label for="syarat" class="ml-2 block text-[13px] font-bold text-gray-700 cursor-pointer">
                            <?= L('agree_terms') ?>
                        </label>
                    </div>
                    <?php if (session('validation') && session('validation')->hasError('syarat')): ?>
                        <p class="text-red-500 text-xs -mt-5 mb-5 font-bold"><?= session('validation')->getError('syarat'); ?></p>
                    <?php endif; ?>

                    <button type="submit" class="tw-btn w-full bg-[#3b82f6] hover:bg-[#2563eb] text-white font-bold py-3 px-4 rounded-lg shadow-sm transition-colors duration-200 text-sm tracking-wide">
                        <?= L('btn_register') ?>
                    </button>
                </form>
            </div>

            <!-- Bottom Section -->
            <div class="border-t border-gray-200 bg-[#f4f5f7] px-8 py-5 flex flex-col items-center gap-2 mt-2">
                <a href="<?= site_url('login'); ?>" class="text-[13px] text-[#3b82f6] font-bold hover:underline">
                    <?= L('already_account') ?> <?= L('login_here') ?>
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
        // Password toggles
        const setupToggle = (btnId, inputId) => {
            const btn = document.getElementById(btnId);
            const input = document.getElementById(inputId);
            if (btn && input) {
                btn.addEventListener('click', function() {
                    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                    input.setAttribute('type', type);
                    this.querySelector('i').classList.toggle('fa-eye');
                    this.querySelector('i').classList.toggle('fa-eye-slash');
                });
            }
        };
        setupToggle('togglePassword', 'password');
        setupToggle('togglePasswordConfirm', 'password_konfirmasi');

        // Role fields toggle
        const roleDosen = document.getElementById('role_dosen');
        const roleMitra = document.getElementById('role_mitra');
        const dosenFields = document.getElementById('dosen-fields');
        const mitraFields = document.getElementById('mitra-fields');

        function toggleFields() {
            if (roleDosen && roleDosen.checked) {
                dosenFields.style.display = 'block';
                mitraFields.style.display = 'none';
            } else if (roleMitra && roleMitra.checked) {
                dosenFields.style.display = 'none';
                mitraFields.style.display = 'block';
            } else {
                if(dosenFields) dosenFields.style.display = 'none';
                if(mitraFields) mitraFields.style.display = 'none';
            }
        }

        if (roleDosen) roleDosen.addEventListener('change', toggleFields);
        if (roleMitra) roleMitra.addEventListener('change', toggleFields);
        toggleFields(); // Initial check

        // Focus invalid
        const invalidInputs = document.querySelectorAll('.is-invalid');
        if (invalidInputs.length > 0) {
            invalidInputs[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
            invalidInputs[0].focus();
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
    });
</script>

<?= $this->endSection() ?>
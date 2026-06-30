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

$I18N = [
    'id' => [
        // breadcrumb/header
        'dashboard' => 'Dashboard',
        'dataAbdimas' => 'Data Abdimas',

        // form labels
        'choosePeriod' => 'Pilih Periode',
        'selectPeriod' => '—PILIH PERIODE—',
        'registrationClosed' => 'Pendaftaran ditutup',

        'nidn' => 'NIDN',
        'chairName' => 'Nama Ketua',
        'sintaId' => 'SINTA ID',

        'choosePartner' => 'Pilih Mitra UMKM',
        'selectPartner' => '—PILIH MITRA UMKM—',

        'partnerProblem' => 'Masalah yang Dihadapi Mitra',
        'partnerSolution' => 'Solusi yang Diberikan',

        'chooseTopicProgram' => 'Pilih Topik - Program - Sub Program',
        'selectTopicProgram' => '—PILIH TOPIK - PROGRAM - SUB PROGRAM—',

        'chooseOutputs' => 'Pilih Beberapa Luaran',
        'selectOutput' => '—PILIH LUARAN—',
        'note' => 'Note:',
        'canChooseMore' => 'Dapat memilih lebih dari 1 luaran',

        'chooseActivityType' => 'Pilih Tipe Kegiatan',
        'selectActivityType' => '—PILIH TIPE KEGIATAN—',
        'individual' => 'Perorangan',
        'group' => 'Kelompok',

        'fundingEstimate' => 'Estimasi Pendanaan',
        'fundingNote1' => 'Untuk Pendanaan Per Semester',
        'fundingNote2' => 'Contoh: 10000000 | tanpa "Rp" dan "." (titik) maupun simbol lainnya',
        'fundingMin' => 'Minimal pendanaan Rp 2.500.000 atau lebih.',
        'fundingPlaceholder' => 'Isi Estimasi Pendanaan Anda',

        'fundingSource' => 'Sumber Pendanaan',
        'fundingSourceNote' => 'Pilih satu atau lebih sumber utama dana kegiatan',
        'selectFundingSource' => '—PILIH SUMBER DANA—',

        // funding source display text (value tetap Indonesia)
        'fundInternal' => 'Internal (Member dues)',
        'fundKemendikbud' => 'External (Kemendikbud)',
        'fundDudi' => 'External (Industry/DUDI)',
        'fundPemda' => 'External (Local gov/PEMDA)',
        'fundMitra' => 'External (Partner/MITRA)',

        'membersInput' => 'Masukan Ketua dan Anggota',
        'membersNote' => 'Nama Ketua WAJIB dimasukan kedalam form anggota',
        'selectMembers' => '—PILIH ANGGOTA—',
        'applicableFor' => '(Berlaku untuk tipe kegiatan Perorangan / Kelompok)',

        'studentsInvolved' => 'Mahasiswa Terlibat',
        'studentsNote' => 'Minimal 2 mahasiswa wajib diinput. Bisa tambah tanpa batas.',
        'studentNamePh' => 'Nama Mahasiswa',
        'studentNpmPh' => 'NPM',
        'chooseProdi' => 'Pilih Prodi',
        'delete' => 'Hapus',
        'addStudent' => 'Tambah Mahasiswa',
        'min2StudentsAlert' => 'Minimal harus ada 2 mahasiswa!',

        'agreeTerms' => 'Dengan ini saya menyetujui syarat dan ketentuan yang berlaku serta mengisi formulir dengan benar dan sadar tanpa adanya paksaan.',

        'reset' => 'Reset',
        'register' => 'Mendaftar',

        // address modal
        'confirmPartnerAddress' => 'Konfirmasi Alamat Mitra',
        'selectedPartnerAddress' => 'Alamat mitra yang dipilih:',
        'isAddressCorrect' => 'Apakah alamat ini sudah benar?',
        'changePartnerAddress' => 'Ubah Alamat Mitra',
        'newAddress' => 'Alamat Baru:',
        'updateAddress' => 'Update Alamat',
        'cancel' => 'Batal',
        'yesCorrect' => 'Ya, Benar',
        'noChangeAddress' => 'Tidak, Ubah Alamat',

        // toast & notification
        'toastSuccessTitle' => 'Berhasil',
        'toastErrorTitle' => 'Error',
        'toastWarnTitle' => 'Peringatan',
        'addrUpdated' => 'Alamat berhasil diperbarui!',
        'addrEmpty' => 'Alamat tidak boleh kosong!',
        'addrUpdateFailed' => 'Terjadi kesalahan saat memperbarui alamat.',
        'confirmAllPartners' => 'Harap konfirmasi alamat semua mitra.',

        // address validation texts
        'warn' => 'Peringatan:',
        'suggestion' => 'Saran:',
        'addrValid' => 'Alamat lengkap dan valid.',
        'addrTooShort' => 'Alamat terlalu pendek',
        'addrIncludeStreet' => 'Sebaiknya cantumkan nama jalan',
        'cityMissing' => 'Kota tidak tersedia',
        'provinceMissing' => 'Provinsi tidak tersedia',
        'addrNotFilled' => 'Alamat belum diisi benar',
    ],

    'en' => [
        'dashboard' => 'Dashboard',
        'dataAbdimas' => 'Community Service (Abdimas)',

        'choosePeriod' => 'Choose Period',
        'selectPeriod' => '—SELECT PERIOD—',
        'registrationClosed' => 'Registration closed',

        'nidn' => 'NIDN',
        'chairName' => 'Chair Name',
        'sintaId' => 'SINTA ID',

        'choosePartner' => 'Select UMKM Partner',
        'selectPartner' => '—SELECT UMKM PARTNER—',

        'partnerProblem' => 'Partner Problems',
        'partnerSolution' => 'Proposed Solutions',

        'chooseTopicProgram' => 'Select Topic - Program - Sub Program',
        'selectTopicProgram' => '—SELECT TOPIC - PROGRAM - SUB PROGRAM—',

        'chooseOutputs' => 'Select Outputs',
        'selectOutput' => '—SELECT OUTPUTS—',
        'note' => 'Note:',
        'canChooseMore' => 'You can select more than 1 output',

        'chooseActivityType' => 'Select Activity Type',
        'selectActivityType' => '—SELECT ACTIVITY TYPE—',
        'individual' => 'Individual',
        'group' => 'Group',

        'fundingEstimate' => 'Funding Estimate',
        'fundingNote1' => 'Funding per semester',
        'fundingNote2' => 'Example: 10000000 | without "Rp", dots, or other symbols',
        'fundingMin' => 'Minimum funding is Rp 2,500,000 or more.',
        'fundingPlaceholder' => 'Enter your funding estimate',

        'fundingSource' => 'Funding Sources',
        'fundingSourceNote' => 'Choose one or more primary funding sources',
        'selectFundingSource' => '—SELECT FUNDING SOURCES—',

        'fundInternal' => 'Internal (Member dues)',
        'fundKemendikbud' => 'External (Kemendikbud)',
        'fundDudi' => 'External (Industry/DUDI)',
        'fundPemda' => 'External (Local gov/PEMDA)',
        'fundMitra' => 'External (Partner/MITRA)',

        'membersInput' => 'Add Chair & Members',
        'membersNote' => 'Chair name MUST be included in the members list',
        'selectMembers' => '—SELECT MEMBERS—',
        'applicableFor' => '(Applies to Individual/Group activity type)',

        'studentsInvolved' => 'Students Involved',
        'studentsNote' => 'At least 2 students are required. You can add more with no limit.',
        'studentNamePh' => 'Student Name',
        'studentNpmPh' => 'Student ID (NPM)',
        'chooseProdi' => 'Select Study Program',
        'delete' => 'Delete',
        'addStudent' => 'Add Student',
        'min2StudentsAlert' => 'At least 2 students are required!',

        'agreeTerms' => 'I agree to the applicable terms and conditions and confirm that I have filled out the form correctly and voluntarily.',

        'reset' => 'Reset',
        'register' => 'Register',

        'confirmPartnerAddress' => 'Confirm Partner Address',
        'selectedPartnerAddress' => 'Selected partner address:',
        'isAddressCorrect' => 'Is this address correct?',
        'changePartnerAddress' => 'Change Partner Address',
        'newAddress' => 'New Address:',
        'updateAddress' => 'Update Address',
        'cancel' => 'Cancel',
        'yesCorrect' => 'Yes, correct',
        'noChangeAddress' => 'No, change address',

        'toastSuccessTitle' => 'Success',
        'toastErrorTitle' => 'Error',
        'toastWarnTitle' => 'Warning',
        'addrUpdated' => 'Address updated successfully!',
        'addrEmpty' => 'Address cannot be empty!',
        'addrUpdateFailed' => 'An error occurred while updating the address.',
        'confirmAllPartners' => 'Please confirm all partner addresses.',

        'warn' => 'Warning:',
        'suggestion' => 'Suggestion:',
        'addrValid' => 'Address looks complete and valid.',
        'addrTooShort' => 'Address is too short',
        'addrIncludeStreet' => 'It is recommended to include a street name',
        'cityMissing' => 'City is missing',
        'provinceMissing' => 'Province is missing',
        'addrNotFilled' => 'Address looks not properly filled',
    ],
];

$__ = function (string $key, ...$args) use ($I18N, $lang) {
    $text = $I18N[$lang][$key] ?? $I18N['id'][$key] ?? $key;
    return $args ? vsprintf($text, $args) : $text;
};

// untuk JS (biar gampang dipakai di script)
$T = $I18N[$lang];
?>

<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    /* ==== CSS kamu: TIDAK DIUBAH ==== */
    #confirmAddressModal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 99999 !important;
        overflow-y: auto;
    }

    #confirmAddressModal.show {
        display: block !important;
        animation: fadeIn 0.15s ease-in;
    }

    #confirmAddressModal .modal-dialog {
        margin: 1.75rem auto;
        max-width: 600px;
    }

    #confirmAddressModal .modal-content {
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }

    #confirmAddressModal .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
        padding: 1rem 1.5rem;
    }

    #confirmAddressModal .modal-header .close {
        color: white;
        opacity: 0.8;
        text-shadow: none;
    }

    #confirmAddressModal .modal-header .close:hover {
        opacity: 1;
    }

    #confirmAddressModal .modal-body {
        padding: 1.5rem;
    }

    #mitra_address_display {
        background-color: #e7f3ff;
        border-left: 4px solid #2196F3;
        padding: 12px 15px;
        margin: 10px 0;
        font-size: 14px;
        line-height: 1.6;
    }

    #address_validation_message .alert {
        margin-top: 10px;
        font-size: 13px;
    }

    #address_validation_message .alert ul {
        padding-left: 20px;
    }

    #update_address_form {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 6px;
        margin-top: 15px;
        border: 1px solid #dee2e6;
    }

    #update_address_form h6 {
        color: #495057;
        margin-bottom: 15px;
        font-weight: 600;
    }

    #update_address_form .form-group {
        margin-bottom: 15px;
    }

    #update_address_form label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 8px;
    }

    #update_address_form textarea {
        border: 2px solid #ced4da;
        border-radius: 5px;
        padding: 10px;
        font-size: 14px;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    #update_address_form textarea:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        outline: none;
    }

    #update_address_form .btn {
        margin-right: 8px;
        padding: 8px 20px;
        font-weight: 500;
        border-radius: 5px;
        transition: all 0.2s;
    }

    #update_address_form .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }

    #update_address_form .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
    }

    #update_address_form .btn-secondary:hover {
        transform: translateY(-1px);
    }

    #modal_buttons {
        padding: 1rem 1.5rem;
        background: #f8f9fa;
        border-top: 1px solid #dee2e6;
    }

    #modal_buttons .btn {
        padding: 10px 24px;
        font-weight: 500;
        border-radius: 5px;
        transition: all 0.2s;
        border: none;
    }

    #modal_buttons .btn-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }

    #modal_buttons .btn-success:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
    }

    #modal_buttons .btn-warning {
        background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
        color: #fff;
    }

    #modal_buttons .btn-warning:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(255, 193, 7, 0.3);
    }

    #customBackdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 99998 !important;
    }

    #customBackdrop.show {
        display: block !important;
        animation: fadeIn 0.15s ease-in;
    }

    .select2-container {
        z-index: 9997 !important;
    }

    .toast.show {
        display: block !important;
        opacity: 1;
        animation: slideInRight 0.3s ease-out;
    }

    .toast.hide {
        display: none !important;
        opacity: 0;
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @media (max-width: 576px) {
        #confirmAddressModal .modal-dialog {
            margin: 0.5rem;
            max-width: calc(100% - 1rem);
        }
    }
</style>

<section class="section">
    <div class="section-header">
        <a href="<?= site_url('abdimas'); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
        <h1><?= $title; ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>"><?= $__('dashboard'); ?></a></div>
            <div class="breadcrumb-item active"><a href="<?= site_url('abdimas'); ?>"><?= $__('dataAbdimas'); ?></a></div>
            <div class="breadcrumb-item"><?= $title; ?></div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= site_url('abdimas'); ?>" method="POST" autocomplete="off">
                            <?= csrf_field(); ?>

                            <?php if (session('error')): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <?= session('error'); ?>
                                </div>
                            <?php endif; ?>

                            <div class="form-group">
                                <label><?= $__('choosePeriod'); ?><span class="text-danger">*</span></label>
                                <select name="periode_id" id="periode_id" class="form-control select2" required>
                                    <option hidden disabled <?= !old('periode_id') ? 'selected' : '' ?>>&mdash;<?= $__('selectPeriod'); ?>&mdash;</option>
                                    <?php foreach ($periode as $mtr => $v_periode): ?>
                                        <?php if ($v_periode->info == 1): ?>
                                            <option value="<?= $v_periode->periode_id; ?>" <?= old('periode_id') == $v_periode->periode_id ? 'selected' : (!old('periode_id') ? 'selected' : '') ?>><?= $v_periode->periode_name; ?> <?= $v_periode->tahun_ajaran; ?></option>
                                        <?php else : ?>
                                            <option disabled value="<?= $v_periode->periode_id; ?>" <?= old('periode_id') == $v_periode->periode_id ? 'selected' : '' ?>><?= $v_periode->periode_name; ?> <?= $v_periode->tahun_ajaran; ?> -||- <?= $__('registrationClosed'); ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label><?= $__('nidn'); ?><span class="text-danger">*</span></label>
                                    <input type="text" name="nidn" id="nidn" value="<?= userLogin()->nidn; ?>" class="form-control" disabled>
                                </div>
                                <div class="form-group col-md-4">
                                    <label><?= $__('chairName'); ?><span class="text-danger">*</span></label>
                                    <input type="text" name="ketua_id" id="ketua_id" class="form-control" placeholder="<?= userLogin()->user_name; ?>" disabled>
                                </div>
                                <div class="form-group col-md-4">
                                    <label><?= $__('sintaId'); ?><span class="text-danger">*</span></label>
                                    <input type="text" name="sinta_id" id="sinta_id" value="<?= userLogin()->sinta_id; ?>" class="form-control" disabled>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?= $__('choosePartner'); ?><span class="text-danger">*</span></label>
                                <select name="mitra_id[]" id="mitra_select" class="form-control select2" multiple required>
                                    <?php foreach ($mitra as $v_mitra): ?>
                                        <?php if ($v_mitra->role_id == 5): ?>
                                            <option value="<?= $v_mitra->user_id; ?>"
                                                <?= (in_array($v_mitra->user_id, (array)(old('mitra_id') ?? []))) ? 'selected' : '' ?>
                                                data-alamat="<?= htmlspecialchars($v_mitra->alamat); ?>"
                                                data-provinsi="<?= htmlspecialchars($v_mitra->provinsi_name); ?>"
                                                data-kota="<?= htmlspecialchars($v_mitra->kota_name); ?>">
                                                <?= $v_mitra->user_name; ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="masalah_mitra"><?= $__('partnerProblem'); ?><span class="text-danger">*</span></label>
                                        <textarea name="masalah_mitra" id="masalah_mitra" class="form-control" rows="3" required><?= old('masalah_mitra') ?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="solusi_mitra"><?= $__('partnerSolution'); ?><span class="text-danger">*</span></label>
                                        <textarea name="solusi_mitra" id="solusi_mitra" class="form-control" rows="3" required><?= old('solusi_mitra') ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?= $__('chooseTopicProgram'); ?><span class="text-danger">*</span></label>
                                <select name="subprogram_id[]" class="form-control select2" multiple required>
                                    <?php foreach ($subprogram as $mtr => $v_subprogram): ?>
                                        <option value="<?= $v_subprogram->subprogram_id; ?>" <?= (in_array($v_subprogram->subprogram_id, (array)(old('subprogram_id') ?? []))) ? 'selected' : '' ?>><?= $v_subprogram->topik_name; ?> -||- <?= $v_subprogram->program_name; ?> -||- <?= $v_subprogram->subprogram_name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- NEW: Bidang Ilmu Dropdown -->
                            <div class="form-group">
                                <label><?= $lang === 'en' ? 'Field of Study' : 'Bidang Ilmu'; ?> <span class="text-danger">*</span></label>
                                <select name="bidang_ilmu" id="bidang_ilmu" class="form-control select2" required>
                                    <option value="" <?= !old('bidang_ilmu') ? 'selected' : '' ?>><?= $lang === 'en' ? '—Select Field of Study—' : '—Pilih Bidang Ilmu—'; ?></option>
                                    <option value="ipa-matematika" <?= old('bidang_ilmu') == 'ipa-matematika' ? 'selected' : '' ?>>1. Ilmu Pengetahuan Alam (IPA) & Matematika: Mencakup Matematika, Fisika, Biologi, Astronomi, Geofisika, dan Meteorologi.</option>
                                    <option value="teknik-rekayasa" <?= old('bidang_ilmu') == 'teknik-rekayasa' ? 'selected' : '' ?>>2. Ilmu Teknik & Rekayasa: Fokus pada aplikasi teknologi, rekayasa sipil, mesin, serta perangkat lunak.</option>
                                    <option value="kesehatan-kedokteran" <?= old('bidang_ilmu') == 'kesehatan-kedokteran' ? 'selected' : '' ?>>3. Ilmu Kesehatan & Kedokteran: Meliputi Ilmu Kedokteran, Kedokteran Gigi, Farmasi, Gizi, dan Kesehatan Lingkungan.</option>
                                    <option value="sosial-humaniora-seni" <?= old('bidang_ilmu') == 'sosial-humaniora-seni' ? 'selected' : '' ?>>4. Ilmu Sosial, Humaniora, & Seni: Meliputi Sosiologi, Hukum, Antropologi, Seni, dan Desain.</option>
                                    <option value="pertanian-tanaman" <?= old('bidang_ilmu') == 'pertanian-tanaman' ? 'selected' : '' ?>>5. Ilmu Pertanian & Tanaman: Meliputi Ilmu Tanah, Hortikultura, dan Budidaya Perkebunan.</option>
                                </select>
                            </div>
                            <!-- END NEW -->

                            <div class="form-group">
                                <label><?= $__('chooseOutputs'); ?><span class="text-danger">*</span>
                                    <span class="text-primary"><b><?= $__('note'); ?></b> <?= $__('canChooseMore'); ?></span>
                                </label>
                                <select name="luaran_id[]" class="form-control select2" id="luaran_id" multiple required>
                                    <?php foreach ($luaran as $mtr => $v_luaran): ?>
                                        <option value="<?= $v_luaran->luaran_id; ?>" <?= (in_array($v_luaran->luaran_id, (array)(old('luaran_id') ?? []))) ? 'selected' : '' ?>><?= $v_luaran->luaran_name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label><?= $__('chooseActivityType'); ?><span class="text-danger">*</span></label>
                                <select name="tipe_kegiatan" class="form-control select2" required>
                                    <option value="" <?= old('tipe_kegiatan') == '' ? 'selected' : '' ?> disabled><?= $__('selectActivityType'); ?></option>
                                    <option value="Perorangan" <?= old('tipe_kegiatan') == 'Perorangan' ? 'selected' : '' ?>><?= $__('individual'); ?></option>
                                    <option value="Kelompok" <?= old('tipe_kegiatan') == 'Kelompok' ? 'selected' : '' ?>><?= $__('group'); ?></option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>
                                    <?= $__('fundingEstimate'); ?><span class="text-danger">*</span>
                                    <span class="text-primary">
                                        <b><?= $__('note'); ?></b> <?= $__('fundingNote1'); ?>
                                        <b>(<?= $__('fundingNote2'); ?>)</b><br>
                                        <small class="text-danger"><?= $__('fundingMin'); ?></small>
                                    </span>
                                </label>
                                <input
                                    type="number"
                                    name="range_dana"
                                    id="range_dana"
                                    class="form-control"
                                    placeholder="<?= $__('fundingPlaceholder'); ?>"
                                    value="<?= old('range_dana') ?>"
                                    required
                                    min="2500000">
                            </div>

                            <div class="form-group">
                                <label for="sumber_dana"><?= $__('fundingSource'); ?> <span class="text-danger">*</span></label>
                                <span class="text-primary d-block mb-1"><b><?= $__('note'); ?></b> <?= $__('fundingSourceNote'); ?></span>

                                <select name="sumber_dana[]" id="sumber_dana" class="form-control select2" multiple required>
                                    <?php
                                    // VALUE tetap Indonesia (jangan ubah, agar backend aman)
                                    $options = [
                                        'Internal (Iuran Anggota)',
                                        'Eksternal (Kemendikbud)',
                                        'Eksternal (DUDI)',
                                        'Eksternal (PEMDA)',
                                        'Eksternal (MITRA)'
                                    ];
                                    $selected = (array)(old('sumber_dana') ?? []);

                                    foreach ($options as $opt) {
                                        $isSelected = in_array($opt, $selected) ? 'selected' : '';

                                        // display text per bahasa (value tetap)
                                        $display = $opt;
                                        if ($lang === 'en') {
                                            if ($opt === 'Internal (Iuran Anggota)') $display = $__('fundInternal');
                                            if ($opt === 'Eksternal (Kemendikbud)')  $display = $__('fundKemendikbud');
                                            if ($opt === 'Eksternal (DUDI)')        $display = $__('fundDudi');
                                            if ($opt === 'Eksternal (PEMDA)')       $display = $__('fundPemda');
                                            if ($opt === 'Eksternal (MITRA)')       $display = $__('fundMitra');
                                        }

                                        echo "<option value=\"$opt\" $isSelected>$display</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label><?= $__('membersInput'); ?><span class="text-danger">*</span>
                                    <span class="text-primary"><b><?= $__('note'); ?></b> <?= $__('membersNote'); ?></span>
                                </label>
                                <select name="anggota_id[]" class="form-control select2" id="anggota_id" multiple required>
                                    <?php foreach ($dosen as $ds => $v_dosen): ?>
                                        <?php 
                                            $selected = '';
                                            if (old('anggota_id')) {
                                                $selected = in_array($v_dosen->user_id, (array)old('anggota_id')) ? 'selected' : '';
                                            } else {
                                                $selected = ($v_dosen->user_id == userLogin()->user_id) ? 'selected' : '';
                                            }
                                        ?>
                                        <option value="<?= $v_dosen->user_id; ?>" <?= $selected ?>><?= $v_dosen->user_name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <label><span class="text-primary"><?= $__('applicableFor'); ?></span></label>
                            </div>

                            <div class="form-group">
                                <label>
                                    <?= $__('studentsInvolved'); ?>
                                    <span class="text-primary">
                                        <b><?= $__('note'); ?></b> <?= $__('studentsNote'); ?>
                                    </span>
                                </label>

                                <div id="mahasiswa_container">
                                    <?php
                                    $old_nama = old('mahasiswa_nama') ?? [];
                                    $old_npm  = old('mahasiswa_npm') ?? [];
                                    $old_jur  = old('mahasiswa_jurusan_id') ?? [];
                                    $rowCount = max(2, count($old_nama));
                                    ?>

                                    <?php for ($i = 0; $i < $rowCount; $i++): ?>
                                        <div class="mahasiswa_row row mb-2">
                                            <div class="col-md-4">
                                                <input type="text"
                                                    name="mahasiswa_nama[]"
                                                    class="form-control"
                                                    placeholder="<?= $__('studentNamePh'); ?>"
                                                    value="<?= $old_nama[$i] ?? '' ?>">
                                            </div>

                                            <div class="col-md-3">
                                                <input type="text"
                                                    name="mahasiswa_npm[]"
                                                    class="form-control"
                                                    placeholder="<?= $__('studentNpmPh'); ?>"
                                                    value="<?= $old_npm[$i] ?? '' ?>">
                                            </div>

                                            <div class="col-md-4">
                                                <select name="mahasiswa_jurusan_id[]" class="form-control">
                                                    <option value=""><?= $__('chooseProdi'); ?></option>
                                                    <?php foreach ($jurusan as $v): ?>
                                                        <option value="<?= $v->jurusan_id; ?>"
                                                            <?= (isset($old_jur[$i]) && $old_jur[$i] == $v->jurusan_id) ? 'selected' : '' ?>>
                                                            <?= $v->jurusan_name; ?> - <?= $v->fakultas_name; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-danger btn-sm remove_mahasiswa">
                                                    <?= $__('delete'); ?>
                                                </button>
                                            </div>
                                        </div>
                                    <?php endfor; ?>
                                </div>

                                <button type="button" class="btn btn-success btn-sm mt-2" id="add_mahasiswa">
                                    <?= $__('addStudent'); ?>
                                </button>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="syarat" class="custom-control-input" id="syarat" <?= old('syarat') ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="syarat"><?= $__('agreeTerms'); ?></label>
                                </div>
                            </div>

                            <div class="text-right">
                                <button type="reset" class="btn btn-danger"><?= $__('reset'); ?></button>
                                <button type="submit" class="btn btn-primary" id="submit_btn"><?= $__('register'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Konfirmasi Alamat Mitra -->
<div class="modal" id="confirmAddressModal" tabindex="-1" role="dialog" style="display: none;">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= $__('confirmPartnerAddress'); ?></h5>
                <button type="button" class="close" onclick="closeAddressModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong><?= $__('selectedPartnerAddress'); ?></strong></p>
                <div id="mitra_address_display"></div>
                <div id="address_validation_message"></div>
                <p><?= $__('isAddressCorrect'); ?></p>

                <div id="update_address_form" style="display: none;">
                    <hr>
                    <h6><?= $__('changePartnerAddress'); ?></h6>
                    <div class="form-group">
                        <label for="new_alamat"><?= $__('newAddress'); ?></label>
                        <textarea class="form-control" id="new_alamat" rows="3"></textarea>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm" onclick="updateAddress()"><?= $__('updateAddress'); ?></button>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="cancelUpdate()"><?= $__('cancel'); ?></button>
                </div>
            </div>
            <div class="modal-footer" id="modal_buttons">
                <button type="button" class="btn btn-success" onclick="confirmAddress()"><?= $__('yesCorrect'); ?></button>
                <button type="button" class="btn btn-warning" onclick="showUpdateForm()"><?= $__('noChangeAddress'); ?></button>
            </div>
        </div>
    </div>
</div>
<div class="modal-backdrop fade" id="customBackdrop" style="display: none;"></div>

<!-- Toast Notification -->
<div aria-live="polite" aria-atomic="true" style="position: fixed; top: 20px; right: 20px; z-index: 100000;">
    <div id="successToast" class="toast hide" role="alert" style="min-width: 300px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); border-radius: 8px; overflow: hidden;">
        <div class="toast-header bg-success text-white" style="border-bottom: none; padding: 12px 15px;">
            <i class="fas fa-check-circle mr-2"></i>
            <strong class="mr-auto"><?= $__('toastSuccessTitle'); ?></strong>
            <button type="button" class="ml-2 mb-1 close text-white" onclick="jQuery('#successToast').removeClass('show').addClass('hide')">
                <span>&times;</span>
            </button>
        </div>
        <div class="toast-body" id="toastMessage" style="padding: 15px; font-size: 14px;">
            <?= $__('addrUpdated'); ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {

            // i18n untuk JS (current lang)
            const T = <?= json_encode($T, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;

            var confirmedMitra = [];
            var currentMitraId = null;

            // ==========================
            // NOTIFIKASI TOAST
            // ==========================
            function showNotification(message, type) {
                type = type || 'success';
                var toast = jQuery('#successToast');
                var header = toast.find('.toast-header');

                header.removeClass('bg-success bg-danger bg-warning');
                if (type === 'success') {
                    header.addClass('bg-success');
                    header.find('i').removeClass().addClass('fas fa-check-circle mr-2');
                    header.find('strong').text(T.toastSuccessTitle);
                } else if (type === 'danger') {
                    header.addClass('bg-danger');
                    header.find('i').removeClass().addClass('fas fa-exclamation-circle mr-2');
                    header.find('strong').text(T.toastErrorTitle);
                } else if (type === 'warning') {
                    header.addClass('bg-warning');
                    header.find('i').removeClass().addClass('fas fa-exclamation-triangle mr-2');
                    header.find('strong').text(T.toastWarnTitle);
                }

                jQuery('#toastMessage').text(message);

                toast.removeClass('hide').addClass('show');
                setTimeout(() => toast.removeClass('show').addClass('hide'), 4000);
            }

            // ==========================
            // MODAL KONFIRMASI ALAMAT
            // ==========================
            window.showAddressModal = function() {
                jQuery('#mitra_select').select2('close');
                jQuery('#customBackdrop').addClass('show');
                jQuery('#confirmAddressModal').addClass('show');
                jQuery('body').css('overflow', 'hidden');
            };

            window.closeAddressModal = function() {
                jQuery('#customBackdrop').removeClass('show');
                jQuery('#confirmAddressModal').removeClass('show');
                jQuery('body').css('overflow', '');
                jQuery('#update_address_form').hide();
                jQuery('#modal_buttons').show();
            };

            window.confirmAddress = function() {
                if (currentMitraId) confirmedMitra.push(currentMitraId);
                closeAddressModal();
            };

            window.showUpdateForm = function() {
                jQuery('#modal_buttons').hide();
                jQuery('#update_address_form').show();
            };

            window.cancelUpdate = function() {
                jQuery('#update_address_form').hide();
                jQuery('#modal_buttons').show();
            };

            // ==========================
            // UPDATE ALAMAT AJAX
            // ==========================
            window.updateAddress = function() {
                var newAlamat = jQuery('#new_alamat').val().trim();
                if (!newAlamat) {
                    showNotification(T.addrEmpty, 'danger');
                    return;
                }

                jQuery.ajax({
                    url: '<?= site_url("mitra/updateAlamat") ?>',
                    type: 'POST',
                    data: {
                        <?= csrf_token() ?>: '<?= csrf_hash() ?>',
                        mitra_id: currentMitraId,
                        alamat: newAlamat
                    },
                    success: function(response) {
                        if (response.success) {
                            var option = jQuery('#mitra_select option[value="' + currentMitraId + '"]');
                            var kota = option.data('kota');
                            var provinsi = option.data('provinsi');
                            var mitraNama = option.text().split(' - ')[0];

                            option.attr('data-alamat', newAlamat);

                            var newText = `${mitraNama} - ${newAlamat}, ${kota}, ${provinsi}`;
                            option.text(newText);

                            confirmedMitra.push(currentMitraId);
                            closeAddressModal();

                            setTimeout(function() {
                                showNotification(T.addrUpdated, 'success');

                                setTimeout(function() {
                                    var currentVals = confirmedMitra.slice();

                                    jQuery('#mitra_select').select2('destroy');
                                    jQuery('#mitra_select').trigger('change');

                                    jQuery('#mitra_select').select2({
                                        placeholder: T.selectPartner,
                                        allowClear: true,
                                        width: '100%'
                                    });

                                    jQuery('#mitra_select').val(currentVals).trigger('change');

                                }, 200);
                            }, 400);
                        } else {
                            showNotification('Gagal: ' + (response.message || 'Unknown error'), 'danger');
                        }
                    },
                    error: function() {
                        showNotification(T.addrUpdateFailed, 'danger');
                    }
                });
            };

            // ==========================
            // SELECT2 INIT
            // ==========================
            // Select2 config via object (placeholder/allowClear/width) :contentReference[oaicite:2]{index=2}
            jQuery('#periode_id').select2({
                placeholder: T.selectPeriod,
                allowClear: true,
                width: '100%'
            });
            jQuery('#mitra_select').select2({
                placeholder: T.selectPartner,
                allowClear: true,
                width: '100%'
            });
            jQuery('select[name="subprogram_id[]"]').select2({
                placeholder: T.selectTopicProgram,
                allowClear: true,
                width: '100%'
            });
            jQuery('#luaran_id').select2({
                placeholder: T.selectOutput,
                allowClear: true,
                width: '100%'
            });
            jQuery('select[name="tipe_kegiatan[]"]').select2({
                placeholder: T.selectActivityType,
                allowClear: true,
                width: '100%'
            });
            jQuery('#sumber_dana').select2({
                placeholder: T.selectFundingSource,
                allowClear: true,
                width: '100%'
            });
            jQuery('#anggota_id').select2({
                placeholder: T.selectMembers,
                allowClear: true,
                width: '100%'
            });

            // NEW: Bidang Ilmu Select2
            jQuery('#bidang_ilmu').select2({
                placeholder: "<?= $lang === 'en' ? 'Select Field of Study' : 'Pilih Bidang Ilmu'; ?>",
                allowClear: true,
                width: '100%'
            });
            // END NEW


            // ==========================
            // VALIDASI ALAMAT
            // ==========================
            function validateAddress(alamat, kota, provinsi) {
                var messages = [],
                    isValid = true;

                if (!alamat || alamat.trim().length < 10) {
                    messages.push(T.addrTooShort);
                    isValid = false;
                }
                if (!alamat.toLowerCase().match(/jl\.|jalan|jln\.|gang|gg\./)) messages.push(T.addrIncludeStreet);
                if (!kota || !kota.trim()) {
                    messages.push(T.cityMissing);
                    isValid = false;
                }
                if (!provinsi || !provinsi.trim()) {
                    messages.push(T.provinceMissing);
                    isValid = false;
                }
                if (alamat.toLowerCase().match(/belum diisi|n\/a|tidak ada/)) {
                    messages.push(T.addrNotFilled);
                    isValid = false;
                }

                return {
                    isValid: isValid,
                    messages: messages
                };
            }

            // ==========================
            // EVENT SELECT MITRA
            // ==========================
            jQuery('#mitra_select').on('select2:select', function(e) {
                var selectedOption = e.params.data.element;
                var mitraId = e.params.data.id;
                var alamat = jQuery(selectedOption).data('alamat');
                var kota = jQuery(selectedOption).data('kota');
                var provinsi = jQuery(selectedOption).data('provinsi');

                if (confirmedMitra.indexOf(mitraId) === -1) {
                    currentMitraId = mitraId;
                    jQuery('#mitra_address_display').html(`${alamat}, ${kota}, ${provinsi}`);
                    jQuery('#new_alamat').val(alamat);

                    var validation = validateAddress(alamat, kota, provinsi);
                    var messageDiv = jQuery('#address_validation_message');

                    if (!validation.isValid) {
                        var html = `<div class="alert alert-warning mt-2"><strong>${T.warn}</strong><ul class="mb-0 mt-1">`;
                        validation.messages.forEach(m => html += `<li>${m}</li>`);
                        messageDiv.html(html + '</ul></div>');
                    } else if (validation.messages.length > 0) {
                        var html = `<div class="alert alert-info mt-2"><strong>${T.suggestion}</strong><ul class="mb-0 mt-1">`;
                        validation.messages.forEach(m => html += `<li>${m}</li>`);
                        messageDiv.html(html + '</ul></div>');
                    } else {
                        messageDiv.html(`<div class="alert alert-success mt-2"><i class="fas fa-check-circle"></i> ${T.addrValid}</div>`);
                    }

                    showAddressModal();
                }
            });

            jQuery('#mitra_select').on('select2:unselect', function(e) {
                confirmedMitra = confirmedMitra.filter(id => id !== e.params.data.id);
            });

            // ==========================
            // VALIDASI SUBMIT
            // ==========================
            jQuery('#submit_btn').on('click', function(e) {
                var selectedMitra = jQuery('#mitra_select').val();
                if (selectedMitra) {
                    for (var i = 0; i < selectedMitra.length; i++) {
                        if (confirmedMitra.indexOf(selectedMitra[i]) === -1) {
                            e.preventDefault();
                            showNotification(T.confirmAllPartners, 'warning');
                            return false;
                        }
                    }
                }
            });

            // =======================================================
            // MAHASISWA
            // =======================================================
            const PH_STUDENT_NAME = T.studentNamePh;
            const PH_STUDENT_NPM = T.studentNpmPh;
            const TXT_CHOOSE_PRODI = T.chooseProdi;
            const BTN_DELETE = T.delete;
            const ALERT_MIN_2 = T.min2StudentsAlert;

            document.getElementById('add_mahasiswa').addEventListener('click', function() {
                let row = `
                <div class="mahasiswa_row row mb-2">
                    <div class="col-md-4">
                        <input type="text" name="mahasiswa_nama[]" class="form-control" placeholder="${PH_STUDENT_NAME}">
                    </div>

                    <div class="col-md-3">
                        <input type="text" name="mahasiswa_npm[]" class="form-control" placeholder="${PH_STUDENT_NPM}">
                    </div>

                    <div class="col-md-4">
                        <select name="mahasiswa_jurusan_id[]" class="form-control">
                            <option value="">${TXT_CHOOSE_PRODI}</option>
                            <?php foreach ($jurusan as $v): ?>
                            <option value="<?= $v->jurusan_id; ?>">
                                <?= $v->jurusan_name; ?> - <?= $v->fakultas_name; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger btn-sm remove_mahasiswa">${BTN_DELETE}</button>
                    </div>
                </div>
            `;
                document.getElementById('mahasiswa_container').insertAdjacentHTML('beforeend', row);
            });

            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove_mahasiswa')) {
                    let total = document.querySelectorAll('#mahasiswa_container .mahasiswa_row').length;
                    if (total <= 2) {
                        alert(ALERT_MIN_2);
                        return;
                    }
                    e.target.closest('.mahasiswa_row').remove();
                }
            });

        });
    });
</script>

<?= $this->endSection() ?>
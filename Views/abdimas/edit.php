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

// ========== TRANSLATION ARRAY ==========
$t = [
    'id' => [
        'dashboard' => 'Dashboard',
        'data_abdimas' => 'Data Abdimas',
        'pilih_periode' => 'Pilih Periode',
        'pilih_periode_ph' => '&mdash;PILIH PERIODE&mdash;',
        'pendaftaran_ditutup' => 'Pendaftaran ditutup',
        'nidn' => 'NIDN',
        'nama_ketua' => 'Nama Ketua',
        'sinta_id' => 'SINTA ID',
        'pilih_mitra' => 'Pilih Mitra',
        'pilih_mitra_ph' => '&mdash;PILIH MITRA UMKM&mdash;',
        'note_mitra' => '<b>Note:</b> Jika mitra belum terdaftar silahkan hubungi Staff LPM UG atau Silahkan',
        'daftarkan_mitra' => 'Daftarkan Mitra',
        'masalah_mitra' => 'Masalah yang Dihadapi Mitra',
        'solusi_mitra' => 'Solusi yang Diberikan',
        'pilih_topik' => 'Pilih Topik - Program - Sub Program',
        'pilih_topik_ph' => '&mdash;PILIH TOPIK - PROGRAM - SUB PROGRAM&mdash;',
        'pilih_luaran' => 'Pilih Beberapa Luaran',
        'note_luaran' => '<b>Note:</b> Dapat memilih lebih dari 1 luaran',
        'pilih_luaran_ph' => '&mdash;PILIH LUARAN&mdash;',
        'pilih_tipe' => 'Pilih Tipe Kegiatan',
        'pilih_tipe_ph' => '&mdash;PILIH TIPE KEGIATAN&mdash;',
        'perorangan' => 'Perorangan',
        'kelompok' => 'Kelompok',
        'estimasi_dana' => 'Estimasi Pendanaan',
        'note_dana' => '(Untuk Pendanaan Per Semester) <b>(Contoh: 10000000 | tanpa "Rp" dan "." (titik) maupun simbol lainnya)</b>',
        'sumber_dana' => 'Sumber Pendanaan',
        'note_sumber' => '<b>Note:</b> Pilih satu atau lebih sumber utama dana kegiatan',
        'pilih_sumber_ph' => '&mdash;PILIH SUMBER DANA&mdash;',
        'internal' => 'Internal (Iuran Anggota)',
        'eksternal_kemendikbud' => 'Eksternal (Kemendikbud)',
        'eksternal_dudi' => 'Eksternal (DUDI)',
        'eksternal_pemda' => 'Eksternal (PEMDA)',
        'eksternal_mitra' => 'Eksternal (MITRA)',
        'masukan_anggota' => 'Masukan Ketua dan Anggota',
        'note_anggota' => '<b>Note:</b> Nama Ketua <b>WAJIB</b> dimasukan kedalam form anggota <b>(Berlaku untuk tipe kegiatan Perorangan / Kelompok)</b>',
        'pilih_anggota_ph' => '&mdash;PILIH ANGGOTA&mdash;',
        'max_anggota' => 'Maksimal 10 anggota. Setiap anggota maksimal terdaftar di 2 laporan.',
        'mahasiswa_terlibat' => 'Mahasiswa Terlibat',
        'note_mahasiswa' => '<b>Note:</b> Jika ada mahasiswa yang terlibat dalam kegiatan. Minimal 2 mahasiswa dapat ditambahkan.',
        'nama_mahasiswa' => 'Nama Mahasiswa',
        'npm' => 'NPM',
        'pilih_jurusan' => 'Pilih Jurusan',
        'pilih_prodi' => 'Pilih Prodi',
        'hapus' => 'Hapus',
        'tambah_mahasiswa' => 'Tambah Mahasiswa',
        'kembali' => 'Kembali',
        'simpan' => 'Simpan',
        'required' => '*',
        'error' => '❌ Error!',
        'berhasil' => '✓ Berhasil!',
        'peringatan' => '⚠️ Peringatan!',
        'menyimpan' => '<i class="fas fa-spinner fa-spin"></i> Menyimpan...',
        'error_max_anggota' => 'Jumlah anggota tidak boleh lebih dari 10 orang!',
        'error_duplikat' => 'Ada anggota yang dipilih lebih dari sekali!',
        'error_max_laporan' => 'sudah terdaftar di',
        'error_max_laporan2' => 'laporan lain. Maksimal 2 laporan per anggota!',
        'error_min_mahasiswa' => 'Jumlah mahasiswa minimal 2 orang!',
    ],
    'en' => [
        'dashboard' => 'Dashboard',
        'data_abdimas' => 'Community Service Data',
        'pilih_periode' => 'Select Period',
        'pilih_periode_ph' => '&mdash;SELECT PERIOD&mdash;',
        'pendaftaran_ditutup' => 'Registration closed',
        'nidn' => 'NIDN',
        'nama_ketua' => 'Team Leader Name',
        'sinta_id' => 'SINTA ID',
        'pilih_mitra' => 'Select Partner',
        'pilih_mitra_ph' => '&mdash;SELECT MSME PARTNER&mdash;',
        'note_mitra' => '<b>Note:</b> If the partner is not registered, please contact LPM UG Staff or',
        'daftarkan_mitra' => 'Register Partner',
        'masalah_mitra' => 'Problems Faced by Partner',
        'solusi_mitra' => 'Solutions Provided',
        'pilih_topik' => 'Select Topic - Program - Sub Program',
        'pilih_topik_ph' => '&mdash;SELECT TOPIC - PROGRAM - SUB PROGRAM&mdash;',
        'pilih_luaran' => 'Select Multiple Outputs',
        'note_luaran' => '<b>Note:</b> You can select more than 1 output',
        'pilih_luaran_ph' => '&mdash;SELECT OUTPUTS&mdash;',
        'pilih_tipe' => 'Select Activity Type',
        'pilih_tipe_ph' => '&mdash;SELECT ACTIVITY TYPE&mdash;',
        'perorangan' => 'Individual',
        'kelompok' => 'Group',
        'estimasi_dana' => 'Funding Estimation',
        'note_dana' => '(For Funding Per Semester) <b>(Example: 10000000 | without "Rp" and "." (dot) or other symbols)</b>',
        'sumber_dana' => 'Funding Source',
        'note_sumber' => '<b>Note:</b> Select one or more main funding sources',
        'pilih_sumber_ph' => '&mdash;SELECT FUNDING SOURCE&mdash;',
        'internal' => 'Internal (Member Contributions)',
        'eksternal_kemendikbud' => 'External (Ministry of Education)',
        'eksternal_dudi' => 'External (Industry)',
        'eksternal_pemda' => 'External (Local Government)',
        'eksternal_mitra' => 'External (Partner)',
        'masukan_anggota' => 'Enter Leader and Members',
        'note_anggota' => '<b>Note:</b> Team Leader Name <b>MUST</b> be entered in the member form <b>(Applies to Individual / Group activity types)</b>',
        'pilih_anggota_ph' => '&mdash;SELECT MEMBERS&mdash;',
        'max_anggota' => 'Maximum 10 members. Each member can be registered in a maximum of 2 reports.',
        'mahasiswa_terlibat' => 'Involved Students',
        'note_mahasiswa' => '<b>Note:</b> If there are students involved in the activity. A minimum of 2 students can be added.',
        'nama_mahasiswa' => 'Student Name',
        'npm' => 'Student ID',
        'pilih_jurusan' => 'Select Department',
        'pilih_prodi' => 'Select Study Program',
        'hapus' => 'Remove',
        'tambah_mahasiswa' => 'Add Student',
        'kembali' => 'Back',
        'simpan' => 'Save',
        'required' => '*',
        'error' => '❌ Error!',
        'berhasil' => '✓ Success!',
        'peringatan' => '⚠️ Warning!',
        'menyimpan' => '<i class="fas fa-spinner fa-spin"></i> Saving...',
        'error_max_anggota' => 'Number of members cannot exceed 10 people!',
        'error_duplikat' => 'There are members selected more than once!',
        'error_max_laporan' => 'is already registered in',
        'error_max_laporan2' => 'other reports. Maximum 2 reports per member!',
        'error_min_mahasiswa' => 'Minimum number of students is 2!',
    ]
];
?>

<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
    <!-- Alert Container untuk validasi real-time -->
    <div id="alert-container" class="container-fluid px-3"></div>

    <div class="section-header">
        <a href="<?= site_url('abdimas'); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
        <h1><?= $title; ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>"><?= $t[$lang]['dashboard']; ?></a></div>
            <div class="breadcrumb-item active"><a href="<?= site_url('abdimas'); ?>"><?= $t[$lang]['data_abdimas']; ?></a></div>
            <div class="breadcrumb-item"><?= $title; ?></div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= site_url('abdimas/' . $abdimas->laporan_id); ?>" method="POST" autocomplete="off" enctype="multipart/form-data" id="form-abdimas">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="_method" value="PATCH">
                            <input type="hidden" name="ketua_id" value="<?= userLogin()->user_id; ?>">

                            <div class="form-group">
                                <label><?= $t[$lang]['pilih_periode']; ?><span class="text-danger"><?= $t[$lang]['required']; ?></span></label>
                                <select name="periode_id" class="form-control select2">
                                    <option selected disabled><?= $t[$lang]['pilih_periode_ph']; ?></option>
                                    <?php foreach ($periode as $mtr => $v_periode): ?>
                                        <?php if ($v_periode->info == 1): ?>
                                            <option value="<?= $v_periode->periode_id; ?>"
                                                <?= $abdimas->periode_id == $v_periode->periode_id ? 'selected' : null; ?>>
                                                <?= $v_periode->periode_name; ?> <?= $v_periode->tahun_ajaran; ?>
                                            </option>
                                        <?php elseif ($v_periode->info == 2) : ?>
                                            <option value="<?= $v_periode->periode_id; ?>"
                                                <?= $abdimas->periode_id == $v_periode->periode_id ? 'selected' : null; ?>>
                                                <?= $v_periode->periode_name; ?> <?= $v_periode->tahun_ajaran; ?>
                                            </option>
                                        <?php elseif ($v_periode->info == 3) : ?>
                                            <option value="<?= $v_periode->periode_id; ?>"
                                                <?= $abdimas->periode_id == $v_periode->periode_id ? 'selected' : null; ?>>
                                                <?= $v_periode->periode_name; ?> <?= $v_periode->tahun_ajaran; ?>
                                            </option>
                                        <?php elseif ($v_periode->info == 0) : ?>
                                            <option value="<?= $v_periode->periode_id; ?>"
                                                <?= $abdimas->periode_id == $v_periode->periode_id ? 'selected' : null; ?>>
                                                <?= $v_periode->periode_name; ?> <?= $v_periode->tahun_ajaran; ?>
                                            </option>
                                        <?php else : ?>
                                            <option disabled value="<?= $v_periode->periode_id; ?>"><?= $v_periode->periode_name; ?> <?= $v_periode->tahun_ajaran; ?> -||- <?= $t[$lang]['pendaftaran_ditutup']; ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label><?= $t[$lang]['nidn']; ?><span class="text-danger"><?= $t[$lang]['required']; ?></span></label>
                                    <input type="text" name="nidn" id="nidn" value="<?= userLogin()->nidn; ?>" class="form-control" disabled autofocus>
                                </div>
                                <div class="form-group col-md-4">
                                    <label><?= $t[$lang]['nama_ketua']; ?><span class="text-danger"><?= $t[$lang]['required']; ?></span></label>
                                    <input type="text" name="ketua_name_display" id="ketua_id" class="form-control" placeholder="<?= userLogin()->user_name; ?>" disabled autofocus>
                                </div>
                                <div class="form-group col-md-4">
                                    <label><?= $t[$lang]['sinta_id']; ?><span class="text-danger"><?= $t[$lang]['required']; ?></span></label>
                                    <input type="text" name="sinta_id" id="sinta_id" value="<?= userLogin()->sinta_id; ?>" class="form-control" disabled autofocus>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?= $t[$lang]['pilih_mitra']; ?><span class="text-danger"><?= $t[$lang]['required']; ?></span> <span class="text-primary"><?= $t[$lang]['note_mitra']; ?> <a href="<?= site_url('mitra/new'); ?>" class="badge badge-primary">"<?= $t[$lang]['daftarkan_mitra']; ?>"</a></span></label>
                                <select name="mitra_id" class="form-control select2">
                                    <option selected disabled><?= $t[$lang]['pilih_mitra_ph']; ?></option>
                                    <?php foreach ($mitra as $mtr => $v_mitra): ?>
                                        <?php if ($v_mitra->role_id == 5): ?>
                                            <option value="<?= $v_mitra->user_id; ?>" <?= $abdimas->mitra_id == $v_mitra->user_id ? 'selected' : null; ?>>
                                                <?= $v_mitra->user_name; ?> - <?= $v_mitra->kota_name; ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="masalah_mitra"><?= $t[$lang]['masalah_mitra']; ?></label>
                                        <textarea name="masalah_mitra" id="masalah_mitra" class="form-control" rows="3"><?= esc(old('masalah_mitra', $abdimas->masalah_mitra ?? '')) ?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="solusi_mitra"><?= $t[$lang]['solusi_mitra']; ?></label>
                                        <textarea name="solusi_mitra" id="solusi_mitra" class="form-control" rows="3"><?= esc(old('solusi_mitra', $abdimas->solusi_mitra ?? '')) ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label><?= $t[$lang]['pilih_topik']; ?><span class="text-danger"><?= $t[$lang]['required']; ?></span></label>
                                <select name="subprogram_id" class="form-control select2">
                                    <option selected disabled><?= $t[$lang]['pilih_topik_ph']; ?></option>
                                    <?php foreach ($subprogram as $mtr => $v_subprogram): ?>
                                        <option value="<?= $v_subprogram->subprogram_id; ?>" <?= $abdimas->subprogram_id == $v_subprogram->subprogram_id ? 'selected' : null; ?>>
                                            <?= $v_subprogram->topik_name; ?> -||- <?= $v_subprogram->program_name; ?> -||- <?= $v_subprogram->subprogram_name; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- NEW: Bidang Ilmu Dropdown -->
                            <div class="form-group">
                                <label><?= $lang === 'en' ? 'Field of Study' : 'Bidang Ilmu'; ?> <span class="text-danger"><?= $t[$lang]['required']; ?></span></label>
                                <select name="bidang_ilmu" id="bidang_ilmu" class="form-control select2" required>
                                    <option value=""><?= $lang === 'en' ? '—Select Field of Study—' : '—Pilih Bidang Ilmu—'; ?></option>
                                    <?php $current_bidang = old('bidang_ilmu', $abdimas->bidang_ilmu ?? ''); ?>
                                    <option value="ipa-matematika" <?= $current_bidang == 'ipa-matematika' ? 'selected' : '' ?>>1. Ilmu Pengetahuan Alam (IPA) & Matematika</option>
                                    <option value="teknik-rekayasa" <?= $current_bidang == 'teknik-rekayasa' ? 'selected' : '' ?>>2. Ilmu Teknik & Rekayasa</option>
                                    <option value="kesehatan-kedokteran" <?= $current_bidang == 'kesehatan-kedokteran' ? 'selected' : '' ?>>3. Ilmu Kesehatan & Kedokteran</option>
                                    <option value="sosial-humaniora-seni" <?= $current_bidang == 'sosial-humaniora-seni' ? 'selected' : '' ?>>4. Ilmu Sosial, Humaniora, & Seni</option>
                                    <option value="pertanian-tanaman" <?= $current_bidang == 'pertanian-tanaman' ? 'selected' : '' ?>>5. Ilmu Pertanian & Tanaman</option>
                                </select>
                            </div>
                            <!-- END NEW -->
                            <div class="form-group">
                                <label><?= $t[$lang]['pilih_luaran']; ?><span class="text-danger"><?= $t[$lang]['required']; ?></span> <span class="text-primary"><?= $t[$lang]['note_luaran']; ?></span></label>
                                <select name="luaran_id[]" class="form-control select2" id="luaran_id" multiple>
                                    <?php
                                    $selected_luaran_ids = [];
                                    foreach ($tagluaran as $v_tagluaran):
                                        if ($abdimas->laporan_id == $v_tagluaran->laporan_id):
                                            if (!in_array($v_tagluaran->luaran_id, $selected_luaran_ids)):
                                                $selected_luaran_ids[] = $v_tagluaran->luaran_id;
                                    ?>
                                                <option value="<?= $v_tagluaran->luaran_id; ?>" selected>
                                                    <?= $v_tagluaran->luaran_name; ?>
                                                </option>
                                            <?php
                                            endif;
                                        endif;
                                    endforeach;
                                    foreach ($luaran as $v_luaran):
                                        if (!in_array($v_luaran->luaran_id, $selected_luaran_ids)):
                                            ?>
                                            <option value="<?= $v_luaran->luaran_id; ?>">
                                                <?= $v_luaran->luaran_name; ?>
                                            </option>
                                    <?php
                                        endif;
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label><?= $t[$lang]['pilih_tipe']; ?><span class="text-danger"><?= $t[$lang]['required']; ?></span></label>
                                <select name="tipe_kegiatan" class="form-control select2" required>
<option value="" <?= (old('tipe_kegiatan') ?: ($abdimas->tipe_kegiatan ?: 'Kelompok')) == '' ? 'selected' : '' ?> disabled><?= $t[$lang]['pilih_tipe_ph']; ?></option>
                                    <option value="Perorangan" <?= (old('tipe_kegiatan') ?: ($abdimas->tipe_kegiatan ?: 'Kelompok')) == 'Perorangan' ? 'selected' : '' ?>><?= $t[$lang]['perorangan']; ?></option>
                                    <option value="Kelompok" <?= (old('tipe_kegiatan') ?: ($abdimas->tipe_kegiatan ?: 'Kelompok')) == 'Kelompok' ? 'selected' : '' ?>><?= $t[$lang]['kelompok']; ?></option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label><?= $t[$lang]['estimasi_dana']; ?><span class="text-danger"><?= $t[$lang]['required']; ?></span> <span class="text-primary"><?= $t[$lang]['note_dana']; ?></span></label>
                                <input type="number" name="range_dana" id="range_dana" class="form-control" value="<?= $abdimas->range_dana; ?>" autofocus>
                            </div>
                            <div class="form-group">
                                <label for="sumber_dana"><?= $t[$lang]['sumber_dana']; ?> <span class="text-danger"><?= $t[$lang]['required']; ?></span></label>
                                <span class="text-primary d-block mb-1"><?= $t[$lang]['note_sumber']; ?></span>
                                <select name="sumber_dana[]" id="sumber_dana" class="form-control select2" multiple required>
                                    <?php
                                    $options = [
                                        'Internal (Iuran Anggota)' => $t[$lang]['internal'],
                                        'Eksternal (Kemendikbud)' => $t[$lang]['eksternal_kemendikbud'],
                                        'Eksternal (DUDI)' => $t[$lang]['eksternal_dudi'],
                                        'Eksternal (PEMDA)' => $t[$lang]['eksternal_pemda'],
                                        'Eksternal (MITRA)' => $t[$lang]['eksternal_mitra']
                                    ];
                                    $selected = old('sumber_dana') ?? (isset($abdimas->sumber_dana) ? explode(', ', $abdimas->sumber_dana) : []);
                                    foreach ($options as $value => $label) {
                                        $isSelected = in_array($value, $selected) ? 'selected' : '';
                                        echo "<option value=\"$value\" $isSelected>$label</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- FORM ANGGOTA dengan Badge Counter -->
                            <div class="form-group">
                                <label><?= $t[$lang]['masukan_anggota']; ?>
                                    <span class="badge badge-info" id="anggota-count">0/10</span>
                                    <span class="text-danger"><?= $t[$lang]['required']; ?></span>
                                </label>
                                <span class="text-primary d-block mb-2">
                                    <?= $t[$lang]['note_anggota']; ?>
                                </span>
                                <select name="anggota_id[]" class="form-control select2" id="anggota_id" multiple>
                                    <?php
                                    $selected_user_ids = [];
                                    foreach ($tags as $v_tags):
                                        if ($abdimas->laporan_id == $v_tags->laporan_id):
                                            $selected_user_ids[] = $v_tags->user_id;

                                            // Cari laporan_count dari dosen
                                            $laporan_count = 0;
                                            foreach ($dosen as $d) {
                                                if ($d->user_id == $v_tags->user_id) {
                                                    $laporan_count = $d->laporan_count ?? 0;
                                                    break;
                                                }
                                            }
                                    ?>
                                            <option value="<?= $v_tags->user_id; ?>" data-laporan-count="<?= $laporan_count; ?>" selected>
                                                <?= $v_tags->user_name; ?>
                                            </option>
                                        <?php
                                        endif;
                                    endforeach;

                                    foreach ($dosen as $v_dosen):
                                        if ($v_dosen->role_id == 4 && !in_array($v_dosen->user_id, $selected_user_ids)):
                                        ?>
                                            <option value="<?= $v_dosen->user_id; ?>" data-laporan-count="<?= $v_dosen->laporan_count ?? 0; ?>">
                                                <?= $v_dosen->user_name; ?>
                                            </option>
                                    <?php
                                        endif;
                                    endforeach;
                                    ?>
                                </select>
                                <small class="text-muted"><?= $t[$lang]['max_anggota']; ?></small>
                            </div>

                            <!-- FORM MAHASISWA dengan Badge Counter -->
                            <div class="form-group">
                                <label><?= $t[$lang]['mahasiswa_terlibat']; ?>
                                    <span class="badge badge-info" id="mahasiswa-count">0</span>
                                </label>
                                <span class="text-primary d-block mb-2">
                                    <?= $t[$lang]['note_mahasiswa']; ?>
                                </span>
                                <div id="mahasiswa_container">
                                    <?php if (isset($mahasiswa) && !empty($mahasiswa)): ?>
                                        <?php foreach ($mahasiswa as $mhs): ?>
                                            <div class="mahasiswa_row row mb-2">
                                                <div class="col-md-4">
                                                    <input type="text" name="mahasiswa_nama[]" class="form-control mahasiswa-nama-input" placeholder="<?= $t[$lang]['nama_mahasiswa']; ?>" value="<?= esc($mhs->mahasiswa_name); ?>">
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="text" name="mahasiswa_npm[]" class="form-control" placeholder="<?= $t[$lang]['npm']; ?>" value="<?= esc($mhs->mahasiswa_npm); ?>">
                                                </div>
                                                <div class="col-md-4">
                                                    <select name="mahasiswa_jurusan_id[]" class="form-control">
                                                        <option value=""><?= $t[$lang]['pilih_jurusan']; ?></option>
                                                        <?php foreach ($jurusan as $jrs => $v_jurusan): ?>
                                                            <option value="<?= $v_jurusan->jurusan_id; ?>" <?= ($mhs->jurusan_id == $v_jurusan->jurusan_id) ? 'selected' : ''; ?>><?= $v_jurusan->jurusan_name; ?> - <?= $v_jurusan->fakultas_name; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="button" class="btn btn-danger btn-sm remove_mahasiswa"><?= $t[$lang]['hapus']; ?></button>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="mahasiswa_row row mb-2">
                                            <div class="col-md-4">
                                                <input type="text" name="mahasiswa_nama[]" class="form-control mahasiswa-nama-input" placeholder="<?= $t[$lang]['nama_mahasiswa']; ?>">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" name="mahasiswa_npm[]" class="form-control" placeholder="<?= $t[$lang]['npm']; ?>">
                                            </div>
                                            <div class="col-md-4">
                                                <select name="mahasiswa_jurusan_id[]" class="form-control">
                                                    <option value=""><?= $t[$lang]['pilih_prodi']; ?></option>
                                                    <?php foreach ($jurusan as $jrs => $v_jurusan): ?>
                                                        <option value="<?= $v_jurusan->jurusan_id; ?>"><?= $v_jurusan->jurusan_name; ?> - <?= $v_jurusan->fakultas_name; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-danger btn-sm remove_mahasiswa"><?= $t[$lang]['hapus']; ?></button>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <button type="button" class="btn btn-success btn-sm" id="add_mahasiswa"><?= $t[$lang]['tambah_mahasiswa']; ?></button>
                            </div>

                            <div class="float-right">
                                <a href="<?= site_url('abdimas'); ?>" class="btn btn-dark"><?= $t[$lang]['kembali']; ?></a>
                                <button type="submit" class="btn btn-primary" id="submit_btn"><?= $t[$lang]['simpan']; ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    // Translation object untuk JavaScript
    const translations = {
        id: {
            error: '<?= $t["id"]["error"]; ?>',
            berhasil: '<?= $t["id"]["berhasil"]; ?>',
            peringatan: '<?= $t["id"]["peringatan"]; ?>',
            menyimpan: '<?= $t["id"]["menyimpan"]; ?>',
            simpan: '<?= $t["id"]["simpan"]; ?>',
            error_max_anggota: '<?= $t["id"]["error_max_anggota"]; ?>',
            error_duplikat: '<?= $t["id"]["error_duplikat"]; ?>',
            error_max_laporan: '<?= $t["id"]["error_max_laporan"]; ?>',
            error_max_laporan2: '<?= $t["id"]["error_max_laporan2"]; ?>',
            error_min_mahasiswa: '<?= $t["id"]["error_min_mahasiswa"]; ?>',
            nama_mahasiswa: '<?= $t["id"]["nama_mahasiswa"]; ?>',
            npm: '<?= $t["id"]["npm"]; ?>',
            pilih_jurusan: '<?= $t["id"]["pilih_jurusan"]; ?>',
            hapus: '<?= $t["id"]["hapus"]; ?>'
        },
        en: {
            error: '<?= $t["en"]["error"]; ?>',
            berhasil: '<?= $t["en"]["berhasil"]; ?>',
            peringatan: '<?= $t["en"]["peringatan"]; ?>',
            menyimpan: '<?= $t["en"]["menyimpan"]; ?>',
            simpan: '<?= $t["en"]["simpan"]; ?>',
            error_max_anggota: '<?= $t["en"]["error_max_anggota"]; ?>',
            error_duplikat: '<?= $t["en"]["error_duplikat"]; ?>',
            error_max_laporan: '<?= $t["en"]["error_max_laporan"]; ?>',
            error_max_laporan2: '<?= $t["en"]["error_max_laporan2"]; ?>',
            error_min_mahasiswa: '<?= $t["en"]["error_min_mahasiswa"]; ?>',
            nama_mahasiswa: '<?= $t["en"]["nama_mahasiswa"]; ?>',
            npm: '<?= $t["en"]["npm"]; ?>',
            pilih_jurusan: '<?= $t["en"]["pilih_jurusan"]; ?>',
            hapus: '<?= $t["en"]["hapus"]; ?>'
        }
    };

    const currentLang = '<?= $lang; ?>';
    const t = translations[currentLang];

    document.addEventListener('DOMContentLoaded', function() {
        const currentLaporanId = <?= $abdimas->laporan_id ?? 0 ?>;

        // ========== FUNGSI ALERT ==========
        function showAlert(message, type = 'danger') {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            let icon = t.error;
            if (type === 'success') icon = t.berhasil;
            else if (type === 'warning') icon = t.peringatan;
            alertDiv.innerHTML = `
            <strong>${icon}</strong> ${message}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        `;
            const container = document.getElementById('alert-container');
            container.innerHTML = '';
            container.appendChild(alertDiv);

            // Auto hide
            setTimeout(() => alertDiv.remove(), 5000);

            // Scroll to top
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // ========== UPDATE COUNTER ANGGOTA ==========
        function updateAnggotaCount() {
            const selected = jQuery('#anggota_id').val() || [];
            const count = selected.filter(v => v !== '').length;
            const counter = document.getElementById('anggota-count');
            counter.textContent = `${count}/10`;
            counter.className = count > 10 ? 'badge badge-danger' : 'badge badge-info';
            return count;
        }

        // ========== UPDATE COUNTER MAHASISWA ==========
        function updateMahasiswaCount() {
            const inputs = document.querySelectorAll('.mahasiswa-nama-input');
            const count = Array.from(inputs).filter(i => i.value.trim() !== '').length;
            const counter = document.getElementById('mahasiswa-count');
            counter.textContent = `${count}/2`;
            counter.className = count > 2 ? 'badge badge-danger' : 'badge badge-info';
            return count;
        }

        // ========== VALIDASI ANGGOTA ==========
        function validateAnggota() {
            const selectedIds = jQuery('#anggota_id').val() || [];
            const validIds = selectedIds.filter(id => id !== '');

            // Cek jumlah maksimal
            if (validIds.length > 10) {
                return {
                    valid: false,
                    message: t.error_max_anggota
                };
            }

            // Cek duplikat dalam form yang sama
            const uniqueIds = [...new Set(validIds)];
            if (uniqueIds.length !== validIds.length) {
                return {
                    valid: false,
                    message: t.error_duplikat
                };
            }

            // Cek setiap anggota sudah berapa laporan
            for (let id of validIds) {
                const option = jQuery('#anggota_id option[value="' + id + '"]')[0];
                if (option) {
                    let laporanCount = parseInt(option.dataset.laporanCount) || 0;
                    if (currentLaporanId > 0) {
                        laporanCount -= 1;
                    }
                    if (laporanCount >= 2) {
                        return {
                            valid: false,
                            message: `"${option.text.trim()}" ${t.error_max_laporan} ${laporanCount} ${t.error_max_laporan2}`
                        };
                    }
                }
            }

            return {
                valid: true
            };
        }

        // ========== VALIDASI MAHASISWA ==========
        function validateMahasiswa() {
            const count = updateMahasiswaCount();
            if (count < 2) {
                return {
                    valid: false,
                    message: t.error_min_mahasiswa
                };
            }
            return {
                valid: true
            };
        }

        // ========== EVENT: ANGGOTA BERUBAH ==========
        jQuery('#anggota_id').on('change', function() {
            updateAnggotaCount();
            const result = validateAnggota();
            if (!result.valid) {
                showAlert(result.message, 'warning');
            }
        });

        // ========== EVENT: MAHASISWA INPUT ==========
        jQuery(document).on('input', '.mahasiswa-nama-input', function() {
            updateMahasiswaCount();
            const result = validateMahasiswa();
            if (!result.valid) {
                showAlert(result.message, 'warning');
            }
        });

        // ========== EVENT: TAMBAH MAHASISWA ==========
        jQuery('#add_mahasiswa').on('click', function() {
            const row = '<div class="mahasiswa_row row mb-2">' +
                '<div class="col-md-4"><input type="text" name="mahasiswa_nama[]" class="form-control mahasiswa-nama-input" placeholder="' + t.nama_mahasiswa + '"></div>' +
                '<div class="col-md-3"><input type="text" name="mahasiswa_npm[]" class="form-control" placeholder="' + t.npm + '"></div>' +
                '<div class="col-md-4"><select name="mahasiswa_jurusan_id[]" class="form-control"><option value="">' + t.pilih_jurusan + '</option><?php foreach ($jurusan as $jrs => $v_jurusan): ?><option value="<?= $v_jurusan->jurusan_id; ?>"><?= $v_jurusan->jurusan_name; ?> - <?= $v_jurusan->fakultas_name; ?></option><?php endforeach; ?></select></div>' +
                '<div class="col-md-1"><button type="button" class="btn btn-danger btn-sm remove_mahasiswa">' + t.hapus + '</button></div>' +
                '</div>';

            jQuery('#mahasiswa_container').append(row);
            updateMahasiswaCount();
        });

        // ========== EVENT: HAPUS MAHASISWA ==========
        jQuery(document).on('click', '.remove_mahasiswa', function() {
            jQuery(this).closest('.mahasiswa_row').remove();
            updateMahasiswaCount();
        });

        // ========== VALIDASI SEBELUM SUBMIT ==========
        jQuery('#form-abdimas').on('submit', function(e) {
            // Clear alerts
            document.getElementById('alert-container').innerHTML = '';

            // Validasi anggota
            const anggotaResult = validateAnggota();
            if (!anggotaResult.valid) {
                e.preventDefault();
                showAlert(anggotaResult.message, 'danger');
                return false;
            }

            // Validasi mahasiswa
            const mahasiswaResult = validateMahasiswa();
            if (!mahasiswaResult.valid) {
                e.preventDefault();
                showAlert(mahasiswaResult.message, 'danger');
                return false;
            }

            // Disable button dan show loading
            const submitBtn = document.getElementById('submit_btn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = t.menyimpan;
        });

        // ========== INISIALISASI SELECT2 ==========
        var selectedLuaran = [];
        jQuery('#luaran_id option:selected').each(function() {
            selectedLuaran.push(jQuery(this).val());
        });

        var selectedSumberDana = [];
        jQuery('#sumber_dana option:selected').each(function() {
            selectedSumberDana.push(jQuery(this).val());
        });

        var selectedAnggota = [];
        jQuery('#anggota_id option:selected').each(function() {
            selectedAnggota.push(jQuery(this).val());
        });

        // Inisialisasi Select2
        jQuery('select[name="periode_id"]').select2({
            placeholder: "<?= $t[$lang]['pilih_periode_ph']; ?>",
            allowClear: true,
            width: '100%'
        });
        jQuery('select[name="mitra_id"]').select2({
            placeholder: "<?= $t[$lang]['pilih_mitra_ph']; ?>",
            allowClear: true,
            width: '100%'
        });
        jQuery('select[name="subprogram_id"]').select2({
            placeholder: "<?= $t[$lang]['pilih_topik_ph']; ?>",
            allowClear: true,
            width: '100%'
        });
        jQuery('#luaran_id').select2({
            placeholder: "<?= $t[$lang]['pilih_luaran_ph']; ?>",
            allowClear: true,
            width: '100%'
        });
        jQuery('select[name="tipe_kegiatan"]').select2({
            placeholder: "<?= $t[$lang]['pilih_tipe_ph']; ?>",
            allowClear: true,
            width: '100%'
        });
        jQuery('#sumber_dana').select2({
            placeholder: "<?= $t[$lang]['pilih_sumber_ph']; ?>",
            allowClear: true,
            width: '100%'
        });
        jQuery('#anggota_id').select2({
            placeholder: "<?= $t[$lang]['pilih_anggota_ph']; ?>",
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

        // Restore value yang sebelumnya dipilih
        jQuery('#luaran_id').val(selectedLuaran).trigger('change');
        jQuery('#sumber_dana').val(selectedSumberDana).trigger('change');
        jQuery('#anggota_id').val(selectedAnggota).trigger('change');

        // Update counter
        updateAnggotaCount();
        updateMahasiswaCount();
    });
</script>

<style>
    .mahasiswa_row {
        padding: 10px;
        background: #f9f9f9;
        border: 1px solid #e3e6f0;
        border-radius: 5px;
    }

    #alert-container {
        position: relative;
        z-index: 1050;
        margin-bottom: 15px;
    }

    #alert-container .alert {
        margin-bottom: 0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .badge {
        font-size: 0.85em;
        padding: 5px 10px;
        vertical-align: middle;
    }
</style>

<?= $this->endSection() ?>
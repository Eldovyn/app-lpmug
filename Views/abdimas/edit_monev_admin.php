<?php
// ====== LANG (ID/EN) IN ONE VIEW (COOKIE) ======
helper(['cookie', 'url']);

$request = service('request');

$allowed = ['id', 'en'];
$lang = get_cookie('lang') ?: 'id';
if (! in_array($lang, $allowed, true)) {
    $lang = 'id';
}

// switch bahasa via query param: ?lang=id / ?lang=en
$reqLang = $request->getGet('lang');
if ($reqLang && in_array($reqLang, $allowed, true)) {
    set_cookie('lang', $reqLang, 60 * 60 * 24 * 30); // 30 hari
    $lang = $reqLang;
}

// Kamus terjemahan (SEMUA DI VIEW INI)
$TR = [
    'id' => [
        'btn_back' => 'Kembali',
        'btn_save' => 'Simpan',

        'breadcrumb_dashboard' => 'Dashboard',
        'breadcrumb_monev' => 'Monitoring dan Evaluasi',

        'title_center_1' => 'MONITORING DAN EVALUASI KEGIATAN',
        'title_center_2' => 'PROGRAM PENGABDIAN KEPADA MASYARAKAT',
        'title_center_3' => 'UNIVERSITAS GUNADARMA PERIODE',

        'label_leader' => 'Nama Ketua Tim PKM',
        'label_date' => 'Tanggal Pelaksanaan',
        'label_partner' => 'Nama Mitra PKM',
        'label_activity_title' => 'Judul Kegiatan PKM',
        'label_member_count' => 'Jumlah Anggota Tim',

        'msg_no_date' => 'Belum ada tanggal kegiatan',
        'msg_no_title' => 'Belum ada Judul / Nama Kegiatan',
        'msg_no_value' => 'Belum ada Nilai',

        'people_present_note' => 'Orang (yang hadir, presensi terlampir)',

        'note_label' => 'Note',
        'note_text' => 'Periksa kembali isian nilai anda sebelum submit, penilaian hanya dapat dilakukan 1x dan tidak dapat diubah setelah anda klik tombol submit',

        'th_no' => 'No',
        'th_component' => 'Komponen',
        'th_desc' => 'Keterangan',
        'th_weight' => 'Bobot',
        'th_team' => 'Nilai Team',
        'th_lpm' => 'Nilai LPM',

        'sec1_title' => 'Penilaian berdasarkan Pelaksana Kegiatan Pengabdian Kepada Masyarakat',
        'sec1_a' => 'a. Materi dan pelaksanaan kegiatan',
        'sec1_a_1' => '1. Kesesuaian dengan kebutuhan Mitra',
        'sec1_a_2' => '2. Kelengkapan Materi dalam menyelesaikan masalah Mitra',
        'sec1_a_3' => '3. Akses materi oleh Mitra (Kemudahan Mitra memperoleh Materi)',
        'sec1_a_4' => '4. Kesiapan luaran kegiatan',
        'sec1_a_5' => '5. Kesiapan dan pelaksanaan kegiatan',

        'sec1_b' => 'b. Peran dan Kontribusi Anggota',
        'sec1_b_1' => '1. Kesesuaian dan kelengkapan bidang ilmu dalam menyelesaikan masalah Mitra',
        'sec1_b_2' => '2. Kehadiran dan kontribusi setiap anggota dalam kegiatan',

        'sec2_title' => 'Kondisi Mitra',
        'sec2_1' => '1. Partisipasi Mitra saat kegiatan',
        'sec2_2' => '2. Manfaat yang dirasakan Mitra',

        'label_suggestion' => 'Saran & Masukan',
        'avg_title_component' => 'Komponen',
        'avg_title_total'     => 'Total Nilai',
        'avg_overall'         => 'Rata-rata Keseluruhan',
    ],
    'en' => [
        'avg_title_component' => 'Component',
        'avg_title_total'     => 'Total Score',
        'avg_overall'         => 'Overall Average',
        'btn_back' => 'Back',
        'btn_save' => 'Save',

        'breadcrumb_dashboard' => 'Dashboard',
        'breadcrumb_monev' => 'Monitoring & Evaluation',

        'title_center_1' => 'MONITORING AND EVALUATION',
        'title_center_2' => 'COMMUNITY SERVICE PROGRAM',
        'title_center_3' => 'GUNADARMA UNIVERSITY PERIOD',

        'label_leader' => 'Team Leader Name',
        'label_date' => 'Activity Date',
        'label_partner' => 'Partner Name',
        'label_activity_title' => 'Activity Title',
        'label_member_count' => 'Number of Team Members',

        'msg_no_date' => 'No activity date yet',
        'msg_no_title' => 'No activity title yet',
        'msg_no_value' => 'No score yet',

        'people_present_note' => 'people (present, attendance attached)',

        'note_label' => 'Note',
        'note_text' => 'Please re-check your scores before submitting. Assessment can only be submitted once and cannot be changed after submission.',

        'th_no' => 'No',
        'th_component' => 'Component',
        'th_desc' => 'Description',
        'th_weight' => 'Weight',
        'th_team' => 'Team Score',
        'th_lpm' => 'LPM Score',

        'sec1_title' => 'Assessment based on Community Service Activity Implementation',
        'sec1_a' => 'a. Materials and implementation',
        'sec1_a_1' => '1. Alignment with partner needs',
        'sec1_a_2' => '2. Completeness of materials in solving partner problems',
        'sec1_a_3' => '3. Partner access to materials (ease of obtaining materials)',
        'sec1_a_4' => '4. Readiness of activity outputs',
        'sec1_a_5' => '5. Readiness and implementation of the activity',

        'sec1_b' => 'b. Member roles and contributions',
        'sec1_b_1' => '1. Suitability and completeness of expertise to solve partner problems',
        'sec1_b_2' => '2. Attendance and contribution of each member during the activity',

        'sec2_title' => 'Partner Condition',
        'sec2_1' => '1. Partner participation during the activity',
        'sec2_2' => '2. Benefits felt by the partner',

        'label_suggestion' => 'Suggestions & Feedback',
    ],
];

// IMPORTANT: simpan ke $GLOBALS biar aman dipakai di function t()
$GLOBALS['I18N_TR'] = $TR;
$GLOBALS['I18N_LANG'] = $lang;

if (! function_exists('t')) {
    function t(string $key): string
    {
        $TR = $GLOBALS['I18N_TR'] ?? [];
        $lang = $GLOBALS['I18N_LANG'] ?? 'id';

        return $TR[$lang][$key] ?? $TR['id'][$key] ?? $key;
    }
}

if (! function_exists('lang_url')) {
    function lang_url(string $locale): string
    {
        $request = service('request');
        $base = current_url(); // tanpa query string
        $q = $request->getGet();
        $q['lang'] = $locale;

        return $base . '?' . http_build_query($q);
    }
}
?>

<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>

<style>
    .table-bordered td,
    .table-bordered th {
        border: 1px solid #dee2e6;
    }

    .table td,
    .table th {
        vertical-align: middle;
    }
</style>

<!-- Library untuk export Excel dan PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>

<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <a href="<?= site_url('rekapan'); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
        <h1><?= $title; ?></h1>

        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>"><?= t('breadcrumb_dashboard') ?></a></div>
            <div class="breadcrumb-item active"><a href="<?= site_url('rekapan'); ?>"><?= t('breadcrumb_monev') ?></a></div>
            <div class="breadcrumb-item"><?= $title; ?></div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= site_url('monevadmin/' . $abdimas->laporan_id); ?>" method="POST"
                            autocomplete="off" enctype="multipart/form-data">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="_method" value="PATCH">

                            <div class="text-center mb-4">
                                <h5><?= t('title_center_1') ?></h5>
                                <h5><?= t('title_center_2') ?></h5>
                                <h5><?= t('title_center_3') ?>
                                    <span class="text-primary">
                                        <?php foreach ($periode as $mtr => $v_periode) : ?>
                                            <?php if ($abdimas->periode_id == $v_periode->periode_id) : ?>
                                                <?= $v_periode->periode_name; ?> <?= $v_periode->tahun_ajaran; ?>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </span>
                                </h5>
                            </div>

                            <div class="mb-3">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td class="col-2 font-weight-bold" style="border: 1px solid"><?= t('label_leader') ?></td>
                                            <td class="col-5" style="border: 1px solid">:
                                                <?php foreach ($tags as $ds => $v_tags) : ?>
                                                    <?php if ($abdimas->laporan_id == $v_tags->laporan_id) : ?>
                                                        <?php if ($v_tags->anggota_id == $abdimas->ketua_id) : ?>
                                                            <input type="hidden" name="ketua_id" id="ketua_id" class="form-control"
                                                                placeholder="<?= $v_tags->user_name; ?>" readonly autofocus>
                                                            <?= $v_tags->user_name; ?> <br>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </td>

                                            <td class="font-weight-bold" style="border: 1px solid"><?= t('label_date') ?></td>
                                            <td style="border: 1px solid">
                                                <?php if (empty($abdimas->tanggal_kegiatan)) : ?>
                                                    <span class="text-danger"><?= t('msg_no_date') ?></span>
                                                <?php else : ?>
                                                    <?php
                                                    // Biar nggak error double declare
                                                    if (!function_exists('formatTanggalID')) {
                                                        function formatTanggalID($tanggal)
                                                        {
                                                            if (empty($tanggal)) return '-';

                                                            $dateObj = date_create(trim($tanggal));
                                                            if (!$dateObj) return '-';

                                                            // Format: 1 January 2025 (month name mengikuti locale server PHP)
                                                            return date_format($dateObj, 'j F Y');
                                                        }
                                                    }

                                                    // Pisahkan tanggal mulai & selesai
                                                    $tanggal = array_map('trim', explode(' - ', $abdimas->tanggal_kegiatan));
                                                    $tanggalMulai = $tanggal[0] ?? null;
                                                    $tanggalSelesai = $tanggal[1] ?? null;

                                                    $formattedMulai = formatTanggalID($tanggalMulai);
                                                    $formattedSelesai = formatTanggalID($tanggalSelesai);
                                                    ?>
                                                    <span><?= $formattedMulai ?> — <?= $formattedSelesai ?></span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="col-2 font-weight-bold" style="border: 1px solid"><?= t('label_partner') ?></td>
                                            <td colspan="3" style="border: 1px solid">:
                                                <?php foreach ($mitra as $mtr => $v_mitra) : ?>
                                                    <?php if ($abdimas->mitra_id == $v_mitra->user_id) : ?>
                                                        <?= $v_mitra->user_name; ?>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="col-2 font-weight-bold" style="border: 1px solid"><?= t('label_activity_title') ?></td>
                                            <td colspan="3" style="border: 1px solid">:
                                                <?php if ($abdimas->judul_kegiatan == null) : ?>
                                                    <span class="text-danger"><?= t('msg_no_title') ?></span>
                                                <?php else : ?>
                                                    <?= $abdimas->judul_kegiatan; ?>
                                                    <input type="hidden" name="judul_kegiatan" id="judul_kegiatan"
                                                        class="form-control" value="<?= $abdimas->judul_kegiatan; ?>"
                                                        autofocus readonly>
                                                <?php endif; ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="col-2 font-weight-bold" style="border: 1px solid"><?= t('label_member_count') ?></td>
                                            <td colspan="3" style="border: 1px solid">
                                                <?php
                                                $seen = [];

                                                foreach ($tags as $key => $v_tags) {
                                                    if ($abdimas->laporan_id == $v_tags->laporan_id && !isset($seen[$v_tags->laporan_id])) {
                                                        $seen[$v_tags->laporan_id] = [];
                                                    }
                                                }

                                                foreach ($tags as $key => $v_tags) {
                                                    if ($abdimas->laporan_id == $v_tags->laporan_id && isset($seen[$v_tags->laporan_id])) {
                                                        $seen[$v_tags->laporan_id][] = $v_tags->user_id;
                                                    }
                                                }

                                                foreach ($seen as $laporan_id => $users) {
                                                    echo count($users);
                                                }
                                                ?>
                                                <?= t('people_present_note') ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <span class="text-primary">
                                <b><?= t('note_label') ?>:</b> <?= t('note_text') ?>
                            </span>

                            <table class="table table-bordered" style="border: 1px solid; margin-top: 10px;">
                                <thead>
                                    <tr>
                                        <th style="border: 1px solid"><?= t('th_no') ?></th>
                                        <th style="border: 1px solid"><?= t('th_component') ?></th>
                                        <th style="border: 1px solid"><?= t('th_desc') ?></th>
                                        <th class="text-center" style="border: 1px solid"><?= t('th_weight') ?></th>
                                        <th class="text-center" style="border: 1px solid"><?= t('th_team') ?></th>
                                        <th class="text-center" style="border: 1px solid"><?= t('th_lpm') ?></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <th scope="row" rowspan="9" style="border: 1px solid">1</th>
                                        <td colspan="5" style="border: 1px solid">
                                            <?= t('sec1_title') ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td rowspan="6" style="border: 1px solid"><?= t('sec1_a') ?></td>
                                    </tr>

                                    <tr>
                                        <td style="border: 1px solid"><?= t('sec1_a_1') ?></td>
                                        <td class="text-center" style="border: 1px solid">30</td>
                                        <td class="text-center" style="border: 1px solid; width:150px;">
                                            <?php if ($abdimas->nt1 == null) : ?>
                                                <span class="text-danger"><?= t('msg_no_value') ?></span>
                                            <?php else : ?>
                                                <?= $abdimas->nt1; ?>
                                                <input type="hidden" name="nt1" id="nt1" class="form-control"
                                                    value="<?= $abdimas->nt1; ?>" autofocus readonly>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center" style="border: 1px solid; width:150px;">
                                            <input type="number"
                                                name="nlpm1"
                                                id="nlpm1"
                                                class="form-control"
                                                min="1"
                                                max="30"
                                                value="<?= old('nlpm1', $rekapan->nlpm1 ?? '') ?>">
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="border: 1px solid"><?= t('sec1_a_2') ?></td>
                                        <td class="text-center" style="border: 1px solid">20</td>
                                        <td class="text-center" style="border: 1px solid; width:150px;">
                                            <?php if ($abdimas->nt2 == null) : ?>
                                                <span class="text-danger"><?= t('msg_no_value') ?></span>
                                            <?php else : ?>
                                                <?= $abdimas->nt2; ?>
                                                <input type="hidden" name="nt2" id="nt2" class="form-control"
                                                    value="<?= $abdimas->nt2; ?>" autofocus readonly>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center" style="border: 1px solid; width:150px;">
                                            <input type="number"
                                                name="nlpm2"
                                                id="nlpm2"
                                                class="form-control"
                                                min="1"
                                                max="20"
                                                value="<?= old('nlpm2', $rekapan->nlpm2 ?? '') ?>">
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="border: 1px solid"><?= t('sec1_a_3') ?></td>
                                        <td class="text-center" style="border: 1px solid">10</td>
                                        <td class="text-center" style="border: 1px solid; width:150px;">
                                            <?php if ($abdimas->nt3 == null) : ?>
                                                <span class="text-danger"><?= t('msg_no_value') ?></span>
                                            <?php else : ?>
                                                <?= $abdimas->nt3; ?>
                                                <input type="hidden" name="nt3" id="nt3" class="form-control"
                                                    value="<?= $abdimas->nt3; ?>" autofocus readonly>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center" style="border: 1px solid; width:150px;">
                                            <input type="number"
                                                name="nlpm3"
                                                id="nlpm3"
                                                class="form-control"
                                                min="1"
                                                max="10"
                                                value="<?= old('nlpm3', $rekapan->nlpm3 ?? '') ?>">
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="border: 1px solid"><?= t('sec1_a_4') ?></td>
                                        <td class="text-center" style="border: 1px solid">20</td>
                                        <td class="text-center" style="border: 1px solid; width:150px;">
                                            <?php if ($abdimas->nt4 == null) : ?>
                                                <span class="text-danger"><?= t('msg_no_value') ?></span>
                                            <?php else : ?>
                                                <?= $abdimas->nt4; ?>
                                                <input type="hidden" name="nt4" id="nt4" class="form-control"
                                                    value="<?= $abdimas->nt4; ?>" autofocus readonly>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center" style="border: 1px solid; width:150px;">
                                            <input type="number"
                                                name="nlpm4"
                                                id="nlpm4"
                                                class="form-control"
                                                min="1"
                                                max="20"
                                                value="<?= old('nlpm4', $rekapan->nlpm4 ?? '') ?>">
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="border: 1px solid"><?= t('sec1_a_5') ?></td>
                                        <td class="text-center" style="border: 1px solid">20</td>
                                        <td class="text-center" style="border: 1px solid; width:150px;">
                                            <?php if ($abdimas->nt5 == null) : ?>
                                                <span class="text-danger"><?= t('msg_no_value') ?></span>
                                            <?php else : ?>
                                                <?= $abdimas->nt5; ?>
                                                <input type="hidden" name="nt5" id="nt5" class="form-control"
                                                    value="<?= $abdimas->nt5; ?>" autofocus readonly>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center" style="border: 1px solid; width:150px;">
                                            <input type="number"
                                                name="nlpm5"
                                                id="nlpm5"
                                                class="form-control"
                                                min="1"
                                                max="20"
                                                value="<?= old('nlpm5', $rekapan->nlpm5 ?? '') ?>">
                                        </td>
                                    </tr>

                                    <tr>
                                        <td rowspan="2" style="border: 1px solid"><?= t('sec1_b') ?></td>
                                        <td style="border: 1px solid"><?= t('sec1_b_1') ?></td>
                                        <td class="text-center" style="border: 1px solid">40</td>
                                        <td class="text-center" style="border: 1px solid; width:150px;">
                                            <?php if ($abdimas->nt6 == null) : ?>
                                                <span class="text-danger"><?= t('msg_no_value') ?></span>
                                            <?php else : ?>
                                                <?= $abdimas->nt6; ?>
                                                <input type="hidden" name="nt6" id="nt6" class="form-control"
                                                    value="<?= $abdimas->nt6; ?>" autofocus readonly>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center" style="border: 1px solid; width:150px;">
                                            <input type="number"
                                                name="nlpm6"
                                                id="nlpm6"
                                                class="form-control"
                                                min="1"
                                                max="40"
                                                value="<?= old('nlpm6', $rekapan->nlpm6 ?? '') ?>">
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="border: 1px solid"><?= t('sec1_b_2') ?></td>
                                        <td class="text-center" style="border: 1px solid">60</td>
                                        <td class="text-center" style="border: 1px solid; width:150px;">
                                            <?php if ($abdimas->nt7 == null) : ?>
                                                <span class="text-danger"><?= t('msg_no_value') ?></span>
                                            <?php else : ?>
                                                <?= $abdimas->nt7; ?>
                                                <input type="hidden" name="nt7" id="nt7" class="form-control"
                                                    value="<?= $abdimas->nt7; ?>" autofocus readonly>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center" style="border: 1px solid; width:150px;">
                                            <input type="number"
                                                name="nlpm7"
                                                id="nlpm7"
                                                class="form-control"
                                                min="1"
                                                max="60"
                                                value="<?= old('nlpm7', $rekapan->nlpm7 ?? '') ?>">
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row" rowspan="2" style="border: 1px solid">2</th>
                                        <td rowspan="2" style="border: 1px solid"><?= t('sec2_title') ?></td>
                                        <td style="border: 1px solid"><?= t('sec2_1') ?></td>
                                        <td class="text-center" style="border: 1px solid">40</td>
                                        <td class="text-center" style="border: 1px solid; width:150px;">
                                            <?php if ($abdimas->nt8 == null) : ?>
                                                <span class="text-danger"><?= t('msg_no_value') ?></span>
                                            <?php else : ?>
                                                <?= $abdimas->nt8; ?>
                                                <input type="hidden" name="nt8" id="nt8" class="form-control"
                                                    value="<?= $abdimas->nt8; ?>" autofocus readonly>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center" style="border: 1px solid; width:150px;">
                                            <input type="number"
                                                name="nlpm8"
                                                id="nlpm8"
                                                class="form-control"
                                                min="1"
                                                max="40"
                                                value="<?= old('nlpm8', $rekapan->nlpm8 ?? '') ?>">
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="border: 1px solid"><?= t('sec2_2') ?></td>
                                        <td class="text-center" style="border: 1px solid">60</td>
                                        <td class="text-center" style="border: 1px solid; width:150px;">
                                            <?php if ($abdimas->nt9 == null) : ?>
                                                <span class="text-danger"><?= t('msg_no_value') ?></span>
                                            <?php else : ?>
                                                <?= $abdimas->nt9; ?>
                                                <input type="hidden" name="nt9" id="nt9" class="form-control"
                                                    value="<?= $abdimas->nt9; ?>" autofocus readonly>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center" style="border: 1px solid; width:150px;">
                                            <input type="number"
                                                name="nlpm9"
                                                id="nlpm9"
                                                class="form-control"
                                                min="1"
                                                max="60"
                                                value="<?= old('nlpm9', $rekapan->nlpm9 ?? '') ?>">
                                        </td>
                                    </tr>
                                    <?php
                                    // ================== HITUNG NILAI RATA-RATA ==================
                                    function nilai($nlpm, $nt)
                                    {
                                        if ($nlpm !== null && $nlpm !== '') return (int)$nlpm;
                                        if ($nt !== null && $nt !== '') return (int)$nt;
                                        return 0;
                                    }

                                    // a. Materials and implementation (1–5)
                                    $totalA =
                                        nilai($rekapan->nlpm1 ?? null, $abdimas->nt1 ?? null) +
                                        nilai($rekapan->nlpm2 ?? null, $abdimas->nt2 ?? null) +
                                        nilai($rekapan->nlpm3 ?? null, $abdimas->nt3 ?? null) +
                                        nilai($rekapan->nlpm4 ?? null, $abdimas->nt4 ?? null) +
                                        nilai($rekapan->nlpm5 ?? null, $abdimas->nt5 ?? null);

                                    // b. Member roles and contributions (6–7)
                                    $totalB =
                                        nilai($rekapan->nlpm6 ?? null, $abdimas->nt6 ?? null) +
                                        nilai($rekapan->nlpm7 ?? null, $abdimas->nt7 ?? null);

                                    // Partner Condition (8–9)
                                    $totalC =
                                        nilai($rekapan->nlpm8 ?? null, $abdimas->nt8 ?? null) +
                                        nilai($rekapan->nlpm9 ?? null, $abdimas->nt9 ?? null);

                                    // rata-rata keseluruhan
                                    $totalAll = $totalA + $totalB + $totalC;
                                    $avgAll = round($totalAll / 3, 2);
                                    ?>

                                    <tr>
                                        <td colspan="6" style="border: 1px solid; width:150px;">
                                            <label class="font-weight-bold mt-1"><?= t('label_suggestion') ?>:</label><br>
                                            <?php if ($rekapan->saran_masukan == null) : ?>
                                                <textarea name="saran_masukan" id="saran_masukan" class="form-control mb-2" style="height:150px;"></textarea>
                                            <?php else : ?>
                                                <p class="text-break"><?= $rekapan->saran_masukan; ?></p>
                                                <textarea name="saran_masukan" id="saran_masukan" class="form-control" hidden readonly><?= $rekapan->saran_masukan; ?></textarea>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- ===================== TABEL RATA-RATA ===================== -->
                            <div class="mt-4">
                                <table class="table table-bordered" style="border:1px solid;">
                                    <thead>
                                        <tr class="text-center">
                                            <th style="border:1px solid; width:70%;">
                                                <?= t('avg_title_component') ?>
                                            </th>
                                            <th style="border:1px solid; width:30%;">
                                                <?= t('avg_title_total') ?>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="border:1px solid;"><?= t('sec1_a') ?></td>
                                            <td class="text-center" style="border:1px solid;"><?= $totalA ?></td>
                                        </tr>
                                        <tr>
                                            <td style="border:1px solid;"><?= t('sec1_b') ?></td>
                                            <td class="text-center" style="border:1px solid;"><?= $totalB ?></td>
                                        </tr>
                                        <tr>
                                            <td style="border:1px solid;"><?= t('sec2_title') ?></td>
                                            <td class="text-center" style="border:1px solid;"><?= $totalC ?></td>
                                        </tr>
                                        <tr class="font-weight-bold bg-light">
                                            <td style="border:1px solid;">
                                                <?= t('avg_overall') ?>
                                            </td>
                                            <td class="text-center" style="border:1px solid;">
                                                <?= $avgAll ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Tombol Download Table -->
                            <div class="float-left mb-3">
                                <button type="button" class="btn btn-success" id="downloadExcel">
                                    <i class="fas fa-file-excel"></i> Download Excel
                                </button>
                                <button type="button" class="btn btn-info" id="downloadPDF">
                                    <i class="fas fa-file-pdf"></i> Download PDF
                                </button>
                            </div>
                            <div class="float-right">
                                <a href="<?= site_url('rekapan'); ?>" class="btn btn-dark"><?= t('btn_back') ?></a>
                                <button type="submit" class="btn btn-primary"><?= t('btn_save') ?></button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===================== HANDLE INPUT NLPM1 - NLPM9 (NO UI/UX CHANGE) ===================== -->
<script>
    (function() {
        const MAX = {
            nlpm1: 30,
            nlpm2: 20,
            nlpm3: 10,
            nlpm4: 20,
            nlpm5: 20,
            nlpm6: 40,
            nlpm7: 60,
            nlpm8: 40,
            nlpm9: 60
        };
        const MIN_DEFAULT = 1;

        function sanitizeToInt(value) {
            // buang semua selain digit (cegah 1e3, -, +, koma, spasi, dll)
            const digits = String(value ?? '').replace(/[^\d]/g, '');
            if (digits === '') return '';
            const n = parseInt(digits, 10);
            return Number.isFinite(n) ? n : '';
        }

        function clamp(name, value) {
            if (value === '') return '';
            const max = MAX[name] ?? 999999;
            let n = value;

            if (n < MIN_DEFAULT) n = MIN_DEFAULT;
            if (n > max) n = max;

            return n;
        }

        function bindInput(id) {
            const el = document.getElementById(id);
            if (!el) return;

            // kalau sudah ada nilai, di view kamu jadi hidden+readonly -> biarkan
            if (el.type === 'hidden' || el.hasAttribute('readonly') || el.disabled) return;

            // cegah scroll wheel ngubah angka saat fokus
            el.addEventListener('wheel', function(e) {
                if (document.activeElement === el) e.preventDefault();
            }, {
                passive: false
            });

            // sanitize + clamp realtime
            el.addEventListener('input', function() {
                const sanitized = sanitizeToInt(el.value);
                const clamped = clamp(id, sanitized === '' ? '' : sanitized);
                el.value = clamped;
            });

            // clamp saat blur
            el.addEventListener('blur', function() {
                const sanitized = sanitizeToInt(el.value);
                const clamped = clamp(id, sanitized === '' ? '' : sanitized);
                el.value = clamped;
            });
        }

        // bind nlpm1..nlpm9
        for (let i = 1; i <= 9; i++) bindInput('nlpm' + i);

        // clamp terakhir saat submit (jaga-jaga)
        const form = document.querySelector('form[action*="monevadmin"]');
        if (form) {
            form.addEventListener('submit', function() {
                for (let i = 1; i <= 9; i++) {
                    const id = 'nlpm' + i;
                    const el = document.getElementById(id);
                    if (!el) continue;
                    if (el.type === 'hidden' || el.hasAttribute('readonly') || el.disabled) continue;

                    const sanitized = sanitizeToInt(el.value);
                    const clamped = clamp(id, sanitized === '' ? '' : sanitized);
                    el.value = clamped;
                }
            });
        }
    })();
</script>
<!-- Script untuk Download Table ke Excel dan PDF (Sesuai UI) -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ================ DOWNLOAD EXCEL ================
        document.getElementById('downloadExcel').addEventListener('click', function() {
            try {
                // Ambil data dari dokumen
                const ketuaNama = '<?php foreach ($tags as $v_tags) : ?><?php if ($abdimas->laporan_id == $v_tags->laporan_id && $v_tags->anggota_id == $abdimas->ketua_id) : ?><?= $v_tags->user_name; ?><?php endif; ?><?php endforeach; ?>';
                const mitraNama = '<?php foreach ($mitra as $v_mitra) : ?><?php if ($abdimas->mitra_id == $v_mitra->user_id) : ?><?= $v_mitra->user_name; ?><?php endif; ?><?php endforeach; ?>';
                const judulKegiatan = '<?= $abdimas->judul_kegiatan ?? ""; ?>';
                const tanggalKegiatan = '<?= $abdimas->tanggal_kegiatan ?? ""; ?>';
                const periode = '<?php foreach ($periode as $v_periode) : ?><?php if ($abdimas->periode_id == $v_periode->periode_id) : ?><?= $v_periode->periode_name; ?> <?= $v_periode->tahun_ajaran; ?><?php endif; ?><?php endforeach; ?>';

                // Buat workbook
                const wb = XLSX.utils.book_new();

                // ======== SHEET 1: INFO KEGIATAN ========
                const headRows = [];
                headRows.push(['<?= t("title_center_1") ?>']);
                headRows.push(['<?= t("title_center_2") ?>']);
                headRows.push(['<?= t("title_center_3") ?> ' + periode]);
                headRows.push(['']);
                headRows.push(['<?= t("label_leader") ?>', ketuaNama]);
                headRows.push(['<?= t("label_date") ?>', tanggalKegiatan]);
                headRows.push(['<?= t("label_partner") ?>', mitraNama]);
                headRows.push(['<?= t("label_activity_title") ?>', judulKegiatan]);
                headRows.push(['<?= t("label_member_count") ?>', '<?php foreach ($seen as $laporan_id => $users) {
                                                                        echo count($users);
                                                                    } ?> <?= t("people_present_note") ?>']);

                const wsHead = XLSX.utils.aoa_to_sheet(headRows);
                wsHead['!cols'] = [{
                    wch: 30
                }, {
                    wch: 50
                }];

                if (!wsHead['!merges']) wsHead['!merges'] = [];
                wsHead['!merges'].push({
                    s: {
                        r: 0,
                        c: 0
                    },
                    e: {
                        r: 0,
                        c: 1
                    }
                }, {
                    s: {
                        r: 1,
                        c: 0
                    },
                    e: {
                        r: 1,
                        c: 1
                    }
                }, {
                    s: {
                        r: 2,
                        c: 0
                    },
                    e: {
                        r: 2,
                        c: 1
                    }
                });

                XLSX.utils.book_append_sheet(wb, wsHead, 'Info');

                // ======== SHEET 2: TABEL PENILAIAN ========
                const rows = [];
                rows.push(['<?= t("th_no") ?>', '<?= t("th_component") ?>', '<?= t("th_desc") ?>', '<?= t("th_weight") ?>', '<?= t("th_team") ?>', '<?= t("th_lpm") ?>']);

                // Bagian 1: Pelaksana Kegiatan
                rows.push(['1', '<?= t("sec1_title") ?>', '', '', '', '']);

                // 1.a Materi dan pelaksanaan
                rows.push(['', '<?= t("sec1_a") ?>', '', '', '', '']);
                rows.push(['', '', '<?= t("sec1_a_1") ?>', '30', '<?= $abdimas->nt1 ?? ""; ?>', '<?= $rekapan->nlpm1 ?? ""; ?>']);
                rows.push(['', '', '<?= t("sec1_a_2") ?>', '20', '<?= $abdimas->nt2 ?? ""; ?>', '<?= $rekapan->nlpm2 ?? ""; ?>']);
                rows.push(['', '', '<?= t("sec1_a_3") ?>', '10', '<?= $abdimas->nt3 ?? ""; ?>', '<?= $rekapan->nlpm3 ?? ""; ?>']);
                rows.push(['', '', '<?= t("sec1_a_4") ?>', '20', '<?= $abdimas->nt4 ?? ""; ?>', '<?= $rekapan->nlpm4 ?? ""; ?>']);
                rows.push(['', '', '<?= t("sec1_a_5") ?>', '20', '<?= $abdimas->nt5 ?? ""; ?>', '<?= $rekapan->nlpm5 ?? ""; ?>']);

                // 1.b Peran dan Kontribusi
                rows.push(['', '<?= t("sec1_b") ?>', '', '', '', '']);
                rows.push(['', '', '<?= t("sec1_b_1") ?>', '40', '<?= $abdimas->nt6 ?? ""; ?>', '<?= $rekapan->nlpm6 ?? ""; ?>']);
                rows.push(['', '', '<?= t("sec1_b_2") ?>', '60', '<?= $abdimas->nt7 ?? ""; ?>', '<?= $rekapan->nlpm7 ?? ""; ?>']);

                // Bagian 2: Kondisi Mitra
                rows.push(['2', '<?= t("sec2_title") ?>', '', '', '', '']);
                rows.push(['', '', '<?= t("sec2_1") ?>', '40', '<?= $abdimas->nt8 ?? ""; ?>', '<?= $rekapan->nlpm8 ?? ""; ?>']);
                rows.push(['', '', '<?= t("sec2_2") ?>', '60', '<?= $abdimas->nt9 ?? ""; ?>', '<?= $rekapan->nlpm9 ?? ""; ?>']);

                // Saran & Masukan
                rows.push(['']);
                const saran = '<?= $rekapan->saran_masukan ?? ""; ?>';
                rows.push(['<?= t("label_suggestion") ?>', saran]);

                const wsPenilaian = XLSX.utils.aoa_to_sheet(rows);
                wsPenilaian['!cols'] = [{
                        wch: 8
                    },
                    {
                        wch: 35
                    },
                    {
                        wch: 50
                    },
                    {
                        wch: 10
                    },
                    {
                        wch: 12
                    },
                    {
                        wch: 12
                    }
                ];

                XLSX.utils.book_append_sheet(wb, wsPenilaian, 'Penilaian');

                // ======== SHEET 3: RATA-RATA ========
                const avgRows = [];
                avgRows.push(['<?= t("avg_title_component") ?>', '<?= t("avg_title_total") ?>']);
                avgRows.push(['<?= t("sec1_a") ?>', '<?= $totalA ?>']);
                avgRows.push(['<?= t("sec1_b") ?>', '<?= $totalB ?>']);
                avgRows.push(['<?= t("sec2_title") ?>', '<?= $totalC ?>']);
                avgRows.push(['<?= t("avg_overall") ?>', '<?= $avgAll ?>']);

                const wsAvg = XLSX.utils.aoa_to_sheet(avgRows);
                wsAvg['!cols'] = [{
                    wch: 50
                }, {
                    wch: 15
                }];

                XLSX.utils.book_append_sheet(wb, wsAvg, 'Rata-rata');

                // Generate filename
                const filename = 'Monev_' + ketuaNama.replace(/[^a-zA-Z0-9]/g, '_') + '_' +
                    new Date().toISOString().slice(0, 10) + '.xlsx';

                XLSX.writeFile(wb, filename);

                alert('✅ File Excel berhasil diunduh dengan 3 sheet terpisah!');
            } catch (error) {
                console.error('Error:', error);
                alert('❌ Gagal mengunduh Excel. Error: ' + error.message);
            }
        });
        // ================ DOWNLOAD PDF ================
        document.getElementById('downloadPDF').addEventListener('click', function() {
            try {
                const {
                    jsPDF
                } = window.jspdf;
                const doc = new jsPDF('p', 'mm', 'a4');

                // Header
                doc.setFontSize(12);
                doc.setFont(undefined, 'bold');
                doc.text('<?= t("title_center_1") ?>', 105, 15, {
                    align: 'center'
                });
                doc.text('<?= t("title_center_2") ?>', 105, 22, {
                    align: 'center'
                });

                const periode = '<?php foreach ($periode as $v_periode) : ?><?php if ($abdimas->periode_id == $v_periode->periode_id) : ?><?= $v_periode->periode_name; ?> <?= $v_periode->tahun_ajaran; ?><?php endif; ?><?php endforeach; ?>';
                doc.text('<?= t("title_center_3") ?> ' + periode, 105, 29, {
                    align: 'center'
                });

                // Info kegiatan
                doc.setFontSize(10);
                doc.setFont(undefined, 'normal');
                let yPos = 40;

                const ketuaNama = '<?php foreach ($tags as $v_tags) : ?><?php if ($abdimas->laporan_id == $v_tags->laporan_id && $v_tags->anggota_id == $abdimas->ketua_id) : ?><?= $v_tags->user_name; ?><?php endif; ?><?php endforeach; ?>';
                const mitraNama = '<?php foreach ($mitra as $v_mitra) : ?><?php if ($abdimas->mitra_id == $v_mitra->user_id) : ?><?= $v_mitra->user_name; ?><?php endif; ?><?php endforeach; ?>';
                const judulKegiatan = '<?= $abdimas->judul_kegiatan ?? ""; ?>';
                const tanggalKegiatan = '<?= $abdimas->tanggal_kegiatan ?? ""; ?>';

                doc.text('<?= t("label_leader") ?>: ' + ketuaNama, 15, yPos);
                yPos += 6;
                doc.text('<?= t("label_date") ?>: ' + tanggalKegiatan, 15, yPos);
                yPos += 6;
                doc.text('<?= t("label_partner") ?>: ' + mitraNama, 15, yPos);
                yPos += 6;
                doc.text('<?= t("label_activity_title") ?>: ' + judulKegiatan, 15, yPos);
                yPos += 10;

                // Tabel Penilaian
                const tableData = [
                    [{
                        content: '1',
                        rowSpan: 9
                    }, {
                        content: '<?= t("sec1_title") ?>',
                        colSpan: 5
                    }],
                    [{
                        content: '<?= t("sec1_a") ?>',
                        rowSpan: 6
                    }, {}, {}, {}, {}],
                    ['<?= t("sec1_a_1") ?>', '30', '<?= $abdimas->nt1 ?? "0"; ?>', '<?= $rekapan->nlpm1 ?? "0"; ?>'],
                    ['<?= t("sec1_a_2") ?>', '20', '<?= $abdimas->nt2 ?? "0"; ?>', '<?= $rekapan->nlpm2 ?? "0"; ?>'],
                    ['<?= t("sec1_a_3") ?>', '10', '<?= $abdimas->nt3 ?? "0"; ?>', '<?= $rekapan->nlpm3 ?? "0"; ?>'],
                    ['<?= t("sec1_a_4") ?>', '20', '<?= $abdimas->nt4 ?? "0"; ?>', '<?= $rekapan->nlpm4 ?? "0"; ?>'],
                    ['<?= t("sec1_a_5") ?>', '20', '<?= $abdimas->nt5 ?? "0"; ?>', '<?= $rekapan->nlpm5 ?? "0"; ?>'],
                    [{
                        content: '<?= t("sec1_b") ?>',
                        rowSpan: 2
                    }, '<?= t("sec1_b_1") ?>', '40', '<?= $abdimas->nt6 ?? "0"; ?>', '<?= $rekapan->nlpm6 ?? "0"; ?>'],
                    ['<?= t("sec1_b_2") ?>', '60', '<?= $abdimas->nt7 ?? "0"; ?>', '<?= $rekapan->nlpm7 ?? "0"; ?>'],
                    [{
                        content: '2',
                        rowSpan: 2
                    }, {
                        content: '<?= t("sec2_title") ?>',
                        rowSpan: 2
                    }, '<?= t("sec2_1") ?>', '40', '<?= $abdimas->nt8 ?? "0"; ?>', '<?= $rekapan->nlpm8 ?? "0"; ?>'],
                    ['<?= t("sec2_2") ?>', '60', '<?= $abdimas->nt9 ?? "0"; ?>', '<?= $rekapan->nlpm9 ?? "0"; ?>']
                ];

                doc.autoTable({
                    startY: yPos,
                    head: [
                        ['<?= t("th_no") ?>', '<?= t("th_component") ?>', '<?= t("th_desc") ?>', '<?= t("th_weight") ?>', '<?= t("th_team") ?>', '<?= t("th_lpm") ?>']
                    ],
                    body: tableData,
                    theme: 'grid',
                    styles: {
                        fontSize: 8,
                        cellPadding: 2,
                        valign: 'middle',
                        halign: 'center'
                    },
                    headStyles: {
                        fillColor: [52, 152, 219],
                        fontStyle: 'bold',
                        halign: 'center'
                    },
                    columnStyles: {
                        0: {
                            cellWidth: 10,
                            halign: 'center'
                        },
                        1: {
                            cellWidth: 40,
                            halign: 'left'
                        },
                        2: {
                            cellWidth: 70,
                            halign: 'left'
                        },
                        3: {
                            cellWidth: 15,
                            halign: 'center'
                        },
                        4: {
                            cellWidth: 20,
                            halign: 'center'
                        },
                        5: {
                            cellWidth: 20,
                            halign: 'center'
                        }
                    }
                });

                // Tabel Rata-rata
                yPos = doc.lastAutoTable.finalY + 10;
                doc.autoTable({
                    startY: yPos,
                    head: [
                        ['<?= t("avg_title_component") ?>', '<?= t("avg_title_total") ?>']
                    ],
                    body: [
                        ['<?= t("sec1_a") ?>', '<?= $totalA ?>'],
                        ['<?= t("sec1_b") ?>', '<?= $totalB ?>'],
                        ['<?= t("sec2_title") ?>', '<?= $totalC ?>'],
                        [{
                            content: '<?= t("avg_overall") ?>',
                            styles: {
                                fontStyle: 'bold'
                            }
                        }, {
                            content: '<?= $avgAll ?>',
                            styles: {
                                fontStyle: 'bold',
                                fillColor: [230, 230, 230]
                            }
                        }]
                    ],
                    theme: 'grid',
                    styles: {
                        fontSize: 9
                    },
                    headStyles: {
                        fillColor: [52, 152, 219],
                        fontStyle: 'bold'
                    },
                    columnStyles: {
                        0: {
                            cellWidth: 120,
                            halign: 'left'
                        },
                        1: {
                            cellWidth: 55,
                            halign: 'center'
                        }
                    }
                });

                // Saran dan Masukan
                const saran = '<?= $rekapan->saran_masukan ?? ""; ?>';
                if (saran) {
                    yPos = doc.lastAutoTable.finalY + 8;
                    doc.setFont(undefined, 'bold');
                    doc.text('<?= t("label_suggestion") ?>:', 15, yPos);
                    doc.setFont(undefined, 'normal');
                    doc.text(saran, 15, yPos + 6, {
                        maxWidth: 180
                    });
                }

                // Save PDF
                const filename = 'Monev_' + ketuaNama.replace(/[^a-zA-Z0-9]/g, '_') + '_' +
                    new Date().toISOString().slice(0, 10) + '.pdf';
                doc.save(filename);

                alert('✅ File PDF berhasil diunduh!');
            } catch (error) {
                console.error('Error:', error);
                alert('❌ Gagal mengunduh PDF. Error: ' + error.message);
            }
        });
    });
</script>

<?= $this->endSection() ?>
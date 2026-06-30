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

// Array terjemahan lengkap
$I18N = [
    'id' => [
        'dashboard' => 'Dashboard',
        'dataAbdimas' => 'Data abdimas',

        // Tab labels
        'tabDetailReport' => 'Detail Laporan Abdimas',
        'tabMonevScore' => 'Nilai Monitoring dan Evaluasi',

        // Table labels
        'abdimasPeriod' => 'Periode Abdimas',
        'leaderData' => 'Data Ketua',
        'nidn' => 'NIDN:',
        'leaderName' => 'Nama Ketua:',
        'sintaId' => 'SINTA ID:',
        'partnerName' => 'Nama Mitra',
        'partnerAddress' => 'Alamat Mitra:',
        'partnerProblem' => 'Masalah Mitra',
        'solution' => 'Solusi:',
        'topicProgramSub' => 'Topik - Program - Sub Program',
        'topic' => 'Topik:',
        'program' => 'Program:',
        'subProgram' => 'SubProgram:',
        'activityOutput' => 'Luaran Kegiatan',
        'activityType' => 'Tipe Kegiatan',
        'leaderMembers' => 'Ketua dan Anggota',
        'leader' => 'Ketua',
        'students' => 'Mahasiswa',
        'fundingEstimate' => 'Estimasi Pendanaan',
        'fundingSource' => 'Sumber Dana',
        'activityDate' => 'Tanggal Kegiatan',
        'notYetEnteredDate' => 'Belum memasukkan tanggal kegiatan',
        'activityTitle' => 'Judul/Nama Kegiatan',
        'notYetEnteredTitle' => 'Belum memasukan Judul / Nama kegiatan',
        'activityReport' => 'Laporan Kegiatan',
        'notYetEnteredReport' => 'Belum memasukan Laporan kegiatan',
        'viewReport' => 'Lihat Laporan',
        'activityEvidence' => 'Bukti Kegiatan',
        'notYetEnteredEvidence' => 'Belum memasukan Bukti kegiatan',
        'viewEvidence' => 'Lihat Bukti Kegiatan',
        'outputLink' => 'Link Luaran Kegiatan',
        'notYetEnteredLink' => 'Belum memasukan Link Luaran kegiatan',
        'verificationStatus' => 'Status Verifikasi',
        'statusAccepted' => 'Terima',
        'statusRevision' => 'Revisi',
        'statusProcess' => 'Proses',
        'revisionNotes' => 'Catatan Revisi',
        'btnBack' => 'kembali',

        // MonEv section
        'monevTitle1' => 'MONITORING DAN EVALUASI KEGIATAN',
        'monevTitle2' => 'PROGRAM PENGABDIAN KEPADA MASYARAKAT',
        'monevTitle3' => 'UNIVERSITAS GUNADARMA PERIODE',
        'teamLeaderName' => 'Nama Ketua Tim PKM',
        'executionDate' => 'Tanggal Pelaksanaan',
        'noActivityDate' => 'Belum ada tanggal kegiatan',
        'partnerNamePKM' => 'Nama Mitra PKM',
        'activityTitlePKM' => 'Judul Kegiatan PKM',
        'noActivityTitle' => 'Belum ada Judul / Nama Kegiatan',
        'teamMemberCount' => 'Jumlah Anggota Tim',
        'peoplePresent' => 'Orang (yang hadir, presensi terlampir)',

        // Table headers
        'no' => 'No',
        'component' => 'Komponen',
        'description' => 'Keterangan',
        'weight' => 'Bobot',
        'teamScore' => 'Nilai Team',
        'lpmScore' => 'Nilai LPM',
        'noScore' => 'Belum ada Nilai',

        // Assessment criteria
        'assessment1' => 'Penilaian berdasarkan Pelaksana Kegiatan Pengabdian Kepada Masyarakat',
        'criteria1a' => 'a. Materi dan pelaksanaan kegiatan',
        'criteria1a1' => '1. Kesesuaian dengan kebutuhan Mitra',
        'criteria1a2' => '2. Kelengkapan Materi dalam menyelesaikan masalah Mitra',
        'criteria1a3' => '3. Akses materi oleh Mitra (Kemudahan Mitra memperoleh Materi)',
        'criteria1a4' => '4. Kesiapan luaran kegiatan',
        'criteria1a5' => '5. Kesiapan dan pelaksanaan kegiatan',
        'criteria1b' => 'b. Peran dan Kontribusi Anggota',
        'criteria1b1' => '1. Kesesuaian dan kelengkapan bidang ilmu dalam menyelesaikan masalah Mitra',
        'criteria1b2' => '2. Kehadiran dan kontribusi setiap anggota dalam kegiatan',
        'criteria2' => 'Kondisi Mitra',
        'criteria2a' => '1. Partisipasi Mitra saat kegiatan',
        'criteria2b' => '2. Manfaat yang dirasakan Mitra',

        'suggestionsInput' => 'Saran & Masukan:',
        'noSuggestions' => 'Belum ada Saran dan Masukan',
    ],
    'en' => [
        'dashboard' => 'Dashboard',
        'dataAbdimas' => 'Abdimas Data',

        // Tab labels
        'tabDetailReport' => 'Abdimas Report Details',
        'tabMonevScore' => 'Monitoring and Evaluation Score',

        // Table labels
        'abdimasPeriod' => 'Abdimas Period',
        'leaderData' => 'Leader Data',
        'nidn' => 'NIDN:',
        'leaderName' => 'Leader Name:',
        'sintaId' => 'SINTA ID:',
        'partnerName' => 'Partner Name',
        'partnerAddress' => 'Partner Address:',
        'partnerProblem' => 'Partner Problem',
        'solution' => 'Solution:',
        'topicProgramSub' => 'Topic - Program - Sub Program',
        'topic' => 'Topic:',
        'program' => 'Program:',
        'subProgram' => 'SubProgram:',
        'activityOutput' => 'Activity Output',
        'activityType' => 'Activity Type',
        'leaderMembers' => 'Leader and Members',
        'leader' => 'Leader',
        'students' => 'Students',
        'fundingEstimate' => 'Funding Estimate',
        'fundingSource' => 'Funding Source',
        'activityDate' => 'Activity Date',
        'notYetEnteredDate' => 'Activity date not yet entered',
        'activityTitle' => 'Activity Title/Name',
        'notYetEnteredTitle' => 'Activity title/name not yet entered',
        'activityReport' => 'Activity Report',
        'notYetEnteredReport' => 'Activity report not yet uploaded',
        'viewReport' => 'View Report',
        'activityEvidence' => 'Activity Evidence',
        'notYetEnteredEvidence' => 'Activity evidence not yet uploaded',
        'viewEvidence' => 'View Activity Evidence',
        'outputLink' => 'Activity Output Link',
        'notYetEnteredLink' => 'Activity output link not yet entered',
        'verificationStatus' => 'Verification Status',
        'statusAccepted' => 'Accepted',
        'statusRevision' => 'Revision',
        'statusProcess' => 'In Process',
        'revisionNotes' => 'Revision Notes',
        'btnBack' => 'back',

        // MonEv section
        'monevTitle1' => 'ACTIVITY MONITORING AND EVALUATION',
        'monevTitle2' => 'COMMUNITY SERVICE PROGRAM',
        'monevTitle3' => 'GUNADARMA UNIVERSITY PERIOD',
        'teamLeaderName' => 'PKM Team Leader Name',
        'executionDate' => 'Execution Date',
        'noActivityDate' => 'No activity date yet',
        'partnerNamePKM' => 'PKM Partner Name',
        'activityTitlePKM' => 'PKM Activity Title',
        'noActivityTitle' => 'No Activity Title/Name yet',
        'teamMemberCount' => 'Number of Team Members',
        'peoplePresent' => 'People (present, attendance attached)',

        // Table headers
        'no' => 'No',
        'component' => 'Component',
        'description' => 'Description',
        'weight' => 'Weight',
        'teamScore' => 'Team Score',
        'lpmScore' => 'LPM Score',
        'noScore' => 'No Score Yet',

        // Assessment criteria
        'assessment1' => 'Assessment based on Community Service Activity Implementers',
        'criteria1a' => 'a. Materials and activity implementation',
        'criteria1a1' => '1. Suitability with Partner needs',
        'criteria1a2' => '2. Completeness of Materials in solving Partner problems',
        'criteria1a3' => '3. Material access by Partner (Ease of Partner obtaining Materials)',
        'criteria1a4' => '4. Activity output readiness',
        'criteria1a5' => '5. Activity preparation and implementation',
        'criteria1b' => 'b. Member Role and Contribution',
        'criteria1b1' => '1. Suitability and completeness of scientific fields in solving Partner problems',
        'criteria1b2' => '2. Attendance and contribution of each member in activities',
        'criteria2' => 'Partner Condition',
        'criteria2a' => '1. Partner participation during activities',
        'criteria2b' => '2. Benefits felt by Partner',

        'suggestionsInput' => 'Suggestions & Input:',
        'noSuggestions' => 'No Suggestions and Input yet',
    ],
];

// Helper function untuk translate
$t = function (string $key, ...$args) use ($I18N, $lang) {
    $text = $I18N[$lang][$key] ?? $I18N['id'][$key] ?? $key;
    return $args ? vsprintf($text, $args) : $text;
};

// Function untuk format tanggal
if (!function_exists('formatTanggalID')) {
    function formatTanggalID($tanggal, $lang = 'id')
    {
        if (empty($tanggal)) return '-';

        $dateObj = date_create(trim($tanggal));
        if (!$dateObj) return '-';

        if ($lang === 'en') {
            return date_format($dateObj, 'F j, Y');
        }

        // Format Indonesia
        $bulanIndonesia = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        ];

        $formatted = date_format($dateObj, 'j F Y');
        return strtr($formatted, $bulanIndonesia);
    }
}
?>

<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <a href="<?= site_url('abdimas'); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
        <h1><?= $title; ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>"><?= $t('dashboard'); ?></a></div>
            <div class="breadcrumb-item active"><a href="<?= site_url('abdimas'); ?>"><?= $t('dataAbdimas'); ?></a></div>
            <div class="breadcrumb-item"><?= $title; ?></div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                                    aria-controls="home" aria-selected="true"><?= $t('tabDetailReport'); ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                                    aria-controls="profile" aria-selected="false"><?= $t('tabMonevScore'); ?></a>
                            </li>
                        </ul>

                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <form action="<?= site_url('abdimas/' . $abdimas->laporan_id); ?>" method="POST"
                                    autocomplete="off" enctype="multipart/form-data">
                                    <?= csrf_field(); ?>
                                    <input type="hidden" name="_method" value="PATCH">

                                    <div class="table-responsive-md">
                                        <table class="table table-bordered" style="border: 1px solid">
                                            <tbody>
                                                <tr>
                                                    <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                        <?= $t('abdimasPeriod'); ?>
                                                    </td>
                                                    <td class="col-9" style="border: 1px solid">
                                                        <div class="form-group m-2">
                                                            <?php foreach ($periode as $mtr => $v_periode) : ?>
                                                                <?php if ($abdimas->periode_id == $v_periode->periode_id) : ?>
                                                                    <?= esc($v_periode->periode_name); ?>
                                                                    <?= esc($v_periode->tahun_ajaran); ?>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                        <?= $t('leaderData'); ?>
                                                    </td>
                                                    <td class="col-9" style="border: 1px solid">
                                                        <div class="form-group m-2">
                                                            <?php foreach ($tags as $ds => $v_tags) : ?>
                                                                <?php if ($abdimas->laporan_id == $v_tags->laporan_id) : ?>
                                                                    <?php if ($v_tags->anggota_id == $abdimas->ketua_id) : ?>
                                                                        <input type="hidden" name="nidn" id="nidn"
                                                                            value="<?= esc($v_tags->nidn); ?>" class="form-control"
                                                                            readonly autofocus>
                                                                        <b><?= $t('nidn'); ?></b> <?= esc($v_tags->nidn); ?> <br>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>

                                                            <?php foreach ($tags as $ds => $v_tags) : ?>
                                                                <?php if ($abdimas->laporan_id == $v_tags->laporan_id) : ?>
                                                                    <?php if ($v_tags->anggota_id == $abdimas->ketua_id) : ?>
                                                                        <input type="hidden" name="ketua_id" id="ketua_id"
                                                                            class="form-control"
                                                                            placeholder="<?= esc($v_tags->user_name); ?>" readonly
                                                                            autofocus>
                                                                        <b><?= $t('leaderName'); ?></b> <?= esc($v_tags->user_name); ?> <br>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>

                                                            <?php foreach ($tags as $ds => $v_tags) : ?>
                                                                <?php if ($abdimas->laporan_id == $v_tags->laporan_id) : ?>
                                                                    <?php if ($v_tags->anggota_id == $abdimas->ketua_id) : ?>
                                                                        <input type="hidden" name="sinta_id" id="sinta_id"
                                                                            value="<?= esc($v_tags->sinta_id); ?>" class="form-control"
                                                                            readonly autofocus>
                                                                        <b><?= $t('sintaId'); ?></b> <?= esc($v_tags->sinta_id); ?>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                        <?= $t('partnerName'); ?>
                                                    </td>
                                                    <td class="col-9" style="border: 1px solid">
                                                        <div class="form-group m-2">
                                                            <?php foreach ($mitra as $mtr => $v_mitra) : ?>
                                                                <?php if ($abdimas->mitra_id == $v_mitra->user_id) : ?>
                                                                    <?= esc($v_mitra->user_name); ?> <br>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>

                                                            <?php foreach ($mitra as $mtr => $v_mitra) : ?>
                                                                <?php if ($abdimas->mitra_id == $v_mitra->user_id) : ?>
                                                                    <b><?= $t('partnerAddress'); ?></b><br>
                                                                    <?= esc($v_mitra->alamat) . ' -|-|- ' . esc($v_mitra->kota_name); ?>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="col-6" style="border: 1px solid">
                                                        <strong><?= $t('partnerProblem'); ?></strong>
                                                        <div class="form-group mt-2">
                                                            <?= esc($abdimas->masalah_mitra); ?>
                                                        </div>
                                                    </td>
                                                    <td class="col-6" style="border: 1px solid">
                                                        <strong><?= $t('solution'); ?></strong>
                                                        <div class="form-group mt-2">
                                                            <?= esc($abdimas->solusi_mitra); ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                        <?= $t('topicProgramSub'); ?>
                                                    </td>
                                                    <td class="col-9" style="border: 1px solid">
                                                        <div class="form-group m-2">
                                                            <?php foreach ($subprogram as $mtr => $v_subprogram) : ?>
                                                                <?php if ($abdimas->subprogram_id == $v_subprogram->subprogram_id) : ?>
                                                                    <b><?= $t('topic'); ?></b> <?= esc($v_subprogram->topik_name); ?> <br>
                                                                    <b><?= $t('program'); ?></b> <?= esc($v_subprogram->program_name); ?> <br>
                                                                    <b><?= $t('subProgram'); ?></b> <?= esc($v_subprogram->subprogram_name); ?>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                        <?= $t('activityOutput'); ?>
                                                    </td>
                                                    <td class="col-9" style="border: 1px solid">
                                                        <div class="form-group m-2">
                                                            <?php
                                                            $seen = [];
                                                            $counter = 1;

                                                            foreach ($tagluaran as $ls => $v_tagluaran) :
                                                                if ($abdimas->laporan_id == $v_tagluaran->laporan_id && !isset($seen[$v_tagluaran->laporan_id])) : ?>
                                                                    <?= $counter . '. ' . esc(ucwords(strtolower($v_tagluaran->luaran_name))); ?><br>
                                                                <?php $seen[$v_tagluaran->luaran_id] = true;
                                                                    $counter++;
                                                                endif; ?>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                        <?= $lang === 'en' ? 'Field of Study' : 'Bidang Ilmu'; ?>:
                                                    </td>
                                                    <td class="col-9" style="border: 1px solid">
                                                        <div class="form-group m-2">
                                                            <?= esc($abdimas->bidang_ilmu ?? '-'); ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                        <?= $t('activityType'); ?>
                                                    </td>
                                                    <td class="col-9" style="border: 1px solid">
                                                        <div class="form-group m-2">
                                                            <?= esc($abdimas->tipe_kegiatan ?: '-'); ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                        <?= $lang === 'en' ? 'Field of Study' : 'Bidang Ilmu'; ?>:
                                                    </td>
                                                    <td class="col-9" style="border: 1px solid">
                                                        <div class="form-group m-2">
                                                            <?= esc($abdimas->bidang_ilmu ?: '-'); ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                        <?= $t('leaderMembers'); ?>
                                                    </td>
                                                    <td class="col-9" style="border: 1px solid">
                                                        <div class="form-group m-2">
                                                            <?php
                                                            $counter_dosen = 1;
                                                            $seen_dosen = [];
                                                            foreach ($tags as $v_tags) :
                                                                if ($abdimas->laporan_id == $v_tags->laporan_id && !isset($seen_dosen[$v_tags->user_id])) :
                                                                    if ($v_tags->anggota_id == $abdimas->ketua_id) : ?>
                                                                        <?= $counter_dosen . '. ' . esc(ucwords(strtolower($v_tags->user_name))); ?> (<span class="text-danger font-weight-bold"><?= $t('leader'); ?></span>)<br>
                                                                    <?php $counter_dosen++;
                                                                        $seen_dosen[$v_tags->user_id] = true;
                                                                    else : ?>
                                                                        <?= $counter_dosen . '. ' . esc(ucwords(strtolower($v_tags->user_name))); ?><br>
                                                            <?php $counter_dosen++;
                                                                        $seen_dosen[$v_tags->user_id] = true;
                                                                    endif;
                                                                endif;
                                                            endforeach; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                        <?= $t('students'); ?>
                                                    </td>
                                                    <td class="col-9" style="border: 1px solid">
                                                        <div class="form-group m-2">
                                                            <?php
                                                            $counter_mahasiswa = 1;
                                                            foreach ($mahasiswa as $mhs) : ?>
                                                                <?= $counter_mahasiswa . '. ' . esc(ucwords(strtolower($mhs->mahasiswa_name))); ?> - <?= esc($mhs->mahasiswa_npm) ?> - <?= esc($mhs->jurusan_name) ?><br>
                                                                <?php $counter_mahasiswa++; ?>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                        <?= $t('fundingEstimate'); ?>
                                                    </td>
                                                    <td class="col-9" style="border: 1px solid">
                                                        <div class="form-group m-2">
                                                            <?= 'Rp. ' . number_format($abdimas->range_dana, 0, ',', '.'); ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                        <?= $t('fundingSource'); ?>
                                                    </td>
                                                    <td class="col-9" style="border: 1px solid">
                                                        <div class="form-group m-2">
                                                            <?= esc($abdimas->sumber_dana); ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                        <?= $t('activityDate'); ?>
                                                    </td>
                                                    <td class="col-9" style="border: 1px solid">
                                                        <div class="form-group m-2">
                                                            <?php if (empty($abdimas->tanggal_kegiatan)) : ?>
                                                                <span class="text-danger"><?= $t('notYetEnteredDate'); ?></span>
                                                            <?php else : ?>
                                                                <?php
                                                                $tanggal = array_map('trim', explode(' - ', $abdimas->tanggal_kegiatan));
                                                                $tanggalMulai = $tanggal[0] ?? null;
                                                                $tanggalSelesai = $tanggal[1] ?? null;

                                                                $formattedMulai = formatTanggalID($tanggalMulai, $lang);
                                                                $formattedSelesai = formatTanggalID($tanggalSelesai, $lang);
                                                                ?>
                                                                <span><?= $formattedMulai ?> — <?= $formattedSelesai ?></span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                        <?= $t('activityTitle'); ?>
                                                    </td>
                                                    <td class="col-9" style="border: 1px solid">
                                                        <div class="form-group m-2">
                                                            <?php if (isset($abdimas->judul_kegiatan) && $abdimas->judul_kegiatan != null) : ?>
                                                                <p class="text-break"><?= esc($abdimas->judul_kegiatan); ?></p>
                                                                <input type="hidden" name="judul_kegiatan"
                                                                    id="judul_kegiatan" class="form-control"
                                                                    value="<?= esc($abdimas->judul_kegiatan); ?>" autofocus
                                                                    readonly>
                                                            <?php else : ?>
                                                                <span class="text-danger"><?= $t('notYetEnteredTitle'); ?></span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                        <?= $t('activityReport'); ?>
                                                    </td>
                                                    <td class="col-9" style="border: 1px solid">
                                                        <div class="form-group m-2">
                                                            <?php if (!isset($abdimas->laporan) || $abdimas->laporan == null) : ?>
                                                                <span class="text-danger"><?= $t('notYetEnteredReport'); ?></span>
                                                            <?php else : ?>
                                                                <input type="file" hidden name="laporan" id="laporan"
                                                                    class="form-control" autofocus>
                                                                <a href="<?= site_url('berkas/laporan/' . $abdimas->laporan); ?>"
                                                                    class="btn btn-info text-light" target="_blank">
                                                                    <?= $t('viewReport'); ?>
                                                                </a>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                        <?= $t('activityEvidence'); ?>
                                                    </td>
                                                    <td class="col-9" style="border: 1px solid">
                                                        <div class="form-group m-2">
                                                            <?php if (!isset($abdimas->bukti_kegiatan) || $abdimas->bukti_kegiatan == null) : ?>
                                                                <span class="text-danger"><?= $t('notYetEnteredEvidence'); ?></span>
                                                            <?php else : ?>
                                                                <input type="file" hidden name="bukti_kegiatan"
                                                                    id="bukti_kegiatan" class="form-control" autofocus>
                                                                <a href="<?= site_url('berkas/kegiatan/' . $abdimas->bukti_kegiatan); ?>"
                                                                    class="btn btn-info text-light" target="_blank">
                                                                    <?= $t('viewEvidence'); ?>
                                                                </a>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                        <?= $t('outputLink'); ?>
                                                    </td>
                                                    <td class="col-9" style="border: 1px solid">
                                                        <div class="form-group m-2">
                                                            <?php if (!isset($abdimas->link_luaran) || $abdimas->link_luaran == null) : ?>
                                                                <span class="text-danger"><?= $t('notYetEnteredLink'); ?></span>
                                                            <?php else : ?>
                                                                <a class="text-break"
                                                                    href="<?= esc($abdimas->link_luaran); ?>" target="_blank"><?= esc($abdimas->link_luaran); ?></a>
                                                                <input type="hidden" name="link_luaran" id="link_luaran"
                                                                    class="form-control"
                                                                    value="<?= esc($abdimas->link_luaran); ?>">
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                        <?= $t('verificationStatus'); ?>
                                                    </td>
                                                    <td class="col-9" style="border: 1px solid">
                                                        <div class="form-group m-2">
                                                            <?php if (isset($abdimas->verifikasi) && $abdimas->verifikasi == 1) : ?>
                                                                <span class="badge badge-success px-4 font-weight-bold"><?= $t('statusAccepted'); ?></span>
                                                            <?php elseif ($abdimas->verifikasi == 2) : ?>
                                                                <span class="badge badge-warning px-4 text-dark font-weight-bold"><?= $t('statusRevision'); ?></span>
                                                            <?php elseif ($abdimas->verifikasi == 0) : ?>
                                                                <span class="badge badge-primary px-4 font-weight-bold"><?= $t('statusProcess'); ?></span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                        <?= $t('revisionNotes'); ?>
                                                    </td>
                                                    <td class="col-9" style="border: 1px solid">
                                                        <div class="form-group m-2 text-break">
                                                            <?= esc($abdimas->revisi); ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                        SPM
                                                    </td>
                                                    <td class="col-9" style="border: 1px solid">
                                                        <div class="form-group m-2 text-break">
                                                            <?php $spm = $abdimas->spm ?? null; ?>
                                                            <?php if (!empty($spm)) : ?>
                                                                <a href="<?= base_url('berkas/spm/' . $spm); ?>" target="_blank" class="btn btn-sm btn-primary">
                                                                    Lihat SPM
                                                                </a>
                                                            <?php else : ?>
                                                                <span class="text-muted">Belum ada file SPM</span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="float-right">
                                        <a href="<?= site_url('abdimas'); ?>" class="btn btn-dark"><?= $t('btnBack'); ?></a>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <form action="<?= site_url('monev/' . $abdimas->laporan_id); ?>" method="POST"
                                    autocomplete="off" enctype="multipart/form-data">
                                    <?= csrf_field(); ?>
                                    <input type="hidden" name="_method" value="PATCH">
                                    <div class="text-center mb-4">
                                        <h5><?= strtoupper($t('monevTitle1')); ?></h5>
                                        <h5><?= strtoupper($t('monevTitle2')); ?></h5>
                                        <h5><?= strtoupper($t('monevTitle3')); ?>
                                            <span class="text-primary">
                                                <?php foreach ($periode as $mtr => $v_periode) : ?>
                                                    <?php if ($abdimas->periode_id == $v_periode->periode_id) : ?>
                                                        <?= esc($v_periode->periode_name); ?> <?= esc($v_periode->tahun_ajaran); ?>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </span>
                                        </h5>
                                    </div>
                                    <div class="mb-3">
                                        <table class="table table-bordered">
                                            <tbody>
<<<<<<< HEAD
=======
                                            <tr>
                                                <td class="col-2 font-weight-bold" style="border: 1px solid">Nama Ketua Tim PKM</td>
                                                <td class="col-5" style="border: 1px solid">
                                                    <?php foreach ($tags as $ds => $v_tags) : ?>
                                                        <?php if ($abdimas->laporan_id == $v_tags->laporan_id && $v_tags->anggota_id == $abdimas->ketua_id) : ?>
                                                            <input type="hidden" name="ketua_id" id="ketua_id" class="form-control"
                                                                placeholder="<?= $v_tags->user_name; ?>" readonly autofocus>
                                                            <?= $v_tags->user_name; ?> <br>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </td>
                                            
                                                <td class="font-weight-bold" style="border: 1px solid">Tanggal Pelaksanaan</td>
                                                <td style="border: 1px solid">
                                                    <?php if (!empty($abdimas->tanggal_kegiatan)) : ?>
                                                        <?php
                                                            // Format tanggal hanya jika ada nilai
                                                            $formattedDate = date('l, d F Y', strtotime($abdimas->tanggal_kegiatan));
                                                            $storedDate = date('Y-m-d', strtotime($abdimas->tanggal_kegiatan));
                                                        ?>
                                                        <input type="hidden" name="tanggal_kegiatan" id="tanggal_kegiatan" value="<?= $storedDate; ?>">
                                                        <?= $formattedDate; ?>
                                                    <?php else : ?>
                                                        <span class="text-danger">Belum ada tanggal kegiatan</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
>>>>>>> 55c0835 (refactor: update code)
                                                <tr>
                                                    <td class="col-2 font-weight-bold" style="border: 1px solid"><?= $t('teamLeaderName'); ?></td>
                                                    <td class="col-5" style="border: 1px solid">
                                                        <?php foreach ($tags as $ds => $v_tags) : ?>
                                                            <?php if ($abdimas->laporan_id == $v_tags->laporan_id) : ?>
                                                                <?php if ($v_tags->anggota_id == $abdimas->ketua_id) : ?>
                                                                    <input type="hidden" name="ketua_id" id="ketua_id"
                                                                        class="form-control"
                                                                        placeholder="<?= esc($v_tags->user_name); ?>" readonly autofocus>
                                                                    <?= esc($v_tags->user_name); ?> <br>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    </td>
                                                    <td class="font-weight-bold" style="border: 1px solid"><?= $t('executionDate'); ?></td>
                                                    <td style="border: 1px solid">
                                                        <?php if (empty($abdimas->tanggal_kegiatan)) : ?>
                                                            <span class="text-danger"><?= $t('noActivityDate'); ?></span>
                                                        <?php else : ?>
                                                            <?php
                                                            $tanggal = array_map('trim', explode(' - ', $abdimas->tanggal_kegiatan));
                                                            $tanggalMulai = $tanggal[0] ?? null;
                                                            $tanggalSelesai = $tanggal[1] ?? null;

                                                            $formattedMulai = formatTanggalID($tanggalMulai, $lang);
                                                            $formattedSelesai = formatTanggalID($tanggalSelesai, $lang);
                                                            ?>
                                                            <span><?= $formattedMulai ?> — <?= $formattedSelesai ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                <tr>
                                                    <td class="col-2 font-weight-bold" style="border: 1px solid"><?= $t('partnerNamePKM'); ?></td>
                                                    <td colspan="3" style="border: 1px solid">
                                                        <?php foreach ($mitra as $mtr => $v_mitra) : ?>
                                                            <?php if ($abdimas->mitra_id == $v_mitra->user_id) : ?>
                                                                <?= esc($v_mitra->user_name); ?>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="col-2 font-weight-bold" style="border: 1px solid"><?= $t('activityTitlePKM'); ?></td>
                                                    <td colspan="3" style="border: 1px solid">
                                                        <?php if ($abdimas->judul_kegiatan == null) : ?>
                                                            <span class="text-danger"><?= $t('noActivityTitle'); ?></span>
                                                        <?php else : ?>
                                                            <p class="text-break"><?= esc($abdimas->judul_kegiatan); ?></p>
                                                            <input type="hidden" name="judul_kegiatan" id="judul_kegiatan"
                                                                class="form-control"
                                                                value="<?= esc($abdimas->judul_kegiatan); ?>" autofocus readonly>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="col-2 font-weight-bold" style="border: 1px solid"><?= $t('teamMemberCount'); ?></td>
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
                                                        ?> <?= $t('peoplePresent'); ?></td>
                                                </tr>
                                            </tbody>
                                    </div>
                                    <table class="table table-bordered" style="border: 1px solid">
                                        <thead>
                                            <tr>
                                                <th style="border: 1px solid"><?= $t('no'); ?></th>
                                                <th style="border: 1px solid"><?= $t('component'); ?></th>
                                                <th style="border: 1px solid"><?= $t('description'); ?></th>
                                                <th class="text-center" style="border: 1px solid"><?= $t('weight'); ?></th>
                                                <th class="text-center" style="border: 1px solid"><?= $t('teamScore'); ?></th>
                                                <th class="text-center" style="border: 1px solid"><?= $t('lpmScore'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th scope="row" rowspan="9" style="border: 1px solid">1</th>
                                                <td colspan="5" style="border: 1px solid">
                                                    <?= $t('assessment1'); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td rowspan="6" style="border: 1px solid"><?= $t('criteria1a'); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid"><?= $t('criteria1a1'); ?></td>
                                                <td class="text-center" style="border: 1px solid">30</td>
                                                <td class="text-center" style="border: 1px solid; width:150px;">
                                                    <?php if ($abdimas->nt1 == null) : ?>
                                                        <span class="text-danger"><?= $t('noScore'); ?></span>
                                                    <?php else : ?>
                                                        <?= esc($abdimas->nt1); ?>
                                                        <input type="hidden" name="nt1" id="nt1" class="form-control"
                                                            value="<?= esc($abdimas->nt1); ?>" autofocus readonly>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center" style="border: 1px solid; width:150px;">
                                                    <?php if ($abdimas->nlpm1 == null) : ?>
                                                        <span class="text-danger"><?= $t('noScore'); ?></span>
                                                    <?php else : ?>
                                                        <?= esc($abdimas->nlpm1); ?>
                                                        <input type="hidden" name="nlpm1" id="nlpm1" class="form-control"
                                                            value="<?= esc($abdimas->nlpm1); ?>" autofocus readonly>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid"><?= $t('criteria1a2'); ?></td>
                                                <td class="text-center" style="border: 1px solid">20</td>
                                                <td class="text-center" style="border: 1px solid; width:150px;">
                                                    <?php if ($abdimas->nt2 == null) : ?>
                                                        <span class="text-danger"><?= $t('noScore'); ?></span>
                                                    <?php else : ?>
                                                        <?= esc($abdimas->nt2); ?>
                                                        <input type="hidden" name="nt2" id="nt2" class="form-control"
                                                            value="<?= esc($abdimas->nt2); ?>" autofocus readonly>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center" style="border: 1px solid; width:150px;">
                                                    <?php if ($abdimas->nlpm2 == null) : ?>
                                                        <span class="text-danger"><?= $t('noScore'); ?></span>
                                                    <?php else : ?>
                                                        <?= esc($abdimas->nlpm2); ?>
                                                        <input type="hidden" name="nlpm2" id="nlpm2" class="form-control"
                                                            value="<?= esc($abdimas->nlpm2); ?>" autofocus readonly>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid"><?= $t('criteria1a3'); ?></td>
                                                <td class="text-center" style="border: 1px solid">10</td>
                                                <td class="text-center" style="border: 1px solid; width:150px;">
                                                    <?php if ($abdimas->nt3 == null) : ?>
                                                        <span class="text-danger"><?= $t('noScore'); ?></span>
                                                    <?php else : ?>
                                                        <?= esc($abdimas->nt3); ?>
                                                        <input type="hidden" name="nt3" id="nt3" class="form-control"
                                                            value="<?= esc($abdimas->nt3); ?>" autofocus readonly>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center" style="border: 1px solid; width:150px;">
                                                    <?php if ($abdimas->nlpm3 == null) : ?>
                                                        <span class="text-danger"><?= $t('noScore'); ?></span>
                                                    <?php else : ?>
                                                        <?= esc($abdimas->nlpm3); ?>
                                                        <input type="hidden" name="nlpm3" id="nlpm3" class="form-control"
                                                            value="<?= esc($abdimas->nlpm3); ?>" autofocus readonly>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid"><?= $t('criteria1a4'); ?></td>
                                                <td class="text-center" style="border: 1px solid">20</td>
                                                <td class="text-center" style="border: 1px solid; width:150px;">
                                                    <?php if ($abdimas->nt4 == null) : ?>
                                                        <span class="text-danger"><?= $t('noScore'); ?></span>
                                                    <?php else : ?>
                                                        <?= esc($abdimas->nt4); ?>
                                                        <input type="hidden" name="nt4" id="nt4" class="form-control"
                                                            value="<?= esc($abdimas->nt4); ?>" autofocus readonly>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center" style="border: 1px solid; width:150px;">
                                                    <?php if ($abdimas->nlpm4 == null) : ?>
                                                        <span class="text-danger"><?= $t('noScore'); ?></span>
                                                    <?php else : ?>
                                                        <?= esc($abdimas->nlpm4); ?>
                                                        <input type="hidden" name="nlpm4" id="nlpm4" class="form-control"
                                                            value="<?= esc($abdimas->nlpm4); ?>" autofocus readonly>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid"><?= $t('criteria1a5'); ?></td>
                                                <td class="text-center" style="border: 1px solid">20</td>
                                                <td class="text-center" style="border: 1px solid; width:150px;">
                                                    <?php if ($abdimas->nt5 == null) : ?>
                                                        <span class="text-danger"><?= $t('noScore'); ?></span>
                                                    <?php else : ?>
                                                        <?= esc($abdimas->nt5); ?>
                                                        <input type="hidden" name="nt5" id="nt5" class="form-control"
                                                            value="<?= esc($abdimas->nt5); ?>" autofocus readonly>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center" style="border: 1px solid; width:150px;">
                                                    <?php if ($abdimas->nlpm5 == null) : ?>
                                                        <span class="text-danger"><?= $t('noScore'); ?></span>
                                                    <?php else : ?>
                                                        <?= esc($abdimas->nlpm5); ?>
                                                        <input type="hidden" name="nlpm5" id="nlpm5" class="form-control"
                                                            value="<?= esc($abdimas->nlpm5); ?>" autofocus readonly>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td rowspan="2" style="border: 1px solid"><?= $t('criteria1b'); ?></td>
                                                <td style="border: 1px solid"><?= $t('criteria1b1'); ?></td>
                                                <td class="text-center" style="border: 1px solid">40</td>
                                                <td class="text-center" style="border: 1px solid; width:150px;">
                                                    <?php if ($abdimas->nt6 == null) : ?>
                                                        <span class="text-danger"><?= $t('noScore'); ?></span>
                                                    <?php else : ?>
                                                        <?= esc($abdimas->nt6); ?>
                                                        <input type="hidden" name="nt6" id="nt6" class="form-control"
                                                            value="<?= esc($abdimas->nt6); ?>" autofocus readonly>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center" style="border: 1px solid; width:150px;">
                                                    <?php if ($abdimas->nlpm6 == null) : ?>
                                                        <span class="text-danger"><?= $t('noScore'); ?></span>
                                                    <?php else : ?>
                                                        <?= esc($abdimas->nlpm6); ?>
                                                        <input type="hidden" name="nlpm6" id="nlpm6" class="form-control"
                                                            value="<?= esc($abdimas->nlpm6); ?>" autofocus readonly>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid"><?= $t('criteria1b2'); ?></td>
                                                <td class="text-center" style="border: 1px solid">60</td>
                                                <td class="text-center" style="border: 1px solid; width:150px;">
                                                    <?php if ($abdimas->nt7 == null) : ?>
                                                        <span class="text-danger"><?= $t('noScore'); ?></span>
                                                    <?php else : ?>
                                                        <?= esc($abdimas->nt7); ?>
                                                        <input type="hidden" name="nt7" id="nt7" class="form-control"
                                                            value="<?= esc($abdimas->nt7); ?>" autofocus readonly>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center" style="border: 1px solid; width:150px;">
                                                    <?php if ($abdimas->nlpm7 == null) : ?>
                                                        <span class="text-danger"><?= $t('noScore'); ?></span>
                                                    <?php else : ?>
                                                        <?= esc($abdimas->nlpm7); ?>
                                                        <input type="hidden" name="nlpm7" id="nlpm7" class="form-control"
                                                            value="<?= esc($abdimas->nlpm7); ?>" autofocus readonly>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row" rowspan="2" style="border: 1px solid">2</th>
                                                <td rowspan="2" style="border: 1px solid"><?= $t('criteria2'); ?></td>
                                                <td style="border: 1px solid"><?= $t('criteria2a'); ?></td>
                                                <td class="text-center" style="border: 1px solid">40</td>
                                                <td class="text-center" style="border: 1px solid; width:150px;">
                                                    <?php if ($abdimas->nt8 == null) : ?>
                                                        <span class="text-danger"><?= $t('noScore'); ?></span>
                                                    <?php else : ?>
                                                        <?= esc($abdimas->nt8); ?>
                                                        <input type="hidden" name="nt8" id="nt8" class="form-control"
                                                            value="<?= esc($abdimas->nt8); ?>" autofocus readonly>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center" style="border: 1px solid; width:150px;">
                                                    <?php if ($abdimas->nlpm8 == null) : ?>
                                                        <span class="text-danger"><?= $t('noScore'); ?></span>
                                                    <?php else : ?>
                                                        <?= esc($abdimas->nlpm8); ?>
                                                        <input type="hidden" name="nlpm8" id="nlpm8" class="form-control"
                                                            value="<?= esc($abdimas->nlpm8); ?>" autofocus readonly>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="border: 1px solid"><?= $t('criteria2b'); ?></td>
                                                <td class="text-center" style="border: 1px solid">60</td>
                                                <td class="text-center" style="border: 1px solid; width:150px;">
                                                    <?php if ($abdimas->nt9 == null) : ?>
                                                        <span class="text-danger"><?= $t('noScore'); ?></span>
                                                    <?php else : ?>
                                                        <?= esc($abdimas->nt9); ?>
                                                        <input type="hidden" name="nt9" id="nt9" class="form-control"
                                                            value="<?= esc($abdimas->nt9); ?>" autofocus readonly>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center" style="border: 1px solid; width:150px;">
                                                    <?php if ($abdimas->nlpm9 == null) : ?>
                                                        <span class="text-danger"><?= $t('noScore'); ?></span>
                                                    <?php else : ?>
                                                        <?= esc($abdimas->nlpm9); ?>
                                                        <input type="hidden" name="nlpm9" id="nlpm9" class="form-control"
                                                            value="<?= esc($abdimas->nlpm9); ?>" autofocus readonly>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
<<<<<<< HEAD
                                                <td colspan="6" style="border: 1px solid; width:150px;">
                                                    <label class="font-weight-bold"><?= $t('suggestionsInput'); ?></label><br>
                                                    <?php if ($abdimas->saran_masukan == null) : ?>
                                                        <span class="text-danger"><?= $t('noSuggestions'); ?></span>
                                                    <?php else : ?>
                                                        <p class="text-break"><?= esc($abdimas->saran_masukan); ?></p>
                                                        <textarea name="saran_masukan" id="saran_masukan" class="form-control" hidden readonly><?= esc($rekapan->saran_masukan); ?></textarea>
=======
                                                <td style="border: 1px solid; width: 20%;" class="font-weight-bold">
                                                    SKM
                                                </td>
                                                <td style="border: 1px solid; width: 80%;">
                                                    <div class="m-2 text-break">
                                                        <?php $skm = $abdimas->skm ?? null; ?>
                                                        <?php if (!empty($skm)) : ?>
                                                            <a href="<?= base_url('berkas/skm/' . $skm); ?>" target="_blank" class="btn btn-sm btn-primary">
                                                                Lihat SKM
                                                            </a>
                                                        <?php else : ?>
                                                            <span class="text-muted">Belum ada file SKM</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="6" style="border: 1px solid; width:150px;">
                                                    <label class="font-weight-bold">Saran & Masukan:</label><br>
                                                    
                                                    <?php if (isset($rekapan) && !empty($rekapan->saran_masukan)) : ?>
                                                        <p class="text-break"><?= esc($rekapan->saran_masukan); ?></p>
                                                        <textarea name="saran_masukan" id="saran_masukan" class="form-control" hidden readonly>
                                                            <?= esc($rekapan->saran_masukan); ?>
                                                        </textarea>
                                                    <?php else : ?>
                                                        <span class="text-danger">Belum ada Saran dan Masukan</span>
>>>>>>> 55c0835 (refactor: update code)
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
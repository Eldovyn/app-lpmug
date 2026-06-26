<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>
<style>
    .mb-3 table.table-bordered td,
    .mb-3 table.table-bordered th {
        border: 1px solid #000 !important;
    }

    .mb-3 table.table-bordered {
        --bs-table-border-color: #000 !important;
    }
</style>
<?= $this->endSection() ?>

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

$tr = [
    'id' => [
        // breadcrumb & general
        'dashboard' => 'Dashboard',
        'rekap_data' => 'Data rekapan',
        'back' => 'kembali',
        'save' => 'Simpan',

        // tabs
        'report_detail' => 'Detail Laporan rekapan',
        'monev_score' => 'Nilai Monitoring dan Evaluasi',

        // table labels (detail)
        'period_rekap' => 'Periode rekapan',
        'leader_data' => 'Data Ketua',
        'partner_name' => 'Nama Mitra',
        'partner_address' => 'Alamat Mitra:',
        'partner_problem' => 'Masalah Mitra',
        'solution' => 'Solusi:',
        'topic_program_sub' => 'Topik - Program - Sub Program',
        'topic' => 'Topik:',
        'program' => 'Program:',
        'subprogram' => 'SubProgram:',
        'outputs' => 'Luaran Kegiatan',
        'activity_type' => 'Tipe Kegiatan',
        'leader_and_members' => 'Ketua dan Anggota',
        'leader' => 'Ketua',
        'student' => 'Mahasiswa',
        'total' => 'Total:',
        'participants' => 'Peserta',
        'funding_est' => 'Estimasi Pendanaan',
        'activity_date' => 'Tanggal Kegiatan',
        'no_activity_date' => 'Belum memasukkan tanggal kegiatan',
        'activity_title' => 'Judul/Nama Kegiatan',
        'no_activity_title' => 'Belum memasukan Judul / Nama kegiatan',
        'activity_report' => 'Laporan Kegiatan',
        'no_report' => 'Belum memasukan Laporan kegiatan',
        'view_report' => 'Lihat Laporan',
        'activity_evidence' => 'Bukti Kegiatan',
        'no_evidence' => 'Belum memasukan Bukti kegiatan',
        'view_evidence' => 'Lihat Bukti Kegiatan',
        'output_link' => 'Link Luaran Kegiatan',
        'no_output_link' => 'Belum memasukan Link Luaran kegiatan',

        // verification
        'verify' => 'Silahkan Verifikasi',
        'choose_verify' => '—PILIH VERIFIKASI—',
        'accept' => 'Terima',
        'process' => 'Proses',
        'revision' => 'Revisi',
        'revision_note' => 'Berikan Catatan Revisi (Jika ada)',

        // monev header
        'monev_h1' => 'MONITORING DAN EVALUASI KEGIATAN',
        'monev_h2' => 'PROGRAM PENGABDIAN KEPADA MASYARAKAT',
        'monev_h3' => 'UNIVERSITAS GUNADARMA PERIODE',

        // monev top table
        'team_leader_name' => 'Nama Ketua Tim PKM',
        'implementation_date' => 'Tanggal Pelaksanaan',
        'no_date' => 'Belum ada tanggal kegiatan',
        'partner_name_pkm' => 'Nama Mitra PKM',
        'activity_title_pkm' => 'Judul Kegiatan PKM',
        'no_title' => 'Belum ada Judul / Nama Kegiatan',
        'team_members_count' => 'Jumlah Anggota Tim:',
        'people_present_note' => 'Orang (yang hadir, presensi terlampir)',

        // monev note & columns
        'note' => 'Note:',
        'note_text_1' => 'Periksa kembali isian nilai anda',
        'note_text_2' => 'sebelum submit',
        'note_text_3' => 'penilaian hanya dapat dilakukan 1x dan tidak dapat diubah setelah anda klik tombol submit',
        'no' => 'No',
        'component' => 'Komponen',
        'desc' => 'Keterangan',
        'weight' => 'Bobot',
        'team_score' => 'Nilai Team',
        'lpm_score' => 'Nilai LPM',

        // monev rubric
        'rubric_title' => 'Penilaian berdasarkan Pelaksana Kegiatan Pengabdian Kepada Masyarakat',
        'rubric_a' => 'a. Materi dan pelaksanaan kegiatan',
        'rubric_a1' => '1. Kesesuaian dengan kebutuhan Mitra',
        'rubric_a2' => '2. Kelengkapan Materi dalam menyelesaikan masalah Mitra',
        'rubric_a3' => '3. Akses materi oleh Mitra (Kemudahan Mitra memperoleh Materi)',
        'rubric_a4' => '4. Kesiapan luaran kegiatan',
        'rubric_a5' => '5. Kesiapan dan pelaksanaan kegiatan',
        'rubric_b' => 'b. Peran dan Kontribusi Anggota',
        'rubric_b1' => '1. Kesesuaian dan kelengkapan bidang ilmu dalam menyelesaikan masalah Mitra',
'bidang_ilmu' => 'Bidang Ilmu',
        'rubric_b2' => '2. Kehadiran dan kontribusi setiap anggota dalam kegiatan',
        'partner_condition' => 'Kondisi Mitra',
        'partner_condition_1' => '1. Partisipasi Mitra saat kegiatan',
        'partner_condition_2' => '2. Manfaat yang dirasakan Mitra',
        'suggestions' => 'Saran & Masukan:',
        'no_value' => 'Belum ada Nilai',
        'no_suggestion' => 'Belum ada Saran dan Masukan',
    ],
    'en' => [
        // breadcrumb & general
        'dashboard' => 'Dashboard',
        'rekap_data' => 'Recap Data',
        'back' => 'Back',
        'save' => 'Save',

        // tabs
        'report_detail' => 'Recap Report Details',
        'monev_score' => 'Monitoring & Evaluation Scores',

        // table labels (detail)
        'period_rekap' => 'Recap Period',
        'leader_data' => 'Leader Data',
        'partner_name' => 'Partner Name',
        'partner_address' => 'Partner Address:',
        'partner_problem' => 'Partner Problem',
        'solution' => 'Solution:',
        'topic_program_sub' => 'Topic - Program - Sub Program',
        'topic' => 'Topic:',
        'program' => 'Program:',
        'subprogram' => 'Sub Program:',
        'outputs' => 'Activity Outputs',
        'activity_type' => 'Activity Type',
        'leader_and_members' => 'Leader and Members',
        'leader' => 'Leader',
        'student' => 'Student',
        'total' => 'Total:',
        'participants' => 'Participants',
        'funding_est' => 'Estimated Funding',
        'activity_date' => 'Activity Date',
        'no_activity_date' => 'Activity date has not been entered',
        'activity_title' => 'Activity Title/Name',
        'no_activity_title' => 'Activity title/name has not been entered',
        'activity_report' => 'Activity Report',
        'no_report' => 'Activity report has not been uploaded',
        'view_report' => 'View Report',
        'activity_evidence' => 'Activity Evidence',
        'no_evidence' => 'Activity evidence has not been uploaded',
        'view_evidence' => 'View Evidence',
        'output_link' => 'Output Link',
        'no_output_link' => 'Output link has not been entered',

        // verification
        'verify' => 'Please Verify',
        'choose_verify' => '—SELECT VERIFICATION—',
        'accept' => 'Accept',
        'process' => 'In Progress',
        'revision' => 'Revision',
        'revision_note' => 'Revision Notes (if any)',

        // monev header
        'monev_h1' => 'MONITORING AND EVALUATION',
        'monev_h2' => 'COMMUNITY SERVICE PROGRAM',
        'monev_h3' => 'GUNADARMA UNIVERSITY PERIOD',

        // monev top table
        'team_leader_name' => 'PKM Team Leader Name',
        'implementation_date' => 'Implementation Date',
        'no_date' => 'No activity date yet',
        'partner_name_pkm' => 'PKM Partner Name',
        'activity_title_pkm' => 'PKM Activity Title',
        'no_title' => 'No activity title/name yet',
        'team_members_count' => 'Number of Team Members:',
        'people_present_note' => 'People (present; attendance attached)',

        // monev note & columns
        'note' => 'Note:',
        'note_text_1' => 'Please double-check your score entries',
        'note_text_2' => 'before submitting',
        'note_text_3' => 'scoring can only be submitted once and cannot be changed after you click submit',
        'no' => 'No',
        'component' => 'Component',
        'desc' => 'Description',
        'weight' => 'Weight',
        'team_score' => 'Team Score',
        'lpm_score' => 'LPM Score',

        // monev rubric
        'rubric_title' => 'Assessment based on Community Service Activity Implementation',
        'rubric_a' => 'a. Materials and implementation',
        'rubric_a1' => '1. Relevance to partner needs',
        'rubric_a2' => '2. Completeness of materials to solve partner problems',
        'rubric_a3' => '3. Partner access to materials (ease of obtaining materials)',
        'rubric_a4' => '4. Readiness of outputs',
        'rubric_a5' => '5. Readiness and execution',
        'rubric_b' => 'b. Member roles and contributions',
        'rubric_b1' => '1. Suitability/completeness of expertise to solve partner problems',
'bidang_ilmu' => 'Field of Study',
        'rubric_b2' => '2. Attendance and contribution of each member',
        'partner_condition' => 'Partner Condition',
        'partner_condition_1' => '1. Partner participation during the activity',
        'partner_condition_2' => '2. Benefits perceived by the partner',
        'suggestions' => 'Suggestions & Feedback:',
        'no_value' => 'No score yet',
        'no_suggestion' => 'No suggestions/feedback yet',
    ],
];

$t = function (string $key) use ($tr, $lang) {
    return $tr[$lang][$key] ?? $tr['id'][$key] ?? $key;
};

// formatter tanggal sesuai bahasa (tanpa redeclare function global)
$formatTanggal = function (?string $tanggal) use ($lang) {
    if (empty($tanggal)) return '-';
    $dateObj = date_create(trim($tanggal));
    if (!$dateObj) return '-';

    $formatted = date_format($dateObj, 'j F Y'); // example: 1 January 2025

    if ($lang === 'id') {
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
            'December' => 'Desember',
        ];
        return strtr($formatted, $bulanIndonesia);
    }

    return $formatted; // EN
};
?>

<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <a href="<?= site_url('rekapan'); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
        <h1><?= $title; ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>"><?= esc($t('dashboard')); ?></a></div>
            <div class="breadcrumb-item active"><a href="<?= site_url('rekapan'); ?>"><?= esc($t('rekap_data')); ?></a></div>
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
                                    aria-controls="home" aria-selected="true"><?= esc($t('report_detail')); ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                                    aria-controls="profile" aria-selected="false"><?= esc($t('monev_score')); ?></a>
                            </li>
                        </ul>

                        <div class="tab-content" id="myTabContent">
                            <!-- TAB 1 -->
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <form action="<?= site_url('rekapan/' . $rekapan->laporan_id); ?>" method="POST" autocomplete="off">
                                    <?= csrf_field(); ?>
                                    <input type="hidden" name="_method" value="PATCH">

                                    <div class="table-responsive-md">
                                        <table class="table table-bordered" style="border: 1px solid">
                                            <tbody>
                                                <tr>
                                                    <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                        <?= esc($t('period_rekap')); ?>
                                                    </td>
                                                    <td class="col-9" style="border: 1px solid">
                                                        <div class="form-group m-2">
                                                            <?php foreach ($periode as $mtr => $v_periode) : ?>
                                                                <?php if ($rekapan->periode_id == $v_periode->periode_id) : ?>
                                                                    <?= $v_periode->periode_name; ?>
                                                                    <?= $v_periode->tahun_ajaran; ?>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                        <?= esc($t('leader_data')); ?>
                                                    </td>
                                                    <td class="col-9" style="border: 1px solid">
                                                        <div class="form-group m-2">
                                                            <?php foreach ($tags as $ds => $v_tags) : ?>
                                                                <?php if ($rekapan->laporan_id == $v_tags->laporan_id && $v_tags->anggota_id == $rekapan->ketua_id) : ?>
                                                                    <input type="hidden" name="nidn" id="nidn" value="<?= $v_tags->nidn; ?>" class="form-control" readonly autofocus>
                                                                    <b>NIDN:</b> <?= $v_tags->nidn; ?> <br>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>

                                                            <?php foreach ($tags as $ds => $v_tags) : ?>
                                                                <?php if ($rekapan->laporan_id == $v_tags->laporan_id && $v_tags->anggota_id == $rekapan->ketua_id) : ?>
                                                                    <input type="hidden" name="ketua_id" id="ketua_id" class="form-control" placeholder="<?= $v_tags->user_name; ?>" readonly autofocus>
                                                                    <b><?= ($lang === 'en' ? 'Leader Name:' : 'Nama Ketua:'); ?></b> <?= $v_tags->user_name; ?> <br>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>

                                                            <?php foreach ($tags as $ds => $v_tags) : ?>
                                                                <?php if ($rekapan->laporan_id == $v_tags->laporan_id && $v_tags->anggota_id == $rekapan->ketua_id) : ?>
                                                                    <input type="hidden" name="sinta_id" id="sinta_id" value="<?= $v_tags->sinta_id; ?>" class="form-control" readonly autofocus>
                                                                    <b>SINTA ID:</b> <?= $v_tags->sinta_id; ?>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                        <?= esc($t('partner_name')); ?>
                                                    </td>
                                                    <td class="col-9" style="border: 1px solid">
                                                        <div class="form-group m-2">
                                                            <?php foreach ($mitra as $mtr => $v_mitra) : ?>
                                                                <?php if ($rekapan->mitra_id == $v_mitra->user_id) : ?>
                                                                    <?= $v_mitra->user_name; ?> <br>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>

                                                            <?php foreach ($mitra as $mtr => $v_mitra) : ?>
                                                                <?php if ($rekapan->mitra_id == $v_mitra->user_id) : ?>
                                                                    <b><?= esc($t('partner_address')); ?></b><br>
                                                                    <?= $v_mitra->alamat . ' -|-|- ' . $v_mitra->kota_name; ?>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="col-6" style="border: 1px solid">
                                                        <strong><?= esc($t('partner_problem')); ?></strong>
                                                        <div class="form-group mt-2">
                                                            <?= esc($abdimas->masalah_mitra); ?>
                                                        </div>
                                                    </td>
                                                    <td class="col-6" style="border: 1px solid">
                                                        <strong><?= esc($t('solution')); ?></strong>
                                                        <div class="form-group mt-2">
                                                            <?= esc($abdimas->solusi_mitra); ?>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                        <?= esc($t('topic_program_sub')); ?>
                                                    </td>
                                                    <td class="col-9" style="border: 1px solid">
                                                        <div class="form-group m-2">
                                                            <?php foreach ($subprogram as $mtr => $v_subprogram) : ?>
                                                                <?php if ($rekapan->subprogram_id == $v_subprogram->subprogram_id) : ?>
                                                                    <b><?= esc($t('topic')); ?> </b><?= $v_subprogram->topik_name; ?> <br>
                                                                    <b><?= esc($t('program')); ?> </b><?= $v_subprogram->program_name; ?> <br>
                                                                    <b><?= esc($t('subprogram')); ?> </b><?= $v_subprogram->subprogram_name; ?>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                        <?= esc($t('outputs')); ?>
                                                    </td>
                                                    <td class="col-9" style="border: 1px solid">
                                                        <div class="form-group m-2">
                                                            <?php
                                                            $seen = [];
                                                            $counter = 1;
                                                            foreach ($tagluaran as $ls => $v_tagluaran) :
                                                                if ($rekapan->laporan_id == $v_tagluaran->laporan_id && !isset($seen[$v_tagluaran->laporan_id])) : ?>
                                                                    <?= $counter . '. ' . ucwords(strtolower($v_tagluaran->luaran_name)); ?><br>
                                                                    <?php $seen[$v_tagluaran->luaran_id] = true;
                                                                    $counter++; ?>
                                                            <?php endif;
                                                            endforeach; ?>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                        <?= esc($t('activity_type')); ?>
                                                    </td>
                                                    <td class="col-9" style="border: 1px solid">
                                                        <div class="form-group m-2">
                                                        <?= $rekapan->tipe_kegiatan; ?>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                        <?= esc($t('bidang_ilmu')); ?>
                                                    </td>
                                                    <td class="col-9" style="border: 1px solid">
                                                        <div class="form-group m-2">
                                                            <?= esc($rekapan->bidang_ilmu ?? '-'); ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                        <?= esc($t('leader_and_members')); ?>
                                                    </td>
                                                    <td class="col-9" style="border: 1px solid">
                                                        <div class="form-group m-2">
                                                            <?php
                                                            $dosen_list = [];
                                                            foreach ($tags as $v_tags) {
                                                                if ($rekapan->laporan_id == $v_tags->laporan_id) {
                                                                    $dosen_list[$v_tags->user_id] = $v_tags;
                                                                }
                                                            }
                                                            $counter = 1;
                                                            $ketua_displayed = false;

                                                            if (isset($dosen_list[$rekapan->ketua_id])) {
                                                                $ketua = $dosen_list[$rekapan->ketua_id];
                                                            ?>
                                                                <?= $counter . '. ' . ucwords(strtolower($ketua->user_name)) ?>
                                                                (<span class="text-danger font-weight-bold"><?= esc($t('leader')); ?></span>)<br>
                                                            <?php
                                                                unset($dosen_list[$rekapan->ketua_id]);
                                                                $counter++;
                                                                $ketua_displayed = true;
                                                            }

                                                            foreach ($dosen_list as $dosen) {
                                                            ?>
                                                                <?= $counter . '. ' . ucwords(strtolower($dosen->user_name)) ?><br>
                                                                <?php
                                                                $counter++;
                                                            }

                                                            $total_dosen = count($dosen_list) + ($ketua_displayed ? 1 : 0);
                                                            ?>
                                                            <br><b><?= esc($t('total')); ?></b> <?= $total_dosen ?> <?= esc($t('participants')); ?><br>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                        <?= esc($t('student')); ?>
                                                    </td>
                                                    <td class="col-9" style="border: 1px solid">
                                                        <div class="form-group m-2">
                                                            <?php
                                                            $counter_mahasiswa = 1;
                                                            if (!empty($mahasiswa)) {
                                                                foreach ($mahasiswa as $mhs) : ?>
                                                                    <?= $counter_mahasiswa . '. ' . esc(ucwords(strtolower($mhs->mahasiswa_name))); ?> - <?= esc($mhs->mahasiswa_npm) ?> - <?= esc($mhs->jurusan_name) ?><br>
                                                                    <?php $counter_mahasiswa++; ?>
                                                                <?php endforeach; ?>
                                                            <?php } ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tr>

                                                <tr>
                                                    <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                        <?= esc($t('funding_est')); ?>
                                                    </td>
                                                    <td class="col-9" style="border: 1px solid">
                                                        <div class="form-group m-2">
                                                            <?= 'Rp. ' . number_format($rekapan->range_dana, 0, ',', '.'); ?>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                        <?= esc($t('activity_date')); ?>
                                                    </td>
                                                    <td class="col-9" style="border: 1px solid">
                                                        <div class="form-group m-2">
                                                            <?php if (empty($abdimas->tanggal_kegiatan)) : ?>
                                                                <span class="text-danger"><?= esc($t('no_activity_date')); ?></span>
                                                            <?php else : ?>
                                                                <?php
                                                                $tanggal = array_map('trim', explode(' - ', $abdimas->tanggal_kegiatan));
                                                                $tanggalMulai = $tanggal[0] ?? null;
                                                                $tanggalSelesai = $tanggal[1] ?? null;

                                                                $formattedMulai = $formatTanggal($tanggalMulai);
                                                                $formattedSelesai = $formatTanggal($tanggalSelesai);
                                                                ?>
                                                                <span><?= $formattedMulai ?> — <?= $formattedSelesai ?></span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                        <?= esc($t('activity_title')); ?>
                                                    </td>
                                                    <td class="col-9" style="border: 1px solid">
                                                        <div class="form-group m-2">
                                                            <?php if ($rekapan->judul_kegiatan == null) : ?>
                                                                <span class="text-danger"><?= esc($t('no_activity_title')); ?></span>
                                                            <?php else : ?>
                                                                <?= $rekapan->judul_kegiatan; ?>
                                                                <input type="hidden" name="judul_kegiatan" id="judul_kegiatan" class="form-control" value="<?= $rekapan->judul_kegiatan; ?>" autofocus readonly>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                        <?= esc($t('activity_report')); ?>
                                                    </td>
                                                    <td class="col-9" style="border: 1px solid">
                                                        <div class="form-group m-2">
                                                            <?php if ($rekapan->laporan == null) : ?>
                                                                <span class="text-danger"><?= esc($t('no_report')); ?></span>
                                                            <?php else : ?>
                                                                <input type="file" hidden name="laporan" id="laporan" class="form-control" autofocus>
                                                                <a href="<?= site_url('berkas/laporan/' . $rekapan->laporan); ?>" class="btn btn-info text-light" target="_blank">
                                                                    <?= esc($t('view_report')); ?>
                                                                </a>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                        <?= esc($t('activity_evidence')); ?>
                                                    </td>
                                                    <td class="col-9" style="border: 1px solid">
                                                        <div class="form-group m-2">
                                                            <?php if ($rekapan->bukti_kegiatan == null) : ?>
                                                                <span class="text-danger"><?= esc($t('no_evidence')); ?></span>
                                                            <?php else : ?>
                                                                <input type="file" hidden name="bukti_kegiatan" id="bukti_kegiatan" class="form-control" autofocus>
                                                                <a href="<?= site_url('berkas/kegiatan/' . $rekapan->bukti_kegiatan); ?>" class="btn btn-info text-light" target="_blank">
                                                                    <?= esc($t('view_evidence')); ?>
                                                                </a>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                        <?= esc($t('output_link')); ?>
                                                    </td>
                                                    <td class="col-9" style="border: 1px solid">
                                                        <div class="form-group m-2">
                                                            <?php if ($rekapan->link_luaran == null) : ?>
                                                                <span class="text-danger"><?= esc($t('no_output_link')); ?></span>
                                                            <?php else : ?>
                                                                <a href="<?= $rekapan->link_luaran; ?>"><?= $rekapan->link_luaran; ?></a>
                                                                <input type="hidden" name="link_luaran" id="link_luaran" class="form-control" value="<?= $rekapan->link_luaran; ?>">
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                        <?= esc($t('verify')); ?>
                                                    </td>
                                                    <td class="col-9" style="border: 1px solid">
                                                        <div class="form-group m-2">
                                                            <select name="verifikasi" class="form-control select2">
                                                                <option selected disabled>&mdash;<?= esc($t('choose_verify')); ?>&mdash;</option>
                                                                <?php if ($rekapan->verifikasi == 1) : ?>
                                                                    <option value="<?= $rekapan->verifikasi; ?>" selected><?= esc($t('accept')); ?></option>
                                                                    <option value="0"><?= esc($t('process')); ?></option>
                                                                    <option value="2"><?= esc($t('revision')); ?></option>
                                                                <?php elseif ($rekapan->verifikasi == 2) : ?>
                                                                    <option value="<?= $rekapan->verifikasi; ?>" selected><?= esc($t('revision')); ?></option>
                                                                    <option value="0"><?= esc($t('process')); ?></option>
                                                                    <option value="1"><?= esc($t('accept')); ?></option>
                                                                <?php elseif ($rekapan->verifikasi == 0) : ?>
                                                                    <option value="<?= $rekapan->verifikasi; ?>" selected><?= esc($t('process')); ?></option>
                                                                    <option value="1"><?= esc($t('accept')); ?></option>
                                                                    <option value="2"><?= esc($t('revision')); ?></option>
                                                                <?php endif; ?>
                                                            </select>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                        <?= esc($t('revision_note')); ?> <b>(<?= ($lang === 'en' ? 'If any' : 'Jika ada'); ?>)</b>
                                                    </td>
                                                    <td class="col-9" style="border: 1px solid">
                                                        <div class="form-group m-2">
                                                            <textarea name="revisi" class="form-control" style="height: 150px"><?= $rekapan->revisi; ?></textarea>
                                                        </div>
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="text-right">
                                        <a href="<?= site_url('rekapan'); ?>" class="btn btn-dark"><?= esc($t('back')); ?></a>
                                        <button type="submit" class="btn btn-primary"><?= esc($t('save')); ?></button>
                                    </div>
                                </form>
                            </div>

                            <!-- TAB 2 -->
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <form action="<?= site_url('monev/' . $rekapan->laporan_id); ?>" method="POST" autocomplete="off" enctype="multipart/form-data">
                                    <?= csrf_field(); ?>
                                    <input type="hidden" name="_method" value="PATCH">

                                    <div class="text-center mb-4">
                                        <h5><?= esc($t('monev_h1')); ?></h5>
                                        <h5><?= esc($t('monev_h2')); ?></h5>
                                        <h5><?= esc($t('monev_h3')); ?>
                                            <span class="text-primary">
                                                <?php foreach ($periode as $mtr => $v_periode) : ?>
                                                    <?php if ($rekapan->periode_id == $v_periode->periode_id) : ?>
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
                                                    <td class="col-2 font-weight-bold"><?= esc($t('team_leader_name')); ?></td>
                                                    <td class="col-5">:
                                                        <?php foreach ($tags as $ds => $v_tags) : ?>
                                                            <?php if ($rekapan->laporan_id == $v_tags->laporan_id && $v_tags->anggota_id == $rekapan->ketua_id) : ?>
                                                                <input type="hidden" name="ketua_id" id="ketua_id" class="form-control" placeholder="<?= $v_tags->user_name; ?>" readonly autofocus>
                                                                <?= $v_tags->user_name; ?> <br>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    </td>

                                                    <td class="font-weight-bold" style="border: 1px solid"><?= esc($t('implementation_date')); ?></td>
                                                    <td style="border: 1px solid">
                                                        <?php if (empty($abdimas->tanggal_kegiatan)) : ?>
                                                            <span class="text-danger"><?= esc($t('no_date')); ?></span>
                                                        <?php else : ?>
                                                            <?php
                                                            $tanggal = array_map('trim', explode(' - ', $abdimas->tanggal_kegiatan));
                                                            $tanggalMulai = $tanggal[0] ?? null;
                                                            $tanggalSelesai = $tanggal[1] ?? null;

                                                            $formattedMulai = $formatTanggal($tanggalMulai);
                                                            $formattedSelesai = $formatTanggal($tanggalSelesai);
                                                            ?>
                                                            <span><?= $formattedMulai ?> — <?= $formattedSelesai ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="col-2 font-weight-bold"><?= esc($t('partner_name_pkm')); ?></td>
                                                    <td colspan="3">:
                                                        <?php foreach ($mitra as $mtr => $v_mitra) : ?>
                                                            <?php if ($rekapan->mitra_id == $v_mitra->user_id) : ?>
                                                                <?= $v_mitra->user_name; ?>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="col-2 font-weight-bold"><?= esc($t('activity_title_pkm')); ?></td>
                                                    <td colspan="3">:
                                                        <?php if ($rekapan->judul_kegiatan == null) : ?>
                                                            <span class="text-danger"><?= esc($t('no_title')); ?></span>
                                                        <?php else : ?>
                                                            <?= $rekapan->judul_kegiatan; ?>
                                                            <input type="hidden" name="judul_kegiatan" id="judul_kegiatan" class="form-control" value="<?= $rekapan->judul_kegiatan; ?>" autofocus readonly>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class="col-2 font-weight-bold"><?= esc($t('team_members_count')); ?></td>
                                                    <td colspan="3">:
                                                        <?php
                                                        $dosen_count = 0;
                                                        $seen = [];
                                                        foreach ($tags as $v_tags) {
                                                            if ($rekapan->laporan_id == $v_tags->laporan_id && !isset($seen[$v_tags->user_id])) {
                                                                $seen[$v_tags->user_id] = true;
                                                                $dosen_count++;
                                                            }
                                                        }
                                                        echo $dosen_count;
                                                        ?>
                                                        <?= esc($t('people_present_note')); ?>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <table class="table table-bordered" style="border: 1px solid">
                                        <span class="text-primary"><b><?= esc($t('note')); ?></b>
                                            <?= esc($t('note_text_1')); ?> <b><?= esc($t('note_text_2')); ?></b>,
                                            <?= esc($t('note_text_3')); ?>
                                        </span>

                                        <thead>
                                            <tr>
                                                <th style="border: 1px solid"><?= esc($t('no')); ?></th>
                                                <th style="border: 1px solid"><?= esc($t('component')); ?></th>
                                                <th style="border: 1px solid"><?= esc($t('desc')); ?></th>
                                                <th class="text-center" style="border: 1px solid"><?= esc($t('weight')); ?></th>
                                                <th class="text-center" style="border: 1px solid"><?= esc($t('team_score')); ?></th>
                                                <th class="text-center" style="border: 1px solid"><?= esc($t('lpm_score')); ?></th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr>
                                                <th scope="row" rowspan="9" style="border: 1px solid">1</th>
                                                <td colspan="5" style="border: 1px solid"><?= esc($t('rubric_title')); ?></td>
                                            </tr>
                                            <tr>
                                                <td rowspan="6" style="border: 1px solid"><?= esc($t('rubric_a')); ?></td>
                                            </tr>

                                            <tr>
                                                <td style="border: 1px solid"><?= esc($t('rubric_a1')); ?></td>
                                                <td class="text-center" style="border: 1px solid">30</td>
                                                <td class="text-center" style="border: 1px solid; width:150px;">
                                                    <?php if ($rekapan->nt1 == null) : ?>
                                                        <span class="text-danger"><?= esc($t('no_value')); ?></span>
                                                    <?php else : ?>
                                                        <?= $rekapan->nt1; ?>
                                                        <input type="hidden" name="nt1" id="nt1" class="form-control" value="<?= $rekapan->nt1; ?>" autofocus readonly>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center" style="border: 1px solid; width:150px;">
                                                    <?php if ($rekapan->nlpm1 == null) : ?>
                                                        <span class="text-danger"><?= esc($t('no_value')); ?></span>
                                                    <?php else : ?>
                                                        <?= $rekapan->nlpm1; ?>
                                                        <input type="hidden" name="nlpm1" id="nlpm1" class="form-control" value="<?= $rekapan->nlpm1; ?>" autofocus readonly>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="border: 1px solid"><?= esc($t('rubric_a2')); ?></td>
                                                <td class="text-center" style="border: 1px solid">20</td>
                                                <td class="text-center" style="border: 1px solid; width:150px;">
                                                    <?php if ($rekapan->nt2 == null) : ?>
                                                        <span class="text-danger"><?= esc($t('no_value')); ?></span>
                                                    <?php else : ?>
                                                        <?= $rekapan->nt2; ?>
                                                        <input type="hidden" name="nt2" id="nt2" class="form-control" value="<?= $rekapan->nt2; ?>" autofocus readonly>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center" style="border: 1px solid; width:150px;">
                                                    <?php if ($rekapan->nlpm2 == null) : ?>
                                                        <span class="text-danger"><?= esc($t('no_value')); ?></span>
                                                    <?php else : ?>
                                                        <?= $rekapan->nlpm2; ?>
                                                        <input type="hidden" name="nlpm2" id="nlpm2" class="form-control" value="<?= $rekapan->nlpm2; ?>" autofocus readonly>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="border: 1px solid"><?= esc($t('rubric_a3')); ?></td>
                                                <td class="text-center" style="border: 1px solid">10</td>
                                                <td class="text-center" style="border: 1px solid; width:150px;">
                                                    <?php if ($rekapan->nt3 == null) : ?>
                                                        <span class="text-danger"><?= esc($t('no_value')); ?></span>
                                                    <?php else : ?>
                                                        <?= $rekapan->nt3; ?>
                                                        <input type="hidden" name="nt3" id="nt3" class="form-control" value="<?= $rekapan->nt3; ?>" autofocus readonly>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center" style="border: 1px solid; width:150px;">
                                                    <?php if ($rekapan->nlpm3 == null) : ?>
                                                        <span class="text-danger"><?= esc($t('no_value')); ?></span>
                                                    <?php else : ?>
                                                        <?= $rekapan->nlpm3; ?>
                                                        <input type="hidden" name="nlpm3" id="nlpm3" class="form-control" value="<?= $rekapan->nlpm3; ?>" autofocus readonly>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="border: 1px solid"><?= esc($t('rubric_a4')); ?></td>
                                                <td class="text-center" style="border: 1px solid">20</td>
                                                <td class="text-center" style="border: 1px solid; width:150px;">
                                                    <?php if ($rekapan->nt4 == null) : ?>
                                                        <span class="text-danger"><?= esc($t('no_value')); ?></span>
                                                    <?php else : ?>
                                                        <?= $rekapan->nt4; ?>
                                                        <input type="hidden" name="nt4" id="nt4" class="form-control" value="<?= $rekapan->nt4; ?>" autofocus readonly>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center" style="border: 1px solid; width:150px;">
                                                    <?php if ($rekapan->nlpm4 == null) : ?>
                                                        <span class="text-danger"><?= esc($t('no_value')); ?></span>
                                                    <?php else : ?>
                                                        <?= $rekapan->nlpm4; ?>
                                                        <input type="hidden" name="nlpm4" id="nlpm4" class="form-control" value="<?= $rekapan->nlpm4; ?>" autofocus readonly>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="border: 1px solid"><?= esc($t('rubric_a5')); ?></td>
                                                <td class="text-center" style="border: 1px solid">20</td>
                                                <td class="text-center" style="border: 1px solid; width:150px;">
                                                    <?php if ($rekapan->nt5 == null) : ?>
                                                        <span class="text-danger"><?= esc($t('no_value')); ?></span>
                                                    <?php else : ?>
                                                        <?= $rekapan->nt5; ?>
                                                        <input type="hidden" name="nt5" id="nt5" class="form-control" value="<?= $rekapan->nt5; ?>" autofocus readonly>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center" style="border: 1px solid; width:150px;">
                                                    <?php if ($rekapan->nlpm5 == null) : ?>
                                                        <span class="text-danger"><?= esc($t('no_value')); ?></span>
                                                    <?php else : ?>
                                                        <?= $rekapan->nlpm5; ?>
                                                        <input type="hidden" name="nlpm5" id="nlpm5" class="form-control" value="<?= $rekapan->nlpm5; ?>" autofocus readonly>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td rowspan="2" style="border: 1px solid"><?= esc($t('rubric_b')); ?></td>
                                                <td style="border: 1px solid"><?= esc($t('rubric_b1')); ?></td>
                                                <td class="text-center" style="border: 1px solid">40</td>
                                                <td class="text-center" style="border: 1px solid; width:150px;">
                                                    <?php if ($rekapan->nt6 == null) : ?>
                                                        <span class="text-danger"><?= esc($t('no_value')); ?></span>
                                                    <?php else : ?>
                                                        <?= $rekapan->nt6; ?>
                                                        <input type="hidden" name="nt6" id="nt6" class="form-control" value="<?= $rekapan->nt6; ?>" autofocus readonly>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center" style="border: 1px solid; width:150px;">
                                                    <?php if ($rekapan->nlpm6 == null) : ?>
                                                        <span class="text-danger"><?= esc($t('no_value')); ?></span>
                                                    <?php else : ?>
                                                        <?= $rekapan->nlpm6; ?>
                                                        <input type="hidden" name="nlpm6" id="nlpm6" class="form-control" value="<?= $rekapan->nlpm6; ?>" autofocus readonly>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="border: 1px solid"><?= esc($t('rubric_b2')); ?></td>
                                                <td class="text-center" style="border: 1px solid">60</td>
                                                <td class="text-center" style="border: 1px solid; width:150px;">
                                                    <?php if ($rekapan->nt7 == null) : ?>
                                                        <span class="text-danger"><?= esc($t('no_value')); ?></span>
                                                    <?php else : ?>
                                                        <?= $rekapan->nt7; ?>
                                                        <input type="hidden" name="nt7" id="nt7" class="form-control" value="<?= $rekapan->nt7; ?>" autofocus readonly>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center" style="border: 1px solid; width:150px;">
                                                    <?php if ($rekapan->nlpm7 == null) : ?>
                                                        <span class="text-danger"><?= esc($t('no_value')); ?></span>
                                                    <?php else : ?>
                                                        <?= $rekapan->nlpm7; ?>
                                                        <input type="hidden" name="nlpm7" id="nlpm7" class="form-control" value="<?= $rekapan->nlpm7; ?>" autofocus readonly>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th scope="row" rowspan="2" style="border: 1px solid">2</th>
                                                <td rowspan="2" style="border: 1px solid"><?= esc($t('partner_condition')); ?></td>
                                                <td style="border: 1px solid"><?= esc($t('partner_condition_1')); ?></td>
                                                <td class="text-center" style="border: 1px solid">40</td>
                                                <td class="text-center" style="border: 1px solid; width:150px;">
                                                    <?php if ($rekapan->nt8 == null) : ?>
                                                        <span class="text-danger"><?= esc($t('no_value')); ?></span>
                                                    <?php else : ?>
                                                        <?= $rekapan->nt8; ?>
                                                        <input type="hidden" name="nt8" id="nt8" class="form-control" value="<?= $rekapan->nt8; ?>" autofocus readonly>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center" style="border: 1px solid; width:150px;">
                                                    <?php if ($rekapan->nlpm8 == null) : ?>
                                                        <span class="text-danger"><?= esc($t('no_value')); ?></span>
                                                    <?php else : ?>
                                                        <?= $rekapan->nlpm8; ?>
                                                        <input type="hidden" name="nlpm8" id="nlpm8" class="form-control" value="<?= $rekapan->nlpm8; ?>" autofocus readonly>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="border: 1px solid"><?= esc($t('partner_condition_2')); ?></td>
                                                <td class="text-center" style="border: 1px solid">60</td>
                                                <td class="text-center" style="border: 1px solid; width:150px;">
                                                    <?php if ($rekapan->nt9 == null) : ?>
                                                        <span class="text-danger"><?= esc($t('no_value')); ?></span>
                                                    <?php else : ?>
                                                        <?= $rekapan->nt9; ?>
                                                        <input type="hidden" name="nt9" id="nt9" class="form-control" value="<?= $rekapan->nt9; ?>" autofocus readonly>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center" style="border: 1px solid; width:150px;">
                                                    <?php if ($rekapan->nlpm9 == null) : ?>
                                                        <span class="text-danger"><?= esc($t('no_value')); ?></span>
                                                    <?php else : ?>
                                                        <?= $rekapan->nlpm9; ?>
                                                        <input type="hidden" name="nlpm9" id="nlpm9" class="form-control" value="<?= $rekapan->nlpm9; ?>" autofocus readonly>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="6" style="border: 1px solid; width:150px;">
                                                    <label class="font-weight-bold"><?= esc($t('suggestions')); ?></label><br>
                                                    <?php if ($rekapan->saran_masukan == null) : ?>
                                                        <span class="text-danger"><?= esc($t('no_suggestion')); ?></span>
                                                    <?php else : ?>
                                                        <?= $rekapan->saran_masukan; ?>
                                                        <textarea name="saran_masukan" id="saran_masukan" class="form-control" hidden readonly><?= $rekapan->saran_masukan; ?></textarea>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                            <!-- end tab 2 -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<title><?= html_entity_decode($title_tab); ?></title>
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
        'dashboard' => 'Dashboard',
        'congrats' => 'Selamat!',
        'warning_error' => 'Warning Error!',

        'progress' => 'Progress',
        'leader' => 'Ketua',
        'members' => 'Anggota',
        'description' => 'Deskripsi',
        'period' => 'Periode',
        'status' => 'Status',
        'action' => 'Action',

        'submission' => 'Pengusulan',
        'implementation' => 'Pelaksanaan <br> Abdimas',
        'team_eval_done' => 'Sudah <br> Penilaian Team <br> (MonEv Team)',
        'needed' => 'dibutuhkan:',
        'need_1' => 'Penilaian dari LPM',
        'need_2' => 'Upload laporan',
        'need_3' => 'Bukti Kegiatan',
        'need_4' => 'Link luaran',
        'need_lpm_eval' => 'Butuh <br> Penilaian LPM <br> (MonEv LPM)',
        'reporting' => 'Pelaporan Hasil <br> Abdimas',
        'done' => 'Selesai',
        'incomplete' => 'Data Laporan <br> Belum Lengkap',

        'activity_title' => 'Judul Kegiatan:',
        'field' => 'Bidang Ilmu:',
        'partner' => 'Mitra:',
        'address' => 'Alamat:',
        'funding' => 'Dana Pengabdian:',

        'approved' => 'DISETUJUI',
        'revision' => 'REVISI',
        'process' => 'PROSES',

        'view' => 'Lihat',
        'approval_sheet' => 'Lembar Pengesahan',
        'archive_docs' => 'Arsip Dokumen Abdimas',
        'invitation_letter' => 'Surat Undangan',
        'delete' => 'Hapus',
        'score_monev' => 'Nilai MonEv',

        'confirm_delete' => 'Hapus data? | Apakah anda yakin?',
        'leader_tag' => 'Ketua',
    ],
    'en' => [
        'dashboard' => 'Dashboard',
        'congrats' => 'Congratulations!',
        'warning_error' => 'Warning!',

        'progress' => 'Progress',
        'leader' => 'Leader',
        'members' => 'Members',
        'description' => 'Description',
        'period' => 'Period',
        'status' => 'Status',
        'action' => 'Action',

        'submission' => 'Submission',
        'implementation' => 'Implementation <br> (Community Service)',
        'team_eval_done' => 'Team Evaluation <br> Completed <br> (MonEv Team)',
        'needed' => 'required:',
        'need_1' => 'Evaluation from LPM',
        'need_2' => 'Upload report',
        'need_3' => 'Activity evidence',
        'need_4' => 'Output link',
        'need_lpm_eval' => 'Needs <br> LPM Evaluation <br> (MonEv LPM)',
        'reporting' => 'Results <br> Reporting',
        'done' => 'Completed',
        'incomplete' => 'Report Data <br> Incomplete',

        'activity_title' => 'Activity Title:',
        'field' => 'Field:',
        'partner' => 'Partner:',
        'address' => 'Address:',
        'funding' => 'Funding:',

        'approved' => 'APPROVED',
        'revision' => 'REVISION',
        'process' => 'IN PROGRESS',

        'view' => 'View',
        'approval_sheet' => 'Approval Sheet',
        'archive_docs' => 'Document Archive',
        'invitation_letter' => 'Invitation Letter',
        'delete' => 'Delete',
        'score_monev' => 'MonEv Score',

        'confirm_delete' => 'Delete data? | Are you sure?',
        'leader_tag' => 'Leader',
    ],
];

$t = function (string $key) use ($tr, $lang) {
    return $tr[$lang][$key] ?? $tr['id'][$key] ?? $key;
};
?>

<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <h1><?= esc($title); ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active">
                <a href="<?= site_url('dashboard'); ?>"><?= esc($t('dashboard')); ?></a>
            </div>
            <div class="breadcrumb-item"><?= esc($title); ?></div>
        </div>
    </div>

    <?php if ($message = session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b><?= esc($t('congrats')); ?></b> <?= esc($message); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($message = session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b><?= esc($t('warning_error')); ?></b> <?= esc($message); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="section-body">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive-md">
                    <table class="table table-striped" id="table-1">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?= esc($t('progress')); ?></th>
                                <th><?= esc($t('leader')); ?></th>
                                <th><?= esc($t('members')); ?></th>
                                <th><?= esc($t('description')); ?></th>
                                <th><?= esc($t('period')); ?></th>
                                <th><?= esc($t('status')); ?></th>
                                <th class="text-center"><?= esc($t('action')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $page = isset($_GET['page']) ? $_GET['page'] : 1;
                            $no = 1 + (1000 * ($page - 1));
                            foreach ($abdimas as $v_abdimas) :
                                if ($v_abdimas->verifikasi == 0 || $v_abdimas->verifikasi == 1 || $v_abdimas->verifikasi == 2) : ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td>
                                            <?php
                                            $isPengusulan =
                                                !is_null($v_abdimas->laporan_id) &&
                                                !is_null($v_abdimas->ketua_id) &&
                                                !is_null($v_abdimas->mitra_id) &&
                                                !is_null($v_abdimas->subprogram_id) &&
                                                !is_null($v_abdimas->luaran_id) &&
                                                !is_null($v_abdimas->periode_id) &&
                                                !is_null($v_abdimas->tipe_kegiatan) &&
                                                !is_null($v_abdimas->range_dana) &&
                                                is_null($v_abdimas->tanggal_kegiatan) &&
                                                is_null($v_abdimas->judul_kegiatan);

                                            $isPelaksanaan =
                                                !is_null($v_abdimas->tanggal_kegiatan) &&
                                                !is_null($v_abdimas->judul_kegiatan) &&
                                                is_null($v_abdimas->nt1) &&
                                                is_null($v_abdimas->nt2) &&
                                                is_null($v_abdimas->nt3) &&
                                                is_null($v_abdimas->nt4) &&
                                                is_null($v_abdimas->nt5) &&
                                                is_null($v_abdimas->nt6) &&
                                                is_null($v_abdimas->nt7) &&
                                                is_null($v_abdimas->nt8) &&
                                                is_null($v_abdimas->nt9) &&
                                                is_null($v_abdimas->nlpm1) &&
                                                is_null($v_abdimas->nlpm2) &&
                                                is_null($v_abdimas->nlpm3) &&
                                                is_null($v_abdimas->nlpm4) &&
                                                is_null($v_abdimas->nlpm5) &&
                                                is_null($v_abdimas->nlpm6) &&
                                                is_null($v_abdimas->nlpm7) &&
                                                is_null($v_abdimas->nlpm8) &&
                                                is_null($v_abdimas->nlpm9);

                                            $isMonevTeam =
                                                !is_null($v_abdimas->nt1) &&
                                                !is_null($v_abdimas->nt2) &&
                                                !is_null($v_abdimas->nt3) &&
                                                !is_null($v_abdimas->nt4) &&
                                                !is_null($v_abdimas->nt5) &&
                                                !is_null($v_abdimas->nt6) &&
                                                !is_null($v_abdimas->nt7) &&
                                                !is_null($v_abdimas->nt8) &&
                                                !is_null($v_abdimas->nt9) &&
                                                is_null($v_abdimas->nlpm1) &&
                                                is_null($v_abdimas->nlpm2) &&
                                                is_null($v_abdimas->nlpm3) &&
                                                is_null($v_abdimas->nlpm4) &&
                                                is_null($v_abdimas->nlpm5) &&
                                                is_null($v_abdimas->nlpm6) &&
                                                is_null($v_abdimas->nlpm7) &&
                                                is_null($v_abdimas->nlpm8) &&
                                                is_null($v_abdimas->nlpm9);

                                            $isMonevLPM =
                                                !is_null($v_abdimas->nt1) &&
                                                !is_null($v_abdimas->nt2) &&
                                                !is_null($v_abdimas->nt3) &&
                                                !is_null($v_abdimas->nt4) &&
                                                !is_null($v_abdimas->nt5) &&
                                                !is_null($v_abdimas->nt6) &&
                                                !is_null($v_abdimas->nt7) &&
                                                !is_null($v_abdimas->nt8) &&
                                                !is_null($v_abdimas->nt9) &&
                                                is_null($v_abdimas->nlpm1) &&
                                                is_null($v_abdimas->nlpm2) &&
                                                is_null($v_abdimas->nlpm3) &&
                                                is_null($v_abdimas->nlpm4) &&
                                                is_null($v_abdimas->nlpm5) &&
                                                is_null($v_abdimas->nlpm6) &&
                                                is_null($v_abdimas->nlpm7) &&
                                                is_null($v_abdimas->nlpm8) &&
                                                is_null($v_abdimas->nlpm9) &&
                                                !is_null($v_abdimas->laporan) &&
                                                !is_null($v_abdimas->bukti_kegiatan) &&
                                                !is_null($v_abdimas->link_luaran);

                                            $isPelaporan =
                                                !is_null($v_abdimas->laporan) &&
                                                !is_null($v_abdimas->bukti_kegiatan) &&
                                                !is_null($v_abdimas->link_luaran);

                                            $isSelesai =
                                                !is_null($v_abdimas->laporan_id) &&
                                                !is_null($v_abdimas->ketua_id) &&
                                                !is_null($v_abdimas->mitra_id) &&
                                                !is_null($v_abdimas->subprogram_id) &&
                                                !is_null($v_abdimas->luaran_id) &&
                                                !is_null($v_abdimas->periode_id) &&
                                                !is_null($v_abdimas->tipe_kegiatan) &&
                                                !is_null($v_abdimas->range_dana) &&
                                                !is_null($v_abdimas->tanggal_kegiatan) &&
                                                !is_null($v_abdimas->judul_kegiatan) &&
                                                !is_null($v_abdimas->nt1) &&
                                                !is_null($v_abdimas->nt2) &&
                                                !is_null($v_abdimas->nt3) &&
                                                !is_null($v_abdimas->nt4) &&
                                                !is_null($v_abdimas->nt5) &&
                                                !is_null($v_abdimas->nt6) &&
                                                !is_null($v_abdimas->nt7) &&
                                                !is_null($v_abdimas->nt8) &&
                                                !is_null($v_abdimas->nt9) &&
                                                !is_null($v_abdimas->nlpm1) &&
                                                !is_null($v_abdimas->nlpm2) &&
                                                !is_null($v_abdimas->nlpm3) &&
                                                !is_null($v_abdimas->nlpm4) &&
                                                !is_null($v_abdimas->nlpm5) &&
                                                !is_null($v_abdimas->nlpm6) &&
                                                !is_null($v_abdimas->nlpm7) &&
                                                !is_null($v_abdimas->nlpm8) &&
                                                !is_null($v_abdimas->nlpm9) &&
                                                !is_null($v_abdimas->laporan) &&
                                                !is_null($v_abdimas->bukti_kegiatan) &&
                                                !is_null($v_abdimas->link_luaran);
                                            ?>

                                            <?php if ($isPengusulan): ?>
                                                <span class="badge badge-dark"><?= esc($t('submission')); ?></span>
                                            <?php elseif ($isPelaksanaan): ?>
                                                <span class="badge badge-primary"><?= $t('implementation'); ?></span>
                                            <?php elseif ($isMonevTeam): ?>
                                                <span class="badge badge-warning text-dark"><?= $t('team_eval_done'); ?></span>
                                                <br><br>
                                                <span class="text-dark text-small">
                                                    <?= esc($t('needed')); ?> <br>
                                                    1. <?= esc($t('need_1')); ?> <br>
                                                    2. <?= esc($t('need_2')); ?> <br>
                                                    3. <?= esc($t('need_3')); ?> <br>
                                                    4. <?= esc($t('need_4')); ?>
                                                </span>
                                            <?php elseif ($isMonevLPM): ?>
                                                <span class="badge badge-danger text-dark"><?= $t('need_lpm_eval'); ?></span>
                                            <?php elseif ($isPelaporan): ?>
                                                <span class="badge badge-info text-dark"><?= $t('reporting'); ?></span>
                                            <?php elseif ($isSelesai): ?>
                                                <span class="badge badge-success text-dark"><?= esc($t('done')); ?></span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary text-dark"><?= $t('incomplete'); ?></span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-wrap">
                                            <?php foreach ($tags as $v_tags) :
                                                if ($v_abdimas->laporan_id == $v_tags->laporan_id && $v_tags->anggota_id == $v_abdimas->ketua_id) :
                                                    echo esc(ucwords(strtolower($v_tags->user_name))) .
                                                        '<br> <b>NIDN:</b> ' . $v_tags->nidn .
                                                        '<br> <b>SINTA ID</b>: ' . $v_tags->sinta_id .
                                                        ' (<span class="text-danger">' . esc($t('leader_tag')) . '</span>)';
                                                endif;
                                            endforeach; ?>
                                        </td>

                                        <td class="text-wrap">
                                            <?php
                                            $seen = [];
                                            $counter = 1;
                                            foreach ($tags as $v_tags) :
                                                if ($v_abdimas->laporan_id == $v_tags->laporan_id && !isset($seen[$v_tags->user_id])) :
                                                    echo $counter++ . '. ' . esc(ucwords(strtolower($v_tags->user_name))) .
                                                        '<br> <b>NIDN:</b> ' . $v_tags->nidn .
                                                        '<br> <b>SINTA ID: </b>' . $v_tags->sinta_id . '<br><br>';
                                                    $seen[$v_tags->user_id] = true;
                                                endif;
                                            endforeach;
                                            ?>
                                        </td>

                                        <td class="text-wrap">
                                            <b><?= esc($t('activity_title')); ?></b>
                                            <?= $v_abdimas->judul_kegiatan; ?><br><br>

                                            <b><?= esc($t('field')); ?></b>
                                            <?php foreach ($dosen as $v_dosen) :
                                                if ($v_dosen->user_id == $v_abdimas->ketua_id) :
                                                    echo esc($v_dosen->jurusan_name);
                                                endif;
                                            endforeach; ?><br><br>

                                            <b><?= esc($t('partner')); ?></b>
                                            <?php
                                            $seen = [];
                                            foreach ($mitra as $v_mitra) :
                                                if ($v_mitra->user_id == $v_abdimas->mitra_id && !isset($seen[$v_mitra->user_id])) :
                                                    echo esc($v_mitra->user_name);
                                                    $seen[$v_mitra->user_id] = true;
                                                endif;
                                            endforeach; ?><br><br>

                                            <b><?= esc($t('address')); ?></b>
                                            <?php
                                            $seen = [];
                                            foreach ($mitra as $alt_mitra) :
                                                if ($alt_mitra->user_id == $v_abdimas->mitra_id && !isset($seen[$alt_mitra->user_id])) :
                                                    echo esc($alt_mitra->alamat);
                                                    $seen[$alt_mitra->user_id] = true;
                                                endif;
                                            endforeach; ?><br><br>

                                            <b><?= esc($t('label_fund')); ?></b>
                                            <?= 'Rp. ' . number_format((int) $v_abdimas->range_dana, 0, ',', '.'); ?><br><br>

                                            <b>Mahasiswa Terlibat:</b><br>
                                            <?php
                                            $mhs_list = [];
                                            if (isset($mahasiswa) && (is_array($mahasiswa) || is_object($mahasiswa))) {
                                                foreach ($mahasiswa as $mhs) {
                                                    if ($v_abdimas->laporan_id == $mhs->laporan_id) {
                                                        $mhs_list[] = esc(ucwords(strtolower($mhs->mahasiswa_name))) . ' (' . esc($mhs->mahasiswa_npm) . ')';
                                                    }
                                                }
                                            }
                                            echo !empty($mhs_list) ? implode('<br>', $mhs_list) : '<span class="text-danger">-</span>';
                                            ?>
                                        </td>

                                        <td class="text-wrap">
                                            <?php foreach ($periode as $v_periode) :
                                                if ($v_periode->periode_id == $v_abdimas->periode_id) :
                                                    echo esc($v_periode->periode_name) . ' ' . esc($v_periode->tahun_ajaran);
                                                endif;
                                            endforeach; ?>
                                        </td>

                                        <td class="text-center">
                                            <span class="badge <?= $v_abdimas->verifikasi == 1 ? 'badge-success' : ($v_abdimas->verifikasi == 2 ? 'badge-warning text-dark' : 'badge-primary') ?>">
                                                <?= $v_abdimas->verifikasi == 1 ? esc($t('approved')) : ($v_abdimas->verifikasi == 2 ? esc($t('revision')) : esc($t('process'))) ?>
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            <div class="d-flex flex-column align-items-center">
                                                <a href="<?= site_url('rekapan/' . $v_abdimas->laporan_id . '/edit'); ?>"
                                                    class="btn btn-dark btn-sm p-1 mb-1" style="width:150px; text-align:center;">
                                                    <?= esc($t('view')); ?>
                                                </a>

                                                <a href="<?= site_url('/abdimas/pdf/' . $v_abdimas->laporan_id); ?>"
                                                    class="btn btn-success btn-sm p-1 mb-1" style="width:150px">
                                                    <?= esc($t('approval_sheet')); ?>
                                                </a>

                                                <a href="<?= site_url('abdimas/arsip/' . $v_abdimas->laporan_id); ?>"
                                                    class="btn btn-warning btn-sm p-1 mb-1" style="width:150px">
                                                    <?= esc($t('archive_docs')); ?>
                                                </a>

                                                <?php if (!empty($v_abdimas->surat_undangan)) : ?>
                                                    <a href="<?= site_url('berkas/undangan/' . $v_abdimas->surat_undangan); ?>"
                                                        class="btn btn-info btn-sm p-1 mb-1" style="width:150px" target="_blank">
                                                        <i class="fas fa-eye mr-1"></i><?= esc($t('invitation_letter')); ?>
                                                    </a>
                                                <?php endif; ?>

                                                <?php if (in_array(userLogin()->role_id, [1, 2, 6])) : ?>
                                                    <form action="<?= site_url('abdimas/' . $v_abdimas->laporan_id); ?>" method="POST" class="d-inline" id="del-<?= $v_abdimas->laporan_id; ?>">
                                                        <?= csrf_field(); ?>
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <button class="btn btn-danger btn-sm p-1 mb-1" style="width:150px"
                                                            data-confirm="<?= esc($t('confirm_delete')); ?>"
                                                            data-confirm-yes="submitDel(<?= $v_abdimas->laporan_id; ?>)">
                                                            <?= esc($t('delete')); ?>
                                                        </button>
                                                    </form>

                                                    <a href="<?= site_url('monevadmin/' . $v_abdimas->laporan_id . '/edit'); ?>"
                                                        class="btn btn-primary btn-sm p-1 mx-auto" style="width:150px; text-align:center;">
                                                        <?= esc($t('score_monev')); ?>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                            <?php endif;
                            endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
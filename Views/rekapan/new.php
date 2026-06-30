<?= $this->extend('layouts/default') ?>

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

$TR = [
    'id' => [
        'dashboard' => 'Dashboard',

        'success_title' => 'Selamat!',
        'error_title'   => 'Warning Error!',

        'th_progress'   => 'Progress',
        'th_leader'     => 'Ketua',
        'th_members'    => 'Anggota',
        'th_desc'       => 'Deskripsi',
        'th_period'     => 'Periode',
        'th_status'     => 'Status',
        'th_action'     => 'Action',

        // progress badges
        'prog_propose'  => 'Pengusulan',
        'prog_exec'     => 'Pelaksanaan <br> Abdimas',
        'prog_team_done' => 'Sudah <br> Penilaian Team <br> (MonEv Team)',
        'prog_need'     => 'dibutuhkan: <br>
                        1. Penilaian dari LPM <br>
                        2. Upload laporan <br>
                        3. Bukti Kegiatan <br>
                        4. Link luaran',
        'prog_need_lpm' => 'Butuh <br> Penilaian LPM <br> (MonEv LPM)',
        'prog_report'   => 'Pelaporan Hasil <br> Abdimas',
        'prog_done'     => 'Selesai',
        'prog_incomplete' => 'Data Laporan <br> Belum Lengkap',

        // description labels
        'lbl_activity_title' => 'Judul Kegiatan:',
        'lbl_field'          => 'Bidang Ilmu:',
        'lbl_partner'        => 'Mitra:',
        'lbl_address'        => 'Alamat:',
        'lbl_fund'           => 'Dana Pengabdian:',

        // status badge text
        'status_process'  => 'PROSES',
        'status_approved' => 'DISETUJUI',
        'status_revision' => 'REVISI',

        // buttons
        'btn_view'        => 'Lihat',
        'btn_sheet'       => 'Lembar Pengesahan',
        'btn_archive'     => 'Arsip Dokumen Abdimas',
        'btn_invitation'  => 'Surat Undangan',
        'btn_delete'      => 'Hapus',
        'btn_score'       => 'Nilai MonEv',

        // confirm delete
        'confirm_delete_title' => 'Hapus data?',
        'confirm_delete_msg'   => 'Apakah anda yakin?',
    ],
    'en' => [
        'dashboard' => 'Dashboard',

        'success_title' => 'Success!',
        'error_title'   => 'Warning!',

        'th_progress'   => 'Progress',
        'th_leader'     => 'Leader',
        'th_members'    => 'Members',
        'th_desc'       => 'Description',
        'th_period'     => 'Period',
        'th_status'     => 'Status',
        'th_action'     => 'Action',

        // progress badges
        'prog_propose'  => 'Submission',
        'prog_exec'     => 'Implementation <br> (Community Service)',
        'prog_team_done' => 'Team Review <br> Completed <br> (MonEv Team)',
        'prog_need'     => 'required: <br>
                        1. LPM assessment <br>
                        2. Upload report <br>
                        3. Activity evidence <br>
                        4. Output link',
        'prog_need_lpm' => 'Needs <br> LPM Assessment <br> (MonEv LPM)',
        'prog_report'   => 'Final Reporting <br> (Community Service)',
        'prog_done'     => 'Completed',
        'prog_incomplete' => 'Report Data <br> Incomplete',

        // description labels
        'lbl_activity_title' => 'Activity Title:',
        'lbl_field'          => 'Field of Study:',
        'lbl_partner'        => 'Partner:',
        'lbl_address'        => 'Address:',
        'lbl_fund'           => 'Funding:',

        // status badge text
        'status_process'  => 'IN PROGRESS',
        'status_approved' => 'APPROVED',
        'status_revision' => 'REVISION',

        // buttons
        'btn_view'        => 'View',
        'btn_sheet'       => 'Approval Sheet',
        'btn_archive'     => 'Abdimas Document Archive',
        'btn_invitation'  => 'Invitation Letter',
        'btn_delete'      => 'Delete',
        'btn_score'       => 'MonEv Score',

        // confirm delete
        'confirm_delete_title' => 'Delete data?',
        'confirm_delete_msg'   => 'Are you sure?',
    ],
];

$t = static function (string $key) use ($TR, $lang): string {
    return $TR[$lang][$key] ?? $TR['id'][$key] ?? $key;
};
?>

<?= $this->section('title') ?>
<title><?= html_entity_decode($title_tab); ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <h1><?= esc($title); ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="<?= site_url('dashboard'); ?>"><?= esc($t('dashboard')) ?></a></div>
            <div class="breadcrumb-item"><?= esc($title); ?></div>
        </div>
    </div>

    <?php if ($message = session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b><?= esc($t('success_title')) ?></b> <?= esc($message); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($message = session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b><?= esc($t('error_title')) ?></b> <?= esc($message); ?>
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
                                <th><?= $t('th_progress') ?></th>
                                <th><?= $t('th_leader') ?></th>
                                <th><?= $t('th_members') ?></th>
                                <th><?= $t('th_desc') ?></th>
                                <th><?= $t('th_period') ?></th>
                                <th><?= $t('th_status') ?></th>
                                <th class="text-center"><?= $t('th_action') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $page = isset($_GET['page']) ? $_GET['page'] : 1;
                            $no = 1 + (10 * ($page - 1));
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
                                                <span class="badge badge-dark"><?= $t('prog_propose') ?></span>
                                            <?php elseif ($isPelaksanaan): ?>
                                                <span class="badge badge-primary"><?= $t('prog_exec') ?></span>
                                            <?php elseif ($isMonevTeam): ?>
                                                <span class="badge badge-warning text-dark"><?= $t('prog_team_done') ?></span>
                                                <br><br>
                                                <span class="text-dark text-small"><?= $t('prog_need') ?></span>
                                            <?php elseif ($isMonevLPM): ?>
                                                <span class="badge badge-danger text-dark"><?= $t('prog_need_lpm') ?></span>
                                            <?php elseif ($isPelaporan): ?>
                                                <span class="badge badge-info text-dark"><?= $t('prog_report') ?></span>
                                            <?php elseif ($isSelesai): ?>
                                                <span class="badge badge-success text-dark"><?= $t('prog_done') ?></span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary text-dark"><?= $t('prog_incomplete') ?></span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-wrap">
                                            <?php foreach ($tags as $v_tags) :
                                                if ($v_abdimas->laporan_id == $v_tags->laporan_id && $v_tags->anggota_id == $v_abdimas->ketua_id) :
                                                    echo esc(ucwords(strtolower($v_tags->user_name))) . '<br> <b>NIDN:</b> ' . $v_tags->nidn . '<br> <b>SINTA ID</b>: ' . $v_tags->sinta_id . ' (<span class="text-danger">Ketua</span>)';
                                                endif;
                                            endforeach; ?>
                                        </td>

                                        <td class="text-wrap">
                                            <?php
                                            $seen = [];
                                            $counter = 1;
                                            foreach ($tags as $v_tags) :
                                                if ($v_abdimas->laporan_id == $v_tags->laporan_id && !isset($seen[$v_tags->user_id])) :
                                                    echo $counter++ . '. ' . esc(ucwords(strtolower($v_tags->user_name))) . '<br> <b>NIDN:</b> ' . $v_tags->nidn . '<br> <b>SINTA ID: </b>' . $v_tags->sinta_id . '<br><br>';
                                                    $seen[$v_tags->user_id] = true;
                                                endif;
                                            endforeach; ?>
                                        </td>

                                        <td class="text-wrap">
                                            <b><?= $t('lbl_activity_title') ?></b>
                                            <?= $v_abdimas->judul_kegiatan; ?><br><br>

                                            <b><?= $t('lbl_field') ?></b>
                                            <?php foreach ($dosen as $v_dosen) :
                                                if ($v_dosen->user_id == $v_abdimas->ketua_id) :
                                                    echo esc($v_dosen->jurusan_name);
                                                endif;
                                            endforeach; ?><br><br>

                                            <b><?= $t('lbl_partner') ?></b>
                                            <?php
                                            $seen = [];
                                            foreach ($mitra as $v_mitra) :
                                                if ($v_mitra->user_id == $v_abdimas->mitra_id && !isset($seen[$v_mitra->user_id])) :
                                                    echo esc($v_mitra->user_name);
                                                    $seen[$v_mitra->user_id] = true;
                                                endif;
                                            endforeach; ?><br><br>

                                            <b><?= $t('lbl_address') ?></b>
                                            <?php
                                            $seen = [];
                                            foreach ($mitra as $alt_mitra) :
                                                if ($alt_mitra->user_id == $v_abdimas->mitra_id && !isset($seen[$alt_mitra->user_id])) :
                                                    echo esc($alt_mitra->alamat);
                                                    $seen[$alt_mitra->user_id] = true;
                                                endif;
                                            endforeach; ?><br><br>

                                            <b><?= $t('lbl_fund') ?></b>
                                            <?= 'Rp. ' . number_format((int) $v_abdimas->range_dana, 0, ',', '.'); ?>
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
                                                <?= $v_abdimas->verifikasi == 1 ? $t('status_approved') : ($v_abdimas->verifikasi == 2 ? $t('status_revision') : $t('status_process')) ?>
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            <a href="<?= site_url('rekapan/' . $v_abdimas->laporan_id . '/edit'); ?>" class="btn btn-dark btn-sm p-1 mb-1" style="width:150px"><?= $t('btn_view') ?></a>
                                            <a href="<?= site_url('/abdimas/pdf/' . $v_abdimas->laporan_id); ?>" class="btn btn-success btn-sm p-1 mb-1" style="width:150px"><?= $t('btn_sheet') ?></a>
                                            <a href="<?= site_url('abdimas/arsip/' . $v_abdimas->laporan_id); ?>" class="btn btn-warning btn-sm p-1 mb-1" style="width:150px"><?= $t('btn_archive') ?></a>

                                            <?php if (!empty($v_abdimas->surat_undangan)) : ?>
                                                <a href="<?= site_url('berkas/undangan/' . $v_abdimas->surat_undangan); ?>" class="btn btn-info btn-sm p-1 mb-1" style="width:150px" target="_blank">
                                                    <i class="fas fa-eye mr-1"></i><?= $t('btn_invitation') ?>
                                                </a>
                                            <?php endif; ?>

                                            <?php if (in_array(userLogin()->role_id, [1, 2])) : ?>
                                                <form action="<?= site_url('abdimas/' . $v_abdimas->laporan_id); ?>" method="POST" class="d-inline" id="del-<?= $v_abdimas->laporan_id; ?>">
                                                    <?= csrf_field(); ?>
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button
                                                        class="btn btn-danger btn-sm p-1 mb-1" style="width:150px"
                                                        data-confirm="<?= esc($t('confirm_delete_title')) ?> | <?= esc($t('confirm_delete_msg')) ?>"
                                                        data-confirm-yes="submitDel(<?= $v_abdimas->laporan_id; ?>)">
                                                        <?= $t('btn_delete') ?>
                                                    </button>
                                                </form>
                                                <a href="<?= site_url('monevadmin/' . $v_abdimas->laporan_id . '/edit'); ?>" class="btn btn-primary btn-sm p-1 mb-1" style="width:150px"><?= $t('btn_score') ?></a>
                                            <?php endif; ?>
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
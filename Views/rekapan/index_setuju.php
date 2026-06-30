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

        'th_progress' => 'Progress',
        'th_leader'   => 'Ketua',
        'th_members'  => 'Anggota',
        'th_desc'     => 'Deskripsi',
        'th_period'   => 'Periode',
        'th_status'   => 'Status',
        'th_action'   => 'Action',

        'progress_pengusulan'     => 'Pengusulan',
        'progress_pelaksanaan'    => 'Pelaksanaan <br> Abdimas',
        'progress_monev_team'     => 'Sudah <br> Penilaian Team <br> (MonEv Team)',
        'progress_need'           => 'dibutuhkan:',
        'need_1'                  => 'Penilaian dari LPM',
        'need_2'                  => 'Upload laporan',
        'need_3'                  => 'Bukti Kegiatan',
        'need_4'                  => 'Link luaran',
        'progress_monev_lpm'      => 'Butuh <br> Penilaian LPM <br> (MonEv LPM)',
        'progress_pelaporan'      => 'Pelaporan Hasil <br> Abdimas',
        'progress_selesai'        => 'Selesai',
        'progress_incomplete'     => 'Data Laporan <br> Belum Lengkap',

        'label_ketua'         => 'Ketua',
        'label_activity_title' => 'Judul Kegiatan:',
        'label_field'         => 'Bidang Ilmu:',
        'label_partner'       => 'Mitra:',
        'label_address'       => 'Alamat:',
        'label_fund'          => 'Dana Pengabdian:',

        'status_approved' => 'DISETUJUI',
        'status_revision' => 'REVISI',
        'status_process'  => 'PROSES',

        'btn_view'           => 'Lihat',
        'btn_approval_sheet' => 'Lembar Pengesahan',
        'btn_archive'        => 'Arsip Dokumen Abdimas',
        'btn_invitation'     => 'Surat Undangan',
        'btn_delete'         => 'Hapus',
        'btn_monev'          => 'Nilai MonEv',

        'confirm_delete_title' => 'Hapus data?',
        'confirm_delete_msg'   => 'Apakah anda yakin?',
    ],
    'en' => [
        'dashboard' => 'Dashboard',

        'success_title' => 'Success!',
        'error_title'   => 'Warning!',

        'th_progress' => 'Progress',
        'th_leader'   => 'Leader',
        'th_members'  => 'Members',
        'th_desc'     => 'Description',
        'th_period'   => 'Period',
        'th_status'   => 'Status',
        'th_action'   => 'Action',

        'progress_pengusulan'     => 'Submission',
        'progress_pelaksanaan'    => 'Implementation <br> (Community Service)',
        'progress_monev_team'     => 'Team Assessment <br> Completed <br> (Monitoring & Evaluation)',
        'progress_need'           => 'required:',
        'need_1'                  => 'Assessment from LPM',
        'need_2'                  => 'Upload report',
        'need_3'                  => 'Activity evidence',
        'need_4'                  => 'Output link',
        'progress_monev_lpm'      => 'Waiting for <br> LPM Assessment <br> (Monitoring & Evaluation)',
        'progress_pelaporan'      => 'Final Reporting <br> (Community Service)',
        'progress_selesai'        => 'Completed',
        'progress_incomplete'     => 'Report Data <br> Incomplete',

        'label_ketua'         => 'Leader',
        'label_activity_title' => 'Activity Title:',
        'label_field'         => 'Field of Study:',
        'label_partner'       => 'Partner:',
        'label_address'       => 'Address:',
        'label_fund'          => 'Funding:',

        'status_approved' => 'APPROVED',
        'status_revision' => 'REVISION',
        'status_process'  => 'IN PROGRESS',

        'btn_view'           => 'View',
        'btn_approval_sheet' => 'Approval Sheet',
        'btn_archive'        => 'Community Service Archive',
        'btn_invitation'     => 'Invitation Letter',
        'btn_delete'         => 'Delete',
        'btn_monev'          => 'Score M&E',

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
                <b><?= esc($t('success_title')); ?></b> <?= esc($message); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($message = session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b><?= esc($t('error_title')); ?></b> <?= esc($message); ?>
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
                                <th><?= $t('th_progress'); ?></th>
                                <th><?= $t('th_leader'); ?></th>
                                <th><?= $t('th_members'); ?></th>
                                <th><?= $t('th_desc'); ?></th>
                                <th><?= $t('th_period'); ?></th>
                                <th><?= $t('th_status'); ?></th>
                                <th class="text-center"><?= $t('th_action'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $page = isset($_GET['page']) ? $_GET['page'] : 1;
                            $no = 1 + (25 * ($page - 1));
                            foreach ($abdimas as $v_abdimas) :
                                if ($v_abdimas->verifikasi == 1) : ?>
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
                                                <span class="badge badge-dark"><?= $t('progress_pengusulan'); ?></span>
                                            <?php elseif ($isPelaksanaan): ?>
                                                <span class="badge badge-primary"><?= $t('progress_pelaksanaan'); ?></span>
                                            <?php elseif ($isMonevTeam): ?>
                                                <span class="badge badge-warning text-dark"><?= $t('progress_monev_team'); ?></span>
                                                <br><br>
                                                <span class="text-dark text-small">
                                                    <?= $t('progress_need'); ?><br>
                                                    1. <?= $t('need_1'); ?><br>
                                                    2. <?= $t('need_2'); ?><br>
                                                    3. <?= $t('need_3'); ?><br>
                                                    4. <?= $t('need_4'); ?>
                                                </span>
                                            <?php elseif ($isMonevLPM): ?>
                                                <span class="badge badge-danger text-dark"><?= $t('progress_monev_lpm'); ?></span>
                                            <?php elseif ($isPelaporan): ?>
                                                <span class="badge badge-info text-dark"><?= $t('progress_pelaporan'); ?></span>
                                            <?php elseif ($isSelesai): ?>
                                                <span class="badge badge-success text-dark"><?= $t('progress_selesai'); ?></span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary text-dark"><?= $t('progress_incomplete'); ?></span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-wrap">
                                            <?php foreach ($tags as $v_tags) :
                                                if ($v_abdimas->laporan_id == $v_tags->laporan_id && $v_tags->anggota_id == $v_abdimas->ketua_id) :
                                                    echo esc(ucwords(strtolower($v_tags->user_name)))
                                                        . '<br> <b>NIDN:</b> ' . esc($v_tags->nidn)
                                                        . '<br> <b>SINTA ID</b>: ' . esc($v_tags->sinta_id)
                                                        . ' (<span class="text-danger">' . esc($t('label_ketua')) . '</span>)';
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
                                            endforeach; 
                                            ?>
                                        </td>

                                        <td class="text-wrap">
                                            <b><?= esc($t('label_activity_title')); ?></b>
                                            <?= esc($v_abdimas->judul_kegiatan); ?><br><br>

                                            <b><?= esc($t('label_field')); ?></b>
                                            <?php foreach ($dosen as $v_dosen) :
                                                if ($v_dosen->user_id == $v_abdimas->ketua_id) :
                                                    echo esc($v_dosen->jurusan_name);
                                                endif;
                                            endforeach; ?><br><br>

                                            <b><?= esc($t('label_partner')); ?></b>
                                            <?php
                                            $seen = [];
                                            foreach ($mitra as $v_mitra) :
                                                if ($v_mitra->user_id == $v_abdimas->mitra_id && !isset($seen[$v_mitra->user_id])) :
                                                    echo esc($v_mitra->user_name);
                                                    $seen[$v_mitra->user_id] = true;
                                                endif;
                                            endforeach; ?><br><br>

                                            <b><?= esc($t('label_address')); ?></b>
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
                                                <?= $v_abdimas->verifikasi == 1 ? $t('status_approved') : ($v_abdimas->verifikasi == 2 ? $t('status_revision') : $t('status_process')) ?>
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            <a href="<?= site_url('rekapan/' . $v_abdimas->laporan_id . '/edit'); ?>" class="btn btn-dark btn-sm p-1 mb-1" style="width:150px">
                                                <?= esc($t('btn_view')); ?>
                                            </a>
                                            <a href="<?= site_url('/abdimas/pdf/' . $v_abdimas->laporan_id); ?>" class="btn btn-success btn-sm p-1 mb-1" style="width:150px">
                                                <?= esc($t('btn_approval_sheet')); ?>
                                            </a>
                                            <a href="<?= site_url('abdimas/arsip/' . $v_abdimas->laporan_id); ?>" class="btn btn-warning btn-sm p-1 mb-1" style="width:150px">
                                                <?= esc($t('btn_archive')); ?>
                                            </a>

                                            <?php if (!empty($v_abdimas->surat_undangan)) : ?>
                                                <a href="<?= site_url('berkas/undangan/' . $v_abdimas->surat_undangan); ?>" class="btn btn-info btn-sm p-1 mb-1" style="width:150px" target="_blank">
                                                    <i class="fas fa-eye mr-1"></i><?= esc($t('btn_invitation')); ?>
                                                </a>
                                            <?php endif; ?>

                                            <?php if (in_array(userLogin()->role_id, [1, 2])) : ?>
                                                <form action="<?= site_url('abdimas/' . $v_abdimas->laporan_id); ?>" method="POST" class="d-inline" id="del-<?= $v_abdimas->laporan_id; ?>">
                                                    <?= csrf_field(); ?>
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button
                                                        class="btn btn-danger btn-sm p-1 mb-1"
                                                        style="width:150px"
                                                        data-confirm="<?= esc($t('confirm_delete_title')); ?> | <?= esc($t('confirm_delete_msg')); ?>"
                                                        data-confirm-yes="submitDel(<?= (int) $v_abdimas->laporan_id; ?>)">
                                                        <?= esc($t('btn_delete')); ?>
                                                    </button>
                                                </form>

                                                <a href="<?= site_url('monevadmin/' . $v_abdimas->laporan_id . '/edit'); ?>" class="btn btn-primary btn-sm p-1 mb-1" style="width:150px">
                                                    <?= esc($t('btn_monev')); ?>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                            <?php endif;
                            endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php
                $total = $pager->getTotal();
                $from  = $total > 0 ? (1 + (25 * ($page - 1))) : 0;
                $to    = max(0, $no - 1);
                ?>
                <div class="mt-3">
                    <div class="float-left">
                        <i>Showing <?= $from; ?> to <?= $to; ?> of <?= $total; ?> entries</i>
                    </div>
                    <div class="float-right">
                        <?= $pager->links('default', 'pagination'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
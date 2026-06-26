<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<title><?= html_entity_decode($title_tab); ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
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

// Translator sederhana (ID sebagai default)
$t = static function (string $id, ?string $en = null) use ($lang): string {
    return $lang === 'en' ? ($en ?? $id) : $id;
};
?>
<section class="section">
    <div class="section-header">
        <h1><?= esc($title); ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active">
                <a href="<?= site_url('dashboard'); ?>"><?= esc($t('Dashboard', 'Dashboard')); ?></a>
            </div>
            <div class="breadcrumb-item"><?= esc($title); ?></div>
        </div>
    </div>

    <?php if ($message = session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b><?= esc($t('Selamat!', 'Congratulations!')); ?></b> <?= esc($message); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($message = session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b><?= esc($t('Warning Error!', 'Warning!')); ?></b> <?= esc($message); ?>
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
                                <th><?= esc($t('Progress', 'Progress')); ?></th>
                                <th><?= esc($t('Ketua', 'Leader')); ?></th>
                                <th><?= esc($t('Anggota', 'Members')); ?></th>
                                <th><?= esc($t('Deskripsi', 'Description')); ?></th>
                                <th><?= esc($t('Periode', 'Period')); ?></th>
                                <th><?= esc($t('Status', 'Status')); ?></th>
                                <th class="text-center"><?= esc($t('Action', 'Action')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $page = isset($_GET['page']) ? $_GET['page'] : 1;
                            $no = 1 + (25 * ($page - 1));
                            foreach ($abdimas as $v_abdimas) :
                                if ($v_abdimas->verifikasi == 2) : ?>
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
                                                <span class="badge badge-dark"><?= esc($t('Pengusulan', 'Proposal')); ?></span>
                                            <?php elseif ($isPelaksanaan): ?>
                                                <span class="badge badge-primary"><?= $t('Pelaksanaan <br> Abdimas', 'Implementation <br> Community Service'); ?></span>
                                            <?php elseif ($isMonevTeam): ?>
                                                <span class="badge badge-warning text-dark"><?= $t('Sudah <br> Penilaian Team <br> (MonEv Team)', 'Reviewed <br> by Team <br> (MonEv Team)'); ?></span>
                                                <br><br>
                                                <span class="text-dark text-small">
                                                    <?= $t('dibutuhkan: <br>', 'required: <br>'); ?>
                                                    1. <?= esc($t('Penilaian dari LPM', 'Evaluation by LPM')); ?> <br>
                                                    2. <?= esc($t('Upload laporan', 'Upload report')); ?> <br>
                                                    3. <?= esc($t('Bukti Kegiatan', 'Activity evidence')); ?> <br>
                                                    4. <?= esc($t('Link luaran', 'Output link')); ?>
                                                </span>
                                            <?php elseif ($isMonevLPM): ?>
                                                <span class="badge badge-danger text-dark"><?= $t('Butuh <br> Penilaian LPM <br> (MonEv LPM)', 'Needs <br> LPM Evaluation <br> (MonEv LPM)'); ?></span>
                                            <?php elseif ($isPelaporan): ?>
                                                <span class="badge badge-info text-dark"><?= $t('Pelaporan Hasil <br> Abdimas', 'Results Reporting <br> Community Service'); ?></span>
                                            <?php elseif ($isSelesai): ?>
                                                <span class="badge badge-success text-dark"><?= esc($t('Selesai', 'Completed')); ?></span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary text-dark"><?= $t('Data Laporan <br> Belum Lengkap', 'Report Data <br> Incomplete'); ?></span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-wrap">
                                            <?php foreach ($tags as $v_tags) :
                                                if ($v_abdimas->laporan_id == $v_tags->laporan_id && $v_tags->anggota_id == $v_abdimas->ketua_id) :
                                                    echo esc(ucwords(strtolower($v_tags->user_name))) .
                                                        '<br> <b>NIDN:</b> ' . $v_tags->nidn .
                                                        '<br> <b>SINTA ID</b>: ' . $v_tags->sinta_id .
                                                        ' (<span class="text-danger">' . esc($t('Ketua', 'Leader')) . '</span>)';
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
                                            endforeach; ?>
                                        </td>

                                        <td class="text-wrap">
                                            <b><?= esc($t('Judul Kegiatan:', 'Activity Title:')); ?></b>
                                            <?= $v_abdimas->judul_kegiatan; ?><br><br>

                                            <b><?= esc($t('Bidang Ilmu:', 'Field of Study:')); ?></b>
                                            <?php foreach ($dosen as $v_dosen) :
                                                if ($v_dosen->user_id == $v_abdimas->ketua_id) :
                                                    echo esc($v_dosen->jurusan_name);
                                                endif;
                                            endforeach; ?><br><br>

                                            <b><?= esc($t('Mitra:', 'Partner:')); ?></b>
                                            <?php
                                            $seen = [];
                                            foreach ($mitra as $v_mitra) :
                                                if ($v_mitra->user_id == $v_abdimas->mitra_id && !isset($seen[$v_mitra->user_id])) :
                                                    echo esc($v_mitra->user_name);
                                                    $seen[$v_mitra->user_id] = true;
                                                endif;
                                            endforeach; ?><br><br>

                                            <b><?= esc($t('Alamat:', 'Address:')); ?></b>
                                            <?php
                                            $seen = [];
                                            foreach ($mitra as $alt_mitra) :
                                                if ($alt_mitra->user_id == $v_abdimas->mitra_id && !isset($seen[$alt_mitra->user_id])) :
                                                    echo esc($alt_mitra->alamat);
                                                    $seen[$alt_mitra->user_id] = true;
                                                endif;
                                            endforeach; ?><br><br>

                                            <b><?= esc($t('Dana Pengabdian:', 'Funding:')); ?></b>
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
                                                <?= $v_abdimas->verifikasi == 1
                                                    ? esc($t('DISETUJUI', 'APPROVED'))
                                                    : ($v_abdimas->verifikasi == 2 ? esc($t('REVISI', 'REVISION')) : esc($t('PROSES', 'PROCESS'))); ?>
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            <a href="<?= site_url('rekapan/' . $v_abdimas->laporan_id . '/edit'); ?>"
                                                class="btn btn-dark btn-sm p-1 mb-1" style="width:150px">
                                                <?= esc($t('Lihat', 'View')); ?>
                                            </a>

                                            <a href="<?= site_url('/abdimas/pdf/' . $v_abdimas->laporan_id); ?>"
                                                class="btn btn-success btn-sm p-1 mb-1" style="width:150px">
                                                <?= esc($t('Lembar Pengesahan', 'Approval Sheet')); ?>
                                            </a>

                                            <a href="<?= site_url('abdimas/arsip/' . $v_abdimas->laporan_id); ?>"
                                                class="btn btn-warning btn-sm p-1 mb-1" style="width:150px">
                                                <?= esc($t('Arsip Dokumen Abdimas', 'Abdimas Document Archive')); ?>
                                            </a>

                                            <?php if (in_array(userLogin()->role_id, [1, 2])) : ?>
                                                <form action="<?= site_url('abdimas/' . $v_abdimas->laporan_id); ?>"
                                                    method="POST" class="d-inline" id="del-<?= $v_abdimas->laporan_id; ?>">
                                                    <?= csrf_field(); ?>
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button class="btn btn-danger btn-sm p-1 mb-1" style="width:150px"
                                                        data-confirm="<?= esc($t('Hapus data? | Apakah anda yakin?', 'Delete data? | Are you sure?'), 'attr'); ?>"
                                                        data-confirm-yes="submitDel(<?= $v_abdimas->laporan_id; ?>)">
                                                        <?= esc($t('Hapus', 'Delete')); ?>
                                                    </button>
                                                </form>

                                                <a href="<?= site_url('monevadmin/' . $v_abdimas->laporan_id . '/edit'); ?>"
                                                    class="btn btn-primary btn-sm p-1 mb-1" style="width:150px">
                                                    <?= esc($t('Nilai MonEv', 'Score MonEv')); ?>
                                                </a>
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
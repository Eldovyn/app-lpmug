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
        'pageTitle' => 'Pelaksanaan',
        'dashboard' => 'Dashboard',

        'successTitle' => 'Selamat!',
        'errorTitle'   => 'Warning Error!',

        'colNo'     => '#',
        'colReport' => 'Laporan',
        'colAction' => 'Action',

        'activityTitle'        => 'Judul Kegiatan:',
        'noActivityTitle'      => 'Belum ada Judul Kegiatan',

        'activityFund'         => 'Dana Kegiatan:',
        'noActivityFund'       => 'Belum ada Dana Kegiatan',

        'partner'              => 'Mitra:',
        'period'               => 'Periode:',
        'notAvailable'          => 'Tidak Tersedia',

        'status'               => 'Status:',
        'statusProcess'         => 'PROSES',
        'statusApproved'        => 'DISETUJUI',
        'statusRevision'        => 'REVISI',

        'revisionNote'          => 'Catatan Perbaikan:',
        'noRevisionNote'        => 'Tidak ada catatan revisi',

        'members'               => 'Anggota:',
        'leader'                => 'Ketua',

        'nidn'                  => 'NIDN:',
        'sinta'                 => 'SINTA ID:',

        'btnEditImplementation' => 'Edit Pelaksanaan',
        'btnInvitationLetter'   => 'Surat Undangan',
        'btnReplyLetter'        => 'Surat Balasan',

        'showing'               => 'Menampilkan %1$s sampai %2$s dari %3$s entri',
        'noDataAvailable'       => 'Tidak ada data yang tersedia',
    ],
    'en' => [
        'pageTitle' => 'Implementation',
        'dashboard' => 'Dashboard',

        'successTitle' => 'Success!',
        'errorTitle'   => 'Error!',

        'colNo'     => '#',
        'colReport' => 'Report',
        'colAction' => 'Action',

        'activityTitle'        => 'Activity Title:',
        'noActivityTitle'      => 'No activity title yet',

        'activityFund'         => 'Activity Funding:',
        'noActivityFund'       => 'No activity funding yet',

        'partner'              => 'Partner:',
        'period'               => 'Period:',
        'notAvailable'          => 'Not Available',

        'status'               => 'Status:',
        'statusProcess'         => 'IN PROCESS',
        'statusApproved'        => 'APPROVED',
        'statusRevision'        => 'REVISION',

        'revisionNote'          => 'Revision Notes:',
        'noRevisionNote'        => 'No revision notes',

        'members'               => 'Members:',
        'leader'                => 'Leader',

        'nidn'                  => 'NIDN:',
        'sinta'                 => 'SINTA ID:',

        'btnEditImplementation' => 'Edit Implementation',
        'btnInvitationLetter'   => 'Invitation Letter',
        'btnReplyLetter'        => 'Reply Letter',

        'showing'               => 'Showing %1$s to %2$s of %3$s entries',
        'noDataAvailable'       => 'No data available',
    ],
];

$t = function (string $key, ...$args) use ($I18N, $lang) {
    $text = $I18N[$lang][$key] ?? $I18N['id'][$key] ?? $key;
    return $args ? vsprintf($text, $args) : $text;
};
?>

<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <h1><?= $t('pageTitle'); ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="<?= site_url('dashboard'); ?>"><?= $t('dashboard'); ?></a></div>
            <div class="breadcrumb-item"><?= $t('pageTitle'); ?></div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b><?= $t('successTitle'); ?></b>
                <?= session()->getFlashdata('success'); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b><?= $t('errorTitle'); ?></b>
                <?= session()->getFlashdata('error'); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="section-body">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><?= $t('colNo'); ?></th>
                                <th><?= $t('colReport'); ?></th>
                                <th class="text-center"><?= $t('colAction'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $page = isset($_GET['page']) ? $_GET['page'] : 1;
                            $no = 1 + (10 * ($page - 1));
                            $hasData = false;
                            foreach ($laporan as $tag => $v_abdimas) : ?>
                                <?php if ($v_abdimas->anggota_id == userLogin()->user_id):
                                    $hasData = true;
                                ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td class="p-2">

                                            <!-- Informasi Tambahan -->
                                            <div class="row">

                                                <div class="col-md-4">
                                                    <div class="card p-2">
                                                        <b><?= $t('activityTitle'); ?> </b>

                                                        <?php if ($v_abdimas->judul_kegiatan == !null) : ?>
                                                            <?= esc($v_abdimas->judul_kegiatan); ?>
                                                        <?php else : ?>
                                                            <span class="text-danger"><?= $t('noActivityTitle'); ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="card p-2">
                                                        <b><?= $t('activityFund'); ?> </b>

                                                        <?php if ($v_abdimas->range_dana == !null) : ?>
                                                            <?= 'Rp. ' . number_format($v_abdimas->range_dana, 0, ',', '.'); ?>
                                                        <?php else : ?>
                                                            <span class="text-danger"><?= $t('noActivityFund'); ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="card p-2">
                                                        <b><?= $t('partner'); ?></b>
                                                        <?php $seen = [];
                                                        foreach ($mitra as $mtr => $v_mitra): ?>
                                                            <?php if ($v_mitra->user_id == $v_abdimas->mitra_id && !isset($seen[$v_mitra->user_id])): ?>
                                                                <?= esc($v_mitra->user_name); ?>
                                                                <?php $seen[$v_mitra->user_id] = true; ?>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="card p-2">
                                                        <b><?= $t('period'); ?></b>
                                                        <div>
                                                            <?= !empty($v_abdimas->periode_name) && !empty($v_abdimas->tahun_ajaran)
                                                                ? esc($v_abdimas->periode_name) . ' ' . esc($v_abdimas->tahun_ajaran)
                                                                : '<span class="text-danger">' . $t('notAvailable') . '</span>'; ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="card p-2">
                                                        <b><?= $t('status'); ?></b>
                                                        <?php if ($v_abdimas->verifikasi == 0): ?>
                                                            <span class='badge badge-primary'><?= $t('statusProcess'); ?></span>
                                                        <?php elseif ($v_abdimas->verifikasi == 1): ?>
                                                            <span class='badge badge-success'><?= $t('statusApproved'); ?></span>
                                                        <?php elseif ($v_abdimas->verifikasi == 2): ?>
                                                            <span class='badge badge-warning text-dark'><?= $t('statusRevision'); ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="card p-2">
                                                        <b><?= $t('revisionNote'); ?> </b>
                                                        <?php if ($v_abdimas->revisi == !null) : ?>
                                                            <?= esc($v_abdimas->revisi); ?>
                                                        <?php else : ?>
                                                            <span class="text-danger"><?= $t('noRevisionNote'); ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-2">
                                                <div class="col-md-12">
                                                    <div class="card p-2">
                                                        <b>Mahasiswa Terlibat: </b>
                                                        <?php
                                                        $mhs_list = [];
                                                        if (isset($mahasiswa) && (is_array($mahasiswa) || is_object($mahasiswa))) {
                                                            foreach ($mahasiswa as $mhs) {
                                                                if ($v_abdimas->laporan_id == $mhs->laporan_id) {
                                                                    $mhs_list[] = esc(ucwords(strtolower($mhs->mahasiswa_name))) . ' (' . esc($mhs->mahasiswa_npm) . ')';
                                                                }
                                                            }
                                                        }
                                                        echo !empty($mhs_list) ? implode(', ', $mhs_list) : '<span class="text-danger">Tidak ada mahasiswa</span>';
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card p-2">
                                                <b><?= $t('members'); ?> </b>
                                                <?php
                                                $seen = [];
                                                $counter = 1;
                                                $output = [];

                                                foreach ($tags as $key => $v_tags):
                                                    if ($v_abdimas->laporan_id == $v_tags->laporan_id && !isset($seen[$v_tags->laporan_id])):
                                                        if ($v_tags->anggota_id == $v_abdimas->ketua_id):
                                                            $output[] =
                                                                "<div class='item'>{$counter}. " . esc(ucwords(strtolower($v_tags->user_name))) .
                                                                " (<span class='text-danger'>{$t('leader')}</span>)<br>
                                                        <b>{$t('nidn')}</b> " . esc($v_tags->nidn) . " <br>
                                                        <b>{$t('sinta')}</b> " . esc($v_tags->sinta_id) . "</div>";
                                                        else:
                                                            $output[] =
                                                                "<div class='item'>{$counter}. " . esc(ucwords(strtolower($v_tags->user_name))) .
                                                                "<br> <b>{$t('nidn')}</b> " . esc($v_tags->nidn) . " <br>
                                                        <b>{$t('sinta')}</b> " . esc($v_tags->sinta_id) . "</div>";
                                                        endif;

                                                        $seen[$v_tags->laporan_id] = true;
                                                        $counter++;
                                                    endif;
                                                endforeach;
                                                ?>

                                                <!-- Tampilan dengan Flexbox -->
                                                <style>
                                                    .container {
                                                        display: flex;
                                                        flex-wrap: wrap;
                                                        gap: 20px;
                                                    }

                                                    .item {
                                                        width: calc(25% - 20px);
                                                        background: #f8f9fa;
                                                        padding: 10px;
                                                        border: 1px solid #ddd;
                                                        border-radius: 5px;
                                                    }
                                                </style>

                                                <div class="container">
                                                    <?= implode("\n", $output); ?>
                                                </div>
                                            </div>

                                            <br>

                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex flex-column align-items-start gap-2">
                                                <a href="<?= site_url('pelaksanaan/' . $v_abdimas->laporan_id) . '/edit'; ?>"
                                                    class="btn btn-primary btn-sm" style="width:150px;"><?= $t('btnEditImplementation'); ?></a>

                                                <?php if (!empty($v_abdimas->tanggal_kegiatan) && !empty($v_abdimas->judul_kegiatan)): ?>
                                                    <a href="<?= site_url('pelaksanaan/upload-undangan/' . $v_abdimas->laporan_id); ?>"
                                                        class="btn btn-warning btn-sm" style="width:150px;">
                                                        <i class="fas fa-upload mr-1"></i><?= $t('btnInvitationLetter'); ?>
                                                    </a>
                                                    <a href="<?= site_url('abdimas/surat-balasan-pdf/' . $v_abdimas->laporan_id); ?>"
                                                        class="btn btn-success btn-sm" style="width:150px;" target="_blank">
                                                        <?= $t('btnReplyLetter'); ?>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
<<<<<<< HEAD
                                        </td>
                                    </tr>
                                <?php endif; ?>
=======
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="card p-2">
                                                <b>Catatan Perbaikan: </b>
                                                <?php if($v_abdimas->revisi == !null) :?>
                                                    <?= $v_abdimas->revisi; ?>
                                                <?php else :?>
                                                    <span class="text-danger">Tidak ada catatan revisi</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="card p-2">
                                        <b>Anggota : </b>
                                        <?php 
                                        $seen = []; 
                                        $counter = 1; 
                                        $output = []; // Array untuk menyimpan hasil
                                        
                                        foreach ($tags as $key => $v_tags): 
                                            if ($v_abdimas->laporan_id == $v_tags->laporan_id && !isset($seen[$v_tags->laporan_id])): 
                                                if ($v_tags->anggota_id == $v_abdimas->ketua_id): 
                                                    $output[] = "<div class='item'>{$counter}. " . ucwords(strtolower($v_tags->user_name)) . 
                                                                " (<span class='text-danger'>Ketua</span>)<br> 
                                                                <b>NIDN:</b> {$v_tags->nidn} <br> 
                                                                <b>SINTA ID:</b> {$v_tags->sinta_id}</div>";
                                                else: 
                                                    $output[] = "<div class='item'>{$counter}. " . ucwords(strtolower($v_tags->user_name)) . 
                                                                "<br> <b>NIDN:</b> {$v_tags->nidn} <br> 
                                                                <b>SINTA ID:</b> {$v_tags->sinta_id}</div>";
                                                endif;
                                                
                                                $seen[$v_mitra->user_id] = true; 
                                                $counter++;
                                            endif; 
                                        endforeach;
                                        ?>
                                        
                                        <!-- Tampilan dengan Flexbox -->
                                        <style>
                                            .container {
                                                display: flex;
                                                flex-wrap: wrap;
                                                gap: 20px;
                                            }
                                            .item {
                                                width: calc(25% - 20px); /* Membagi jadi 2 kolom */
                                                background: #f8f9fa;
                                                padding: 10px;
                                                border: 1px solid #ddd;
                                                border-radius: 5px;
                                            }
                                        </style>
                                        
                                        <div class="container">
                                            <?= implode("\n", $output); ?>
                                        </div>
                                    </div>
                                    
                                    <br>
                                    
                                </td>
                                <td class="text-center">
                                    <!-- <a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-eye"></i></a> -->
                                    <!--<a href="<?= site_url('abdimas/' . $v_abdimas->laporan_id); ?>"-->
                                    <!--    class="btn btn-dark btn-sm m-1 show-item" style="width:150px;">Lihat-->
                                    <!--    Proposal</a><br>-->
                                    <a href="<?= site_url('pelaksanaan/' . $v_abdimas->laporan_id) . '/edit'; ?>"
                                        class="btn btn-primary btn-sm" style="width:150px;">Edit Pelaksanaan</a>
                                    <a href="<?= site_url('abdimas/surat-balasan-pdf/' . $v_abdimas->laporan_id); ?>"
                                        class="btn btn-success btn-sm mt-1" style="width:150px;" target="_blank">Surat Balasan</a>
                                    <!--<a href="<?= site_url('abdimas/formSuratBalasan'); ?>" -->
                                    <!--   class="btn btn-success btn-lg">-->
                                    <!--   <i class="fas fa-envelope-open-text"></i> Form Surat Balasan-->
                                    <!--</a>-->

                                </td>
                            </tr>
                            <?php endif; ?>
>>>>>>> 55c0835 (refactor: update code)
                            <?php endforeach; ?>

                            <?php if (!$hasData): ?>
                                <tr>
                                    <td colspan="3" class="text-center">
                                        <em><?= $t('noDataAvailable'); ?></em>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
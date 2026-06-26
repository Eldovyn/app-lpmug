<?= $this->extend('layouts/default') ?>

<?php
helper(['cookie', 'url']);

$request = service('request');

// bahasa aktif
$allowed = ['id', 'en'];
$lang    = get_cookie('lang') ?: 'id';
if (! in_array($lang, $allowed, true)) {
    $lang = 'id';
}

$reqLang = $request->getGet('lang');
if ($reqLang && in_array($reqLang, $allowed, true)) {
    set_cookie('lang', $reqLang, 60 * 60 * 24 * 30);
    $lang = $reqLang;
}

// dictionary
$dict = [
    'id' => [
        'hibah' => 'Hibah',
        'dashboard' => 'Dashboard',
        'congrats' => 'Selamat!',
        'error' => 'Error!',
        'upload_hibah' => 'Upload Hibah',
        'search' => 'Pencarian...',
        'hibah_field' => 'Hibah',
        'action' => 'Action',
        'no_title' => 'Belum ada Judul Hibah',
        'position_ketua' => 'Ketua',
        'position_anggota' => 'Anggota',
        'status_draft' => 'Draft',
        'status_submitted' => 'Menunggu Verifikasi',
        'status_approved' => 'Disetujui',
        'status_rejected' => 'Ditolak',
        'upload_date' => 'Tanggal Upload',
        'view_detail' => 'Lihat Detail',
        'delete_confirm' => 'Apakah Anda yakin ingin menghapus hibah ini?',
        'showing' => 'Menampilkan',
        'to' => 'dari',
        'entries' => 'entri',
    ],
    'en' => [
        'hibah' => 'Grant',
        'dashboard' => 'Dashboard',
        'congrats' => 'Congratulations!',
        'error' => 'Error!',
        'upload_hibah' => 'Upload Grant',
        'search' => 'Search...',
        'hibah_field' => 'Grant',
        'action' => 'Action',
        'no_title' => 'No Grant Title Yet',
        'position_ketua' => 'Chair',
        'position_anggota' => 'Member',
        'status_draft' => 'Draft',
        'status_submitted' => 'Waiting Verification',
        'status_approved' => 'Approved',
        'status_rejected' => 'Rejected',
        'upload_date' => 'Upload Date',
        'view_detail' => 'View Detail',
        'delete_confirm' => 'Are you sure you want to delete this grant?',
        'showing' => 'Showing',
        'to' => 'to',
        'entries' => 'entries',
    ],
];

$t = $dict[$lang] ?? $dict['id'];
?>

<?= $this->section('title') ?>
<title><?= esc($title_tab); ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <h1><?= esc($t['hibah']); ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="<?= site_url('dashboard'); ?>"><?= esc($t['dashboard']); ?></a></div>
            <div class="breadcrumb-item"><?= esc($t['hibah']); ?></div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b><?= esc($t['congrats']); ?></b>
                <?= session()->getFlashdata('success'); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b><?= esc($t['error']); ?></b>
                <?= session()->getFlashdata('error'); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4 class="text-sm">
                    <a href="<?= site_url('hibah/upload'); ?>" class="btn btn-success"><i class="fas fa-plus-circle mr-1"></i><?= esc($t['upload_hibah']); ?></a>
                </h4>
                <div class="card-header-form">
                    <form action="" method="GET" autocomplete="off">
                        <div class="input-group">
                            <input name="keyword" value="<?= $keyword ?? ''; ?>" type="text" class="form-control" placeholder="<?= esc($t['search']); ?>">
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?= esc($t['hibah_field']); ?></th>
                                <th class="text-center"><?= esc($t['action']); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $page = isset($_GET['page']) ? $_GET['page'] : 1;
                            $no = 1 + (10 * ($page - 1));
                            foreach ($hibah_list as $hibah) : ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td class="p-2">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card p-2">
                                                    <b><?= esc($t['hibah_field']); ?>: </b>
                                                    <?php if ($hibah['judul'] != null) : ?>
                                                        <?= esc($hibah['judul']); ?>
                                                    <?php else : ?>
                                                        <span class="text-danger"><?= esc($t['no_title']); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="card p-2">
                                                    <b>Position:</b>
                                                    <span class="badge badge-<?= $hibah['posisi_dosen'] == 'ketua' ? 'primary' : 'secondary'; ?>">
                                                        <?= ucfirst($hibah['posisi_dosen'] == 'ketua' ? $t['position_ketua'] : $t['position_anggota']); ?>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="card p-2">
                                                    <b>Status:</b>
                                                    <?php
                                                    $statusBadge = '';
                                                    $statusText = '';
                                                    switch ($hibah['verification_status']) {
                                                        case 'submitted':
                                                            $statusBadge = 'warning';
                                                            $statusText = $t['status_submitted'];
                                                            break;
                                                        case 'approved':
                                                            $statusBadge = 'success';
                                                            $statusText = $t['status_approved'];
                                                            break;
                                                        case 'rejected':
                                                            $statusBadge = 'danger';
                                                            $statusText = $t['status_rejected'];
                                                            break;
                                                        default:
                                                            $statusBadge = 'secondary';
                                                            $statusText = $t['status_draft'];
                                                            break;
                                                    }
                                                    ?>
                                                    <span class='badge badge-<?= $statusBadge; ?>'><?= $statusText; ?></span>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="card p-2">
                                                    <b><?= esc($t['upload_date']); ?>: </b>
                                                    <?= date('d/m/Y H:i', strtotime($hibah['created_at'])); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= site_url('hibah/detail/' . $hibah['id']); ?>" class="btn btn-dark btn-sm mb-2" style="width:150px;"><?= esc($t['view_detail']); ?></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="float-left">
                        <i><?= esc($t['showing']); ?> <?= 1 + (10 * ($page - 1)); ?> <?= esc($t['to']); ?> <?= $no - 1; ?> <?= esc($t['entries']); ?></i>
                    </div>
                    <div class="float-right">
                        <!-- Pagination if needed -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function deleteHibah(id) {
        if (confirm('<?= $t['delete_confirm']; ?>')) {
            window.location.href = '<?= site_url('hibah/delete/'); ?>' + id;
        }
    }
</script>

<?= $this->endSection() ?>

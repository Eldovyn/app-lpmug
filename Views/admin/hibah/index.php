<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <h1>Hibah Verification</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="<?= site_url('dashboard'); ?>">Dashboard</a></div>
            <div class="breadcrumb-item">Hibah Verification</div>
        </div>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b>Selamat!</b>
                <?= session()->getFlashdata('success'); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b>Error!</b>
                <?= session()->getFlashdata('error'); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Status Summary</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="card card-statistic-1">
                                    <div class="card-icon bg-warning">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4>Pending</h4>
                                        </div>
                                        <div class="card-body">
                                            <?= $pending_count ?? 0; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="card card-statistic-1">
                                    <div class="card-icon bg-success">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4>Approved</h4>
                                        </div>
                                        <div class="card-body">
                                            <?= $approved_count ?? 0; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="card card-statistic-1">
                                    <div class="card-icon bg-danger">
                                        <i class="fas fa-times"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header">
                                            <h4>Rejected</h4>
                                        </div>
                                        <div class="card-body">
                                            <?= $rejected_count ?? 0; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Approved Hibah with Active Flags -->
    <?php if (!empty($approved_hibah)): ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h4 class="text-white">
                        <i class="fas fa-flag"></i> Hibah Disetujui (Flag Aktif)
                    </h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>Judul</th>
                                    <th>Pengusul</th>
                                    <th>Tanggal Disetujui</th>
                                    <th>Status Flag</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($approved_hibah as $item): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td>
                                        <strong><?= esc($item['judul']); ?></strong>
                                        <br>
                                        <small class="text-muted">Posisi: <?= ucfirst(esc($item['posisi_dosen'])); ?></small>
                                    </td>
                                    <td><?= esc($item['user_name']); ?></td>
                                    <td>
                                        <?= !empty($item['verified_at']) ? date('d/m/Y H:i', strtotime($item['verified_at'])) : '-'; ?>
                                    </td>
                                    <td>
                                        <?php if ($item['has_active_flag']): ?>
                                            <span class="badge badge-success">
                                                <i class="fas fa-check-circle"></i> Flag Aktif
                                            </span>
                                            <br>
                                            <small class="text-muted">
                                                <?= !empty($item['flag_created_at']) ? date('d/m/Y', strtotime($item['flag_created_at'])) : ''; ?>
                                            </small>
                                        <?php else: ?>
                                            <span class="badge badge-warning">
                                                <i class="fas fa-clock"></i> Menunggu
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h4 class="text-sm">Hibah List</h4>
                <div class="card-header-form">
                    <form action="" method="GET" autocomplete="off">
                        <div class="input-group">
                            <input name="keyword" value="<?= $keyword ?? ''; ?>" type="text" class="form-control" placeholder="Search...">
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
                                <th>Judul</th>
                                <th>Pengusul</th>
                                <th>Status</th>
                                <th>Tanggal Upload</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($hibah)): ?>
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data</td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1; foreach ($hibah as $item): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td>
                                        <div>
                                            <strong><?= esc($item['judul']); ?></strong>
                                            <br>
                                            <small class="text-muted">Posisi: <?= ucfirst(esc($item['posisi_dosen'])); ?></small>
                                        </div>
                                    </td>
                                    <td><?= esc($item['user_name']); ?></td>
                                    <td>
                                        <?php
                                        $statusClass = '';
                                        $statusText = '';
                                        switch ($item['verification_status']) {
                                            case 'submitted':
                                                $statusClass = 'warning';
                                                $statusText = 'Submitted';
                                                break;
                                            case 'approved':
                                                $statusClass = 'success';
                                                $statusText = 'Approved';
                                                break;
                                            case 'rejected':
                                                $statusClass = 'danger';
                                                $statusText = 'Rejected';
                                                break;
                                            default:
                                                $statusClass = 'secondary';
                                                $statusText = 'Draft';
                                                break;
                                        }
                                        ?>
                                        <span class="badge badge-<?= $statusClass; ?>"><?= $statusText; ?></span>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($item['created_at'])); ?></td>
                                    <td>
                                        <a href="<?= site_url('hibah/verification-detail/' . $item['id']); ?>" class="btn btn-info btn-sm mb-1">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                        
                                        <?php if ($item['verification_status'] == 'submitted'): ?>
                                        <div class="btn-group mb-1">
                                            <form action="<?= site_url('hibah/approve/' . $item['id']); ?>" method="POST" class="d-inline">
                                                <?= csrf_field(); ?>
                                                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Approve hibah ini?')">
                                                    <i class="fas fa-check"></i> Approve
                                                </button>
                                            </form>
                                            <form action="<?= site_url('hibah/reject/' . $item['id']); ?>" method="POST" class="d-inline ml-1">
                                                <?= csrf_field(); ?>
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Reject hibah ini?')">
                                                    <i class="fas fa-times"></i> Reject
                                                </button>
                                            </form>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <div class="mt-1">
                                            <form action="<?= site_url('hibah/delete-admin/' . $item['id']); ?>" method="POST" class="d-inline" onsubmit="return confirmDelete(event, '<?= esc($item['judul']); ?>')">
                                                <?= csrf_field(); ?>
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function confirmDelete(event, judul) {
        event.preventDefault();
        
        if (confirm('Apakah Anda yakin ingin menghapus hibah "' + judul + '"?\n\nData yang dihapus tidak dapat dikembalikan!')) {
            if (confirm('Konfirmasi sekali lagi. Hapus hibah ini secara permanen?')) {
                event.target.submit();
            }
        }
        
        return false;
    }
</script>

<?= $this->endSection() ?>

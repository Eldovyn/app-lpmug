<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <h1><?= $title; ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="<?= site_url('dashboard'); ?>">Dashboard</a></div>
            <div class="breadcrumb-item"><?= $title; ?></div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b>Selamat!</b>
                <?= session()->getFlashdata('success'); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b>Warning Error!</b>
                <?= session()->getFlashdata('error'); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4 class="text-sm">
                    <!-- <a href="<?= site_url('abdimas/new'); ?>" class="btn btn-success"><i class="fas fa-plus-circle mr-1"></i>Registrasi abdimas</a> -->
                </h4>
                <div class="card-header-form">
                    <form action="" method="GET" autocomplete="off">
                        <div class="input-group">
                            <input name="keyword" value="<?= $keyword; ?>" type="text" class="form-control" placeholder="Pencarian...">
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
                                <th>Anggota</th>
                                <th>Mitra</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $page = isset($_GET['page']) ? $_GET['page'] : 1;
                            $no = 1 + (10 * ($page - 1));
                            foreach ($laporan as $tag => $v_undangan) : ?>
                                <?php if ($v_undangan->anggota_id == userLogin()->user_id): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td>
                                            <?php foreach ($tags as $key => $v_tags): ?>
                                                <?php if ($v_undangan->laporan_id == $v_tags->laporan_id): ?>
                                                    <?php if ($v_tags->anggota_id == $v_undangan->ketua_id): ?>
                                                        <?= $v_tags->user_name; ?> (<span class="text-danger">Ketua</span>) <br>
                                                    <?php else: ?>
                                                        <?= $v_tags->user_name; ?> <br>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            <?php endforeach ?>
                                        </td>
                                        <td style="max-width:150px;">
                                            <?php foreach ($mitra as $mtr => $v_mitra): ?>
                                                <?php if ($v_mitra->user_id == $v_undangan->mitra_id): ?>
                                                    <?= $v_mitra->user_name; ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($v_undangan->verifikasi == 0): ?>
                                                <span class="badge badge-warning text-dark">REVISI</span>
                                            <?php elseif ($v_undangan->verifikasi == 1): ?>
                                                <span class="badge badge-success px-lg-4">DISETUJUI</span>
                                            <?php elseif ($v_undangan->verifikasi == 2): ?>
                                                <span class="badge badge-primary px-lg-4">PROSES</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">

                                            <a href="<?= site_url('undangan/' . $v_undangan->laporan_id . '/edit'); ?>" class="btn btn-dark btn-sm m-1 show-item" style="width:150px;">Terima / Tolak</a><br>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="float-left">
                        <i>Showing <?= 1 + (10 * ($page - 1)); ?> to <?= $no - 1; ?> of <?= $pager->getTotal(); ?> entries</i>
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
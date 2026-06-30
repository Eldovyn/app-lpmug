<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
<div class="section-header">
    <a href="<?= site_url('provinsi'); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
    <h1><?= $title; ?></h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="<?= site_url('dashboard'); ?>">Dashboard</a></div>
        <div class="breadcrumb-item"><?= $title; ?></div>
    </div>
</div>

<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible show fade">
        <div class="alert-body">
            <button class="close" data-dismiss="alert">x</button>
            <b>Congratulation!</b>
            <?= session()->getFlashdata('success'); ?>
        </div>
    </div>
<?php endif; ?>

<?php if(session()->getFlashdata('error')): ?>
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
                <a href="<?= site_url('provinsi/restore'); ?>" class="btn btn-info"><i class="fas fa-undo-alt mr-1"></i>Rastore all</a>
                <form action="<?= site_url('provinsi/delete2'); ?>" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data anda?');">
                    <?= csrf_field(); ?>
                    <input type="hidden" name="_method" value="DELETE">
                    <button class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i> Delete all
                    </button>
                </form>
            </h4>
            <div class="card-header-form">
                <form>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search">
                        <div class="input-group-btn">
                            <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <th>#</th>
                            <th>Nama Provinsi</th>
                            <th class="text-center">Action</th>
                        </tr>
                        <?php foreach($provinsi as $pro => $v_provinsi): ?>
                        <tr>
                            <td><?= $pro + 1; ?></td>
                            <td><?= $v_provinsi->provinsi_name; ?></td>
                            <td class="text-center">
                                <!-- <a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-eye"></i></a> -->
                                <a href="<?= site_url('provinsi/restore/'. $v_provinsi->provinsi_id); ?>" class="btn btn-primary btn-sm"><i class="fas fa-undo-alt"></i> Restore</a>
                                <form action="<?= site_url('provinsi/delete2/'. $v_provinsi->provinsi_id); ?>" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data anda?');">
                                    <?= csrf_field(); ?>
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i> Delete Permanent
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</section>
<?= $this->endSection() ?>
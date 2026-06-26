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
                <a href="<?= site_url('provinsi/new'); ?>" class="btn btn-primary"><i class="fas fa-plus-circle mr-1"></i>Tambah data</a>
                <a href="<?= site_url('provinsi/trash'); ?>" class="btn btn-danger"><i class="fas fa-trash mr-1"></i>Data yang dihapus</a>
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
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Provinsi</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>    
                    <tbody>
                    <?php 
                        $page = isset($_GET['page']) ? $_GET['page'] : 1;
                        $no = 1 + (10 * ($page - 1));
                        foreach($provinsi as $pro => $v_provinsi): 
                    ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $v_provinsi->provinsi_name; ?></td>
                            <td class="text-center">
                                <!-- <a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-eye"></i></a> -->
                                <a href="<?= site_url('provinsi/edit/'. $v_provinsi->provinsi_id); ?>" class="btn btn-warning btn-sm"><i class="fas fa-pencil-alt"></i></a>
                                <form action="<?= site_url('provinsi/delete/'. $v_provinsi->provinsi_id); ?>" method="POST" class="d-inline" id="del-<?= $v_provinsi->provinsi_id; ?>">
                                    <?= csrf_field(); ?>
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button class="btn btn-danger btn-sm" data-confirm="Hapus data? | Apakah anda yakin?" data-confirm-yes="submitDel(<?= $v_provinsi->provinsi_id; ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="float-left">
                    <i>Showing <?= 1 + (10 * ($page -1)); ?> to <?= $no-1; ?> of <?= countData('tbl_provinsi'); ?> entries</i>
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
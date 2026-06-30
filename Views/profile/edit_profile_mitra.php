<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
<div class="section-header">
    <a href="<?= site_url('dashboard'); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
    <h1><?= $title; ?></h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>">Dashboard</a></div>
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
    <div class="row">
        <div class="col-12 col-md-6 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="<?= site_url('profile_user_mitra/'.$profile_user_mitra->user_id); ?>" method="POST" autocomplete="off">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="_method" value="PUT">
                        <div class="form-group">
                            <label>Nama Mitra<small class="text-danger">*</small></label>
                            <input type="text" name="user_name" id="user_name" value="<?= $profile_user_mitra->user_name; ?>" class="form-control <?= (session('validation')) ? 'is-invalid' : ''; ?>" autofocus>
                            <div class="invalid-feedback">
                                <?php if(session('validation') && session('validation')->hasError('user_name')):?>
                                    <?= session('validation')->getError('user_name'); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Username<small class="text-danger">*</small> (<small class="text-primary">Contoh: mitratest / mitra_test</small>)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </div>
                                <input type="text" name="nidn" value="<?= $profile_user_mitra->nidn; ?>" class="form-control <?= (session('validation')) ? 'is-invalid' : ''; ?>" autofocus>
                                <div class="invalid-feedback">
                                    <?php if(session('validation') && session('validation')->hasError('nidn')):?>
                                        <?= session('validation')->getError('nidn'); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Email<small class="text-danger">*</small> (<small class="text-primary">Contoh: email@email.ac.id</small>)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                </div>
                                <input type="email" name="email" value="<?= $profile_user_mitra->email; ?>"  class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>No.Telp/Whatsapp<small class="text-danger">*</small> (<small class="text-primary">Contoh: 628122300932</small>)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                </div>
                                <input type="number" name="kontak" value="<?= $profile_user_mitra->kontak; ?>" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Provinsi - Kota<small class="text-danger">*</small></label>
                            <select name="kota_id" class="form-control select2" >
                                <option selected disabled>&mdash;Silahkan Pilih&mdash;</option>
                                <?php foreach ($kota as $jrs => $v_kota): ?>
                                    <option value="<?= $v_kota->kota_id; ?>" <?= $profile_user_mitra->kota_id == $v_kota->kota_id ? 'selected' : null; ?>>
                                        <?= $v_kota->provinsi_name; ?> - <?= $v_kota->kota_name; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Alamat lengkap</label>
                            <textarea name="alamat" class="form-control" style="height: 150px"><?= $profile_user_mitra->alamat; ?></textarea>
                        </div>
                        <div class="text-right">
                            <a href="<?= site_url('dashboard'); ?>" class="btn btn-dark">kembali</a>
                            <a href="<?= site_url('ubah_password_mitra/update/' . userLogin()->user_id); ?>" class="btn btn-warning text-dark">Ubah password</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--<div class="col-12 col-md-6 col-lg-5">-->
        <!--    <div class="card">-->
        <!--        <div class="card-header">-->
        <!--            <h4>Keterangan</h4>-->
        <!--        </div>-->
        <!--        <div class="card-body">-->
        <!--            <div class="section-title mt-0">Nama Lengkpa</div>-->
        <!--            <div>-->

        <!--            </div>-->
        <!--            <div class="section-title">Select 2</div>-->
                    
        <!--            <div class="section-title">jQuery Selectric</div>-->
                    
        <!--            <div class="section-title">Select Group Button</div>-->
                
        <!--        </div>-->
        <!--    </div>-->
        <!--</div>-->
    </div>
</div>
</section>
<?= $this->endSection() ?>
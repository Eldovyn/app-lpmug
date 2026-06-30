<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
<div class="section-header">
    <a href="<?= site_url('undangan'); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
    <h1><?= $title; ?></h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>">Dashboard</a></div>
        <div class="breadcrumb-item active"><a href="<?= site_url('undangan'); ?>">Data undangan</a></div>
        <div class="breadcrumb-item"><?= $title; ?></div>
    </div>
</div>

<div class="section-body">
    <div class="row">
        <div class="col-12 col-md-6 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="<?= site_url('undangan/'.$undangan->laporan_id); ?>" method="POST" autocomplete="off" enctype="multipart/form-data">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="_method" value="PATCH">
                        <div class="form-group">
                            <label>Pilih Periode<span class="text-danger">*</span></label>
                            <select name="periode_id" class="form-control select2" disabled>
                                <option selected disabled>&mdash;PILIH PERIODE&mdash;</option>
                                <?php foreach ($periode as $mtr => $v_periode): ?>
                                    <?php if($v_periode->info == 1): ?>
                                        <option value="<?= $v_periode->periode_id; ?>" <?= $undangan->periode_id == $v_periode->periode_id ? 'selected' : null; ?>>
                                            <?= $v_periode->periode_name; ?> <?= $v_periode->tahun_ajaran; ?>
                                        </option>
                                    <?php else :?>
                                        <option disabled value="<?= $v_periode->periode_id; ?>"><?= $v_periode->periode_name; ?> <?= $v_periode->tahun_ajaran; ?> -||- Pendaftaran ditutup</option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>NIDN<span class="text-danger">*</span></label>
                                <input type="text" name="nidn" id="nidn" value="<?= userLogin()->nidn; ?>" class="form-control" disabled autofocus>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Nama Ketua<span class="text-danger">*</span></label>
                                <input type="text" name="ketua_id" id="ketua_id" class="form-control" placeholder="<?= userLogin()->user_name; ?>" disabled autofocus>
                            </div>
                            <div class="form-group col-md-4">
                                <label>SINTA ID<span class="text-danger">*</span></label>
                                <input type="text" name="sinta_id" id="sinta_id" value="<?= userLogin()->sinta_id; ?>" class="form-control" disabled autofocus>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Pilih Mitra<span class="text-danger">*</span> <span class="text-primary"><b>Note:</b> Jika mitra belum terdaftar silahkan hubungi Staff LPM UG atau Silahkan <a href="<?= site_url('mitra/new'); ?>" class="badge badge-primary">"Daftarkan Mitra"</a></span></label>
                            <select name="mitra_id" class="form-control select2" disabled>
                                <option selected disabled>&mdash;PILIH MITRA UMKM&mdash;</option>
                                <?php foreach ($mitra as $mtr => $v_mitra): ?>
                                    <?php if($v_mitra->role_id == 5): ?>
                                        <option value="<?= $v_mitra->user_id; ?>" <?= $undangan->mitra_id == $v_mitra->user_id ? 'selected' : null; ?>>
                                            <?= $v_mitra->user_name; ?> - <?= $v_mitra->kota_name; ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Pilih Topik - Program - Sub Program<span class="text-danger">*</span></label>
                            <select name="subprogram_id" class="form-control select2" disabled>
                                <option selected disabled>&mdash;PILIH TOPIK - PROGRAM - SUB PROGRAM&mdash;</option>
                                <?php foreach ($subprogram as $mtr => $v_subprogram): ?>
                                    <option value="<?= $v_subprogram->subprogram_id; ?>" <?= $undangan->subprogram_id == $v_subprogram->subprogram_id ? 'selected' : null; ?>>
                                        <?= $v_subprogram->topik_name; ?> -||- <?= $v_subprogram->program_name; ?> -||- <?= $v_subprogram->subprogram_name; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Pilih Beberapa Luaran<span class="text-danger">*</span> <span class="text-primary"><b>Note:</b> Dapat memilih lebih dari 1 luaran</span></label>
                            <select name="luaran_id[]" class="form-control select2" id="luaran_id" multiple disabled>
                                <?php foreach ($tagluaran as $ls => $v_tagluaran): ?>
                                    <?php if($undangan->laporan_id == $v_tagluaran->laporan_id): ?>
                                        <option value="<?= $v_tagluaran->luaran_id; ?>" selected>
                                            <?= $v_tagluaran->luaran_name; ?>
                                        </option>
                                        <?= $v_tagluaran->luaran_id; ?> 
                                    <?php endif; ?>
                                <?php endforeach; ?>  
                                <?php foreach ($luaran as $ls => $v_luaran): ?>
                                    <option value="<?= $v_luaran->luaran_id; ?>">
                                        <?= $v_luaran->luaran_name; ?>
                                    </option> 
                                <?php endforeach; ?>  
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Pilih Tipe Kegiatan<span class="text-danger">*</span></label>
                            <select name="tipe_kegiatan" class="form-control select2" disabled>
                                <option selected disabled>&mdash;PILIH TIPE KEGIATAN&mdash;</option>
                                <?php if($undangan->tipe_kegiatan == 'Perorangan'): ?>
                                    <option value="<?= $undangan->tipe_kegiatan; ?>" selected>Perorangan</option>
                                    <option value="Kelompok">Kelompok</option>
                                <?php elseif($undangan->tipe_kegiatan == 'Kelompok'): ?>
                                    <option value="<?= $undangan->tipe_kegiatan; ?>" selected>Kelompok</option>
                                    <option value="Perorangan">Perorangan</option>
                                <?php endif;?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Estimasi Pendanaan<span class="text-danger">*</span> <span class="text-primary">(Untuk Pendanaan Per Tahun)</span></label>
                            <select name="range_dana" class="form-control select2" disabled>
                                <option selected disabled>&mdash;PILIH RANGE PENDANAAN&mdash;</option>
                                <?php if($undangan->range_dana == '1 JT - 5 JT'): ?>
                                    <option value="< 1 JT">< 1 JT</option>
                                    <option value="<?= $undangan->range_dana; ?>" selected>1 JT - 5 JT</option>
                                    <option value="6 JT - 15 JT">6 JT - 15 JT</option>
                                    <option value="16 JT - 25 JT">16 JT - 25 JT</option>
                                    <option value="26 JT - 35 JT">26 JT - 35 JT</option>
                                    <option value="36 JT - 50 JT">36 JT - 50 JT</option>
                                    <option value="> 50 JT">> 50 JT</option>
                                <?php elseif($undangan->range_dana == '6 JT - 15 JT'): ?>
                                    <option value="< 1 JT">< 1 JT</option>
                                    <option value="<?= $undangan->range_dana; ?>" selected>6 JT - 15 JT</option>
                                    <option value="1 JT - 5 JT">1 JT - 5 JT</option>
                                    <option value="16 JT - 25 JT">16 JT - 25 JT</option>
                                    <option value="26 JT - 35 JT">26 JT - 35 JT</option>
                                    <option value="36 JT - 50 JT">36 JT - 50 JT</option>
                                    <option value="> 50 JT">> 50 JT</option>
                                <?php elseif($undangan->range_dana == '16 JT - 25 JT'): ?>
                                    <option value="< 1 JT">< 1 JT</option>
                                    <option value="<?= $undangan->range_dana; ?>" selected>16 JT - 25 JT</option>
                                    <option value="1 JT - 5 JT">1 JT - 5 JT</option>
                                    <option value="6 JT - 15 JT">6 JT - 15 JT</option>
                                    <option value="26 JT - 35 JT">26 JT - 35 JT</option>
                                    <option value="36 JT - 50 JT">36 JT - 50 JT</option>
                                    <option value="> 50 JT">> 50 JT</option>
                                <?php elseif($undangan->range_dana == '26 JT - 35 JT'): ?>
                                    <option value="< 1 JT">< 1 JT</option>
                                    <option value="<?= $undangan->range_dana; ?>" selected>26 JT - 35 JT</option>
                                    <option value="1 JT - 5 JT">1 JT - 5 JT</option>
                                    <option value="6 JT - 15 JT">6 JT - 15 JT</option>
                                    <option value="16 JT - 25 JT">16 JT - 25 JT</option>
                                    <option value="36 JT - 50 JT">36 JT - 50 JT</option>
                                    <option value="> 50 JT">> 50 JT</option>
                                <?php elseif($undangan->range_dana == '36 JT - 50 JT'): ?>
                                    <option value="< 1 JT">< 1 JT</option>
                                    <option value="<?= $undangan->range_dana; ?>" selected>36 JT - 50 JT</option>
                                    <option value="1 JT - 5 JT">1 JT - 5 JT</option>
                                    <option value="6 JT - 15 JT">6 JT - 15 JT</option>
                                    <option value="16 JT - 25 JT">16 JT - 25 JT</option>
                                    <option value="26 JT - 35 JT">26 JT - 35 JT</option>
                                    <option value="> 50 JT">> 50 JT</option>
                                <?php elseif($undangan->range_dana == '< 1 JT'): ?>
                                    <option value="<?= $undangan->range_dana; ?>" selected>< 1 JT</option>
                                    <option value="36 JT - 50 JT">36 JT - 50 JT</option>
                                    <option value="1 JT - 5 JT">1 JT - 5 JT</option>
                                    <option value="6 JT - 15 JT">6 JT - 15 JT</option>
                                    <option value="16 JT - 25 JT">16 JT - 25 JT</option>
                                    <option value="26 JT - 35 JT">26 JT - 35 JT</option>
                                    <option value="> 50 JT">> 50 JT</option>
                                <?php elseif($undangan->range_dana == '> 50 JT'): ?>
                                    <option value="<?= $undangan->range_dana; ?>" selected>> 50 JT</option>
                                    <option value="< 1 JT">< 1 JT</option>
                                    <option value="36 JT - 50 JT">36 JT - 50 JT</option>
                                    <option value="1 JT - 5 JT">1 JT - 5 JT</option>
                                    <option value="6 JT - 15 JT">6 JT - 15 JT</option>
                                    <option value="16 JT - 25 JT">16 JT - 25 JT</option>
                                    <option value="26 JT - 35 JT">26 JT - 35 JT</option>
                                <?php endif;?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Masukan Ketua dan Anggota<span class="text-danger">*</span> <span class="text-primary"><b>Note:</b> Nama Ketua <b>WAJIB</b> dimasukan kedalam form anggota <b>(Berlaku untuk tipe kegiatan Perorangan / Kelompok)</b></span></label>
                            <select name="anggota_id[]" class="form-control select2" id="anggota_id" multiple disabled>
                                <?php foreach ($tags as $ds => $v_tags): ?>
                                    <?php if($undangan->laporan_id == $v_tags->laporan_id): ?>
                                        <option value="<?= $v_tags->user_id; ?>" selected>
                                            <?= $v_tags->user_name; ?>
                                        </option> 
                                        <?= $v_tags->anggota_id; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>   
                                <?php foreach ($dosen as $ds => $v_dosen): ?>
                                    <?php if($v_dosen->role_id == 4): ?>
                                        <option value="<?= $v_dosen->user_id; ?>">
                                            <?= $v_dosen->user_name; ?>
                                        </option> 
                                    <?php endif; ?>
                                <?php endforeach; ?>   
                            </select>
                        </div>
                        <div class="float-left">
                            <?php if($undangan->laporan != NULL): ?>
                                <a href="<?= site_url('berkas/laporan/'.$undangan->laporan); ?>" class="btn btn-info text-dark" target="_blank">Lihat Laporan</a>
                            <?php else: ?>
                                <span class="btn btn-dark">Silahkan upload laporan</span>
                            <?php endif;?>
                            <?php if($undangan->bukti_kegiatan != NULL): ?>
                                <a href="<?= site_url('berkas/kegiatan/'.$undangan->bukti_kegiatan); ?>" class="btn btn-warning text-dark" target="_blank">Lihat Bukti Kegiatan</a>
                            <?php else: ?>
                                <span class="btn btn-dark">Silahkan upload bukti kegiatan</span>
                            <?php endif;?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
<?= $this->endSection() ?>
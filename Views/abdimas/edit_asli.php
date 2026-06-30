<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <a href="<?= site_url('abdimas'); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
        <h1><?= $title; ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>">Dashboard</a></div>
            <div class="breadcrumb-item active"><a href="<?= site_url('abdimas'); ?>">Data abdimas</a></div>
            <div class="breadcrumb-item"><?= $title; ?></div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= site_url('abdimas/' . $abdimas->laporan_id); ?>" method="POST" autocomplete="off" enctype="multipart/form-data">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="_method" value="PATCH">
                            <div class="form-group">
                                <label>Pilih Periode<span class="text-danger">*</span></label>
                                <select name="periode_id" class="form-control select2">
                                    <option selected disabled>&mdash;PILIH PERIODE&mdash;</option>
                                    <?php foreach ($periode as $mtr => $v_periode): ?>
                                        <?php if ($v_periode->info == 1): ?>
                                            <option value="<?= $v_periode->periode_id; ?>" <?= $abdimas->periode_id == $v_periode->periode_id ? 'selected' : null; ?>>
                                                <?= $v_periode->periode_name; ?> <?= $v_periode->tahun_ajaran; ?>
                                            </option>
                                        <?php else : ?>
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
                                <select name="mitra_id" class="form-control select2">
                                    <option selected disabled>&mdash;PILIH MITRA UMKM&mdash;</option>
                                    <?php foreach ($mitra as $mtr => $v_mitra): ?>
                                        <?php if ($v_mitra->role_id == 5): ?>
                                            <option value="<?= $v_mitra->user_id; ?>" <?= $abdimas->mitra_id == $v_mitra->user_id ? 'selected' : null; ?>>
                                                <?= $v_mitra->user_name; ?> - <?= $v_mitra->kota_name; ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Pilih Topik - Program - Sub Program<span class="text-danger">*</span></label>
                                <select name="subprogram_id" class="form-control select2">
                                    <option selected disabled>&mdash;PILIH TOPIK - PROGRAM - SUB PROGRAM&mdash;</option>
                                    <?php foreach ($subprogram as $mtr => $v_subprogram): ?>
                                        <option value="<?= $v_subprogram->subprogram_id; ?>" <?= $abdimas->subprogram_id == $v_subprogram->subprogram_id ? 'selected' : null; ?>>
                                            <?= $v_subprogram->topik_name; ?> -||- <?= $v_subprogram->program_name; ?> -||- <?= $v_subprogram->subprogram_name; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- NEW: Bidang Ilmu Dropdown -->
                            <div class="form-group">
                                <label>Bidang Ilmu <span class="text-danger">*</span></label>
                                <select name="bidang_ilmu" class="form-control select2" required>
                                    <option value="">&mdash;PILIH BIDANG ILMU&mdash;</option>
                                    <?php $current_bidang = old('bidang_ilmu', $abdimas->bidang_ilmu ?? ''); ?>
                                    <option value="ipa-matematika" <?= $current_bidang == 'ipa-matematika' ? 'selected' : '' ?>>1. Ilmu Pengetahuan Alam (IPA) & Matematika</option>
                                    <option value="teknik-rekayasa" <?= $current_bidang == 'teknik-rekayasa' ? 'selected' : '' ?> >2. Ilmu Teknik & Rekayasa</option>
                                    <option value="kesehatan-kedokteran" <?= $current_bidang == 'kesehatan-kedokteran' ? 'selected' : '' ?> >3. Ilmu Kesehatan & Kedokteran</option>
                                    <option value="sosial-humaniora-seni" <?= $current_bidang == 'sosial-humaniora-seni' ? 'selected' : '' ?> >4. Ilmu Sosial, Humaniora, & Seni</option>
                                    <option value="pertanian-tanaman" <?= $current_bidang == 'pertanian-tanaman' ? 'selected' : '' ?> >5. Ilmu Pertanian & Tanaman</option>
                                </select>
                            </div>
                            <!-- END NEW -->
                            <div class="form-group">
                                <label>Pilih Beberapa Luaran<span class="text-danger">*</span> <span class="text-primary"><b>Note:</b> Dapat memilih lebih dari 1 luaran</span></label>
                                <select name="luaran_id[]" class="form-control select2" id="luaran_id" multiple>
                                    <?php foreach ($tagluaran as $ls => $v_tagluaran): ?>
                                        <?php if ($abdimas->laporan_id == $v_tagluaran->laporan_id): ?>
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
                                <select name="tipe_kegiatan" class="form-control select2">
                                    <option selected disabled>&mdash;PILIH TIPE KEGIATAN&mdash;</option>
                                    <?php if ($abdimas->tipe_kegiatan == 'Perorangan'): ?>
                                        <option value="<?= $abdimas->tipe_kegiatan; ?>" selected>Perorangan</option>
                                        <option value="Kelompok">Kelompok</option>
                                    <?php elseif ($abdimas->tipe_kegiatan == 'Kelompok'): ?>
                                        <option value="<?= $abdimas->tipe_kegiatan; ?>" selected>Kelompok</option>
                                        <option value="Perorangan">Perorangan</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Estimasi Pendanaan<span class="text-danger">*</span> <span class="text-primary">(Untuk Pendanaan Per Tahun)</span></label>
                                <select name="range_dana" class="form-control select2">
                                    <option selected disabled>&mdash;PILIH RANGE PENDANAAN&mdash;</option>
                                    <?php if ($abdimas->range_dana == '1 JT - 5 JT'): ?>
                                        <option value="< 1 JT">
                                            < 1 JT</option>
                                        <option value="<?= $abdimas->range_dana; ?>" selected>1 JT - 5 JT</option>
                                        <option value="6 JT - 15 JT">6 JT - 15 JT</option>
                                        <option value="16 JT - 25 JT">16 JT - 25 JT</option>
                                        <option value="26 JT - 35 JT">26 JT - 35 JT</option>
                                        <option value="36 JT - 50 JT">36 JT - 50 JT</option>
                                        <option value="> 50 JT">> 50 JT</option>
                                    <?php elseif ($abdimas->range_dana == '6 JT - 15 JT'): ?>
                                        <option value="< 1 JT">
                                            < 1 JT</option>
                                        <option value="<?= $abdimas->range_dana; ?>" selected>6 JT - 15 JT</option>
                                        <option value="1 JT - 5 JT">1 JT - 5 JT</option>
                                        <option value="16 JT - 25 JT">16 JT - 25 JT</option>
                                        <option value="26 JT - 35 JT">26 JT - 35 JT</option>
                                        <option value="36 JT - 50 JT">36 JT - 50 JT</option>
                                        <option value="> 50 JT">> 50 JT</option>
                                    <?php elseif ($abdimas->range_dana == '16 JT - 25 JT'): ?>
                                        <option value="< 1 JT">
                                            < 1 JT</option>
                                        <option value="<?= $abdimas->range_dana; ?>" selected>16 JT - 25 JT</option>
                                        <option value="1 JT - 5 JT">1 JT - 5 JT</option>
                                        <option value="6 JT - 15 JT">6 JT - 15 JT</option>
                                        <option value="26 JT - 35 JT">26 JT - 35 JT</option>
                                        <option value="36 JT - 50 JT">36 JT - 50 JT</option>
                                        <option value="> 50 JT">> 50 JT</option>
                                    <?php elseif ($abdimas->range_dana == '26 JT - 35 JT'): ?>
                                        <option value="< 1 JT">
                                            < 1 JT</option>
                                        <option value="<?= $abdimas->range_dana; ?>" selected>26 JT - 35 JT</option>
                                        <option value="1 JT - 5 JT">1 JT - 5 JT</option>
                                        <option value="6 JT - 15 JT">6 JT - 15 JT</option>
                                        <option value="16 JT - 25 JT">16 JT - 25 JT</option>
                                        <option value="36 JT - 50 JT">36 JT - 50 JT</option>
                                        <option value="> 50 JT">> 50 JT</option>
                                    <?php elseif ($abdimas->range_dana == '36 JT - 50 JT'): ?>
                                        <option value="< 1 JT">
                                            < 1 JT</option>
                                        <option value="<?= $abdimas->range_dana; ?>" selected>36 JT - 50 JT</option>
                                        <option value="1 JT - 5 JT">1 JT - 5 JT</option>
                                        <option value="6 JT - 15 JT">6 JT - 15 JT</option>
                                        <option value="16 JT - 25 JT">16 JT - 25 JT</option>
                                        <option value="26 JT - 35 JT">26 JT - 35 JT</option>
                                        <option value="> 50 JT">> 50 JT</option>
                                    <?php elseif ($abdimas->range_dana == '< 1 JT'): ?>
                                        <option value="<?= $abdimas->range_dana; ?>" selected>
                                            < 1 JT</option>
                                        <option value="36 JT - 50 JT">36 JT - 50 JT</option>
                                        <option value="1 JT - 5 JT">1 JT - 5 JT</option>
                                        <option value="6 JT - 15 JT">6 JT - 15 JT</option>
                                        <option value="16 JT - 25 JT">16 JT - 25 JT</option>
                                        <option value="26 JT - 35 JT">26 JT - 35 JT</option>
                                        <option value="> 50 JT">> 50 JT</option>
                                    <?php elseif ($abdimas->range_dana == '> 50 JT'): ?>
                                        <option value="<?= $abdimas->range_dana; ?>" selected>> 50 JT</option>
                                        <option value="< 1 JT">
                                            < 1 JT</option>
                                        <option value="36 JT - 50 JT">36 JT - 50 JT</option>
                                        <option value="1 JT - 5 JT">1 JT - 5 JT</option>
                                        <option value="6 JT - 15 JT">6 JT - 15 JT</option>
                                        <option value="16 JT - 25 JT">16 JT - 25 JT</option>
                                        <option value="26 JT - 35 JT">26 JT - 35 JT</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Masukan Ketua dan Anggota<span class="text-danger">*</span> <span class="text-primary"><b>Note:</b> Nama Ketua <b>WAJIB</b> dimasukan kedalam form anggota <b>(Berlaku untuk tipe kegiatan Perorangan / Kelompok)</b></span></label>
                                <select name="anggota_id[]" class="form-control select2" id="anggota_id" multiple>
                                    <?php foreach ($tags as $ds => $v_tags): ?>
                                        <?php if ($abdimas->laporan_id == $v_tags->laporan_id): ?>
                                            <option value="<?= $v_tags->user_id; ?>" selected>
                                                <?= $v_tags->user_name; ?>
                                            </option>
                                            <?= $v_tags->anggota_id; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                    <?php foreach ($dosen as $ds => $v_dosen): ?>
                                        <?php if ($v_dosen->role_id == 4): ?>
                                            <option value="<?= $v_dosen->user_id; ?>">
                                                <?= $v_dosen->user_name; ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Upload Laporan</label>
                                <input type="file" name="laporan" id="laporan" class="form-control <?= (session('validation')) ? 'is-invalid' : ''; ?>" autofocus>
                                <div class="invalid-feedback">
                                    <?php if (session('validation') && session('validation')->hasError('laporan')): ?>
                                        <?= session('validation')->getError('laporan'); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Upload Bukti Kegiatan</label>
                                <input type="file" name="bukti_kegiatan" id="bukti_kegiatan" class="form-control <?= (session('validation')) ? 'is-invalid' : ''; ?>" autofocus>
                                <div class="invalid-feedback">
                                    <?php if (session('validation') && session('validation')->hasError('bukti_kegiatan')): ?>
                                        <?= session('validation')->getError('bukti_kegiatan'); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Link Luaran <span class="text-primary"><b>Contoh:</b> http://link.com atau https://link.com</span></label>
                                <input type="text" name="link_luaran" value="<?= $abdimas->link_luaran; ?>" id="link_luaran" class="form-control" placeholder="Masukan Link Luaran" autofocus>
                            </div>
                            <div class="float-left">
                                <?php if ($abdimas->laporan != NULL): ?>
                                    <a href="<?= site_url('berkas/laporan/' . $abdimas->laporan); ?>" class="btn btn-info text-dark" target="_blank">Lihat Laporan</a>
                                <?php else: ?>
                                    <span class="btn btn-dark">Silahkan upload laporan</span>
                                <?php endif; ?>
                                <?php if ($abdimas->bukti_kegiatan != NULL): ?>
                                    <a href="<?= site_url('berkas/kegiatan/' . $abdimas->bukti_kegiatan); ?>" class="btn btn-warning text-dark" target="_blank">Lihat Bukti Kegiatan</a>
                                <?php else: ?>
                                    <span class="btn btn-dark">Silahkan upload bukti kegiatan</span>
                                <?php endif; ?>
                            </div>
                            <div class="float-right">
                                <a href="<?= site_url('abdimas'); ?>" class="btn btn-dark">kembali</a>
                                <!-- <button type="reset" class="btn btn-danger">Reset</button> -->
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
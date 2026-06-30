<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>

<style>
    .table-bordered td,
    .table-bordered th {
        border: 1px solid #dee2e6;
    }

    .table td,
    .table th {
        vertical-align: middle;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <a href="<?= site_url('monev'); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
        <h1><?= $title; ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>">Dashboard</a></div>
            <div class="breadcrumb-item active"><a href="<?= site_url('monev'); ?>">Monitoring dan Evaluasi</a></div>
            <div class="breadcrumb-item"><?= $title; ?></div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= site_url('monev/' . $abdimas->laporan_id); ?>" method="POST"
                            autocomplete="off" enctype="multipart/form-data">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="_method" value="PATCH">
                            <div class="text-center mb-4">
                                <h5>MONITORING DAN EVALUASI KEGIATAN</h5>
                                <h5>PROGRAM PENGABDIAN KEPADA MASYARAKAT</h5>
                                <h5>UNIVERSITAS GUNADARMA PERIODE
                                    <span class="text-primary">
                                        <?php foreach ($periode as $mtr => $v_periode) : ?>
                                            <?php if ($abdimas->periode_id == $v_periode->periode_id) : ?>
                                                <?= $v_periode->periode_name; ?> <?= $v_periode->tahun_ajaran; ?>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </span>
                                </h5>
                            </div>
                            <div class="mb-3">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td class="col-2 font-weight-bold" style="border: 1px solid">Nama Ketua Tim PKM</td>
                                            <td class="col-5" style="border: 1px solid">
                                                <?php foreach ($tags as $ds => $v_tags) : ?>
                                                    <?php if ($abdimas->laporan_id == $v_tags->laporan_id) : ?>
                                                        <?php if ($v_tags->anggota_id == $abdimas->ketua_id) : ?>
                                                            <input type="hidden" name="ketua_id" id="ketua_id" class="form-control"
                                                                placeholder="<?= $v_tags->user_name; ?>" readonly autofocus>
                                                            <?= $v_tags->user_name; ?> <br>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </td>
                                            <td class="font-weight-bold" style="border: 1px solid">Tanggal Pelaksanaan</td>
                                            <td style="border: 1px solid">
                                                <?php if (empty($abdimas->tanggal_kegiatan)) : ?>
                                                    <span class="text-danger">Belum ada tanggal kegiatan</span>
                                                <?php else : ?>
                                                    <?php
                                                    // Biar nggak error double declare
                                                    if (!function_exists('formatTanggalID')) {
                                                        function formatTanggalID($tanggal)
                                                        {
                                                            if (empty($tanggal)) return '-';

                                                            $dateObj = date_create(trim($tanggal));
                                                            if (!$dateObj) return '-';

                                                            // Format: 1 Januari 2025
                                                            return date_format($dateObj, 'j F Y');
                                                        }
                                                    }

                                                    // Pisahkan tanggal mulai & selesai
                                                    $tanggal = array_map('trim', explode(' - ', $abdimas->tanggal_kegiatan));
                                                    $tanggalMulai = $tanggal[0] ?? null;
                                                    $tanggalSelesai = $tanggal[1] ?? null;

                                                    // Format tanggal ke gaya Indonesia polos
                                                    $formattedMulai = formatTanggalID($tanggalMulai);
                                                    $formattedSelesai = formatTanggalID($tanggalSelesai);
                                                    ?>

                                                    <span><?= $formattedMulai ?> — <?= $formattedSelesai ?></span>
                                                <?php endif; ?>
                                            </td>
                                        <tr>
                                            <td class="col-2 font-weight-bold" style="border: 1px solid">Nama Mitra PKM</td>
                                            <td colspan="3" style="border: 1px solid">
                                                <?php foreach ($mitra as $mtr => $v_mitra) : ?>
                                                    <?php if ($abdimas->mitra_id == $v_mitra->user_id) : ?>
                                                        <?= $v_mitra->user_name; ?>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="col-2 font-weight-bold" style="border: 1px solid">Judul Kegiatan PKM</td>
                                            <td colspan="3" style="border: 1px solid">
                                                <?php if ($abdimas->judul_kegiatan == null) : ?>
                                                    <span class="text-danger">Belum ada Judul / Nama Kegiatan</span>
                                                <?php else : ?>
                                                    <p class="text-break"><?= $abdimas->judul_kegiatan; ?></p>
                                                    <input type="hidden" name="judul_kegiatan" id="judul_kegiatan"
                                                        class="form-control" value="<?= $abdimas->judul_kegiatan; ?>"
                                                        autofocus readonly>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="col-2 font-weight-bold" style="border: 1px solid">Jumlah Anggota Tim</td>
                                            <td colspan="3" style="border: 1px solid">
                                                <?php
                                                $count_anggota = 0;
                                                $seen_users = [];
                                                foreach ($tags as $v_tags) :
                                                    if ($abdimas->laporan_id == $v_tags->laporan_id && !isset($seen_users[$v_tags->user_id])) :
                                                        $count_anggota++;
                                                        $seen_users[$v_tags->user_id] = true;
                                                    endif;
                                                endforeach;
                                                echo $count_anggota; ?> Orang (yang hadir, presensi terlampir)
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="col-2 font-weight-bold" style="border: 1px solid">Tipe Kegiatan</td>
                                            <td colspan="3" style="border: 1px solid"><?= esc($abdimas->tipe_kegiatan ?: 'Kelompok'); ?></td>
                                        </tr>
                                            <tr>
                                                <td class="col-2 font-weight-bold" style="border: 1px solid">Bidang Ilmu</td>
                                                <td colspan="3" style="border: 1px solid"><?= esc($abdimas->bidang_ilmu ?? '-'); ?></td>
                                            </tr>
                                        </tbody>
                                    </div>
                            <table class="table table-bordered" style="border: 1px solid">

                                <div style="border: 2px solid red; background-color: #ffe6e6; color: #b30000; padding: 15px; border-radius: 8px; font-size: 16px;">
                                    <b>Note:</b> Periksa kembali isian nilai anda <b>sebelum
                                        submit</b>,
                                    penilaian hanya
                                    dapat dilakukan 1x dan tidak dapat diubah setelah anda klik tombol submit
                                </div>
                                <thead>
                                    <tr>
                                        <th style="border: 1px solid">No</th>
                                        <th style="border: 1px solid">Komponen</th>
                                        <th style="border: 1px solid">Keterangan</th>
                                        <th class="text-center" style="border: 1px solid">Bobot</th>
                                        <th class="text-center" style="border: 1px solid">Nilai Team</th>
                                        <th class="text-center" style="border: 1px solid">Nilai LPM</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row" rowspan="9" style="border: 1px solid">1</th>
                                        <td colspan="5" style="border: 1px solid">
                                            Penilaian berdasarkan Pelaksana Kegiatan Pengabdian Kepada Masyarakat
                                        </td>
                                    </tr>
                                    <tr>
                                        <td rowspan="6" style="border: 1px solid">a. Materi dan
                                            pelaksanaan kegiatan
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border: 1px solid">1. Kesesuaian dengan kebutuhan Mitra</td>
                                        <td class="text-center" style="border: 1px solid">30</td>
                                        <td class="text-center" style="border: 1px solid; width:150px;">
                                            <?php if ($abdimas->nt1 == null) : ?>
                                                <input type="number" name="nt1" id="nt1" class="form-control" min="1"
                                                    max="30" autofocus>
                                            <?php else : ?>
                                                <?= $abdimas->nt1; ?>
                                                <input type="hidden" name="nt1" id="nt1" class="form-control"
                                                    value="<?= $abdimas->nt1; ?>" autofocus readonly>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center" style="border: 1px solid; width:150px;">
                                            <?php if ($abdimas->nlpm1 == null) : ?>
                                                <span class="text-danger">Belum ada Nilai</span>
                                            <?php else : ?>
                                                <?= $abdimas->nlpm1; ?>
                                                <input type="hidden" name="nlpm1" id="nlpm1" class="form-control"
                                                    value="<?= $abdimas->nlpm1; ?>" autofocus readonly>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border: 1px solid">2. Kelengkapan Materi dalam menyelesaikan masalah
                                            Mitra</td>
                                        <td class="text-center" style="border: 1px solid">20</td>
                                        <td class="text-center" style="border: 1px solid; width:150px;">
                                            <?php if ($abdimas->nt2 == null) : ?>
                                                <input type="number" name="nt2" id="nt2" class="form-control" min="1"
                                                    max="20" autofocus>
                                            <?php else : ?>
                                                <?= $abdimas->nt2; ?>
                                                <input type="hidden" name="nt2" id="nt2" class="form-control"
                                                    value="<?= $abdimas->nt2; ?>" autofocus readonly>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center" style="border: 1px solid; width:150px;">
                                            <?php if ($abdimas->nlpm2 == null) : ?>
                                                <span class="text-danger">Belum ada Nilai</span>
                                            <?php else : ?>
                                                <?= $abdimas->nlpm2; ?>
                                                <input type="hidden" name="nlpm2" id="nlpm2" class="form-control"
                                                    value="<?= $abdimas->nlpm2; ?>" autofocus readonly>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border: 1px solid">3. Akses materi oleh Mitra (Kemudahan Mitra
                                            memperoleh Materi)</td>
                                        <td class="text-center" style="border: 1px solid">10</td>
                                        <td class="text-center" style="border: 1px solid; width:150px;">
                                            <?php if ($abdimas->nt3 == null) : ?>
                                                <input type="number" name="nt3" id="nt3" class="form-control" min="1"
                                                    max="10" autofocus>
                                            <?php else : ?>
                                                <?= $abdimas->nt3; ?>
                                                <input type="hidden" name="nt3" id="nt3" class="form-control"
                                                    value="<?= $abdimas->nt3; ?>" autofocus readonly>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center" style="border: 1px solid; width:150px;">
                                            <?php if ($abdimas->nlpm3 == null) : ?>
                                                <span class="text-danger">Belum ada Nilai</span>
                                            <?php else : ?>
                                                <?= $abdimas->nlpm3; ?>
                                                <input type="hidden" name="nlpm3" id="nlpm3" class="form-control"
                                                    value="<?= $abdimas->nlpm3; ?>" autofocus readonly>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border: 1px solid">4. Kesiapan luaran kegiatan</td>
                                        <td class="text-center" style="border: 1px solid">20</td>
                                        <td class="text-center" style="border: 1px solid; width:150px;">
                                            <?php if ($abdimas->nt4 == null) : ?>
                                                <input type="number" name="nt4" id="nt4" class="form-control" min="1"
                                                    max="20" autofocus>
                                            <?php else : ?>
                                                <?= $abdimas->nt4; ?>
                                                <input type="hidden" name="nt4" id="nt4" class="form-control"
                                                    value="<?= $abdimas->nt4; ?>" autofocus readonly>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center" style="border: 1px solid; width:150px;">
                                            <?php if ($abdimas->nlpm4 == null) : ?>
                                                <span class="text-danger">Belum ada Nilai</span>
                                            <?php else : ?>
                                                <?= $abdimas->nlpm4; ?>
                                                <input type="hidden" name="nlpm4" id="nlpm4" class="form-control"
                                                    value="<?= $abdimas->nlpm4; ?>" autofocus readonly>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border: 1px solid">5. Kesiapan dan pelaksanaan kegiatan</td>
                                        <td class="text-center" style="border: 1px solid">20</td>
                                        <td class="text-center" style="border: 1px solid; width:150px;">
                                            <?php if ($abdimas->nt5 == null) : ?>
                                                <input type="number" name="nt5" id="nt5" class="form-control" min="1"
                                                    max="20" autofocus>
                                            <?php else : ?>
                                                <?= $abdimas->nt5; ?>
                                                <input type="hidden" name="nt5" id="nt5" class="form-control"
                                                    value="<?= $abdimas->nt5; ?>" autofocus readonly>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center" style="border: 1px solid; width:150px;">
                                            <?php if ($abdimas->nlpm5 == null) : ?>
                                                <span class="text-danger">Belum ada Nilai</span>
                                            <?php else : ?>
                                                <?= $abdimas->nlpm5; ?>
                                                <input type="hidden" name="nlpm5" id="nlpm5" class="form-control"
                                                    value="<?= $abdimas->nlpm5; ?>" autofocus readonly>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td rowspan="2" style="border: 1px solid">b. Peran dan Kontribusi Anggota</td>
                                        <td style="border: 1px solid">
                                            1. Kesesuaian dan kelengkapan bidang ilmu dalam menyelesaikan masalah Mitra
                                        </td>
                                        <td class="text-center" style="border: 1px solid">40</td>
                                        <td class="text-center" style="border: 1px solid; width:150px;">
                                            <?php if ($abdimas->nt6 == null) : ?>
                                                <input type="number" name="nt6" id="nt6" class="form-control" min="1"
                                                    max="40" autofocus>
                                            <?php else : ?>
                                                <?= $abdimas->nt6; ?>
                                                <input type="hidden" name="nt6" id="nt6" class="form-control"
                                                    value="<?= $abdimas->nt6; ?>" autofocus readonly>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center" style="border: 1px solid; width:150px;">
                                            <?php if ($abdimas->nlpm6 == null) : ?>
                                                <span class="text-danger">Belum ada Nilai</span>
                                            <?php else : ?>
                                                <?= $abdimas->nlpm6; ?>
                                                <input type="hidden" name="nlpm6" id="nlpm6" class="form-control"
                                                    value="<?= $abdimas->nlpm6; ?>" autofocus readonly>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border: 1px solid">2. Kehadiran dan kontribusi setiap anggota dalam
                                            kegiatan</td>
                                        <td class="text-center" style="border: 1px solid">60</td>
                                        <td class="text-center" style="border: 1px solid; width:150px;">
                                            <?php if ($abdimas->nt7 == null) : ?>
                                                <input type="number" name="nt7" id="nt7" class="form-control" min="1"
                                                    max="60" autofocus>
                                            <?php else : ?>
                                                <?= $abdimas->nt7; ?>
                                                <input type="hidden" name="nt7" id="nt7" class="form-control"
                                                    value="<?= $abdimas->nt7; ?>" autofocus readonly>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center" style="border: 1px solid; width:150px;">
                                            <?php if ($abdimas->nlpm7 == null) : ?>
                                                <span class="text-danger">Belum ada Nilai</span>
                                            <?php else : ?>
                                                <?= $abdimas->nlpm7; ?>
                                                <input type="hidden" name="nlpm7" id="nlpm7" class="form-control"
                                                    value="<?= $abdimas->nlpm7; ?>" autofocus readonly>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row" rowspan="2" style="border: 1px solid">2</th>
                                        <td rowspan="2" style="border: 1px solid">Kondisi Mitra</td>
                                        <td style="border: 1px solid">1. Partisipasi Mitra saat kegiatan</td>
                                        <td class="text-center" style="border: 1px solid">40</td>
                                        <td class="text-center" style="border: 1px solid; width:150px;">
                                            <?php if ($abdimas->nt8 == null) : ?>
                                                <input type="number" name="nt8" id="nt8" class="form-control" min="1"
                                                    max="40" autofocus>
                                            <?php else : ?>
                                                <?= $abdimas->nt8; ?>
                                                <input type="hidden" name="nt8" id="nt8" class="form-control"
                                                    value="<?= $abdimas->nt8; ?>" autofocus readonly>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center" style="border: 1px solid; width:150px;">
                                            <?php if ($abdimas->nlpm8 == null) : ?>
                                                <span class="text-danger">Belum ada Nilai</span>
                                            <?php else : ?>
                                                <?= $abdimas->nlpm8; ?>
                                                <input type="hidden" name="nlpm8" id="nlpm8" class="form-control"
                                                    value="<?= $abdimas->nlpm8; ?>" autofocus readonly>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border: 1px solid">2. Manfaat yang dirasakan Mitra</td>
                                        <td class="text-center" style="border: 1px solid">60</td>
                                        <td class="text-center" style="border: 1px solid; width:150px;">
                                            <?php if ($abdimas->nt9 == null) : ?>
                                                <input type="number" name="nt9" id="nt9" class="form-control" min="1"
                                                    max="60" autofocus>
                                            <?php else : ?>
                                                <?= $abdimas->nt9; ?>
                                                <input type="hidden" name="nt9" id="nt9" class="form-control"
                                                    value="<?= $abdimas->nt9; ?>" autofocus readonly>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center" style="border: 1px solid; width:150px;">
                                            <?php if ($abdimas->nlpm9 == null) : ?>
                                                <span class="text-danger">Belum ada Nilai</span>
                                            <?php else : ?>
                                                <?= $abdimas->nlpm9; ?>
                                                <input type="hidden" name="nlpm9" id="nlpm9" class="form-control"
                                                    value="<?= $abdimas->nlpm9; ?>" autofocus readonly>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" style="border: 1px solid; width:150px;">
                                            <label class="font-weight-bold">Saran & Masukan:</label><br>
                                            <?php if ($rekapan->saran_masukan == null) : ?>
                                                <span class="text-danger">Belum ada Saran dan Masukan</span>
                                            <?php else : ?>
                                                <p class="text-break"><?= $rekapan->saran_masukan; ?></p>
                                                <textarea name="saran_masukan" id="saran_masukan" class="form-control" hidden readonly><?= $rekapan->saran_masukan; ?></textarea>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <!--<tr>-->
                                    <!--    <td colspan="6" style="border: 1px solid; width:150px;">-->
                                    <!--        <label class="font-weight-bold mt-1">Upload File SKM:</label><br>-->
                                    <!--        <?php if ($abdimas->skm == null) : ?>-->
                                    <!--            <input type="file" name="skm" id="skm" class="form-control mb-2" accept=".pdf,.doc,.docx,.jpg,.png">-->
                                    <!--            <small class="text-muted">Format yang diperbolehkan: PDF, DOC, DOCX, JPG, PNG</small>-->
                                    <!--        <?php else : ?>-->
                                    <!--            <p>-->
                                    <!--                <a href="<?= base_url('berkas/skm/' . $abdimas->skm); ?>" target="_blank" class="text-primary">-->
                                    <!--                    <?= $abdimas->skm; ?>-->
                                    <!--                </a>-->
                                    <!--            </p>-->
                                    <!--            <input type="file" name="skm" id="skm" class="form-control mb-2" accept=".pdf,.doc,.docx,.jpg,.png">-->
                                    <!--            <small class="text-muted">Kosongkan jika tidak ingin mengganti file</small>-->
                                    <!--        <?php endif; ?>-->
                                    <!--    </td>-->
                                    <!--</tr>-->

                                </tbody>
                            </table>
                            <div class="float-right">
                                <a href="<?= site_url('monev'); ?>" class="btn btn-dark">kembali</a>
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
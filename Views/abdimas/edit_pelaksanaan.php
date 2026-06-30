<?php
helper(['cookie', 'url']);

$request = service('request');

// bahasa aktif - check query param first, then cookie, default to id
$allowed = ['id', 'en'];
$lang = $request->getGet('lang');
if (! $lang) {
    $lang = get_cookie('lang') ?: 'id';
}
if (! in_array($lang, $allowed, true)) {
    $lang = 'id';
}

// Set cookie if lang from query param
if ($request->getGet('lang')) {
    set_cookie('lang', $lang, 60 * 60 * 24 * 30);
}

// ========== TRANSLATION ARRAY (COMPACT) ==========
$txt = [
    'id' => [
        'note' => '<b>Note:</b> Periksa kembali isian anda <b>sebelum submit</b>, pengisian hanya bisa dilakukan 1x dan tidak dapat diubah setelah Anda klik <b>tombol Simpan</b>.',
        'dashboard' => 'Dashboard',
        'data_pelaksanaan' => 'Data Pelaksanaan',
        'periode' => 'Periode Abdimas',
        'data_ketua' => 'Data Ketua',
        'nama_ketua' => 'Nama Ketua',
        'nidn' => 'NIDN',
        'sinta_id' => 'SINTA ID',
        'mitra' => 'Nama Mitra',
        'alamat' => 'Alamat kegiatan',
        'masalah' => 'Masalah Mitra',
        'solusi' => 'Solusi Mitra',
        'topik' => 'Topik',
        'program' => 'Program',
        'subprogram' => 'SubProgram',
        'topik_title' => 'Topik - Program - Sub Program',
        'luaran' => 'Luaran Kegiatan',
        'tipe' => 'Tipe Kegiatan',
        'ketua_anggota' => 'Ketua dan Anggota',
        'ketua' => 'Ketua',
        'mahasiswa' => 'Mahasiswa',
        'estimasi' => 'Estimasi Pendanaan',
        'sumber' => 'Sumber Dana',
        'tanggal' => 'Tanggal Kegiatan',
        'tgl_mulai' => 'Tanggal Mulai',
        'tgl_selesai' => 'Tanggal Selesai',
        'format_tgl' => 'Format: Tanggal selesai harus sama atau setelah tanggal mulai',
        'alert_tgl' => 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai!',
        'judul' => 'Judul/Nama Kegiatan',
        'placeholder' => 'Masukan judul / nama kegiatan anda',
        'kembali' => 'Kembali',
        'reset' => 'Reset',
        'simpan' => 'Simpan',
        'bulan' => [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
        'hari' => ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']
    ],
    'en' => [
        'note' => '<b>Note:</b> Please review your entries <b>before submitting</b>, filling can only be done once and cannot be changed after you click the <b>Save button</b>.',
        'dashboard' => 'Dashboard',
        'data_pelaksanaan' => 'Implementation Data',
        'periode' => 'Community Service Period',
        'data_ketua' => 'Leader Data',
        'nama_ketua' => 'Leader Name',
        'nidn' => 'NIDN',
        'sinta_id' => 'SINTA ID',
        'mitra' => 'Partner Name',
        'alamat' => 'Activity address',
        'masalah' => 'Partner Problems',
        'solusi' => 'Partner Solutions',
        'topik' => 'Topic',
        'program' => 'Program',
        'subprogram' => 'SubProgram',
        'topik_title' => 'Topic - Program - Sub Program',
        'luaran' => 'Activity Outputs',
        'tipe' => 'Activity Type',
        'ketua_anggota' => 'Leader and Members',
        'ketua' => 'Leader',
        'mahasiswa' => 'Students',
        'estimasi' => 'Funding Estimation',
        'sumber' => 'Funding Source',
        'tanggal' => 'Activity Date',
        'tgl_mulai' => 'Start Date',
        'tgl_selesai' => 'End Date',
        'format_tgl' => 'Format: End date must be equal to or after start date',
        'alert_tgl' => 'End date cannot be earlier than start date!',
        'judul' => 'Activity Title/Name',
        'placeholder' => 'Enter your activity title / name',
        'kembali' => 'Back',
        'reset' => 'Reset',
        'simpan' => 'Save',
        'bulan' => [1 => 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        'hari' => ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
    ]
];

// ========== FUNGSI FORMAT TANGGAL (DILUAR LOOP) ==========
function formatTgl($date, $bulan, $hari)
{
    if (empty($date) || $date == '0000-00-00') return '-';
    $ts = strtotime($date);
    if ($ts === false) return $date;
    return $hari[date('w', $ts)] . ', ' . date('d', $ts) . ' ' . $bulan[(int)date('n', $ts)] . ' ' . date('Y', $ts);
}
?>

<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <a href="<?= site_url('pelaksanaan'); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
        <h1><?= $title; ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>"><?= $txt[$lang]['dashboard']; ?></a></div>
            <div class="breadcrumb-item active"><a href="<?= site_url('pelaksanaan'); ?>"><?= $txt[$lang]['data_pelaksanaan']; ?></a></div>
            <div class="breadcrumb-item"><?= $title; ?></div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= site_url('pelaksanaan/' . $abdimas->laporan_id); ?>" method="POST"
                            autocomplete="off" enctype="multipart/form-data">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="_method" value="PATCH">
                            <div style="border: 2px solid red; background-color: #ffe6e6; color: #b30000; padding: 15px; border-radius: 8px; font-size: 16px;">
                                <?= $txt[$lang]['note']; ?>
                            </div>

                            <div class="table-responsive-md">
                                <table class="table table-bordered" style="border: 1px solid">
                                    <tbody>
                                        <tr>
                                            <td class="col-2 font-weight-bold" style="border: 1px solid">
                                                <?= $txt[$lang]['periode']; ?>
                                            </td>
                                            <td class="col-10" style="border: 1px solid">
                                                <div class="form-group m-2">
                                                    <?php foreach ($periode as $mtr => $v_periode) : ?>
                                                        <?php if ($abdimas->periode_id == $v_periode->periode_id) : ?>
                                                            <?= $v_periode->periode_name; ?> <?= $v_periode->tahun_ajaran; ?>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                <?= $txt[$lang]['data_ketua']; ?>
                                            </td>
                                            <td class="col-9" style="border: 1px solid">
                                                <div class="form-group m-2">
                                                    <?php foreach ($tags as $ds => $v_tags) : ?>
                                                        <?php if ($abdimas->laporan_id == $v_tags->laporan_id) : ?>
                                                            <?php if ($v_tags->anggota_id == $abdimas->ketua_id) : ?>
                                                                <input type="hidden" name="nidn" id="nidn"
                                                                    value="<?= $v_tags->nidn; ?>" class="form-control" readonly
                                                                    autofocus>
                                                                <b>NIDN:</b> <?= $v_tags->nidn; ?> <br>
                                                                <b><?= $txt[$lang]['nidn']; ?>:</b> <?= $v_tags->nidn; ?> <br>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>

                                                    <?php foreach ($tags as $ds => $v_tags) : ?>
                                                        <?php if ($abdimas->laporan_id == $v_tags->laporan_id) : ?>
                                                            <?php if ($v_tags->anggota_id == $abdimas->ketua_id) : ?>
                                                                <input type="hidden" name="ketua_id" id="ketua_id"
                                                                    class="form-control" placeholder="<?= $v_tags->user_name; ?>"
                                                                    readonly autofocus>
                                                                <b><?= $txt[$lang]['nama_ketua']; ?>:</b> <?= $v_tags->user_name; ?> <br>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>

                                                    <?php foreach ($tags as $ds => $v_tags) : ?>
                                                        <?php if ($abdimas->laporan_id == $v_tags->laporan_id) : ?>
                                                            <?php if ($v_tags->anggota_id == $abdimas->ketua_id) : ?>
                                                                <input type="hidden" name="sinta_id" id="sinta_id"
                                                                    value="<?= $v_tags->sinta_id; ?>" class="form-control" readonly
                                                                    autofocus>
                                                                <b>SINTA ID:</b> <?= $v_tags->sinta_id; ?>
                                                                <b><?= $txt[$lang]['sinta_id']; ?>:</b> <?= $v_tags->sinta_id; ?>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                <?= $txt[$lang]['mitra']; ?>
                                            </td>
                                            <td class="col-9" style="border: 1px solid">
                                                <div class="form-group m-2">
                                                    <?php foreach ($mitra as $mtr => $v_mitra) : ?>
                                                        <?php if ($abdimas->mitra_id == $v_mitra->user_id) : ?>
                                                            <?= $v_mitra->user_name; ?> <br>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>

                                                    <?php foreach ($mitra as $mtr => $v_mitra) : ?>
                                                        <?php if ($abdimas->mitra_id == $v_mitra->user_id) : ?>
                                                            <b><?= $txt[$lang]['alamat']; ?>:</b><br>
                                                            <?= $v_mitra->alamat . ' -|-|- ' . $v_mitra->kota_name; ?>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="col-6" style="border: 1px solid">
                                                <strong><?= $txt[$lang]['masalah']; ?></strong>
                                                <div class="form-group mt-2">
                                                    <?= esc($abdimas->masalah_mitra); ?>
                                                </div>
                                            </td>
                                            <td class="col-6" style="border: 1px solid; vertical-align: top;">
                                                <div class="form-group m-2">
                                                    <strong><?= $txt[$lang]['solusi']; ?></strong>
                                                    <div class="mt-2">
                                                        <?= esc($abdimas->solusi_mitra); ?>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                <?= $txt[$lang]['topik_title']; ?>
                                            </td>
                                            <td class="col-9" style="border: 1px solid">
                                                <div class="form-group m-2">
                                                    <?php foreach ($subprogram as $mtr => $v_subprogram) : ?>
                                                        <?php if ($abdimas->subprogram_id == $v_subprogram->subprogram_id) : ?>
                                                            <b><?= $txt[$lang]['topik']; ?>: </b><?= $v_subprogram->topik_name; ?> <br>
                                                            <b><?= $txt[$lang]['program']; ?>: </b><?= $v_subprogram->program_name; ?> <br>
                                                            <b><?= $txt[$lang]['subprogram']; ?>: </b><?= $v_subprogram->subprogram_name; ?>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                <?= $txt[$lang]['luaran']; ?>
                                            </td>
                                            <td class="col-9" style="border: 1px solid">
                                                <div class="form-group m-2">
                                                    <?php
                                                    // ✅ FIX MEMORY LEAK: Buat array unique DULU sebelum loop output
                                                    $uniqueLuaran = [];
                                                    foreach ($tagluaran as $v_tagluaran) {
                                                        if ($abdimas->laporan_id == $v_tagluaran->laporan_id) {
                                                            $uniqueLuaran[$v_tagluaran->luaran_id] = $v_tagluaran->luaran_name;
                                                        }
                                                    }

                                                    // Loop untuk output saja (AMAN)
                                                    $counter = 1;
                                                    foreach ($uniqueLuaran as $luaran_name) {
                                                        echo $counter . '. ' . ucwords(strtolower($luaran_name)) . '<br>';
                                                        $counter++;
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                <?= $lang === 'en' ? 'Field of Study' : 'Bidang Ilmu'; ?>:
                                            </td>
                                            <td class="col-9" style="border: 1px solid">
                                                <div class="form-group m-2">
                                                    <?= esc($abdimas->bidang_ilmu ?? '-'); ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                <?= $txt[$lang]['tipe']; ?>
                                            </td>
                                            <td class="col-9" style="border: 1px solid">
                                                <div class="form-group m-2">
                                                    <?= esc($abdimas->tipe_kegiatan ?: 'Kelompok'); ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                <?= $txt[$lang]['ketua_anggota']; ?>
                                            </td>
                                            <td class="col-9" style="border: 1px solid">
                                                <div class="form-group m-2">
                                                    <?php
                                                    $counter_dosen = 1;
                                                    $seen_dosen = [];
                                                    foreach ($tags as $v_tags) :
                                                        if ($abdimas->laporan_id == $v_tags->laporan_id && !isset($seen_dosen[$v_tags->user_id])) :
                                                            if ($v_tags->anggota_id == $abdimas->ketua_id) : ?>
                                                                <?= $counter_dosen . '. ' . ucwords(strtolower($v_tags->user_name)); ?> (<span class="text-danger font-weight-bold"><?= $txt[$lang]['ketua']; ?></span>)<br>
                                                            <?php $counter_dosen++;
                                                                $seen_dosen[$v_tags->user_id] = true;
                                                            else : ?>
                                                                <?= $counter_dosen . '. ' . ucwords(strtolower($v_tags->user_name)); ?><br>
                                                    <?php $counter_dosen++;
                                                                $seen_dosen[$v_tags->user_id] = true;
                                                            endif;
                                                        endif;
                                                    endforeach; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                <?= $txt[$lang]['mahasiswa']; ?>
                                            </td>
                                            <td class="col-9" style="border: 1px solid">
                                                <div class="form-group m-2">
                                                    <?php
                                                    $counter_mahasiswa = 1;
                                                    foreach ($mahasiswa as $mhs) : ?>
                                                        <?= $counter_mahasiswa . '. ' . ucwords(strtolower($mhs->mahasiswa_name)); ?> - <?= $mhs->mahasiswa_npm ?> - <?= $mhs->jurusan_name ?><br>
                                                        <?php $counter_mahasiswa++; ?>
                                                    <?php endforeach; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="col-2 font-weight-bold" style="border: 1px solid">
                                                <?= $txt[$lang]['estimasi']; ?>
                                            </td>
                                            <td class="col-10" style="border: 1px solid">
                                                <div class="form-group m-2">
                                                    <?= 'Rp. ' . number_format($abdimas->range_dana, 0, ',', '.'); ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="col-3 font-weight-bold" style="border: 1px solid">
                                                <?= $txt[$lang]['sumber']; ?>
                                            </td>
                                            <td class="col-9" style="border: 1px solid">
                                                <div class="form-group m-2">
                                                    <?= esc($abdimas->sumber_dana); ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="col-2 font-weight-bold" style="border: 1px solid;">
                                                <?= $txt[$lang]['tanggal']; ?>
                                            </td>
                                            <td class="col-10" style="border: 1px solid;">
                                                <div class="form-group m-2">
                                                    <?php if (empty($abdimas->tanggal_kegiatan)) : ?>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label for="tanggal_mulai" class="form-label"><?= $txt[$lang]['tgl_mulai']; ?> <span class="text-danger">*</span></label>
                                                                <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" value="<?= esc($tanggal_mulai ?? '') ?>" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="tanggal_selesai" class="form-label"><?= $txt[$lang]['tgl_selesai']; ?> <span class="text-danger">*</span></label>
                                                                <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control" value="<?= esc($tanggal_selesai ?? '') ?>" required>
                                                            </div>
                                                        </div>
                                                        <small class="text-muted"><?= $txt[$lang]['format_tgl']; ?></small>

                                                        <script>
                                                            (function() {
                                                                const msg = '<?= addslashes($txt[$lang]['alert_tgl']); ?>';
                                                                const tMulai = document.getElementById('tanggal_mulai');
                                                                const tSelesai = document.getElementById('tanggal_selesai');

                                                                if (tMulai && tSelesai) {
                                                                    tMulai.addEventListener('change', function() {
                                                                        tSelesai.min = this.value;
                                                                        if (tSelesai.value && tSelesai.value < this.value) {
                                                                            tSelesai.value = this.value;
                                                                        }
                                                                    });

                                                                    tSelesai.addEventListener('change', function() {
                                                                        if (tMulai.value && this.value < tMulai.value) {
                                                                            alert(msg);
                                                                            this.value = tMulai.value;
                                                                        }
                                                                    });
                                                                }
                                                            })();
                                                        </script>

                                                    <?php else : ?>
                                                        <?php
                                                        $tglArr = explode(' - ', $abdimas->tanggal_kegiatan);
                                                        $tglMulai = isset($tglArr[0]) ? trim($tglArr[0]) : null;
                                                        $tglSelesai = isset($tglArr[1]) ? trim($tglArr[1]) : null;

                                                        $fmtMulai = formatTgl($tglMulai, $txt[$lang]['bulan'], $txt[$lang]['hari']);
                                                        $fmtSelesai = formatTgl($tglSelesai, $txt[$lang]['bulan'], $txt[$lang]['hari']);
                                                        ?>

                                                        <div class="p-3 bg-light rounded">
                                                            <i class="fas fa-calendar-check text-success me-2"></i>
                                                            <strong><?= esc($fmtMulai) ?></strong>
                                                            <span class="mx-2">—</span>
                                                            <strong><?= esc($fmtSelesai) ?></strong>
                                                        </div>

                                                        <input type="hidden" name="tanggal_mulai" value="<?= esc($tglMulai) ?>">
                                                        <input type="hidden" name="tanggal_selesai" value="<?= esc($tglSelesai) ?>">
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="col-2 font-weight-bold" style="border: 1px solid">
                                                <?= $txt[$lang]['judul']; ?>
                                            </td>
                                            <td class="col-10" style="border: 1px solid">
                                                <div class="form-group m-2">
                                                    <?php if ($abdimas->judul_kegiatan == null) : ?>
                                                        <input type="text" name="judul_kegiatan" id="judul_kegiatan"
                                                            class="form-control"
                                                            placeholder="<?= $txt[$lang]['placeholder']; ?>" autofocus required>
                                                    <?php else : ?>
                                                        <p class="text-break"><?= $abdimas->judul_kegiatan; ?></p>
                                                        <input type="hidden" name="judul_kegiatan" id="judul_kegiatan"
                                                            class="form-control" value="<?= $abdimas->judul_kegiatan; ?>"
                                                            autofocus readonly>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="float-right">
                                <a href="<?= site_url('abdimas'); ?>" class="btn btn-dark"><?= $txt[$lang]['kembali']; ?></a>
                                <button type="reset" class="btn btn-danger"><?= $txt[$lang]['reset']; ?></button>
                                <button type="submit" class="btn btn-primary"><?= $txt[$lang]['simpan']; ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
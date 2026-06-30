<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>
<?= $this->endSection() ?>

<?php
helper(['cookie', 'url']);

$request = service('request');

$allowed = ['id', 'en'];
$lang = get_cookie('lang') ?: 'id';
if (! in_array($lang, $allowed, true)) {
    $lang = 'id';
}

$reqLang = $request->getGet('lang');
if ($reqLang && in_array($reqLang, $allowed, true)) {
    set_cookie('lang', $reqLang, 60 * 60 * 24 * 30);
    $lang = $reqLang;
}

if (! function_exists('t')) {
    function t(string $key): string
    {
        global $dict, $lang;

        return $dict[$lang][$key]
            ?? $dict['id'][$key]
            ?? $key;
    }
}

if (! function_exists('lang_url')) {
    function lang_url(string $locale): string
    {
        $request = service('request');
        $base = current_url();
        $q = $request->getGet();
        $q['lang'] = $locale;

        return $base . '?' . http_build_query($q);
    }
}

$tr = [
    'id' => [
        'dashboard' => 'Dashboard',
        'rekap_data' => 'Data rekapan',

        'back' => 'kembali',
        'save' => 'Simpan',

        'periode' => 'Periode',
        'choose_periode' => '—PILIH PERIODE—',
        'registration_closed' => 'Pendaftaran ditutup',

        'nidn' => 'NIDN',
        'leader_name' => 'Nama Ketua',
        'partner_selected' => 'Mitra yang dipilih',
        'choose_partner' => '—PILIH MITRA UMKM—',

        'topic_program_sub' => 'Topik - Program - Sub Program',
        'choose_topic_program_sub' => '—PILIH TOPIK - PROGRAM - SUB PROGRAM—',

        'outputs_selected' => 'Luaran yang dipilih',

        'activity_type' => 'Tipe Kegiatan',
        'choose_activity_type' => '—PILIH TIPE KEGIATAN—',
        'individual' => 'Perorangan',
        'group' => 'Kelompok',

        'funding_est' => 'Estimasi Pendanaan',
        'choose_funding' => '—PILIH RANGE PENDANAAN—',

        'member_list' => 'Daftar Anggota',

        'verify' => 'Silahkan Verifikasi',
        'choose_verify' => '—PILIH VERIFIKASI—',
        'accept' => 'Terima',
        'process' => 'Proses',
        'revision' => 'Revisi',

        'revision_note_title' => 'Catatan Revisi / Perbaikan',
        'note' => 'Note:',
        'note_fill_if_choose' => 'Diisi jika anda memilih',
        'note_on_option' => 'pada option diatas | Abaikan jika anda memilih',
        'note_revision' => '"Revisi"',
        'note_accept' => '"Terima"',

        'view_report' => 'Lihat Laporan',
        'no_report' => 'Belum upload laporan',

        'view_evidence' => 'Lihat Bukti Kegiatan',
        'no_evidence' => 'Belum upload bukti kegiatan',

        'required_mark' => '*',
    ],
    'en' => [
        'dashboard' => 'Dashboard',
        'rekap_data' => 'Recap Data',

        'back' => 'Back',
        'save' => 'Save',

        'periode' => 'Period',
        'choose_periode' => '—CHOOSE PERIOD—',
        'registration_closed' => 'Registration closed',

        'nidn' => 'NIDN',
        'leader_name' => 'Leader Name',
        'partner_selected' => 'Selected Partner',
        'choose_partner' => '—CHOOSE UMKM PARTNER—',

        'topic_program_sub' => 'Topic - Program - Sub Program',
        'choose_topic_program_sub' => '—CHOOSE TOPIC - PROGRAM - SUB PROGRAM—',

        'outputs_selected' => 'Selected Outputs',

        'activity_type' => 'Activity Type',
        'choose_activity_type' => '—CHOOSE ACTIVITY TYPE—',
        'individual' => 'Individual',
        'group' => 'Group',

        'funding_est' => 'Funding Estimate',
        'choose_funding' => '—CHOOSE FUNDING RANGE—',

        'member_list' => 'Member List',

        'verify' => 'Verification',
        'choose_verify' => '—CHOOSE VERIFICATION—',
        'accept' => 'Accept',
        'process' => 'In Progress',
        'revision' => 'Revision',

        'revision_note_title' => 'Revision / Improvement Notes',
        'note' => 'Note:',
        'note_fill_if_choose' => 'Fill in if you choose',
        'note_on_option' => 'in the option above | Ignore if you choose',
        'note_revision' => '"Revision"',
        'note_accept' => '"Accept"',

        'view_report' => 'View Report',
        'no_report' => 'Report not uploaded yet',

        'view_evidence' => 'View Activity Evidence',
        'no_evidence' => 'Evidence not uploaded yet',

        'required_mark' => '*',
    ],
];

$t = function (string $key) use ($tr, $lang) {
    return $tr[$lang][$key] ?? $tr['id'][$key] ?? $key;
};
?>

<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <a href="<?= site_url('rekapan'); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
        <h1><?= $title; ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>"><?= esc($t('dashboard')); ?></a></div>
            <div class="breadcrumb-item active"><a href="<?= site_url('rekapan'); ?>"><?= esc($t('rekap_data')); ?></a></div>
            <div class="breadcrumb-item"><?= $title; ?></div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="<?= site_url('rekapan/' . $rekapan->laporan_id); ?>" method="POST" autocomplete="off">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="_method" value="PATCH">

                            <div class="form-group">
                                <label><?= esc($t('periode')); ?><span class="text-danger"><?= esc($t('required_mark')); ?></span></label>
                                <select name="periode_id" class="form-control select2" disabled>
                                    <option selected disabled>&mdash;<?= esc($t('choose_periode')); ?>&mdash;</option>
                                    <?php foreach ($periode as $mtr => $v_periode): ?>
                                        <?php if ($v_periode->info == 1): ?>
                                            <option value="<?= $v_periode->periode_id; ?>" <?= $rekapan->periode_id == $v_periode->periode_id ? 'selected' : null; ?>>
                                                <?= $v_periode->periode_name; ?> <?= $v_periode->tahun_ajaran; ?>
                                            </option>
                                        <?php else : ?>
                                            <option disabled value="<?= $v_periode->periode_id; ?>">
                                                <?= $v_periode->periode_name; ?> <?= $v_periode->tahun_ajaran; ?> -||- <?= esc($t('registration_closed')); ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label><?= esc($t('nidn')); ?><span class="text-danger"><?= esc($t('required_mark')); ?></span></label>
                                    <input type="text" name="nidn" id="nidn" value="<?= userLogin()->nidn; ?>" class="form-control" disabled autofocus>
                                </div>
                                <div class="form-group col-md-4">
                                    <label><?= esc($t('leader_name')); ?><span class="text-danger"><?= esc($t('required_mark')); ?></span></label>
                                    <input type="text" name="ketua_id" id="ketua_id" class="form-control" placeholder="<?= userLogin()->user_name; ?>" disabled autofocus>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>SINTA ID<span class="text-danger"><?= esc($t('required_mark')); ?></span></label>
                                    <input type="text" name="sinta_id" id="sinta_id" value="<?= userLogin()->sinta_id; ?>" class="form-control" disabled autofocus>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?= esc($t('partner_selected')); ?><span class="text-danger"><?= esc($t('required_mark')); ?></span></label>
                                <select name="mitra_id" class="form-control select2" disabled>
                                    <option selected disabled>&mdash;<?= esc($t('choose_partner')); ?>&mdash;</option>
                                    <?php foreach ($mitra as $mtr => $v_mitra): ?>
                                        <?php if ($v_mitra->role_id == 5): ?>
                                            <option value="<?= $v_mitra->user_id; ?>" <?= $rekapan->mitra_id == $v_mitra->user_id ? 'selected' : null; ?>>
                                                <?= $v_mitra->user_name; ?> - <?= $v_mitra->kota_name; ?>
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label><?= esc($t('topic_program_sub')); ?><span class="text-danger"><?= esc($t('required_mark')); ?></span></label>
                                <select name="subprogram_id" class="form-control select2" disabled>
                                    <option selected disabled>&mdash;<?= esc($t('choose_topic_program_sub')); ?>&mdash;</option>
                                    <?php foreach ($subprogram as $mtr => $v_subprogram): ?>
                                        <option value="<?= $v_subprogram->subprogram_id; ?>" <?= $rekapan->subprogram_id == $v_subprogram->subprogram_id ? 'selected' : null; ?>>
                                            <?= $v_subprogram->topik_name; ?> -||- <?= $v_subprogram->program_name; ?> -||- <?= $v_subprogram->subprogram_name; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label><?= esc($t('outputs_selected')); ?><span class="text-danger"><?= esc($t('required_mark')); ?></span></label>
                                <select name="luaran_id[]" class="form-control select2" id="luaran_id" multiple disabled>
                                    <?php foreach ($tagluaran as $ls => $v_tagluaran): ?>
                                        <?php if ($rekapan->laporan_id == $v_tagluaran->laporan_id): ?>
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
                                <label><?= esc($t('activity_type')); ?><span class="text-danger"><?= esc($t('required_mark')); ?></span></label>
                                <select name="tipe_kegiatan" class="form-control select2" disabled>
                                    <option selected disabled>&mdash;<?= esc($t('choose_activity_type')); ?>&mdash;</option>
                                    <?php if ($rekapan->tipe_kegiatan == 'Perorangan'): ?>
                                        <option value="<?= $rekapan->tipe_kegiatan; ?>" selected><?= esc($t('individual')); ?></option>
                                        <option value="Kelompok"><?= esc($t('group')); ?></option>
                                    <?php elseif ($rekapan->tipe_kegiatan == 'Kelompok'): ?>
                                        <option value="<?= $rekapan->tipe_kegiatan; ?>" selected><?= esc($t('group')); ?></option>
                                        <option value="Perorangan"><?= esc($t('individual')); ?></option>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label><?= esc($t('funding_est')); ?><span class="text-danger"><?= esc($t('required_mark')); ?></span></label>
                                <select name="range_dana" class="form-control select2" disabled>
                                    <option selected disabled>&mdash;<?= esc($t('choose_funding')); ?>&mdash;</option>
                                    <?php if ($rekapan->range_dana == '1 JT - 5 JT'): ?>
                                        <option value="<?= $rekapan->range_dana; ?>" selected>1 JT - 5 JT</option>
                                        <option value="6 JT - 15 JT">6 JT - 15 JT</option>
                                        <option value="16 JT - 25 JT">16 JT - 25 JT</option>
                                        <option value="26 JT - 35 JT">26 JT - 35 JT</option>
                                        <option value="36 JT - 50 JT">36 JT - 50 JT</option>
                                    <?php elseif ($rekapan->range_dana == '6 JT - 15 JT'): ?>
                                        <option value="<?= $rekapan->range_dana; ?>" selected>6 JT - 15 JT</option>
                                        <option value="1 JT - 5 JT">1 JT - 5 JT</option>
                                        <option value="16 JT - 25 JT">16 JT - 25 JT</option>
                                        <option value="26 JT - 35 JT">26 JT - 35 JT</option>
                                        <option value="36 JT - 50 JT">36 JT - 50 JT</option>
                                    <?php elseif ($rekapan->range_dana == '16 JT - 25 JT'): ?>
                                        <option value="<?= $rekapan->range_dana; ?>" selected>16 JT - 25 JT</option>
                                        <option value="1 JT - 5 JT">1 JT - 5 JT</option>
                                        <option value="6 JT - 15 JT">6 JT - 15 JT</option>
                                        <option value="26 JT - 35 JT">26 JT - 35 JT</option>
                                        <option value="36 JT - 50 JT">36 JT - 50 JT</option>
                                    <?php elseif ($rekapan->range_dana == '26 JT - 35 JT'): ?>
                                        <option value="<?= $rekapan->range_dana; ?>" selected>26 JT - 35 JT</option>
                                        <option value="1 JT - 5 JT">1 JT - 5 JT</option>
                                        <option value="6 JT - 15 JT">6 JT - 15 JT</option>
                                        <option value="16 JT - 25 JT">16 JT - 25 JT</option>
                                        <option value="36 JT - 50 JT">36 JT - 50 JT</option>
                                    <?php elseif ($rekapan->range_dana == '36 JT - 50 JT'): ?>
                                        <option value="<?= $rekapan->range_dana; ?>" selected>36 JT - 50 JT</option>
                                        <option value="1 JT - 5 JT">1 JT - 5 JT</option>
                                        <option value="6 JT - 15 JT">6 JT - 15 JT</option>
                                        <option value="16 JT - 25 JT">16 JT - 25 JT</option>
                                        <option value="26 JT - 35 JT">26 JT - 35 JT</option>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label><?= esc($t('member_list')); ?><span class="text-danger"><?= esc($t('required_mark')); ?></span></label>
                                <select name="anggota_id[]" class="form-control" id="anggota_id" multiple disabled>
                                    <?php foreach ($tags as $ds => $v_tags): ?>
                                        <?php if ($rekapan->laporan_id == $v_tags->laporan_id): ?>
                                            <option value="<?= $v_tags->user_id; ?>" selected>
                                                <?= $v_tags->user_name; ?>
                                            </option>
                                            <?= $v_tags->anggota_id; ?>
                                            <?php foreach ($dosen as $ds => $v_dosen): ?>
                                                <?php if ($v_dosen->role_id == 4): ?>
                                                    <option value="<?= $v_dosen->user_id; ?>">
                                                        <?= $v_dosen->user_name; ?>
                                                    </option>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label><?= esc($t('verify')); ?><span class="text-danger"><?= esc($t('required_mark')); ?></span></label>
                                <select name="verifikasi" class="form-control select2">
                                    <option selected disabled>&mdash;<?= esc($t('choose_verify')); ?>&mdash;</option>
                                    <?php if ($rekapan->verifikasi == 1): ?>
                                        <option value="<?= $rekapan->verifikasi; ?>" selected><?= esc($t('accept')); ?></option>
                                        <option value="0"><?= esc($t('process')); ?></option>
                                        <option value="2"><?= esc($t('revision')); ?></option>
                                    <?php elseif ($rekapan->verifikasi == 2): ?>
                                        <option value="<?= $rekapan->verifikasi; ?>" selected><?= esc($t('revision')); ?></option>
                                        <option value="0"><?= esc($t('process')); ?></option>
                                        <option value="1"><?= esc($t('accept')); ?></option>
                                    <?php elseif ($rekapan->verifikasi == 0): ?>
                                        <option value="<?= $rekapan->verifikasi; ?>" selected><?= esc($t('process')); ?></option>
                                        <option value="1"><?= esc($t('accept')); ?></option>
                                        <option value="2"><?= esc($t('revision')); ?></option>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <?php if ($rekapan->verifikasi != 1): ?>
                                <div class="form-group" id="boxrevisi">
                                    <label>
                                        <?= esc($t('revision_note_title')); ?>
                                        <small class="text-danger"></small>
                                        <span class="text-primary">
                                            <b><?= esc($t('note')); ?></b>
                                            <?= esc($t('note_fill_if_choose')); ?> <b><?= esc($t('note_revision')); ?></b>
                                            <?= esc($t('note_on_option')); ?> <b><?= esc($t('note_accept')); ?></b>
                                        </span>
                                    </label>
                                    <textarea name="revisi" class="form-control" style="height: 150px"><?= $rekapan->revisi; ?></textarea>
                                </div>
                            <?php endif; ?>

                            <div class="float-left">
                                <?php if ($rekapan->laporan != NULL): ?>
                                    <a href="<?= site_url('berkas/laporan/' . $rekapan->laporan); ?>" class="btn btn-info text-dark" target="_blank">
                                        <?= esc($t('view_report')); ?>
                                    </a>
                                <?php else: ?>
                                    <span class="btn btn-dark"><?= esc($t('no_report')); ?></span>
                                <?php endif; ?>

                                <?php if ($rekapan->bukti_kegiatan != NULL): ?>
                                    <a href="<?= site_url('berkas/kegiatan/' . $rekapan->bukti_kegiatan); ?>" class="btn btn-warning text-dark" target="_blank">
                                        <?= esc($t('view_evidence')); ?>
                                    </a>
                                <?php else: ?>
                                    <span class="btn btn-dark"><?= esc($t('no_evidence')); ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="text-right">
                                <a href="<?= site_url('rekapan'); ?>" class="btn btn-dark"><?= esc($t('back')); ?></a>
                                <button type="submit" class="btn btn-primary"><?= esc($t('save')); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
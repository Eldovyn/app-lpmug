<?= $this->extend('layouts/default') ?>

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

$TR = [
    'id' => [
        'dashboard'      => 'Dashboard',
        'lecturer_data'  => 'Data dosen',

        'success_title'  => 'Selamat!',
        'error_title'    => 'Warning Error!',
        'add_data'       => 'Tambah data',

        'th_name'        => 'Nama',
        'th_field'       => 'Bidang Ilmu',
        'th_action'      => 'Action',

        'delete_title'   => 'Hapus data?',
        'delete_msg'     => 'Apakah anda yakin?',
    ],
    'en' => [
        'dashboard'      => 'Dashboard',
        'lecturer_data'  => 'Lecturer Data',

        'success_title'  => 'Success!',
        'error_title'    => 'Warning!',
        'add_data'       => 'Add data',

        'th_name'        => 'Name',
        'th_field'       => 'Field of Study',
        'th_action'      => 'Action',

        'delete_title'   => 'Delete data?',
        'delete_msg'     => 'Are you sure?',
    ],
];

$t = static function (string $key) use ($TR, $lang): string {
    return $TR[$lang][$key] ?? $TR['id'][$key] ?? $key;
};

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
?>

<?= $this->section('title') ?>
<title><?= esc($title_tab); ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <h1><?= esc($title); ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="<?= site_url('dashboard'); ?>"><?= esc($t('dashboard')) ?></a></div>
            <div class="breadcrumb-item"><?= esc($title); ?></div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b><?= esc($t('success_title')) ?></b>
                <?= esc(session()->getFlashdata('success')); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b><?= esc($t('error_title')) ?></b>
                <?= esc(session()->getFlashdata('error')); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4 class="text-sm">
                    <a href="<?= site_url('dosen/new'); ?>" class="btn btn-primary">
                        <i class="fas fa-plus-circle mr-1"></i><?= esc($t('add_data')) ?>
                    </a>
                </h4>
            </div>

            <div class="card-body">
                <div class="table-responsive-md">
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Sinta ID</th>
                                <th><?= esc($t('th_name')) ?></th>
                                <th>NIDN</th>
                                <th><?= esc($t('th_field')) ?></th>
                                <th><?= esc($t('th_action')) ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $page = isset($_GET['page']) ? $_GET['page'] : 1;
                            $no = 1 + (10 * ($page - 1));
                            foreach ($dosen as $users => $v_dosen) : ?>
                                <?php if ($v_dosen->role_id == 4): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= esc($v_dosen->sinta_id); ?></td>
                                        <td><?= esc($v_dosen->gelar_dpn); ?> <?= esc(ucwords(strtolower($v_dosen->user_name))); ?>, <?= esc($v_dosen->gelar_blkng); ?></td>
                                        <td><?= esc($v_dosen->nidn); ?></td>
                                        <td class="text-wrap"><?= esc($v_dosen->fakultas_name); ?> - <?= esc($v_dosen->jurusan_name); ?></td>
                                        <td>
                                            <a href="<?= site_url('dosen/' . $v_dosen->user_id . '/edit'); ?>" class="btn btn-warning btn-sm">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>

                                            <form action="<?= site_url('dosen/' . $v_dosen->user_id); ?>" method="POST" class="d-inline" id="del-<?= esc($v_dosen->user_id); ?>">
                                                <?= csrf_field(); ?>
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button class="btn btn-danger btn-sm"
                                                    data-confirm="<?= esc($t('delete_title')) ?> | <?= esc($t('delete_msg')) ?>"
                                                    data-confirm-yes="submitDel(<?= (int) $v_dosen->user_id; ?>)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</section>
<?= $this->endSection() ?>
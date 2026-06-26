<?= $this->extend('layouts/default') ?>

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

// dictionary
$dict = [
    'id' => [
        'dashboard' => 'Dashboard',
        'mitra' => 'Mitra',
        'congrats' => 'Selamat!',
        'error' => 'Warning Error!',
        'register_mitra' => 'Daftarkan Mitra',
        'th_no' => '#',
        'th_nama_mitra' => 'Nama Mitra',
        'th_kontak' => 'Kontak',
        'th_kebutuhan' => 'Kebutuhan',
        'th_alamat' => 'Alamat',
        'th_action' => 'Action',
        'contact_person' => 'Contact Person',
        'email' => 'Email',
        'update' => 'Perbaharui',
        'delete_title' => 'Hapus data?',
        'delete_msg' => 'Apakah anda yakin?',
    ],
    'en' => [
        'dashboard' => 'Dashboard',
        'mitra' => 'Partner',
        'congrats' => 'Congratulations!',
        'error' => 'Warning Error!',
        'register_mitra' => 'Register Partner',
        'th_no' => '#',
        'th_nama_mitra' => 'Partner Name',
        'th_kontak' => 'Contact',
        'th_kebutuhan' => 'Needs',
        'th_alamat' => 'Address',
        'th_action' => 'Action',
        'contact_person' => 'Contact Person',
        'email' => 'Email',
        'update' => 'Update',
        'delete_title' => 'Delete data?',
        'delete_msg' => 'Are you sure?',
    ],
];

$t = $dict[$lang] ?? $dict['id'];

// Helper function for translation
function translateMitraText(string $text, string $source, string $target): string
{
    $text = trim($text);
    if ($text === '' || $source === $target) {
        return $text;
    }

    $key = $_ENV['apiKeyGoogleTranslateApi'] ?? null;
    if (! $key) {
        return $text;
    }

    $client = new \Google\Cloud\Translate\V2\TranslateClient(['key' => $key]);

    $result = $client->translate($text, [
        'source' => $source,
        'target' => $target,
    ]);

    return $result['text'] ?? $text;
}
?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <h1><?= esc($t['mitra']); ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="<?= site_url('dashboard'); ?>"><?= esc($t['dashboard']); ?></a></div>
            <div class="breadcrumb-item"><?= esc($t['mitra']); ?></div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b><?= esc($t['congrats']); ?></b>
                <?= session()->getFlashdata('success'); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b><?= esc($t['error']); ?></b>
                <?= session()->getFlashdata('error'); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4 class="text-sm">
                    <a href="<?= site_url('mitra/new'); ?>" class="btn btn-primary"><i class="fas fa-plus-circle mr-1"></i><?= esc($t['register_mitra']); ?></a>
                </h4>
            </div>
            <div class="card-body">
                <div class="table-responsive-md">
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th><?= esc($t['th_no']); ?></th>
                                <th><?= esc($t['th_nama_mitra']); ?></th>
                                <th><?= esc($t['th_kontak']); ?></th>
                                <th><?= esc($t['th_kebutuhan']); ?></th>
                                <th><?= esc($t['th_alamat']); ?></th>
                                <th class="text-center"><?= esc($t['th_action']); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $page = isset($_GET['page']) ? $_GET['page'] : 1;
                            $no = 1 + (10 * ($page - 1));

                            // Translate fields if EN
                            $translatedFields = [];
                            if ($lang === 'en') {
                                foreach ($mitra as $v_mitra) {
                                    if ($v_mitra->role_id == 5) {
                                        $keyProv = 'prov_mitra_' . md5($v_mitra->provinsi_name);
                                        $keyKota = 'kota_mitra_' . md5($v_mitra->kota_name);
                                        $keyKebutuhan = 'keb_mitra_' . md5($v_mitra->kebutuhan);
                                        $keyAlamat = 'alm_mitra_' . md5($v_mitra->alamat);

                                        // Translate provinsi
                                        $translatedProv = cache()->get($keyProv);
                                        if (!$translatedProv && $v_mitra->provinsi_name) {
                                            $translatedProv = translateMitraText($v_mitra->provinsi_name, 'id', 'en');
                                            cache()->save($keyProv, $translatedProv, 86400 * 30);
                                        }

                                        // Translate kota
                                        $translatedKota = cache()->get($keyKota);
                                        if (!$translatedKota && $v_mitra->kota_name) {
                                            $translatedKota = translateMitraText($v_mitra->kota_name, 'id', 'en');
                                            cache()->save($keyKota, $translatedKota, 86400 * 30);
                                        }

                                        // Translate kebutuhan
                                        $translatedKebutuhan = cache()->get($keyKebutuhan);
                                        if (!$translatedKebutuhan && $v_mitra->kebutuhan) {
                                            $translatedKebutuhan = translateMitraText($v_mitra->kebutuhan, 'id', 'en');
                                            cache()->save($keyKebutuhan, $translatedKebutuhan, 86400 * 30);
                                        }

                                        // Translate alamat
                                        $translatedAlamat = cache()->get($keyAlamat);
                                        if (!$translatedAlamat && $v_mitra->alamat) {
                                            $translatedAlamat = translateMitraText($v_mitra->alamat, 'id', 'en');
                                            cache()->save($keyAlamat, $translatedAlamat, 86400 * 30);
                                        }

                                        $translatedFields[$v_mitra->user_id] = [
                                            'provinsi' => $translatedProv ?: $v_mitra->provinsi_name,
                                            'kota' => $translatedKota ?: $v_mitra->kota_name,
                                            'kebutuhan' => $translatedKebutuhan ?: $v_mitra->kebutuhan,
                                            'alamat' => $translatedAlamat ?: $v_mitra->alamat,
                                        ];
                                    }
                                }
                            }

                            foreach ($mitra as $users => $v_mitra) : ?>
                                <?php if ($v_mitra->role_id == 5): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td class="text-wrap" style="max-width:200px;"><?= $v_mitra->user_name; ?></td>
                                        <td>
                                            <?= esc($t['contact_person']); ?>: +<?= $v_mitra->kontak; ?> <br>
                                            <?= esc($t['email']); ?>: <?= $v_mitra->email; ?>
                                        </td>
                                        <td class="text-wrap" style="max-width:200px;">
                                            <?php if ($lang === 'en' && isset($translatedFields[$v_mitra->user_id])): ?>
                                                <?= $translatedFields[$v_mitra->user_id]['kebutuhan']; ?>
                                            <?php else: ?>
                                                <?= $v_mitra->kebutuhan; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-wrap" style="max-width:200px;">
                                            <?php if ($lang === 'en' && isset($translatedFields[$v_mitra->user_id])): ?>
                                                <?= $translatedFields[$v_mitra->user_id]['provinsi']; ?> - <?= $translatedFields[$v_mitra->user_id]['kota']; ?> <br>
                                                <?= $translatedFields[$v_mitra->user_id]['alamat']; ?>
                                            <?php else: ?>
                                                <?= $v_mitra->provinsi_name; ?> - <?= $v_mitra->kota_name; ?> <br>
                                                <?= $v_mitra->alamat; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?= site_url('mitra/' . $v_mitra->user_id . '/edit'); ?>" class="btn btn-warning btn-sm m-1" style="width:100px;"><i class="fas fa-pencil-alt"></i> <?= esc($t['update']); ?></a><br>
                                            <?php if (userLogin()->role_id == 4): ?>
                                                <form action="<?= site_url('mitra/' . $v_mitra->user_id); ?>" method="POST" class="d-inline" id="del-<?= $v_mitra->user_id; ?>">
                                                    <?= csrf_field(); ?>
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button class="btn btn-danger btn-sm m-1" hidden style="width:100px;" data-confirm="<?= esc($t['delete_title']); ?> | <?= esc($t['delete_msg']); ?>" data-confirm-yes="submitDel(<?= $v_mitra->user_id; ?>)">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            <?php elseif (userLogin()->role_id != 4): ?>
                                                <form action="<?= site_url('mitra/' . $v_mitra->user_id); ?>" method="POST" class="d-inline" id="del-<?= $v_mitra->user_id; ?>">
                                                    <?= csrf_field(); ?>
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button class="btn btn-danger btn-sm m-1" style="width:100px;" data-confirm="<?= esc($t['delete_title']); ?> | <?= esc($t['delete_msg']); ?>" data-confirm-yes="submitDel(<?= $v_mitra->user_id; ?>)">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            <?php endif; ?>
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
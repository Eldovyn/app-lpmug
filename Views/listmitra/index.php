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
        'search' => 'Pencarian...',
        'th_no' => '#',
        'th_nama_mitra' => 'Nama mitra',
        'th_username' => 'Username',
        'th_kontak' => 'Kontak',
        'th_alamat' => 'Alamat',
        'contact_person' => 'Contact Person',
        'email' => 'Email',
        'showing' => 'Showing',
        'to' => 'to',
        'of' => 'of',
        'entries' => 'entries',
    ],
    'en' => [
        'dashboard' => 'Dashboard',
        'mitra' => 'Partner',
        'congrats' => 'Congratulations!',
        'error' => 'Warning Error!',
        'register_mitra' => 'Register Partner',
        'search' => 'Search...',
        'th_no' => '#',
        'th_nama_mitra' => 'Partner Name',
        'th_username' => 'Username',
        'th_kontak' => 'Contact',
        'th_alamat' => 'Address',
        'contact_person' => 'Contact Person',
        'email' => 'Email',
        'showing' => 'Showing',
        'to' => 'to',
        'of' => 'of',
        'entries' => 'entries',
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
                    <a href="<?= site_url('listmitra/new'); ?>" class="btn btn-primary"><i class="fas fa-plus-circle mr-1"></i><?= esc($t['register_mitra']); ?></a>
                </h4>
                <div class="card-header-form">
                    <form action="" method="GET" autocomplete="off">
                        <div class="input-group">
                            <input name="keyword" value="<?= $keyword; ?>" type="text" class="form-control" placeholder="<?= esc($t['search']); ?>">
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
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
                                <th><?= esc($t['th_no']); ?></th>
                                <th><?= esc($t['th_nama_mitra']); ?></th>
                                <th><?= esc($t['th_username']); ?></th>
                                <th><?= esc($t['th_kontak']); ?></th>
                                <th><?= esc($t['th_alamat']); ?></th>
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

                                        // Translate alamat
                                        $translatedAlamat = cache()->get($keyAlamat);
                                        if (!$translatedAlamat && $v_mitra->alamat) {
                                            $translatedAlamat = translateMitraText($v_mitra->alamat, 'id', 'en');
                                            cache()->save($keyAlamat, $translatedAlamat, 86400 * 30);
                                        }

                                        $translatedFields[$v_mitra->user_id] = [
                                            'provinsi' => $translatedProv ?: $v_mitra->provinsi_name,
                                            'kota' => $translatedKota ?: $v_mitra->kota_name,
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
                                        <td><?= $v_mitra->nidn; ?></td>
                                        <td>
                                            <?= esc($t['contact_person']); ?>: +<?= $v_mitra->kontak; ?> <br>
                                            <?= esc($t['email']); ?>: <?= $v_mitra->email; ?>
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
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="float-left">
                        <i><?= esc($t['showing']); ?> <?= 1 + (10 * ($page - 1)); ?> <?= esc($t['to']); ?> <?= $no - 1; ?> <?= esc($t['of']); ?> <?= countDataMitra(); ?> <?= esc($t['entries']); ?></i>
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
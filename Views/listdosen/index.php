<?= $this->extend('layouts/default') ?>

<?php
helper(['cookie', 'url']);

$request = service('request');

// bahasa aktif
$allowed = ['id', 'en'];
$lang    = get_cookie('lang') ?: 'id';
if (! in_array($lang, $allowed, true)) {
    $lang = 'id';
}

$reqLang = $request->getGet('lang');
if ($reqLang && in_array($reqLang, $allowed, true)) {
    set_cookie('lang', $reqLang, 60 * 60 * 24 * 30);
    $lang = $reqLang;
}

// dictionary
$dict = [
    'id' => [
        'dashboard' => 'Dashboard',
        'list_dosen' => 'List Dosen',
        'congrats' => 'Selamat!',
        'error' => 'Warning Error!',
        'th_no' => '#',
        'th_sinta_id' => 'Sinta ID',
        'th_nama' => 'Nama',
        'th_nidn' => 'NIDN',
        'th_bidang_ilmu' => 'Bidang Ilmu',
    ],
    'en' => [
        'dashboard' => 'Dashboard',
        'list_dosen' => 'Lecturer List',
        'congrats' => 'Congratulations!',
        'error' => 'Warning Error!',
        'th_no' => '#',
        'th_sinta_id' => 'Sinta ID',
        'th_nama' => 'Name',
        'th_nidn' => 'NIDN',
        'th_bidang_ilmu' => 'Field of Study',
    ],
];

$t = $dict[$lang] ?? $dict['id'];

// Helper function for translation
function translateText(string $text, string $source, string $target): string
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
        <h1><?= $title; ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="<?= site_url('dashboard'); ?>"><?= esc($t['dashboard']); ?></a></div>
            <div class="breadcrumb-item"><?= $title; ?></div>
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
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th><?= esc($t['th_no']); ?></th>
                                <th><?= esc($t['th_sinta_id']); ?></th>
                                <th><?= esc($t['th_nama']); ?></th>
                                <th><?= esc($t['th_nidn']); ?></th>
                                <th><?= esc($t['th_bidang_ilmu']); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $page = isset($_GET['page']) ? $_GET['page'] : 1;
                            $no = 1 + (10 * ($page - 1));

                            // Translate fakultas/jurusan names if EN
                            $translatedFields = [];
                            if ($lang === 'en') {
                                foreach ($dosen as $v_dosen) {
                                    if ($v_dosen->role_id == 4) {
                                        $keyFak = 'fak_' . md5($v_dosen->fakultas_name);
                                        $keyJur = 'jur_' . md5($v_dosen->jurusan_name);

                                        // Check cache for fakultas
                                        $translatedFak = cache()->get($keyFak);
                                        if (!$translatedFak) {
                                            $translatedFak = translateText($v_dosen->fakultas_name ?? '', 'id', 'en');
                                            cache()->save($keyFak, $translatedFak, 86400 * 30);
                                        }

                                        // Check cache forjurusan
                                        $translatedJur = cache()->get($keyJur);
                                        if (!$translatedJur) {
                                            $translatedJur = translateText($v_dosen->jurusan_name ?? '', 'id', 'en');
                                            cache()->save($keyJur, $translatedJur, 86400 * 30);
                                        }

                                        $translatedFields[$v_dosen->user_id] = [
                                            'fakultas' => $translatedFak ?: $v_dosen->fakultas_name,
                                            'jurusan' => $translatedJur ?: $v_dosen->jurusan_name
                                        ];
                                    }
                                }
                            }

                            foreach ($dosen as $users => $v_dosen) : ?>
                                <?php if ($v_dosen->role_id == 4): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $v_dosen->sinta_id; ?></td>
                                        <td><?= ucwords(strtolower($v_dosen->user_name)); ?></td>
                                        <td><?= $v_dosen->nidn; ?></td>
                                        <td class="text-wrap" style="max-width:200px;">
                                            <?php if ($lang === 'en' && isset($translatedFields[$v_dosen->user_id])): ?>
                                                <?= $translatedFields[$v_dosen->user_id]['fakultas']; ?> - <?= $translatedFields[$v_dosen->user_id]['jurusan']; ?>
                                            <?php else: ?>
                                                <?= $v_dosen->fakultas_name; ?> - <?= $v_dosen->jurusan_name; ?>
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
<?php
// Logika bahasa: query param > cookie > default
helper('cookie');
$request = service('request');
$langParam = $request->getGet('lang');
$langCookie = get_cookie('lang');

if ($langParam && in_array($langParam, ['id', 'en'], true)) {
    $lang = $langParam;
    set_cookie('lang', $lang, 60 * 60 * 24 * 30);
} elseif ($langCookie && in_array($langCookie, ['id', 'en'], true)) {
    $lang = $langCookie;
} else {
    $lang = 'id';
}

// Translation
$tr = [
    'id' => [
        'search_placeholder' => 'Pencarian...',
        'search_btn' => 'Cari',
        'partner_name' => 'Nama Mitra',
        'address' => 'Alamat',
        'showing' => 'Menampilkan',
        'to' => 'sampai',
        'of' => 'dari',
        'entries' => 'entri',
    ],
    'en' => [
        'search_placeholder' => 'Search...',
        'search_btn' => 'Search',
        'partner_name' => 'Partner Name',
        'address' => 'Address',
        'showing' => 'Showing',
        'to' => 'to',
        'of' => 'of',
        'entries' => 'entries',
    ],
];
$t = fn(string $key): string => $tr[$lang][$key] ?? ($tr['id'][$key] ?? $key);
?>

<?= $this->extend('layouts/default_section') ?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>
<?= $this->endSection() ?>

<!-- Render Content untuk Mitra Section -->
<?= $this->section('content') ?>
<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-4">
            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                        <div class="card-header-form">
                            <form action="" method="GET" autocomplete="off">
                                <div class="input-group">
                                    <input name="keyword" value="<?= $keyword; ?>" type="text" class="form-control" placeholder="<?= $t('search_placeholder'); ?>" style="width:150px;">
                                    <div class="input-group-btn">
                                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> <?= $t('search_btn'); ?></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive-md">
                            <table class="table table-striped" id="table1">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><?= $t('partner_name'); ?></th>
                                        <th><?= $t('address'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                                    $no = 1 + (10 * ($page - 1));
                                    foreach ($mitra as $users => $v_mitra) : ?>
                                        <?php if ($v_mitra->role_id == 5): ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td class="text-wrap" style="max-width:200px;"><?= $v_mitra->user_name; ?></td>
                                                <td class="text-wrap" style="max-width:200px;">
                                                    <?= $v_mitra->provinsi_name; ?> - <?= $v_mitra->kota_name; ?> <br>
                                                    <?= $v_mitra->alamat; ?>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <div class="float-start">
                                <i>Showing <?= 1 + (10 * ($page - 1)); ?> to <?= $no - 1; ?> of <?= countDataMitra(); ?> entries</i>
                            </div>
                            <div class="float-end">
                                <?= $pager->links('default', 'pagination'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?php
// Ambil bahasa dari query (?lang=id/en) atau cookie (lang=id/en) atau fallback ke 'id'
helper('cookie');
$request = service('request');

$supported = ['id', 'en'];

$langParam  = $request->getGet('lang');
$langCookie = get_cookie('lang');

// Prioritas: query param > cookie > default
if ($langParam && in_array($langParam, $supported, true)) {
    $lang = $langParam;
    set_cookie('lang', $lang, 60 * 60 * 24 * 30);
} elseif ($langCookie && in_array($langCookie, $supported, true)) {
    $lang = $langCookie;
} else {
    $lang = 'id';
}

// Translation
$tr = [
    'id' => [
        'back' => 'Kembali',
        'struktur' => 'Struktur',
        'galeri' => 'Galeri',
        'mitra' => 'Mitra',
        'kalender' => 'Kalender',
        'kontak' => 'Kontak',
    ],
    'en' => [
        'back' => 'Back',
        'struktur' => 'Structure',
        'galeri' => 'Gallery',
        'mitra' => 'Partners',
        'kalender' => 'Calendar',
        'kontak' => 'Contact',
    ],
];
$t = fn(string $key): string => $tr[$lang][$key] ?? ($tr['id'][$key] ?? $key);

// Translate title if it's a known page
$titleKey = strtolower($title ?? '');
$translatedTitle = isset($tr[$lang][$titleKey]) ? $tr[$lang][$titleKey] : ($title ?? '');
?>

<div class="container-fluid page-header py-5 mb-5 wow fadeIn" data-wow-delay="0.1s">
    <div class="container py-5">
        <h1 class="display-4 animated slideInDown mb-4 text-dark"><?= $translatedTitle; ?></h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= site_url(); ?>" class="text-info"><?= $t('back'); ?></a></li>
                <li class="breadcrumb-item text-light"><?= $translatedTitle; ?></li>
                </li>
            </ol>
        </nav>
    </div>
</div>
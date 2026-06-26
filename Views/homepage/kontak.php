<?php
// ============================
// ID/EN support langsung di view (tanpa ubah UI/UX)
// Locale diambil dari:
// 1) URL segment pertama kalau "id" / "en" (mis: /id/kontak, /en/kontak)
// 2) fallback ke cookie (dari controller)
// 3) fallback ke default 'id'
// ============================

helper('cookie');
$request = service('request');
$uri     = service('uri');

$supported = ['id', 'en'];
$seg1 = (string) $uri->getSegment(1);
$langCookie = get_cookie('lang');

// Prioritas: URL segment > cookie > default
if (in_array($seg1, $supported, true)) {
    $locale = $seg1;
} elseif (isset($lang) && in_array($lang, $supported, true)) {
    $locale = $lang;
} elseif (in_array($langCookie, $supported, true)) {
    $locale = $langCookie;
} else {
    $locale = 'id';
}

// Dictionary terjemahan
$tr = [
    'id' => [
        'heading'       => 'Jika anda memiliki pertanyaan, Silahkan hubungi kami.',
        'operational'   => 'Waktu Operasional <b>Senin - Jumat</b> Pukul <b>09.00 - 17.00</b> WIB.',
        'holiday_off'   => '<b>Hari libur nasional</b> dan <b>Libur hari raya </b> kami <b class="text-danger">OFF</b>.',
        'important'     => 'Jika memiliki pertanyaan yang sangat penting silahkan hubungi nomor whatsapp berikut:',
        'wa_text'       => 'Selamat Pagi/Siang/Sore Saya memiliki sebuah pertanyaan, dapatkah anda membantu saya?',
        'wa_label'      => 'Whatsapp Kami',
        'success_title' => 'Berhasil!',
        'title_default' => 'Kontak',
        'title'         => 'Hubungi kami',
    ],
    'en' => [
        'heading'       => 'If you have any questions, please contact us.',
        'operational'   => 'Operating Hours <b>Monday - Friday</b> <b>09:00 - 17:00</b> WIB.',
        'holiday_off'   => 'We are <b class="text-danger">OFF</b> on <b>national holidays</b> and <b>religious holidays</b>.',
        'important'     => 'For urgent questions, please contact us via WhatsApp:',
        'wa_text'       => 'Good morning/afternoon/evening, I have a question. Could you help me?',
        'wa_label'      => 'WhatsApp Us',
        'success_title' => 'Congratulations!',
        'title_default' => 'Contact',
        'title'         => 'Contact us',
    ],
];

$t = fn(string $key): string => $tr[$locale][$key] ?? ($tr['id'][$key] ?? $key);

// WhatsApp URL (tetap 1 link, hanya text-nya menyesuaikan bahasa)
$waNumber = '628179826969';
$waUrl = 'https://wa.me/' . $waNumber . '?text=' . rawurlencode($t('wa_text'));

// Title fallback (kalou controller tidak kirim $title_tab)
if (!isset($title_tab) || trim((string) $title_tab) === '') {
    $title_tab = $t('title');
}
?>

<?= $this->extend('layouts/default_section') ?>

<?= $this->section('title') ?>
<title><?= esc($title_tab); ?></title>
<?= $this->endSection() ?>

<!-- Render Content untuk Kontak Section -->
<?= $this->section('content') ?>
<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                <h1 class="display-6 mb-5">
                    <?= esc($t('heading')); ?>
                </h1>
                <p class="mb-4">
                    <?= $t('operational'); ?>
                    <?= $t('holiday_off'); ?>
                    <?= esc($t('important')); ?>
                    <a href="<?= esc($waUrl); ?>"><?= esc($t('wa_label')); ?></a>.
                </p>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible show fade">
                        <div class="alert-body">
                            <b><?= esc($t('success_title')); ?></b>
                            <?= esc(session()->getFlashdata('success')); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div
                class="col-lg-6 wow fadeIn"
                data-wow-delay="0.5s"
                style="min-height: 450px">
                <div class="position-relative rounded overflow-hidden h-100">
                    <iframe
                        class="position-relative w-100 h-100"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.3085157401724!2d106.8389983738978!3d-6.3540930621605956!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69ec475427cefd%3A0xc4e7eee0f871687!2sUniversitas%20Gunadarma%20Kampus%20E!5e0!3m2!1sen!2sid!4v1696335667683!5m2!1sen!2sid"
                        frameborder="0"
                        style="min-height: 450px; border: 0"
                        allowfullscreen=""
                        aria-hidden="false"
                        tabindex="0"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<!-- END Render Content untuk Kontak Section -->
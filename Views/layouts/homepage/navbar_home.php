<?php
// Ambil bahasa dari query (?lang=id/en) atau cookie (lang=id/en)
helper('cookie');
$request = service('request');

$supported = ['id', 'en'];

$langParam  = $request->getGet('lang');
$langCookie = get_cookie('lang');

// Debug: jika cookie tidak ada atau bukan nilai yang didukung, default ke 'id'
if ($langParam && in_array($langParam, $supported, true)) {
    $lang = $langParam;
    set_cookie('lang', $lang, 60 * 60 * 24 * 30);
} elseif ($langCookie && in_array($langCookie, $supported, true)) {
    $lang = $langCookie;
} else {
    $lang = 'id';
}
?>

<nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top px-4 px-lg-5">
    <a href="<?= site_url(); ?>" class="navbar-brand d-flex align-items-center">
        <h2 class="m-0">
            ISee<span class="text-primary">MonevIn</span>
        </h2>
    </a>
    <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav mx-auto bg-light rounded pe-4 py-3 py-lg-0">
            <a href="<?= site_url(); ?>" class="nav-item nav-link">
                <?= $lang === 'en' ? 'Home' : 'Beranda' ?>
            </a>

            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    Profile
                </a>
                <div class="dropdown-menu bg-light border-0 m-0">
                    <a href="#about" class="dropdown-item">
                        <?= $lang === 'en' ? 'LPM UG Profile' : 'Profile LPM UG' ?>
                    </a>
                    <a href="#staff" class="dropdown-item">
                        <?= $lang === 'en' ? 'Staff Profile' : 'Profile Staff' ?>
                    </a>
                </div>
            </div>

            <a href="<?= site_url('home/struktur'); ?>" class="nav-item nav-link">
                <?= $lang === 'en' ? 'Structure' : 'Struktur' ?>
            </a>

            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <?= $lang === 'en' ? 'Community Service' : 'Pengabdian' ?>
                </a>
                <div class="dropdown-menu bg-light border-0 m-0">
                    <a href="<?= site_url('home/mitra'); ?>" class="dropdown-item">
                        <?= $lang === 'en' ? 'Partners' : 'Mitra' ?>
                    </a>
                    <a href="<?= site_url('home/kalender'); ?>" class="dropdown-item">
                        <?= $lang === 'en' ? 'Activity Schedule' : 'Jadwal Kegiatan' ?>
                    </a>
                </div>
            </div>

            <a href="<?= site_url('home/galeri'); ?>" class="nav-item nav-link">
                <?= $lang === 'en' ? 'Gallery' : 'Galeri' ?>
            </a>

            <a href="#faqSection" class="nav-item nav-link">
                <?= $lang === 'en' ? 'FAQ' : 'FAQ' ?>
            </a>

            <a href="<?= site_url('home/kontak'); ?>" class="nav-item nav-link">
                <?= $lang === 'en' ? 'Contact' : 'Kontak' ?>
            </a>

            <a href="https://ejournal.gunadarma.ac.id/index.php/abdimas" target="__blank" class="nav-item nav-link">
                <?= $lang === 'en' ? 'Journal' : 'Jurnal' ?>
            </a>
        </div>
    </div>

    <a href="<?= site_url('login'); ?>" class="btn btn-dark mx-1 px-3">
        <?= $lang === 'en' ? 'Login' : 'Masuk' ?>
    </a>
    <a href="<?= site_url('registrasi'); ?>" class="btn btn-primary mx-1 px-3">
        <?= $lang === 'en' ? 'Register' : 'Mendaftar' ?>
    </a>
</nav>
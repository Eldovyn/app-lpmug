<?php
// Ambil bahasa dari query (?lang=id/en) atau cookie (lang=id/en)
helper('cookie');
$request = service('request');

$supported = ['id', 'en'];

$langParam  = $request->getGet('lang');   // ambil dari $_GET :contentReference[oaicite:2]{index=2}
$langCookie = get_cookie('lang');         // ambil dari cookie helper :contentReference[oaicite:3]{index=3}

if (in_array($langParam, $supported, true)) {
    $lang = $langParam;

    // Simpan pilihan ke cookie supaya next page tetap konsisten
    // expire: detik (contoh 1 tahun)
    set_cookie('lang', $lang, 60 * 60 * 24 * 365); // cookie helper :contentReference[oaicite:4]{index=4}
} elseif (in_array($langCookie, $supported, true)) {
    $lang = $langCookie;
} else {
    $lang = 'id';
}
?>

<div class="container py-5">
    <div class="row g-5">
        <div class="col-lg-3 col-md-6">
            <h1 class="text-white mb-4">
                <img class="img-fluid me-3 w-25" src="<?= base_url('template/assets/img/logo-gunadarma.png') ?>" alt="" />LPM UG
            </h1>
            <p>
                <?= $lang === 'en'
                    ? 'A community service institute that helps the surrounding community develop their businesses.'
                    : 'Lembaga pengabdian masyarakat yang membantu masyarakat sekitar dalam mengambangkan usaha mereka.' ?>
            </p>
            <div class="d-flex pt-2">
                <a class="btn btn-square me-1" href="">
                    <i class="fab fa-twitter"></i>
                </a>
                <a class="btn btn-square me-1" href="">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a class="btn btn-square me-1" href="">
                    <i class="fab fa-youtube"></i>
                </a>
                <a class="btn btn-square me-0" href="">
                    <i class="fab fa-linkedin-in"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <h5 class="text-light mb-4">
                <?= $lang === 'en'
                    ? 'Address'
                    : 'Alamat' ?>
            </h5>
            <p>
                <i class="fa fa-map-marker-alt me-3"></i>
                <?= $lang === 'en'
                    ? 'Campus E - Gunadarma University, Building 4, Room E411, Kelapa Dua, Depok City, West Java'
                    : 'Kampus E - Universitas Gunadarma, Gedung 4, Ruang E411, Kelapa Dua, Kota Depok, Jawa Barat' ?>
            </p>
            <p>
                <i class="far fa-clock me-3"></i>
                <?= $lang === 'en'
                    ? "Monday - Friday : <br> 09:00 AM - 05:00 PM"
                    : "Senin - Jum'at : <br> 09:00 AM - 17:00 PM" ?>
            </p>
            <p>
                <i class="fa fa-phone-alt me-3"></i>
                (021) 87 27541
            </p>
            <p>
                <i class="fa fa-envelope me-3"></i>
                abdimasug@gmail.com
            </p>
        </div>

        <div class="col-lg-3 col-md-6">
            <h5 class="text-light mb-4">Sitemap</h5>
            <a class="btn btn-link" href="#about">
                <?= $lang === 'en'
                    ? 'About LPM UG'
                    : 'Tentang LPM UG' ?>
            </a>
            <a class="btn btn-link" href="#staff">
                <?= $lang === 'en'
                    ? 'LPM UG Staff'
                    : 'Staff LPM UG' ?>
            </a>
            <a class="btn btn-link" href="<?= site_url('home/galeri'); ?>">
                <?= $lang === 'en'
                    ? 'Gallery'
                    : 'Galeri' ?>
            </a>
            <a class="btn btn-link" href="#testimoni">
                <?= $lang === 'en'
                    ? 'Testimonials'
                    : 'Testimoni' ?>
            </a>
            <a class="btn btn-link" href="<?= site_url('login'); ?>">
                <?= $lang === 'en'
                    ? 'Login'
                    : 'Login' ?>
            </a>
            <a class="btn btn-link" href="<?= site_url('registrasi'); ?>">
                <?= $lang === 'en'
                    ? 'Register'
                    : 'Registrasi' ?>
            </a>
        </div>

        <div class="col-lg-3 col-md-6">
            <h5 class="text-light mb-4">
                <?= $lang === 'en'
                    ? 'Join Us'
                    : 'Bergabung' ?>
            </h5>
            <p>
                <?= $lang === 'en'
                    ? 'Empower local MSMEs with your good intentions. Register yourself to help advance Indonesia’s economy.'
                    : 'Sejahterakan UMKM disekitar dengan niat baik anda. Ayo daftarkan diri anda untuk ikut berkontribusi memajukan ekonomi indonesia.' ?>
            </p>
            <div class="position-relative mx-auto" style="max-width: 400px">
                <a href="<?= site_url('registrasi'); ?>" class="btn btn-primary px-5">
                    <?= $lang === 'en'
                        ? 'Register'
                        : 'Mendaftar' ?>
                </a>
            </div>
        </div>
    </div>
</div>
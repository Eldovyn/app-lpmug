<?php
$allowedLangs = ['id', 'en'];
$defaultLang  = 'id';

if (isset($_GET['lang']) && in_array($_GET['lang'], $allowedLangs, true)) {
    $lang = $_GET['lang'];
    setcookie('lang', $lang, [
        'path'     => '/',                       // berlaku semua page
        'secure'   => !empty($_SERVER['HTTPS']),
        'httponly' => false,
        'samesite' => 'Lax',
    ]);
} else {
    $lang = $_COOKIE['lang'] ?? $defaultLang;
    if (!in_array($lang, $allowedLangs, true)) {
        $lang = $defaultLang;
    }
}
?>

<div class="container-fluid bg-dark text-white-50 py-2 px-0 d-none d-lg-block">
    <div class="row gx-0 align-items-center">
        <div class="col-lg-7 px-5 text-start">
            <div class="h-100 d-inline-flex align-items-center me-4">
                <small class="fa fa-phone-alt me-2"></small>
                <small>(021) 87 27541</small>
            </div>
            <div class="h-100 d-inline-flex align-items-center me-4">
                <small class="far fa-envelope-open me-2"></small>
                <small>abdimasug@gmail.com</small>
            </div>
            <div class="h-100 d-inline-flex align-items-center me-4">
                <small class="far fa-clock me-2"></small>
                <small>
                    <?= $lang === 'en'
                        ? "Monday - Friday : 09:00 AM - 05:00 PM"
                        : "Senin - Jum'at : 09:00 AM - 17:00 PM" ?>
                </small>
            </div>
        </div>
        <div class="col-lg-5 px-5 text-end">
            <div class="h-100 d-inline-flex align-items-center">
                <div class="d-inline-flex align-items-center">
                    <a href="?lang=id" class="text-white-50 text-decoration-none me-2">ID</a>
                    <span class="text-white-50">|</span>
                    <a href="?lang=en" class="text-white-50 text-decoration-none ms-2">EN</a>
                </div>
                <a class="text-white-50 ms-4" href=""><i class="fab fa-facebook-f"></i></a>
                <a class="text-white-50 ms-4" href=""><i class="fab fa-twitter"></i></a>
                <a class="text-white-50 ms-4" href=""><i class="fab fa-linkedin-in"></i></a>
                <a class="text-white-50 ms-4" href=""><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </div>
</div>
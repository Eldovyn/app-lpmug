<?php
// Logika bahasa: query param > cookie > default
helper('cookie');
$request = service('request');
$langParam = $request->getGet('lang');
$langCookie = get_cookie('lang');

// Prioritas: query param > cookie > default 'id'
if ($langParam && in_array($langParam, ['id', 'en'], true)) {
    $lang = $langParam;
    set_cookie('lang', $lang, 60 * 60 * 24 * 30);
} elseif ($langCookie && in_array($langCookie, ['id', 'en'], true)) {
    $lang = $langCookie;
} else {
    $lang = 'id';
}
?>

<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="position-relative overflow-hidden rounded ps-5 pt-5 h-100" style="min-height: 400px">
                    <img class="position-absolute w-100 h-100" src="<?= base_url(); ?>img/about2.jpg" alt="" style="object-fit: cover" />
                    <div class="position-absolute top-0 start-0 bg-white rounded pe-3 pb-3" style="width: 200px; height: 200px">
                        <div class="d-flex flex-column justify-content-center text-center bg-primary rounded h-100 p-3">
                            <h4 class="text-white mb-0">
                                <?= $lang === 'en'
                                    ? 'Institution'
                                    : 'Lembaga' ?>
                            </h4>
                            <h4 class="text-white">
                                <?= $lang === 'en'
                                    ? 'Community Service'
                                    : 'Pengabdian Masyarakat' ?>
                            </h4>
                            <p class="text-white mb-0">
                                <?= $lang === 'en'
                                    ? 'Gunadarma University'
                                    : 'Universitas Gunadarma' ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.5s">
                <div class="h-100">
                    <h1 class="display-6 mb-5">
                        <?= $lang === 'en'
                            ? 'Community Service Institute of Gunadarma University'
                            : 'Lembaga Pengabdian Masyarakat Universitas Gunadarma' ?>
                    </h1>

                    <p class="fs-5 text-primary mb-4">
                        <?= $lang === 'en'
                            ? 'About LPM UG Website'
                            : 'Tentang Situs LPM UG' ?>
                    </p>

                    <div class="row g-4 mb-4">
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <img class="flex-shrink-0 me-3" src="<?= base_url(); ?>img/icon/icon-04-primary.png" alt="" />
                                <h5 class="mb-0">
                                    <?= $lang === 'en'
                                        ? 'Collaborating with MSMEs'
                                        : 'Bekerja Sama dengan UMKM' ?>
                                </h5>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <img class="flex-shrink-0 me-3" src="<?= base_url(); ?>img/icon/icon-03-primary.png" alt="" />
                                <h5 class="mb-0">
                                    <?= $lang === 'en'
                                        ? 'Supporting MSME actors'
                                        : 'Membantu pelaku UMKM' ?>
                                </h5>
                            </div>
                        </div>
                    </div>

                    <p class="mb-4">
                        <?= $lang === 'en'
                            ? 'This Community Service Institute website of Gunadarma University is built with the main goal of becoming an effective communication medium between students, lecturers, administration, parents, alumni, and industry with the community, thus maintaining continuous communication.'
                            : 'Situs Lembaga Pengabdian kepada masyarakat Universitas Gunadarma ini dibangun dengan tujuan utama menjadi media komunikasi yang efektif antar mahasiswa, dosen, administrasi, orang tua, alumni, dan industri kepada masyarakat sehingga akan terjalin komunikasi yang berkesinambungan.' ?>
                    </p>

                    <div class="border-top mt-4 pt-4">
                        <div class="d-flex align-items-center">
                            <img class="flex-shrink-0 rounded-circle me-3" src="<?= base_url(); ?>img/profile.jpg" alt="" />
                            <h5 class="mb-0">
                                <?= $lang === 'en'
                                    ? 'Contact us: (021) 87 27541'
                                    : 'Hubungi kami: (021) 87 27541' ?>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
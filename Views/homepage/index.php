<?= $this->extend('layouts/default_home') ?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>
<?= $this->endSection() ?>

<?php
// Logika bahasa: query param > cookie > default
helper('cookie');
$request = service('request');
$langParam = $request->getGet('lang');
$langCookie = get_cookie('lang');

// Prioritas: query param > cookie > default 'id'
if ($langParam && in_array($langParam, ['id', 'en'], true)) {
    // Ada query param, simpan ke cookie
    $lang = $langParam;
    set_cookie('lang', $lang, 60 * 60 * 24 * 30);
} elseif ($langCookie && in_array($langCookie, ['id', 'en'], true)) {
    // Cookie ada dan valid
    $lang = $langCookie;
} else {
    // Default
    $lang = 'id';
}

/**
 * FAQ items dengan 2 kategori
 */
$faqCategories = [
    'id' => [
        [
            'category' => 'FAQ Umum & Teknis',
            'items' => [
                [
                    'q' => 'Apa itu LPM Universitas Gunadarma?',
                    'a' => 'LPM (Lembaga Pengabdian kepada Masyarakat) Universitas Gunadarma merupakan unit yang mengelola, mengoordinasikan, dan memfasilitasi kegiatan pengabdian kepada masyarakat yang dilakukan oleh sivitas akademika Universitas Gunadarma bersama mitra masyarakat.'
                ],
                [
                    'q' => 'Apa saja program yang LPM UG tawarkan?',
                    'a' => 'LPM Universitas Gunadarma menyelenggarakan program Pengabdian kepada Masyarakat (PKM), pendampingan mitra/UMKM, pelatihan dan pengembangan masyarakat, kerja sama dengan lembaga ataupun komunitas.'
                ],
                [
                    'q' => 'Bagaimana cara menghubungi LPM UG?',
                    'a' => 'LPM Universitas Gunadarma dapat dihubungi melalui:<br>• Alamat: Kampus E Universitas Gunadarma, Gedung 4, Ruang LPM, Depok<br>• Telepon: +62 877-7938-7663 (WhatsApp)<br>• Email: lpmunivgunadarma@gmail.com<br>• Jam operasional: Senin–Sabtu, 09.00–15.00 WIB'
                ],
                [
                    'q' => 'Bagaimana cara mendaftarkan akun?',
                    'a' => 'Pendaftaran akun dilakukan melalui menu Registrasi dengan memilih jenis akun (Dosen atau Mitra), mengisi data yang dibutuhkan, membuat username dan password, lalu menunggu proses verifikasi.',
                    'video' => 'TUTORIAL_PENDAFTARAN_URL'
                ],
                [
                    'q' => 'Mengapa saya tidak bisa login ke akun saya?',
                    'a' => 'Kemungkinan akun belum diverifikasi, data login tidak sesuai, atau akun mengalami kendala teknis. Pastikan username dan password sudah benar.'
                ],
                [
                    'q' => 'Jika akun bermasalah, adakah kontak yang bisa dihubungi?',
                    'a' => 'Jika mengalami kendala akun atau masalah teknis lainnya, pengguna dapat menghubungi admin LPM Universitas Gunadarma melalui email lpm@gunadarma.ac.id atau melalui nomor +62 877-7938-7663 (WhatsApp) pada jam operasional.'
                ],
            ]
        ],
        [
            'category' => 'FAQ PKM (Pengabdian kepada Masyarakat)',
            'items' => [
                [
                    'q' => 'Siapa saja yang bisa mendaftar untuk mengikuti PKM Universitas Gunadarma?',
                    'a' => 'Program PKM dapat diikuti oleh dosen Universitas Gunadarma sebagai pelaksana kegiatan dan mitra masyarakat/UMKM sebagai mitra pengabdian. Mahasiswa dapat terlibat sesuai ketentuan yang berlaku.'
                ],
                [
                    'q' => 'Bagaimana cara saya menjadi mitra untuk kerjasama?',
                    'a' => 'Calon mitra dapat mendaftarkan diri melalui website LPM Universitas Gunadarma dengan membuat akun mitra dan melengkapi data yang diperlukan. Setelah diverifikasi, tim LPM akan menghubungi mitra untuk tindak lanjut kerja sama.'
                ],
                [
                    'q' => 'Di mana saya dapat melihat mitra yang terdaftar?',
                    'a' => 'Daftar mitra yang telah terdaftar dapat dilihat pada menu Mitra di website resmi LPM Universitas Gunadarma.',
                    'video' => 'TUTORIAL_SISTEM_LPM_URL'
                ],
                [
                    'q' => 'Bagaimana alur atau tahapan kegiatan PKM di LPM Universitas Gunadarma?',
                    'a' => 'Tahapan kegiatan PKM bagi pihak Dosen meliputi:<br>1. Pengusulan<br>2. Pelaksanaan<br>3. Monitoring & Evaluasi (MonEv)<br>4. Pelaporan<br><br>Sedangkan bagi pihak Mitra meliputi:<br>1. Surat permohonan Mitra (SPM)<br>2. Surat balasan<br>3. Surat keterangan Mitra (SKM)',
                    'video' => 'TUTORIAL_SISTEM_LPM_URL'
                ],
                [
                    'q' => 'Berapa lama proses verifikasi akun dan pengajuan kegiatan?',
                    'a' => 'Lama proses verifikasi dan pengajuan kegiatan menyesuaikan dengan kelengkapan data dan periode kegiatan yang sedang berjalan.'
                ],
                [
                    'q' => 'Apakah ada ketentuan jumlah anggota dalam satu tim dan jumlah mitra dalam kerja sama?',
                    'a' => 'Ketentuan jumlah anggota tim dan mitra mengikuti pedoman dan kebijakan PKM Universitas Gunadarma yang berlaku pada setiap periode kegiatan.'
                ],
            ]
        ],
    ],
    'en' => [
        [
            'category' => 'General & Technical FAQ',
            'items' => [
                [
                    'q' => 'What is LPM Universitas Gunadarma?',
                    'a' => 'LPM (Community Service Institute) Universitas Gunadarma is a unit that manages, coordinates, and facilitates community service activities carried out by the academic community of Gunadarma University together with community partners.'
                ],
                [
                    'q' => 'What programs does LPM UG offer?',
                    'a' => 'LPM Universitas Gunadarma organizes Community Service Programs (PKM), partner/MSME assistance, community training and development, and cooperation with institutions or communities.'
                ],
                [
                    'q' => 'How can I contact LPM UG?',
                    'a' => 'LPM Universitas Gunadarma can be contacted through:<br>• Address: Campus E Universitas Gunadarma, Building 4, LPM Room, Depok<br>• Phone: +62 877-7938-7663 (WhatsApp)<br>• Email: lpmunivgunadarma@gmail.com<br>• Operating hours: Monday–Saturday, 09:00–15:00 WIB'
                ],
                [
                    'q' => 'How do I register for an account?',
                    'a' => 'Account registration is done through the Registration menu by selecting the account type (Lecturer or Partner), filling in the required data, creating a username and password, then waiting for the verification process.',
                    'video' => 'TUTORIAL_PENDAFTARAN_URL'
                ],
                [
                    'q' => 'Why can\'t I log in to my account?',
                    'a' => 'The account may not have been verified, login data is incorrect, or the account is experiencing technical issues. Make sure your username and password are correct.'
                ],
                [
                    'q' => 'If my account has problems, is there a contact I can reach?',
                    'a' => 'If you experience account issues or other technical problems, users can contact LPM Universitas Gunadarma admin via email lpm@gunadarma.ac.id or through +62 877-7938-7663 (WhatsApp) during operating hours.'
                ],
            ]
        ],
        [
            'category' => 'Community Service Program (PKM) FAQ',
            'items' => [
                [
                    'q' => 'Who can register to participate in PKM Universitas Gunadarma?',
                    'a' => 'The PKM program can be followed by Gunadarma University lecturers as activity implementers and community/MSME partners as service partners. Students can participate according to applicable regulations.'
                ],
                [
                    'q' => 'How do I become a partner for cooperation?',
                    'a' => 'Prospective partners can register through the LPM Universitas Gunadarma website by creating a partner account and completing the required data. After verification, the LPM team will contact partners for further cooperation.'
                ],
                [
                    'q' => 'Where can I see registered partners?',
                    'a' => 'The list of registered partners can be viewed in the Partners menu on the official LPM Universitas Gunadarma website.',
                    'video' => 'TUTORIAL_SISTEM_LPM_URL'
                ],
                [
                    'q' => 'What is the flow or stages of PKM activities at LPM Universitas Gunadarma?',
                    'a' => 'PKM activity stages for Lecturers include:<br>1. Proposal<br>2. Implementation<br>3. Monitoring & Evaluation (MonEv)<br>4. Reporting<br><br>For Partners include:<br>1. Partner Request Letter (SPM)<br>2. Reply Letter<br>3. Partner Certificate (SKM)',
                    'video' => 'TUTORIAL_SISTEM_LPM_URL'
                ],
                [
                    'q' => 'How long is the account verification and activity submission process?',
                    'a' => 'The length of the verification and activity submission process depends on the completeness of the data and the ongoing activity period.'
                ],
                [
                    'q' => 'Are there any provisions on the number of members in a team and the number of partners in cooperation?',
                    'a' => 'The provisions on the number of team members and partners follow the PKM guidelines and policies of Gunadarma University applicable in each activity period.'
                ],
            ]
        ],
    ],
];

$faqTitle = ($lang === 'en') ? 'Frequently Asked Questions' : 'Pertanyaan yang Sering Diajukan';
$watchTutorial = ($lang === 'en') ? 'Watch Tutorial' : 'Tonton Tutorial';
$faqData = $faqCategories[$lang] ?? $faqCategories['id'];
?>

<!-- Render Content untuk Staff Section -->
<?= $this->section('content_staff') ?>
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center mx-auto" style="max-width: 500px">
            <h1 class="display-6 mb-5" data-wow-delay="0.5s">
                <?= $lang === 'en'
                    ? 'Meet our professional team.'
                    : 'Bertemu dengan team professional kami.' ?>
            </h1>
        </div>
        <div class="row g-4">
            <?php foreach ($profilestaff as $pr => $v_profilestaff): ?>
                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="team-item rounded" style="height:430px;">
                        <img class="img-fluid" src="<?= base_url('img/upload/profilestaff/' . $v_profilestaff->gambar) ?>" alt="Profile - <?= $v_profilestaff->judul; ?>" />
                        <div class="text-center p-4">
                            <h5><?= $v_profilestaff->judul; ?></h5>
                            <span><?= $v_profilestaff->deskripsi; ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<!-- END Render Content untuk Staff Section -->

<!-- Render Content untuk Galeri Section -->
<?= $this->section('content_galeri') ?>
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center mx-auto" style="max-width: 500px">
            <h1 class="display-6 mb-5" data-wow-delay="0.5s">
                <?= $lang === 'en'
                    ? 'Documentation of ABDIMAS Activities.'
                    : 'Dokumentasi Kegiatan ABDIMAS.' ?>
            </h1>
        </div>
        <div class="row g-4">
            <?php foreach ($galeri as $pr => $v_galeri): ?>
                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="team-item rounded" style="height:330px;">
                        <img class="img-fluid" src="<?= base_url('/img/upload/galeri/' . $v_galeri->gambar) ?>" alt="galeri - <?= $v_galeri->judul; ?>" />
                        <div class="text-center p-4">
                            <span><b><?= $v_galeri->judul; ?></b></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- ===================== FAQ SECTION (Consistent with Dashboard) ===================== -->
<div class="container-xxl py-5" id="faqSection">
    <div class="container">
        <div class="text-center mx-auto mb-5" style="max-width: 600px">
            <h1 class="display-6 mb-3" data-wow-delay="0.5s">
                <?= esc($faqTitle) ?>
            </h1>
        </div>

        <div class="mx-auto" style="max-width: 1000px;">
            <?php foreach ($faqData as $catIdx => $category): ?>
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card faq-card">
                            <!-- Card Header -->
                            <div class="card-header">
                                <h4><?= esc($category['category']) ?></h4>
                            </div>

                            <!-- Card Body with Accordion -->
                            <div class="card-body p-0">
                                <div id="faqAccordion<?= $catIdx ?>" class="faq-accordion">
                                    <?php foreach ($category['items'] as $idx => $f): ?>
                                        <?php
                                        $n = $catIdx . '_' . $idx;
                                        $isOpen = ($idx === 0);
                                        ?>
                                        <div class="faq-item">
                                            <button
                                                class="faq-btn <?= $isOpen ? '' : 'collapsed' ?>"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#faqCollapse<?= $n ?>"
                                                aria-expanded="<?= $isOpen ? 'true' : 'false' ?>"
                                                aria-controls="faqCollapse<?= $n ?>">
                                                <span><?= esc($f['q']) ?></span>
                                                <span class="faq-icon" aria-hidden="true">
                                                    <i class="fas fa-plus"></i>
                                                </span>
                                            </button>

                                            <div
                                                id="faqCollapse<?= $n ?>"
                                                class="collapse <?= $isOpen ? 'show' : '' ?>"
                                                data-bs-parent="#faqAccordion<?= $catIdx ?>">
                                                <div class="faq-body">
                                                    <?= $f['a'] ?>
                                                    <?php if (isset($f['video'])): ?>
                                                        <div class="mt-3">
                                                            <a href="<?= esc($f['video']) ?>"
                                                                target="_blank"
                                                                class="btn btn-sm btn-youtube">
                                                                <i class="fab fa-youtube"></i>
                                                                <?= esc($watchTutorial) ?>
                                                            </a>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<style>
    /* FAQ Card - Sama dengan Dashboard */
    #faqSection .faq-card {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
    }

    #faqSection .faq-card .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #ffffff;
        padding: 24px 28px;
        border-bottom: 0;
    }

    #faqSection .faq-card .card-header h4 {
        margin: 0;
        font-weight: 800;
        font-size: 1.5rem;
        color: #ffffff;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
        letter-spacing: 0.3px;
    }

    /* Accordion Items */
    #faqSection .faq-accordion .faq-item {
        border-top: 1px solid #e5e7eb;
    }

    #faqSection .faq-accordion .faq-item:first-child {
        border-top: 0;
    }

    /* FAQ Button */
    #faqSection .faq-btn {
        width: 100%;
        border: 0;
        background: transparent;
        padding: 20px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        text-align: left;
        font-weight: 600;
        font-size: 16px;
        color: #111827;
        outline: none;
        box-shadow: none;
        transition: background 0.2s ease;
        cursor: pointer;
    }

    #faqSection .faq-btn:hover {
        background: rgba(102, 126, 234, 0.05);
    }

    #faqSection .faq-btn:focus {
        outline: none;
        box-shadow: none;
    }

    /* FAQ Icon */
    #faqSection .faq-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        color: #667eea;
        transition: transform 0.3s ease;
        flex-shrink: 0;
    }

    #faqSection .faq-btn[aria-expanded="true"] .faq-icon {
        transform: rotate(45deg);
    }

    /* FAQ Body */
    #faqSection .faq-body {
        padding: 0 24px 20px 24px;
        color: #6b7280;
        line-height: 1.7;
    }

    /* YouTube Button */
    #faqSection .btn-youtube {
        background: #FF0000;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    #faqSection .btn-youtube:hover {
        background: #CC0000;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 0, 0, 0.3);
        text-decoration: none;
    }

    #faqSection .btn-youtube i {
        font-size: 16px;
    }

    /* Spacing */
    #faqSection .mt-3 {
        margin-top: 1rem;
    }

    #faqSection .mb-4 {
        margin-bottom: 1.5rem;
    }

    /* Bootstrap Collapse Override */
    #faqSection .collapse {
        transition: height 0.35s ease;
    }

    /* Responsive */
    @media (max-width: 768px) {
        #faqSection .faq-card .card-header h4 {
            font-size: 1.25rem;
        }

        #faqSection .faq-btn {
            padding: 16px 20px;
            font-size: 15px;
        }

        #faqSection .faq-body {
            padding: 0 20px 16px 20px;
        }
    }
</style>

<script>
    (function() {
        // Handle icon toggle untuk semua accordion
        document.querySelectorAll('[id^="faqAccordion"]').forEach(function(accordion) {
            accordion.querySelectorAll('.collapse').forEach(function(collapseEl) {

                collapseEl.addEventListener('show.bs.collapse', function() {
                    const btn = this.previousElementSibling.querySelector('.faq-btn');
                    const icon = btn ? btn.querySelector('.faq-icon i') : null;
                    if (icon) {
                        icon.classList.remove('fa-plus');
                        icon.classList.add('fa-times');
                    }
                });

                collapseEl.addEventListener('hide.bs.collapse', function() {
                    const btn = this.previousElementSibling.querySelector('.faq-btn');
                    const icon = btn ? btn.querySelector('.faq-icon i') : null;
                    if (icon) {
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-plus');
                    }
                });
            });
        });
    })();
</script>
<!-- =================== END FAQ SECTION =================== -->
<?= $this->endSection() ?>
<!-- END Render Content untuk Galeri Section -->

<?= $this->section('content') ?>
INI HALAMAN HOMEPAGE
<?= $this->endSection() ?>
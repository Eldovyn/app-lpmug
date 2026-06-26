<?= $this->extend('layouts/default') ?>

<?php
helper(['cookie', 'url']);

$request = service('request');

$allowed = ['id', 'en'];
$lang = get_cookie('lang') ?: 'id';
if (!in_array($lang, $allowed, true)) {
    $lang = 'id';
}

$reqLang = $request->getGet('lang');
if ($reqLang && in_array($reqLang, $allowed, true)) {
    set_cookie('lang', $reqLang, 60 * 60 * 24 * 30);
    $lang = $reqLang;
}

// Dictionary untuk terjemahan
$dict = [
    'id' => [
        'title' => 'Pertanyaan yang Sering Diajukan',
        'no_faq' => 'Belum ada FAQ tersedia',
        'category_general' => 'FAQ Umum & Teknis',
        'category_pkm' => 'FAQ PKM (Pengabdian kepada Masyarakat)',
        'watch_tutorial' => 'Tonton Tutorial',
    ],
    'en' => [
        'title' => 'Frequently Asked Questions',
        'no_faq' => 'No FAQ available yet',
        'category_general' => 'General & Technical FAQ',
        'category_pkm' => 'Community Service Program (PKM) FAQ',
        'watch_tutorial' => 'Watch Tutorial',
    ],
];

// Data FAQ dengan 2 kategori berdasarkan dokumen
$faqCategories = [
    'id' => [
        [
            'category' => 'category_general',
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
            'category' => 'category_pkm',
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
            'category' => 'category_general',
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
            'category' => 'category_pkm',
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

// Ambil FAQ sesuai bahasa aktif
$categories = $faqCategories[$lang] ?? $faqCategories['id'];
$t = $dict[$lang] ?? $dict['id'];
?>

<?= $this->section('title') ?>
<title><?= esc($t['title']) ?> &mdash; LPM UG</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <h1><?= esc($t['title']) ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active">
                <a href="<?= site_url('dashboard'); ?>">Dashboard</a>
            </div>
            <div class="breadcrumb-item"><?= esc($t['title']) ?></div>
        </div>
    </div>

    <div class="section-body">
        <?php if (!empty($categories)): ?>
            <?php foreach ($categories as $catIdx => $category): ?>
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card faq-card">
                            <div class="card-header">
                                <h4><?= esc($t[$category['category']]) ?></h4>
                            </div>
                            <div class="card-body p-0">
                                <?php if (!empty($category['items'])): ?>
                                    <div id="faqAccordion<?= $catIdx ?>" class="faq-accordion">
                                        <?php foreach ($category['items'] as $idx => $f):
                                            $n = $catIdx . '_' . $idx;
                                            $isOpen = ($idx === 0);
                                        ?>
                                            <div class="faq-item">
                                                <button
                                                    class="faq-btn <?= $isOpen ? '' : 'collapsed' ?>"
                                                    type="button"
                                                    data-toggle="collapse"
                                                    data-target="#faqCollapse<?= $n ?>"
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
                                                    data-parent="#faqAccordion<?= $catIdx ?>">
                                                    <div class="faq-body">
                                                        <?= $f['a'] ?>
                                                        <?php if (isset($f['video'])): ?>
                                                            <div class="mt-3">
                                                                <a href="<?= esc($f['video']) ?>"
                                                                    target="_blank"
                                                                    class="btn btn-sm btn-youtube">
                                                                    <i class="fab fa-youtube"></i>
                                                                    <?= esc($t['watch_tutorial']) ?>
                                                                </a>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="p-5 text-center text-muted">
                                <p><?= esc($t['no_faq']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
    .faq-card {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
    }

    .faq-card .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #ffffff;
        padding: 24px 28px;
        border-bottom: 0;
    }

    .faq-card .card-header h4 {
        margin: 0;
        font-weight: 800;
        font-size: 1.5rem;
        color: #ffffff;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
        letter-spacing: 0.3px;
    }

    .faq-accordion .faq-item {
        border-top: 1px solid #e5e7eb;
    }

    .faq-accordion .faq-item:first-child {
        border-top: 0;
    }

    .faq-btn {
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
    }

    .faq-btn:hover {
        background: rgba(102, 126, 234, 0.05);
    }

    .faq-btn:focus,
    .faq-btn:active {
        outline: none !important;
        box-shadow: none !important;
        border: none !important;
    }

    .faq-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        color: #667eea;
        transition: transform 0.3s ease;
    }

    .faq-btn[aria-expanded="true"] .faq-icon {
        transform: rotate(45deg);
    }

    .faq-body {
        padding: 0 24px 20px 24px;
        color: #6b7280;
        line-height: 1.7;
    }

    .btn-youtube {
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

    .btn-youtube:hover {
        background: #CC0000;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 0, 0, 0.3);
        text-decoration: none;
    }

    .btn-youtube i {
        font-size: 16px;
    }

    .mt-3 {
        margin-top: 1rem;
    }

    .mb-4 {
        margin-bottom: 1.5rem;
    }
</style>

<script>
    (function() {
        if (typeof window.jQuery === 'undefined') return;

        // Handle multiple accordions
        $('[id^="faqAccordion"]').each(function() {
            var $acc = $(this);

            $acc.on('show.bs.collapse', '.collapse', function() {
                $(this).prev('.faq-btn').attr('aria-expanded', 'true');
            });

            $acc.on('hide.bs.collapse', '.collapse', function() {
                $(this).prev('.faq-btn').attr('aria-expanded', 'false');
            });
        });
    })();
</script>

<?= $this->endSection() ?>
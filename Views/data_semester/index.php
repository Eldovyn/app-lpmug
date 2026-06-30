<?php
helper(['cookie', 'url']);

$request = service('request');

$allowed = ['id', 'en'];
$lang = get_cookie('lang') ?: 'id';
if (! in_array($lang, $allowed, true)) {
    $lang = 'id';
}

$reqLang = $request->getGet('lang');
if ($reqLang && in_array($reqLang, $allowed, true)) {
    set_cookie('lang', $reqLang, 60 * 60 * 24 * 30);
    $lang = $reqLang;
}

$TR = [
    'id' => [
        'btn_back' => 'Kembali',
        'btn_save' => 'Simpan',
        'breadcrumb_dashboard' => 'Dashboard',
        'breadcrumb_monev' => 'Monitoring dan Evaluasi',
        'title_center_1' => 'MONITORING DAN EVALUASI KEGIATAN',
        'title_center_2' => 'PROGRAM PENGABDIAN KEPADA MASYARAKAT',
        'title_center_3' => 'UNIVERSITAS GUNADARMA PERIODE',
        'label_leader' => 'Nama Ketua Tim PKM',
        'label_date' => 'Tanggal Pelaksanaan',
        'label_partner' => 'Nama Mitra PKM',
        'label_activity_title' => 'Judul Kegiatan PKM',
        'label_member_count' => 'Jumlah Anggota Tim',
        'label_semester' => 'Filter Semester',
        'label_suggestion' => 'Saran & Masukan',
        'avg_title_component' => 'Komponen',
        'avg_title_total' => 'Rata-rata Nilai',
        'avg_overall' => 'Rata-rata Keseluruhan',
        'sec1_a' => 'a. Materi dan pelaksanaan kegiatan',
        'sec1_b' => 'b. Peran dan Kontribusi Anggota',
        'sec2_title' => 'Kondisi Mitra',
        'all_semester' => 'Semua Semester',
    ],
    'en' => [
        'btn_back' => 'Back',
        'btn_save' => 'Save',
        'breadcrumb_dashboard' => 'Dashboard',
        'breadcrumb_monev' => 'Monitoring & Evaluation',
        'title_center_1' => 'MONITORING AND EVALUATION',
        'title_center_2' => 'COMMUNITY SERVICE PROGRAM',
        'title_center_3' => 'GUNADARMA UNIVERSITY PERIOD',
        'label_leader' => 'Team Leader Name',
        'label_date' => 'Activity Date',
        'label_partner' => 'Partner Name',
        'label_activity_title' => 'Activity Title',
        'label_member_count' => 'Number of Team Members',
        'label_semester' => 'Filter Semester',
        'label_suggestion' => 'Suggestions & Feedback',
        'avg_title_component' => 'Component',
        'avg_title_total' => 'Total Score',
        'avg_overall' => 'Overall Average',
        'sec1_a' => 'a. Materials and implementation',
        'sec1_b' => 'b. Member roles and contributions',
        'sec2_title' => 'Partner Condition',
        'all_semester' => 'All Semesters',
    ],
];

$GLOBALS['I18N_TR'] = $TR;
$GLOBALS['I18N_LANG'] = $lang;

if (! function_exists('t')) {
    function t(string $key): string
    {
        $TR = $GLOBALS['I18N_TR'] ?? [];
        $lang = $GLOBALS['I18N_LANG'] ?? 'id';
        return $TR[$lang][$key] ?? $TR['id'][$key] ?? $key;
    }
}

if (! function_exists('lang_url')) {
    function lang_url(string $locale): string
    {
        $request = service('request');
        $base = current_url();
        $q = $request->getGet();
        $q['lang'] = $locale;
        return $base . '?' . http_build_query($q);
    }
}

$selected_semester = $request->getGet('semester') ?? '';

// Use periodes from controller
$periodes = $periodes ?? [];

// Use laporan_data from controller
$laporan_data = $laporan_data ?? [];

// Fungsi nilai sama seperti di monevadmin
function nilai($nlpm, $nt)
{
    if ($nlpm !== null && $nlpm !== '') return (int)$nlpm;
    if ($nt !== null && $nt !== '') return (int)$nt;
    return 0;
}

// Hitung nilai rata-rata per laporan
if (!empty($laporan_data)) {
    $jumlah_laporan = count($laporan_data);

    $totalA = 0;
    $totalB = 0;
    $totalC = 0;

    foreach ($laporan_data as $laporan) {
        // Bagian A: nt1-nt5
        for ($i = 1; $i <= 5; $i++) {
            $ntVal = $laporan->{'nt' . $i} ?? null;
            $totalA += nilai(null, $ntVal);
        }

        // Bagian B: nt6-nt7
        for ($i = 6; $i <= 7; $i++) {
            $ntVal = $laporan->{'nt' . $i} ?? null;
            $totalB += nilai(null, $ntVal);
        }

        // Bagian C: nt8-nt9
        for ($i = 8; $i <= 9; $i++) {
            $ntVal = $laporan->{'nt' . $i} ?? null;
            $totalC += nilai(null, $ntVal);
        }
    }

    // Bagi dengan jumlah laporan untuk mendapatkan rata-rata
    $avgA = $jumlah_laporan > 0 ? round($totalA / $jumlah_laporan, 2) : 0;
    $avgB = $jumlah_laporan > 0 ? round($totalB / $jumlah_laporan, 2) : 0;
    $avgC = $jumlah_laporan > 0 ? round($totalC / $jumlah_laporan, 2) : 0;

    $avgAll = round(($avgA + $avgB + $avgC) / 3, 2);

    $dummy_data = (object)[
        'ketua_nama' => count($laporan_data) . ' laporan',
        'mitra_nama' => '-',
        'judul_kegiatan' => 'Data Periode Aktif',
        'tanggal_kegiatan' => '-',
        'jumlah_anggota' => count($laporan_data),
    ];
} else {
    // Default jika tidak ada data
    $avgA = 0;
    $avgB = 0;
    $avgC = 0;
    $avgAll = 0;

    $dummy_data = (object)[
        'ketua_nama' => '-',
        'mitra_nama' => '-',
        'judul_kegiatan' => '-',
        'tanggal_kegiatan' => '-',
        'jumlah_anggota' => '0',
    ];
}

function formatTanggalID($tanggal)
{
    if (empty($tanggal)) return '-';
    $dateObj = date_create(trim($tanggal));
    if (!$dateObj) return '-';
    return date_format($dateObj, 'j F Y');
}

$tanggal_array = array_map('trim', explode(' - ', $dummy_data->tanggal_kegiatan ?? '-'));
$tanggalMulai = formatTanggalID($tanggal_array[0] ?? null);
$tanggalSelesai = formatTanggalID($tanggal_array[1] ?? null);
?>

<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<title><?= $title_tab ?? 'Monitoring dan Evaluasi'; ?></title>

<style>
    .table-bordered td,
    .table-bordered th {
        border: 1px solid #dee2e6;
    }

    .table td,
    .table th {
        vertical-align: middle;
    }

    .filter-semester {
        border-radius: 8px;
        margin-bottom: 24px;
    }

    /* ===== Modern Score Table ===== */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    .rekap-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 24px rgba(99,102,241,.08), 0 1px 4px rgba(0,0,0,.05);
        overflow: hidden;
        font-family: 'Inter', -apple-system, sans-serif;
        margin-top: 8px;
    }
    .rekap-card-header {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        padding: 18px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }
    .rekap-card-header-title {
        color: #fff;
        font-size: .9rem;
        font-weight: 700;
        letter-spacing: -.01em;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .rekap-card-header-title .dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        background: rgba(255,255,255,.6);
        animation: pulse-dot 2s ease infinite;
        flex-shrink: 0;
    }
    .rekap-row-list {
        padding: 0;
        margin: 0;
        list-style: none;
    }
    .rekap-row-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px 24px;
        border-bottom: 1px solid #f1f5f9;
        transition: background .15s;
    }
    .rekap-row-item:last-child {
        border-bottom: none;
    }
    .rekap-row-item:hover {
        background: #f8faff;
    }
    .rekap-row-num {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: linear-gradient(135deg, #eef2ff, #ede9fe);
        color: #6366f1;
        font-size: .72rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .rekap-row-body {
        flex: 1;
        min-width: 0;
    }
    .rekap-row-label {
        font-size: .85rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 6px;
        line-height: 1.4;
    }
    .rekap-progress-track {
        height: 6px;
        background: #f1f5f9;
        border-radius: 99px;
        overflow: hidden;
    }
    .rekap-progress-fill {
        height: 100%;
        border-radius: 99px;
        width: 0%;
        transition: width 1s cubic-bezier(.4,0,.2,1);
    }
    .rekap-row-score {
        font-size: 1.05rem;
        font-weight: 800;
        color: #1e293b;
        letter-spacing: -.02em;
        flex-shrink: 0;
        min-width: 40px;
        text-align: right;
        font-family: 'Inter', sans-serif;
    }
    /* Overall row */
    .rekap-overall {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 20px 24px;
        background: linear-gradient(135deg, rgba(99,102,241,.06) 0%, rgba(139,92,246,.06) 100%);
        border-top: 2px solid rgba(99,102,241,.12);
    }
    .rekap-overall-label {
        font-size: .9rem;
        font-weight: 700;
        color: #6366f1;
        font-family: 'Inter', sans-serif;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .rekap-overall-score {
        font-size: 2rem;
        font-weight: 800;
        color: #6366f1;
        letter-spacing: -.04em;
        font-family: 'Inter', sans-serif;
        line-height: 1;
    }
    .rekap-overall-max {
        font-size: .75rem;
        color: #94a3b8;
        font-weight: 500;
        margin-top: 2px;
        text-align: right;
    }
    /* Download buttons */
    .rekap-actions {
        display: flex;
        gap: 10px;
        padding: 20px 24px 0;
        flex-wrap: wrap;
    }
    .rekap-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 20px;
        border-radius: 10px;
        font-size: .82rem;
        font-weight: 700;
        font-family: 'Inter', sans-serif;
        border: none;
        cursor: pointer;
        transition: all .2s cubic-bezier(.4,0,.2,1);
        text-decoration: none;
        letter-spacing: .01em;
    }
    .rekap-btn-excel {
        background: linear-gradient(135deg, #10b981, #06b6d4);
        color: #fff;
        box-shadow: 0 4px 12px rgba(16,185,129,.3);
    }
    .rekap-btn-excel:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(16,185,129,.4);
        color: #fff;
    }
    .rekap-btn-pdf {
        background: linear-gradient(135deg, #f43f5e, #f97316);
        color: #fff;
        box-shadow: 0 4px 12px rgba(244,63,94,.3);
    }
    .rekap-btn-pdf:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(244,63,94,.4);
        color: #fff;
    }
    @keyframes pulse-dot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: .5; transform: scale(1.35); }
    }
    @media (max-width: 575px) {
        .rekap-row-item { padding: 14px 16px; gap: 10px; }
        .rekap-overall  { padding: 16px; }
        .rekap-actions  { padding: 16px 16px 0; }
        .rekap-overall-score { font-size: 1.6rem; }
    }

    /* Custom Searchable Select */
    .cs-wrapper {
        position: relative;
        user-select: none;
    }
    .cs-label {
        font-weight: 600;
        font-size: 0.875rem;
        margin-bottom: 4px;
        display: block;
        color: #495057;
    }
    .cs-selected {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 7px 12px;
        border: 1px solid #ced4da;
        border-radius: 5px;
        background: #fff;
        cursor: pointer;
        font-size: 0.875rem;
        color: #495057;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .cs-selected:hover {
        border-color: #6777ef;
    }
    .cs-selected.open {
        border-color: #6777ef;
        box-shadow: 0 0 0 0.2rem rgba(103,119,239,0.2);
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
    }
    .cs-selected .cs-arrow {
        font-size: 10px;
        color: #adb5bd;
        transition: transform 0.2s;
    }
    .cs-selected.open .cs-arrow {
        transform: rotate(180deg);
    }
    .cs-dropdown {
        display: none;
        position: absolute;
        top: 100%;
        left: 0; right: 0;
        background: #fff;
        border: 1px solid #6777ef;
        border-top: none;
        border-bottom-left-radius: 5px;
        border-bottom-right-radius: 5px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.10);
        z-index: 9999;
        max-height: 260px;
        display: none;
        flex-direction: column;
    }
    .cs-dropdown.open {
        display: flex;
    }
    .cs-search-box {
        padding: 8px 10px;
        border-bottom: 1px solid #e9ecef;
        flex-shrink: 0;
    }
    .cs-search-input {
        width: 100%;
        padding: 5px 10px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        font-size: 0.8rem;
        outline: none;
        box-sizing: border-box;
    }
    .cs-search-input:focus {
        border-color: #6777ef;
    }
    .cs-list {
        overflow-y: auto;
        flex: 1;
    }
    .cs-item {
        padding: 8px 12px;
        font-size: 0.875rem;
        cursor: pointer;
        color: #495057;
    }
    .cs-item:hover {
        background: #f0f2ff;
        color: #6777ef;
    }
    .cs-item.active {
        background: #6777ef;
        color: #fff;
    }
    .cs-no-results {
        padding: 10px 12px;
        font-size: 0.8rem;
        color: #adb5bd;
        font-style: italic;
        text-align: center;
        display: none;
    }
</style>

<!-- Library untuk export Excel dan PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>

<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section class="section">
    <div class="section-header">
        <a href="<?= site_url('rekapan'); ?>" class="btn btn-dark mr-2"><i class="fas fa-arrow-left"></i></a>
        <h1><?= $title ?? 'Monitoring dan Evaluasi'; ?></h1>

        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>"><?= t('breadcrumb_dashboard') ?></a></div>
            <div class="breadcrumb-item active"><a href="<?= site_url('rekapan'); ?>"><?= t('breadcrumb_monev') ?></a></div>
            <div class="breadcrumb-item"><?= $title ?? 'Detail'; ?></div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Filter Semester -->
                        <div class="filter-semester">
                            <div class="row">
                                <div class="col-md-8 offset-md-4 col-lg-4 offset-lg-8 ml-auto">
                                    <!-- Hidden native select (untuk form & aksesibilitas) -->
                                    <select id="semesterFilter" style="display:none;">
                                        <option value=""><?= t('all_semester') ?></option>
                                        <?php if (!empty($periodes)): ?>
                                            <?php foreach ($periodes as $periode): ?>
                                                <option value="<?= (int)$periode->periode_id ?>" <?= ($selected_semester == (int)$periode->periode_id) ? 'selected' : '' ?>>
                                                    <?= esc($periode->periode_name) ?> <?= esc($periode->tahun_ajaran) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>

                                    <!-- Custom searchable dropdown -->
                                    <label class="cs-label" for="semesterFilter"><?= t('label_semester') ?> :</label>
                                    <div class="cs-wrapper" id="csWrapper">
                                        <div class="cs-selected" id="csSelected">
                                            <span id="csSelectedText"><?= t('all_semester') ?></span>
                                            <span class="cs-arrow">&#9660;</span>
                                        </div>
                                        <div class="cs-dropdown" id="csDropdown">
                                            <div class="cs-search-box">
                                                <input type="text" class="cs-search-input" id="csSearchInput" placeholder="Cari semester..." autocomplete="off">
                                            </div>
                                            <div class="cs-list" id="csList"></div>
                                            <div class="cs-no-results" id="csNoResults">Tidak ada hasil ditemukan</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form action="#" method="POST" autocomplete="off" enctype="multipart/form-data">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="_method" value="PATCH">
                            <input type="hidden" name="semester" value="<?= $selected_semester ?>">

                            <!-- Tabel Rata-rata (Modern) -->
                            <div class="rekap-card">
                                <!-- Card Header -->
                                <div class="rekap-card-header">
                                    <div class="rekap-card-header-title">
                                        <span class="dot"></span>
                                        <?= t('avg_title_component') ?>
                                    </div>
                                    <span style="background:rgba(255,255,255,.18);color:#fff;font-size:.7rem;font-weight:700;padding:3px 10px;border-radius:20px;letter-spacing:.06em;">
                                        <?= t('avg_title_total') ?>
                                    </span>
                                </div>

                                <!-- Component Rows -->
                                <?php
                                $rows = [
                                    ['label' => t('sec1_a'), 'value' => $avgA,  'color' => 'linear-gradient(90deg,#6366f1,#8b5cf6)', 'num' => 'A'],
                                    ['label' => t('sec1_b'), 'value' => $avgB,  'color' => 'linear-gradient(90deg,#06b6d4,#6366f1)', 'num' => 'B'],
                                    ['label' => t('sec2_title'), 'value' => $avgC, 'color' => 'linear-gradient(90deg,#10b981,#06b6d4)', 'num' => 'C'],
                                ];
                                $maxScore = 100;
                                ?>
                                <ul class="rekap-row-list">
                                    <?php foreach ($rows as $row): ?>
                                    <li class="rekap-row-item">
                                        <div class="rekap-row-num"><?= $row['num'] ?></div>
                                        <div class="rekap-row-body">
                                            <div class="rekap-row-label"><?= $row['label'] ?></div>
                                            <div class="rekap-progress-track">
                                                <div class="rekap-progress-fill"
                                                     style="background:<?= $row['color'] ?>;"
                                                     data-target="<?= $maxScore > 0 ? round(($row['value'] / $maxScore) * 100, 1) : 0 ?>"
                                                ></div>
                                            </div>
                                        </div>
                                        <div class="rekap-row-score"><?= $row['value'] ?></div>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>

                                <!-- Overall Average -->
                                <div class="rekap-overall">
                                    <div class="rekap-overall-label">
                                        <i class="fas fa-chart-line"></i>
                                        <?= t('avg_overall') ?>
                                    </div>
                                    <div style="text-align:right;">
                                        <div class="rekap-overall-score"><?= $avgAll ?></div>
                                        <div class="rekap-overall-max">/ <?= $maxScore ?></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tombol Download -->
                            <div class="rekap-actions mb-2">
                                <button type="button" class="rekap-btn rekap-btn-excel" id="downloadExcel">
                                    <i class="fas fa-file-excel"></i> Download Excel
                                </button>
                                <button type="button" class="rekap-btn rekap-btn-pdf" id="downloadPDF">
                                    <i class="fas fa-file-pdf"></i> Download PDF
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Toast Notification -->
<div aria-live="polite" aria-atomic="true" style="position: fixed; top: 20px; right: 20px; z-index: 100000;">
    <div id="successToast" class="toast hide" role="alert" style="min-width: 300px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); border-radius: 8px; overflow: hidden;">
        <div class="toast-header bg-success text-white" style="border-bottom: none; padding: 12px 15px;">
            <i class="fas fa-check-circle mr-2"></i>
            <strong class="mr-auto">Berhasil</strong>
            <button type="button" class="ml-2 mb-1 close text-white" onclick="document.getElementById('successToast').classList.remove('show');document.getElementById('successToast').classList.add('hide');">
                <span>&times;</span>
            </button>
        </div>
        <div class="toast-body" id="toastMessage" style="padding: 15px; font-size: 14px;">
            Message
        </div>
    </div>
</div>

<style>
    .toast.show {
        display: block !important;
        opacity: 1;
        animation: slideInRight 0.3s ease-out;
    }

    .toast.hide {
        display: none !important;
        opacity: 0;
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
</style>

<script>
    (function() {
        // ===== Progress Bar Animation =====
        (function() {
            const fills = document.querySelectorAll('.rekap-progress-fill');
            if (!fills.length) return;
            const obs = new IntersectionObserver(function(entries) {
                entries.forEach(function(e) {
                    if (e.isIntersecting) {
                        e.target.style.width = (e.target.dataset.target || 0) + '%';
                        obs.unobserve(e.target);
                    }
                });
            }, { threshold: 0.3 });
            fills.forEach(function(el) { obs.observe(el); });
        })();

        // ===== Custom Searchable Select =====
        (function() {
            const nativeSelect  = document.getElementById('semesterFilter');
            const wrapper       = document.getElementById('csWrapper');
            const selected      = document.getElementById('csSelected');
            const selectedText  = document.getElementById('csSelectedText');
            const dropdown      = document.getElementById('csDropdown');
            const searchInput   = document.getElementById('csSearchInput');
            const list          = document.getElementById('csList');
            const noResults     = document.getElementById('csNoResults');

            if (!nativeSelect || !wrapper) return;

            // Build list items from native select options
            const options = Array.from(nativeSelect.options);
            let activeValue = nativeSelect.value;

            function buildList(filter) {
                filter = (filter || '').toLowerCase().trim();
                list.innerHTML = '';
                let count = 0;
                options.forEach(function(opt) {
                    if (filter && !opt.text.toLowerCase().includes(filter)) return;
                    const item = document.createElement('div');
                    item.className = 'cs-item' + (opt.value === activeValue ? ' active' : '');
                    item.textContent = opt.text;
                    item.dataset.value = opt.value;
                    item.addEventListener('click', function() {
                        selectOption(opt.value, opt.text);
                    });
                    list.appendChild(item);
                    count++;
                });
                noResults.style.display = count === 0 ? 'block' : 'none';
            }

            function selectOption(value, text) {
                activeValue = value;
                selectedText.textContent = text;
                nativeSelect.value = value;
                closeDropdown();

                // Navigate
                const url = new URL(window.location.href);
                if (value === '') {
                    url.searchParams.delete('semester');
                } else {
                    url.searchParams.set('semester', value);
                }
                window.location.href = url.toString();
            }

            function openDropdown() {
                selected.classList.add('open');
                dropdown.classList.add('open');
                searchInput.value = '';
                buildList('');
                searchInput.focus();
            }

            function closeDropdown() {
                selected.classList.remove('open');
                dropdown.classList.remove('open');
            }

            // Set initial displayed text
            const initOpt = options.find(o => o.value === nativeSelect.value);
            if (initOpt) selectedText.textContent = initOpt.text;

            selected.addEventListener('click', function(e) {
                e.stopPropagation();
                if (dropdown.classList.contains('open')) {
                    closeDropdown();
                } else {
                    openDropdown();
                }
            });

            searchInput.addEventListener('input', function() {
                buildList(this.value);
            });

            // Close when clicking outside
            document.addEventListener('click', function(e) {
                if (!wrapper.contains(e.target)) {
                    closeDropdown();
                }
            });

            // Build initial list
            buildList('');
        })();

        // ================ TOAST NOTIFICATION ================
        function showToast(message, type) {
            type = type || 'success';
            const toast = document.getElementById('successToast');
            const header = toast.querySelector('.toast-header');
            const icon = header.querySelector('i');
            const title = header.querySelector('strong');
            const toastMessage = document.getElementById('toastMessage');

            header.className = 'toast-header';
            if (type === 'success') {
                header.classList.add('bg-success', 'text-white');
                icon.className = 'fas fa-check-circle mr-2';
                title.textContent = 'Berhasil';
            } else if (type === 'danger') {
                header.classList.add('bg-danger', 'text-white');
                icon.className = 'fas fa-exclamation-circle mr-2';
                title.textContent = 'Error';
            }

            toastMessage.textContent = message;
            toast.classList.remove('hide');
            toast.classList.add('show');

            setTimeout(() => {
                toast.classList.remove('show');
                toast.classList.add('hide');
            }, 4000);
        }

        // ================ DOWNLOAD EXCEL ================
        document.getElementById('downloadExcel').addEventListener('click', function() {
            try {
                const wb = XLSX.utils.book_new();

                const rows = [];
                rows.push(['<?= t('avg_title_component') ?>', '<?= t('avg_title_total') ?>']);
                rows.push(['<?= t('sec1_a') ?>', '<?= $avgA ?>']);
                rows.push(['<?= t('sec1_b') ?>', '<?= $avgB ?>']);
                rows.push(['<?= t('sec2_title') ?>', '<?= $avgC ?>']);
                rows.push(['<?= t('avg_overall') ?>', '<?= $avgAll ?>']);

                const ws = XLSX.utils.aoa_to_sheet(rows);
                ws['!cols'] = [{
                    wch: 50
                }, {
                    wch: 15
                }];

                XLSX.utils.book_append_sheet(wb, ws, 'Rata-rata');

                const filename = 'Rekapan_Semester_<?= date('Y-m-d') ?>.xlsx';
                XLSX.writeFile(wb, filename);

                showToast('File Excel berhasil diunduh!');
            } catch (error) {
                console.error('Error:', error);
                showToast('Gagal mengunduh Excel: ' + error.message, 'danger');
            }
        });

        // ================ DOWNLOAD PDF ================
        document.getElementById('downloadPDF').addEventListener('click', function() {
            try {
                const {
                    jsPDF
                } = window.jspdf;
                const doc = new jsPDF('p', 'mm', 'a4');

                doc.setFontSize(12);
                doc.setFont(undefined, 'bold');
                doc.text('<?= t('title_center_1') ?>', 105, 15, {
                    align: 'center'
                });
                doc.text('<?= t('title_center_2') ?>', 105, 22, {
                    align: 'center'
                });
                doc.text('<?= t('title_center_3') ?> <?= $selected_semester ?>', 105, 29, {
                    align: 'center'
                });

                doc.autoTable({
                    startY: 40,
                    head: [
                        ['<?= t('avg_title_component') ?>', '<?= t('avg_title_total') ?>']
                    ],
                    body: [
                        ['<?= t('sec1_a') ?>', '<?= $avgA ?>'],
                        ['<?= t('sec1_b') ?>', '<?= $avgB ?>'],
                        ['<?= t('sec2_title') ?>', '<?= $avgC ?>'],
                        ['<?= t('avg_overall') ?>', '<?= $avgAll ?>']
                    ],
                    theme: 'grid',
                    headStyles: {
                        fillColor: [52, 152, 219],
                        fontStyle: 'bold'
                    }
                });

                const filename = 'Rekapan_Semester_<?= date('Y-m-d') ?>.pdf';
                doc.save(filename);

                showToast('File PDF berhasil diunduh!');
            } catch (error) {
                console.error('Error:', error);
                showToast('Gagal mengunduh PDF: ' + error.message, 'danger');
            }
        });
    })();
</script>

<?= $this->endSection() ?>
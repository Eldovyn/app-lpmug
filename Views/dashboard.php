<?= $this->extend('layouts/default') ?>

<?php
helper(['cookie', 'url']);
$request = service('request');

$allowed = ['id', 'en'];
$lang = get_cookie('lang') ?: 'id';
if (!in_array($lang, $allowed, true)) $lang = 'id';

$reqLang = $request->getGet('lang');
if ($reqLang && in_array($reqLang, $allowed, true)) {
  set_cookie('lang', $reqLang, 60 * 60 * 24 * 30);
  $lang = $reqLang;
}

// ===================== DICTIONARY =====================
$dict = [
  'id' => [
    'dashboard'               => 'Dashboard',
    'congrats'                => 'Selamat!',
    'total_user'              => 'Total User',
    'total'                   => 'Total',
    'total_laporan_per_periode' => 'Laporan per Periode',
    'grafik_ketua_anggota'    => 'Ketua & Anggota',
    'grafik_luaran'           => 'Grafik Luaran',
    'semua_prodi'             => 'Semua Prodi',
    'total_provinsi'          => 'Provinsi',
    'total_kota'              => 'Kota',
    'total_fakultas'          => 'Fakultas',
    'total_prodi'             => 'Program Studi',
    'statistik_wilayah'       => 'Statistik Wilayah',
    'data_pengguna'           => 'Data Pengguna',
    'live_data'               => 'Live Data',
    'dosen'                   => 'Dosen',
    'mitra'                   => 'Mitra',
    'jumlah_pengguna'         => 'Jumlah Pengguna',
    'jumlah'                  => 'Jumlah',
    'jenis_pengguna'          => 'Jenis Pengguna',
    'total_laporan'           => 'Total Laporan',
    'jumlah_laporan'          => 'Jumlah Laporan',
    'periode'                 => 'Periode',
    'jumlah_ketua'            => 'Jumlah Ketua',
    'jumlah_anggota'          => 'Jumlah Anggota',
    'jurusan_prefix'          => 'Jurusan',
    'luaran_prefix'           => 'Luaran',
    'not_found'               => '(Tidak ditemukan)',
    'download_chart'          => 'Unduh Grafik',
    'flag_active'             => 'Flag Aktif',
    'flag_pending'            => 'Flag Pending',
    'hibah_disetujui'         => 'Hibah Disetujui',
    'aktif'                   => 'Aktif',
    'terdaftar'               => 'Terdaftar',
    'semua_periode'           => 'Semua Periode',
  ],
  'en' => [
    'dashboard'               => 'Dashboard',
    'congrats'                => 'Congratulations!',
    'total_user'              => 'Total Users',
    'total'                   => 'Total',
    'total_laporan_per_periode' => 'Reports per Period',
    'grafik_ketua_anggota'    => 'Chairs & Members',
    'grafik_luaran'           => 'Output Chart',
    'semua_prodi'             => 'All Programs',
    'total_provinsi'          => 'Provinces',
    'total_kota'              => 'Cities',
    'total_fakultas'          => 'Faculties',
    'total_prodi'             => 'Programs',
    'statistik_wilayah'       => 'Regional Statistics',
    'data_pengguna'           => 'User Data',
    'live_data'               => 'Live Data',
    'dosen'                   => 'Lecturers',
    'mitra'                   => 'Partners',
    'jumlah_pengguna'         => 'Number of Users',
    'jumlah'                  => 'Count',
    'jenis_pengguna'          => 'User Types',
    'total_laporan'           => 'Total Reports',
    'jumlah_laporan'          => 'Number of Reports',
    'periode'                 => 'Period',
    'jumlah_ketua'            => 'Number of Chairs',
    'jumlah_anggota'          => 'Number of Members',
    'jurusan_prefix'          => 'Program',
    'luaran_prefix'           => 'Output',
    'not_found'               => '(Not found)',
    'download_chart'          => 'Download Chart',
    'flag_active'             => 'Flag Active',
    'flag_pending'            => 'Flag Pending',
    'hibah_disetujui'         => 'Approved Hibah',
    'aktif'                   => 'Active',
    'terdaftar'               => 'Registered',
    'semua_periode'           => 'All Periods',
  ],
];

$t    = $dict[$lang];
$i18n = ['dosen'=>$t['dosen'],'mitra'=>$t['mitra'],'jumlah_pengguna'=>$t['jumlah_pengguna'],'jumlah'=>$t['jumlah'],'jenis_pengguna'=>$t['jenis_pengguna'],'total_laporan'=>$t['total_laporan'],'jumlah_laporan'=>$t['jumlah_laporan'],'periode'=>$t['periode'],'jumlah_ketua'=>$t['jumlah_ketua'],'jumlah_anggota'=>$t['jumlah_anggota'],'semua_prodi'=>$t['semua_prodi'],'jurusan_prefix'=>$t['jurusan_prefix'],'luaran_prefix'=>$t['luaran_prefix'],'not_found'=>$t['not_found']];
?>

<?= $this->section('title') ?>
<title><?= esc($t['dashboard']) ?> &mdash; LPM UG</title>
<link rel="stylesheet" href="<?= base_url('template/assets/css/dashboard.css?v=' . time()) ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section dash-page">

  <!-- ===== HEADER ===== -->
  <div class="section-header d-flex justify-content-between align-items-center">
    <div>
      <h1><i class="fas fa-tachometer-alt mr-2"></i><?= esc($t['dashboard']) ?></h1>
    </div>
    <?php if (isset($user_has_hibah_flag) && $user_has_hibah_flag): ?>
      <div class="dash-flag-header">
        <span class="dash-flag-dot"></span>
        <span class="dash-flag-text"><i class="fas fa-flag mr-1"></i><?= esc($t['flag_active']) ?></span>
        <span class="dash-flag-count"><?= (int)($user_approved_hibah_count ?? 0) ?></span>
      </div>
    <?php elseif (isset($user_flag_status) && $user_flag_status === 'pending'): ?>
      <div class="dash-flag-header" style="border-color:rgba(245,158,11,.4);">
        <span class="dash-flag-dot" style="background:#fbbf24;box-shadow:0 0 0 2px rgba(251,191,36,.4);"></span>
        <span class="dash-flag-text"><i class="fas fa-clock mr-1"></i><?= esc($t['flag_pending']) ?></span>
      </div>
    <?php endif; ?>
  </div>

  <div class="section-body">

    <?php if (session()->getFlashdata('success')): ?>
      <?php 
        $flashMsg = session()->getFlashdata('success');
        $isLoginGreeting = ($flashMsg === 'Selamat datang.' || $flashMsg === 'Welcome.');
        
        $alertTitle = esc($t['congrats']);
        $alertText  = esc($flashMsg);
        
        if ($isLoginGreeting) {
            $hour = (int) date('H');
            if ($hour >= 4 && $hour < 11) {
                $timeGreetingID = 'Selamat Pagi';
                $timeGreetingEN = 'Good Morning';
            } elseif ($hour >= 11 && $hour < 15) {
                $timeGreetingID = 'Selamat Siang';
                $timeGreetingEN = 'Good Afternoon';
            } elseif ($hour >= 15 && $hour < 18) {
                $timeGreetingID = 'Selamat Sore';
                $timeGreetingEN = 'Good Afternoon';
            } else {
                $timeGreetingID = 'Selamat Malam';
                $timeGreetingEN = 'Good Evening';
            }
            
            $alertTitle = $lang === 'en' ? $timeGreetingEN : $timeGreetingID;
            $userName = esc(userLogin()->user_name ?? 'User');
            $alertText = $lang === 'en' ? "Welcome, {$userName}!" : "Selamat datang, {$userName}!";
        }
      ?>
      <div class="alert alert-premium-success alert-dismissible show fade">
        <div class="alert-body d-flex align-items-center">
          <div class="alert-icon mr-3">
            <i class="fas fa-check-circle"></i>
          </div>
          <div class="alert-content flex-grow-1">
            <h6 class="alert-title mb-0"><?= $alertTitle ?></h6>
            <p class="alert-text mb-0"><?= $alertText ?></p>
          </div>
          <button class="close ml-3" data-dismiss="alert">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>
    <?php endif; ?>

    <!-- ===== ROW 1: Top Stat Cards (User + Laporan) ===== -->
    <div class="row" style="margin-bottom:20px;">

      <!-- Total User -->
      <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12 mb-3">
        <div class="dash-stat-card dash-stat-card--indigo">
          <div class="dash-stat-icon dash-stat-icon--indigo">
            <i class="fas fa-users"></i>
          </div>
          <div class="dash-stat-body">
            <div class="dash-stat-label"><?= esc($t['total_user']) ?></div>
            <div class="dash-stat-value" data-count="<?= countDataPengguna() ?>">0</div>
            <span class="dash-stat-badge" style="background:rgba(99,102,241,.1);color:#6366f1;"><?= esc($t['live_data']) ?></span>
          </div>
        </div>
      </div>

      <!-- Total Dosen -->
      <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12 mb-3">
        <div class="dash-stat-card dash-stat-card--violet">
          <div class="dash-stat-icon dash-stat-icon--violet">
            <i class="fas fa-chalkboard-teacher"></i>
          </div>
          <div class="dash-stat-body">
            <div class="dash-stat-label"><?= esc($t['dosen']) ?></div>
            <div class="dash-stat-value" data-count="<?= countDataDosen() ?>">0</div>
            <span class="dash-stat-badge" style="background:rgba(139,92,246,.1);color:#8b5cf6;"><?= esc($t['aktif']) ?></span>
          </div>
        </div>
      </div>

      <!-- Total Mitra -->
      <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12 mb-3">
        <div class="dash-stat-card dash-stat-card--amber">
          <div class="dash-stat-icon dash-stat-icon--amber">
            <i class="fas fa-handshake"></i>
          </div>
          <div class="dash-stat-body">
            <div class="dash-stat-label"><?= esc($t['mitra']) ?></div>
            <div class="dash-stat-value" data-count="<?= countDataMitra() ?>">0</div>
            <span class="dash-stat-badge" style="background:rgba(245,158,11,.1);color:#f59e0b;"><?= esc($t['terdaftar']) ?></span>
          </div>
        </div>
      </div>

      <!-- Total Laporan -->
      <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12 mb-3">
        <div class="dash-stat-card dash-stat-card--emerald">
          <div class="dash-stat-icon dash-stat-icon--emerald">
            <i class="fas fa-file-alt"></i>
          </div>
          <div class="dash-stat-body">
            <div class="dash-stat-label"><?= esc($t['total_laporan']) ?></div>
            <div class="dash-stat-value" data-count="<?= $totalLaporanKeseluruhan ?>">0</div>
            <span class="dash-stat-badge" style="background:rgba(16,185,129,.1);color:#10b981;"><?= esc($t['semua_periode']) ?></span>
          </div>
        </div>
      </div>
    </div>

    <!-- ===== ROW 2: Charts (User Pie + Laporan Line) ===== -->
    <div class="row mb-3">

      <!-- Grafik Pengguna -->
      <div class="col-xl-4 col-lg-5 col-md-12 mb-3">
        <div class="dash-chart-card h-100">
          <div class="dash-chart-header">
            <h5 class="dash-chart-title">
              <span class="dot"></span>
              <?= esc($t['data_pengguna']) ?>
            </h5>
            <span class="badge-live"><?= esc($t['live_data']) ?></span>
          </div>
          <div class="dash-chart-body" style="height:280px;">
            <div class="dash-skeleton" id="skel-user">
              <div class="dash-skeleton-bar">
                <span style="height:80px;"></span>
                <span style="height:120px;"></span>
              </div>
            </div>
            <canvas id="userChart" style="width:100%;height:100%;"></canvas>
          </div>
        </div>
      </div>

      <!-- Grafik Laporan per Periode -->
      <div class="col-xl-8 col-lg-7 col-md-12 mb-3">
        <div class="dash-chart-card h-100">
          <div class="dash-chart-header">
            <h5 class="dash-chart-title">
              <span class="dot" style="background:#06b6d4;"></span>
              <?= esc($t['total_laporan_per_periode']) ?>
            </h5>
            <div class="dash-chart-actions">
              <span class="badge" style="background:rgba(6,182,212,.1);color:#06b6d4;font-size:.7rem;font-weight:600;padding:4px 10px;border-radius:20px;"><?= $totalLaporanKeseluruhan ?> Total</span>
              <a href="#" class="dash-btn-icon" id="dl-laporan" title="<?= esc($t['download_chart']) ?>"><i class="fas fa-download"></i></a>
            </div>
          </div>
          <div class="dash-chart-body" style="height:280px;">
            <div class="dash-skeleton" id="skel-laporan">
              <div class="dash-skeleton-bar">
                <span style="height:50px;"></span><span style="height:90px;"></span>
                <span style="height:70px;"></span><span style="height:110px;"></span><span style="height:80px;"></span>
              </div>
            </div>
            <canvas id="laporanChart" style="width:100%;height:100%;"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- ===== ROW 3: Charts (Ketua Anggota + Grafik Luaran) ===== -->
    <div class="row mb-3">

      <!-- Grafik Ketua & Anggota -->
      <div class="col-xl-4 col-lg-5 col-md-12 mb-3">
        <div class="dash-chart-card h-100">
          <div class="dash-chart-header">
            <h5 class="dash-chart-title">
              <span class="dot" style="background:#8b5cf6;"></span>
              <?= esc($t['grafik_ketua_anggota']) ?>
            </h5>
            <div class="dash-chart-actions">
              <select id="selectProdi" class="dash-select" aria-label="Filter Prodi">
                <option value=""><?= esc($t['semua_prodi']) ?></option>
                <?php foreach ($dataPerProdi as $item): ?>
                  <option value="<?= esc($item['jurusan_id']) ?>"><?= esc($item['jurusan_name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="dash-chart-body" style="height:280px;">
            <canvas id="chartProdi" style="width:100%;height:100%;"></canvas>
          </div>
        </div>
      </div>

      <!-- Grafik Luaran -->
      <div class="col-xl-8 col-lg-7 col-md-12 mb-3">
        <div class="dash-chart-card h-100">
          <div class="dash-chart-header">
            <h5 class="dash-chart-title">
              <span class="dot" style="background:#10b981;"></span>
              <?= esc($t['grafik_luaran']) ?>
            </h5>
            <div class="dash-chart-actions">
              <select id="selectJurusanLuaran" class="dash-select" aria-label="Filter Jurusan Luaran">
                <option value=""><?= esc($t['semua_prodi']) ?></option>
                <?php foreach ($dataPerProdi as $item): ?>
                  <option value="<?= esc($item['jurusan_id']) ?>"><?= esc($item['jurusan_name']) ?></option>
                <?php endforeach; ?>
              </select>
              <a href="#" class="dash-btn-icon" id="dl-luaran" title="<?= esc($t['download_chart']) ?>"><i class="fas fa-download"></i></a>
            </div>
          </div>
          <div class="dash-chart-body" style="height:280px;position:relative;">
            <canvas id="luaranChart" style="width:100%;height:100%;"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- ===== SECTION: Sitemap / Statistik Wilayah ===== -->
    <div class="dash-section-divider">
      <span><i class="fas fa-sitemap mr-1"></i> <?= esc($t['statistik_wilayah']) ?></span>
    </div>

    <?php
    $smapProvinsi = (int)countData('tbl_provinsi');
    $smapKota     = (int)countData('tbl_kota');
    $smapFakultas = (int)countData('tbl_fakultas');
    $smapProdi    = (int)$countJurusanUnik;
    $smapMax      = max($smapProvinsi, $smapKota, $smapFakultas, $smapProdi, 1);
    ?>
    <div class="dash-sitemap-grid mb-4">

      <div class="dash-sitemap-card dash-sitemap-card--indigo">
        <div class="dash-sitemap-icon dash-sitemap-icon--indigo"><i class="fas fa-map-marked-alt"></i></div>
        <div class="dash-sitemap-value" data-count="<?= $smapProvinsi ?>">0</div>
        <div class="dash-sitemap-label"><?= esc($t['total_provinsi']) ?></div>
        <div class="dash-sitemap-bar">
          <div class="dash-sitemap-bar-fill" style="background:linear-gradient(90deg,#6366f1,#8b5cf6);" data-target="<?= round($smapProvinsi/$smapMax*100) ?>"></div>
        </div>
      </div>

      <div class="dash-sitemap-card dash-sitemap-card--rose">
        <div class="dash-sitemap-icon dash-sitemap-icon--rose"><i class="fas fa-city"></i></div>
        <div class="dash-sitemap-value" data-count="<?= $smapKota ?>">0</div>
        <div class="dash-sitemap-label"><?= esc($t['total_kota']) ?></div>
        <div class="dash-sitemap-bar">
          <div class="dash-sitemap-bar-fill" style="background:linear-gradient(90deg,#f43f5e,#f59e0b);" data-target="<?= round($smapKota/$smapMax*100) ?>"></div>
        </div>
      </div>

      <div class="dash-sitemap-card dash-sitemap-card--amber">
        <div class="dash-sitemap-icon dash-sitemap-icon--amber"><i class="fas fa-school"></i></div>
        <div class="dash-sitemap-value" data-count="<?= $smapFakultas ?>">0</div>
        <div class="dash-sitemap-label"><?= esc($t['total_fakultas']) ?></div>
        <div class="dash-sitemap-bar">
          <div class="dash-sitemap-bar-fill" style="background:linear-gradient(90deg,#f59e0b,#f97316);" data-target="<?= round($smapFakultas/$smapMax*100) ?>"></div>
        </div>
      </div>

      <div class="dash-sitemap-card dash-sitemap-card--emerald">
        <div class="dash-sitemap-icon dash-sitemap-icon--emerald"><i class="fas fa-graduation-cap"></i></div>
        <div class="dash-sitemap-value" data-count="<?= $smapProdi ?>">0</div>
        <div class="dash-sitemap-label"><?= esc($t['total_prodi']) ?></div>
        <div class="dash-sitemap-bar">
          <div class="dash-sitemap-bar-fill" style="background:linear-gradient(90deg,#10b981,#06b6d4);" data-target="<?= round($smapProdi/$smapMax*100) ?>"></div>
        </div>
      </div>

    </div>

  </div>
</section>

<!-- ===== JS ZONE ===== -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>

<script>
(function() {
  'use strict';

  const I18N = <?= json_encode($i18n) ?>;

  // ===== UTILS =====
  function hideSkeleton(id) {
    const el = document.getElementById(id);
    if (el) { el.classList.add('hidden'); setTimeout(() => el.remove(), 350); }
  }

  function wrapLabel(str, max = 14) {
    if (!str) return '';
    const words = String(str).split(' ');
    const lines = []; let line = '';
    for (const w of words) {
      if ((line + (line ? ' ' : '') + w).length <= max) { line += (line ? ' ' : '') + w; }
      else { if (line) lines.push(line); line = w.length > max ? (lines.push(...w.match(new RegExp(`.{1,${max}}`, 'g'))), '') : w; }
    }
    if (line) lines.push(line);
    return lines;
  }

  // ===== COUNTER ANIMATION =====
  function animateCount(el) {
    const target = parseInt(el.dataset.count, 10) || 0;
    const duration = 1000;
    const start = performance.now();
    function tick(now) {
      const p = Math.min((now - start) / duration, 1);
      const ease = p < .5 ? 2 * p * p : -1 + (4 - 2 * p) * p;
      el.textContent = Math.round(ease * target).toLocaleString('id-ID');
      if (p < 1) requestAnimationFrame(tick);
    }
    requestAnimationFrame(tick);
  }

  const counterObs = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        animateCount(e.target);
        counterObs.unobserve(e.target);
      }
    });
  }, { threshold: .3 });

  document.querySelectorAll('[data-count]').forEach(el => counterObs.observe(el));

  // ===== CHART DEFAULTS =====
  Chart.defaults.font.family = "'Inter', -apple-system, sans-serif";
  Chart.defaults.font.size   = 12;
  Chart.defaults.color       = '#64748b';

  const COLORS = {
    indigo:  '#6366f1', violet: '#8b5cf6', cyan:    '#06b6d4',
    emerald: '#10b981', amber:  '#f59e0b', rose:    '#f43f5e',
    slate:   '#64748b',
  };

  // ===== CHART 1: USER BAR =====
  (function() {
    const ctx = document.getElementById('userChart');
    if (!ctx) return;
    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: [I18N.dosen, I18N.mitra],
        datasets: [{
          data: [<?= countDataDosen() ?>, <?= countDataMitra() ?>],
          backgroundColor: [COLORS.indigo, COLORS.amber],
          borderWidth: 0,
          hoverOffset: 8,
        }]
      },
      options: {
        responsive: true, maintainAspectRatio: false,
        cutout: '68%',
        plugins: {
          legend: { position: 'bottom', labels: { padding: 16, usePointStyle: true, pointStyleWidth: 10, font: { weight: '600', size: 12 } } },
          datalabels: {
            color: '#fff', font: { weight: '700', size: 13 },
            formatter: (v) => v > 0 ? v.toLocaleString('id-ID') : ''
          },
          tooltip: {
            backgroundColor: 'rgba(15,23,42,.9)', borderRadius: 10,
            padding: 10, titleFont: { weight: '600' }
          }
        },
        animation: { animateRotate: true, duration: 900 }
      },
      plugins: [ChartDataLabels]
    });
    hideSkeleton('skel-user');
  })();

  // ===== CHART 2: LAPORAN LINE =====
  (function() {
    const ctx = document.getElementById('laporanChart');
    if (!ctx) return;
    const periodeLabels = <?= $periodeLabels ?>;
    const periodeTotals = <?= $periodeTotals ?>;

    const grad = ctx.getContext('2d').createLinearGradient(0, 0, 0, 260);
    grad.addColorStop(0, 'rgba(6,182,212,.25)');
    grad.addColorStop(1, 'rgba(6,182,212,.01)');

    const laporanChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: periodeLabels,
        datasets: [{
          label: I18N.total_laporan,
          data: periodeTotals,
          borderColor: COLORS.cyan, backgroundColor: grad,
          fill: true, tension: .4,
          pointBackgroundColor: '#fff', pointBorderColor: COLORS.cyan,
          pointBorderWidth: 2, pointRadius: 5, pointHoverRadius: 8,
        }]
      },
      options: {
        responsive: true, maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          datalabels: {
            color: COLORS.cyan, font: { weight: '700', size: 11 },
            anchor: 'end', align: 'top', offset: 4,
            formatter: v => v > 0 ? v : ''
          },
          tooltip: {
            backgroundColor: 'rgba(15,23,42,.9)', borderRadius: 10,
            padding: 10, titleFont: { weight: '600' },
            callbacks: { label: ctx => `${I18N.total_laporan}: ${ctx.parsed.y}` }
          }
        },
        scales: {
          y: {
            beginAtZero: true, grid: { color: '#f1f5f9' },
            ticks: { precision: 0, color: '#94a3b8' },
            title: { display: true, text: I18N.jumlah_laporan, color: '#94a3b8', font: { size: 11 } }
          },
          x: {
            grid: { display: false },
            ticks: { color: '#94a3b8', maxRotation: 30, minRotation: 0 },
          }
        },
        animation: { duration: 1000 }
      },
      plugins: [ChartDataLabels]
    });
    hideSkeleton('skel-laporan');

    // Download button
    document.getElementById('dl-laporan')?.addEventListener('click', (e) => {
      e.preventDefault();
      const a = document.createElement('a');
      a.href = laporanChart.toBase64Image('image/png', 1);
      a.download = 'grafik-laporan.png'; a.click();
    });
  })();

  // ===== CHART 3: KETUA & ANGGOTA BAR =====
  (function() {
    const ctx    = document.getElementById('chartProdi');
    const selEl  = document.getElementById('selectProdi');
    if (!ctx) return;

    const dataPerProdi    = <?= json_encode($dataPerProdi) ?>;
    const _rawTotals      = <?= json_encode($dataKetuaAnggota ?? []) ?>;
    const dataKetuaAnggota = Array.isArray(_rawTotals) ? (_rawTotals[0] || {}) : (_rawTotals || {});

    function computeTotal() {
      let k = 0, a = 0;
      (dataPerProdi || []).forEach(r => { k += +r.jumlah_ketua || 0; a += +r.jumlah_anggota || 0; });
      return { total_ketua: k, total_anggota: a };
    }

    function buildPayload(id) {
      if (!id) {
        const safe = Object.keys(dataKetuaAnggota).length ? dataKetuaAnggota : computeTotal();
        return { labels: ['TOTAL'], ketua: [+safe.total_ketua||0], anggota: [+safe.total_anggota||0] };
      }
      const row = (dataPerProdi||[]).find(r => String(r.jurusan_id) === String(id));
      return {
        labels:  [row ? row.jurusan_name : I18N.not_found],
        ketua:   [row ? +row.jumlah_ketua   || 0 : 0],
        anggota: [row ? +row.jumlah_anggota || 0 : 0]
      };
    }

    let chartProdi = null;
    function renderChart(id) {
      const { labels, ketua, anggota } = buildPayload(id);
      if (chartProdi) {
        chartProdi.data.labels = labels;
        chartProdi.data.datasets[0].data = ketua;
        chartProdi.data.datasets[1].data = anggota;
        chartProdi.update('active'); return;
      }
      chartProdi = new Chart(ctx, {
        type: 'bar',
        data: {
          labels,
          datasets: [
            { label: I18N.jumlah_ketua,   data: ketua,   backgroundColor: COLORS.indigo, borderRadius: 6, borderSkipped: false },
            { label: I18N.jumlah_anggota, data: anggota, backgroundColor: COLORS.cyan, borderRadius: 6, borderSkipped: false },
          ]
        },
        options: {
          responsive: true, maintainAspectRatio: false,
          plugins: {
            legend: { position: 'bottom', labels: { usePointStyle: true, pointStyleWidth: 10, padding: 14, font: { weight: '600' } } },
            datalabels: {
              color: '#fff', font: { weight: '700', size: 12 }, anchor: 'center', align: 'center',
              formatter: v => v > 0 ? v : ''
            },
            tooltip: { backgroundColor: 'rgba(15,23,42,.9)', borderRadius: 10, padding: 10 }
          },
          scales: {
            x: { grid: { display: false } },
            y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { precision: 0, color: '#94a3b8' } }
          },
          animation: { duration: 600 }
        },
        plugins: [ChartDataLabels]
      });
    }

    renderChart(selEl?.value || '');
    document.addEventListener("DOMContentLoaded", function() {
      if (selEl) {
        selEl.addEventListener('change', () => renderChart(selEl.value));
      }
    });
  })();

  // ===== CHART 4: LUARAN BAR =====
  (function() {
    const ctx   = document.getElementById('luaranChart');
    const selEl = document.getElementById('selectJurusanLuaran');
    if (!ctx) return;

    const chartData      = <?= json_encode($chartData ?? []) ?>;
    const luaranChartData = <?= json_encode($luaranChartData ?? []) ?>;

    const defaultLabels = chartData.map(r => r.luaran_name);
    const defaultValues = chartData.map(r => parseInt(r.total_laporan, 10) || 0);

    function buildPayload(id) {
      if (!id) return { labels: defaultLabels, values: defaultValues };
      const filtered = luaranChartData.filter(r => String(r.jurusan_id) === String(id));
      const labels   = [...new Set(filtered.map(r => r.luaran_name))];
      const values   = labels.map(ln => {
        const m = filtered.find(r => r.luaran_name === ln);
        return m ? parseInt(m.total_laporan, 10) || 0 : 0;
      });
      return { labels, values };
    }

    let chartLuaran = null;
    function renderChart(id) {
      const { labels, values } = buildPayload(id);
      if (chartLuaran) {
        chartLuaran.data.labels          = labels;
        chartLuaran.data.datasets[0].data = values;
        chartLuaran.update('active'); return;
      }
      chartLuaran = new Chart(ctx, {
        type: 'bar',
        data: {
          labels,
          datasets: [{
            label: I18N.jumlah,
            data: values,
            backgroundColor: labels.map((_, i) => `hsl(${160 + i * 18}, 70%, 55%)`),
            borderRadius: 6, borderSkipped: false,
          }]
        },
        options: {
          responsive: true, maintainAspectRatio: false,
          indexAxis: labels.length > 6 ? 'y' : 'x',
          plugins: {
            legend: { display: false },
            datalabels: {
              color: '#fff', font: { weight: '700', size: 11 }, anchor: 'center', align: 'center',
              formatter: v => v > 0 ? v : ''
            },
            tooltip: {
              backgroundColor: 'rgba(15,23,42,.9)', borderRadius: 10, padding: 10,
              callbacks: {
                title: items => wrapLabel(items[0].label, 30).join(' '),
                label: ctx  => `${I18N.jumlah}: ${ctx.parsed[labels.length > 6 ? 'x' : 'y']}`
              }
            }
          },
          scales: {
            x: { grid: { display: labels.length > 6 }, ticks: { color: '#94a3b8', precision: 0, callback: v => labels.length > 6 ? v : undefined } },
            y: {
              grid: { color: '#f1f5f9' }, ticks: { color: '#94a3b8', precision: 0,
                callback: (v, i) => labels.length > 6 ? wrapLabel(labels[i], 18) : v
              }
            }
          },
          animation: { duration: 700 }
        },
        plugins: [ChartDataLabels]
      });
    }

    renderChart(selEl?.value || '');
    document.addEventListener("DOMContentLoaded", function() {
      if (selEl) {
        selEl.addEventListener('change', () => renderChart(selEl.value));
      }
    });

    // Download button
    document.getElementById('dl-luaran')?.addEventListener('click', (e) => {
      e.preventDefault();
      const a = document.createElement('a');
      a.href = chartLuaran.toBase64Image('image/png', 1);
      a.download = 'grafik-luaran.png'; a.click();
    });
  })();

})();
</script>
<?= $this->endSection() ?>
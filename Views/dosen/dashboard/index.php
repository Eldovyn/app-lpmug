<?= $this->extend('layouts/default') ?>

<?php
// ===================== LANG =====================
$lang = $_COOKIE['lang'] ?? 'id';
$lang = in_array($lang, ['id', 'en'], true) ? $lang : 'id';

// ===================== DICTIONARY =====================
$dict = [
  'id' => [
    'dashboard'              => 'Dashboard',
    'congrats'               => 'Selamat!',
    'total_user'             => 'Total User',
    'total'                  => 'Total',
    'total_laporan_per_periode' => 'Laporan per Periode',
    'grafik_ketua_anggota'   => 'Ketua & Anggota',
    'grafik_luaran'          => 'Grafik Luaran',
    'semua_prodi'            => 'Semua Prodi',
    'total_provinsi'         => 'Provinsi',
    'total_kota'             => 'Kota',
    'total_fakultas'         => 'Fakultas',
    'total_prodi'            => 'Program Studi',
    'statistik_wilayah'      => 'Statistik Wilayah',
    'live_data'              => 'Live Data',
    'my_flags'               => 'My Flags',
    'hibah_approved'         => 'Hibah Disetujui',
    'flag_active_msg'        => 'Flag aktif untuk hibah yang telah disetujui',
    'view_hibah'             => 'Lihat Hibah',
    'total_flag'             => 'Total Flag',
    'no_active_flags'        => 'Belum ada flag aktif',
    'flags_appear_msg'       => 'Flag akan muncul setelah hibah Anda disetujui admin',
    'recent_hibah'           => 'Hibah Terbaru',
    'view_all'               => 'Lihat Semua',
    'hibah_title'            => 'Judul Hibah',
    'date'                   => 'Tanggal',
    'no_hibah_yet'           => 'Belum ada hibah',
    'start_first_hibah'      => 'Mulai ajukan hibah pertama Anda',
    'submit_hibah'           => 'Ajukan Hibah',
    'dosen'                  => 'Dosen',
    'mitra'                  => 'Mitra',
    'jumlah_pengguna'        => 'Jumlah Pengguna',
    'jumlah'                 => 'Jumlah',
    'jenis_pengguna'         => 'Jenis Pengguna',
    'total_laporan'          => 'Total Laporan',
    'jumlah_laporan'         => 'Jumlah Laporan',
    'periode'                => 'Periode',
    'jumlah_ketua'           => 'Jumlah Ketua',
    'jumlah_anggota'         => 'Jumlah Anggota',
    'jurusan_prefix'         => 'Jurusan',
    'luaran_prefix'          => 'Luaran',
    'not_found'              => '(Tidak ditemukan)',
    'download_chart'         => 'Unduh Grafik',
  ],
  'en' => [
    'dashboard'              => 'Dashboard',
    'congrats'               => 'Congratulations!',
    'total_user'             => 'Total Users',
    'total'                  => 'Total',
    'total_laporan_per_periode' => 'Reports per Period',
    'grafik_ketua_anggota'   => 'Chairs & Members',
    'grafik_luaran'          => 'Output Chart',
    'semua_prodi'            => 'All Programs',
    'total_provinsi'         => 'Provinces',
    'total_kota'             => 'Cities',
    'total_fakultas'         => 'Faculties',
    'total_prodi'            => 'Programs',
    'statistik_wilayah'      => 'Regional Statistics',
    'live_data'              => 'Live Data',
    'my_flags'               => 'My Flags',
    'hibah_approved'         => 'Hibah Approved',
    'flag_active_msg'        => 'Flag active for approved hibah',
    'view_hibah'             => 'View Hibah',
    'total_flag'             => 'Total Flags',
    'no_active_flags'        => 'No active flags yet',
    'flags_appear_msg'       => 'Flags will appear after your hibah is approved by admin',
    'recent_hibah'           => 'Recent Hibah',
    'view_all'               => 'View All',
    'hibah_title'            => 'Hibah Title',
    'date'                   => 'Date',
    'no_hibah_yet'           => 'No hibah yet',
    'start_first_hibah'      => 'Start submitting your first hibah',
    'submit_hibah'           => 'Submit Hibah',
    'dosen'                  => 'Lecturers',
    'mitra'                  => 'Partners',
    'jumlah_pengguna'        => 'Number of Users',
    'jumlah'                 => 'Count',
    'jenis_pengguna'         => 'User Types',
    'total_laporan'          => 'Total Reports',
    'jumlah_laporan'         => 'Number of Reports',
    'periode'                => 'Period',
    'jumlah_ketua'           => 'Number of Chairs',
    'jumlah_anggota'         => 'Number of Members',
    'jurusan_prefix'         => 'Program',
    'luaran_prefix'          => 'Output',
    'not_found'              => '(Not found)',
    'download_chart'         => 'Download Chart',
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

  <div class="section-header">
    <h1><i class="fas fa-tachometer-alt mr-2" style="opacity:.85;font-size:1.2rem;"></i><?= esc($t['dashboard']) ?></h1>
  </div>

  <div class="section-body">

    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success alert-dismissible show fade">
        <div class="alert-body">
          <button class="close" data-dismiss="alert">×</button>
          <b><?= esc($t['congrats']) ?></b> <?= session()->getFlashdata('success'); ?>
        </div>
      </div>
    <?php endif; ?>

    <!-- ===== ROW 1: Stat Cards ===== -->
    <div class="row mb-3">
      <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12 mb-3">
        <div class="dash-stat-card dash-stat-card--indigo">
          <div class="dash-stat-icon dash-stat-icon--indigo"><i class="fas fa-users"></i></div>
          <div class="dash-stat-body">
            <div class="dash-stat-label"><?= esc($t['total_user']) ?></div>
            <div class="dash-stat-value" data-count="<?= countDataPengguna() ?>">0</div>
            <span class="dash-stat-badge" style="background:rgba(99,102,241,.1);color:#6366f1;"><?= esc($t['live_data']) ?></span>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12 mb-3">
        <div class="dash-stat-card dash-stat-card--cyan">
          <div class="dash-stat-icon dash-stat-icon--cyan"><i class="fas fa-file-alt"></i></div>
          <div class="dash-stat-body">
            <div class="dash-stat-label"><?= esc($t['total_laporan']) ?></div>
            <div class="dash-stat-value" data-count="<?= $totalLaporanKeseluruhan ?>">0</div>
            <span class="dash-stat-badge" style="background:rgba(6,182,212,.1);color:#06b6d4;">Semua Periode</span>
          </div>
        </div>
      </div>

      <?php if (!empty($user_hibah_flags)): ?>
      <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12 mb-3">
        <div class="dash-stat-card dash-stat-card--emerald">
          <div class="dash-stat-icon dash-stat-icon--emerald"><i class="fas fa-flag"></i></div>
          <div class="dash-stat-body">
            <div class="dash-stat-label"><?= esc($t['my_flags']) ?></div>
            <div class="dash-stat-value" data-count="<?= count($user_hibah_flags) ?>">0</div>
            <span class="dash-stat-badge" style="background:rgba(16,185,129,.1);color:#10b981;"><?= esc($t['hibah_approved']) ?></span>
          </div>
        </div>
      </div>
      <?php endif; ?>

      <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12 mb-3">
        <div class="dash-stat-card dash-stat-card--violet">
          <div class="dash-stat-icon dash-stat-icon--violet"><i class="fas fa-graduation-cap"></i></div>
          <div class="dash-stat-body">
            <div class="dash-stat-label"><?= esc($t['total_prodi']) ?></div>
            <div class="dash-stat-value" data-count="<?= $countJurusanUnik ?>">0</div>
            <span class="dash-stat-badge" style="background:rgba(139,92,246,.1);color:#8b5cf6;">Prodi Aktif</span>
          </div>
        </div>
      </div>
    </div>

    <!-- ===== ROW 2: Charts ===== -->
    <div class="row mb-3">
      <!-- Pengguna Doughnut -->
      <div class="col-xl-4 col-lg-5 col-md-12 mb-3">
        <div class="dash-chart-card h-100">
          <div class="dash-chart-header">
            <h5 class="dash-chart-title"><span class="dot"></span><?= esc($t['total_user']) ?></h5>
            <span class="badge-live"><?= esc($t['live_data']) ?></span>
          </div>
          <div class="dash-chart-body" style="height:270px;">
            <canvas id="userChart" style="width:100%;height:100%;"></canvas>
          </div>
        </div>
      </div>

      <!-- Laporan Line -->
      <div class="col-xl-8 col-lg-7 col-md-12 mb-3">
        <div class="dash-chart-card h-100">
          <div class="dash-chart-header">
            <h5 class="dash-chart-title"><span class="dot" style="background:#06b6d4;"></span><?= esc($t['total_laporan_per_periode']) ?></h5>
            <div class="dash-chart-actions">
              <span class="badge" style="background:rgba(6,182,212,.1);color:#06b6d4;font-size:.7rem;font-weight:600;padding:4px 10px;border-radius:20px;"><?= $totalLaporanKeseluruhan ?> Total</span>
            </div>
          </div>
          <div class="dash-chart-body" style="height:270px;">
            <canvas id="laporanChart" style="width:100%;height:100%;"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- ===== ROW 3: Ketua Anggota + Luaran ===== -->
    <div class="row mb-3">
      <div class="col-xl-4 col-lg-5 col-md-12 mb-3">
        <div class="dash-chart-card h-100">
          <div class="dash-chart-header">
            <h5 class="dash-chart-title"><span class="dot" style="background:#8b5cf6;"></span><?= esc($t['grafik_ketua_anggota']) ?></h5>
            <div class="dash-chart-actions">
              <select id="selectProdi" class="dash-select">
                <option value=""><?= esc($t['semua_prodi']) ?></option>
                <?php foreach ($dataPerProdi as $item): ?>
                  <option value="<?= esc($item['jurusan_id']) ?>"><?= esc($item['jurusan_name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="dash-chart-body" style="height:270px;">
            <canvas id="chartProdi" style="width:100%;height:100%;"></canvas>
          </div>
        </div>
      </div>

      <div class="col-xl-8 col-lg-7 col-md-12 mb-3">
        <div class="dash-chart-card h-100">
          <div class="dash-chart-header">
            <h5 class="dash-chart-title"><span class="dot" style="background:#10b981;"></span><?= esc($t['grafik_luaran']) ?></h5>
            <div class="dash-chart-actions">
              <select id="selectJurusanLuaran" class="dash-select">
                <option value=""><?= esc($t['semua_prodi']) ?></option>
                <?php foreach ($dataPerProdi as $item): ?>
                  <option value="<?= esc($item['jurusan_id']) ?>"><?= esc($item['jurusan_name']) ?></option>
                <?php endforeach; ?>
              </select>
              <a href="#" class="dash-btn-icon" id="dl-luaran" title="<?= esc($t['download_chart']) ?>"><i class="fas fa-download"></i></a>
            </div>
          </div>
          <div class="dash-chart-body" style="height:270px;">
            <canvas id="luaranChart" style="width:100%;height:100%;"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- ===== ROW 4: Hibah Flags ===== -->
    <?php if (!empty($user_hibah_flags)): ?>
    <div class="dash-section-divider"><span><i class="fas fa-flag mr-1"></i> <?= esc($t['my_flags']) ?></span></div>
    <div class="row mb-3">
      <?php foreach ($user_hibah_flags as $flag): ?>
      <div class="col-xl-4 col-lg-6 col-md-6 mb-3">
        <div class="dash-chart-card" style="border-left:4px solid #10b981;">
          <div class="dash-chart-header" style="border-bottom:none;padding-bottom:10px;">
            <h5 class="dash-chart-title" style="font-size:.85rem;">
              <span class="dot" style="background:#10b981;"></span>
              <?= esc($flag->judul_hibah ?? 'Hibah #'.($flag->hibah_id ?? '')) ?>
            </h5>
            <span class="badge-live" style="background:linear-gradient(135deg,#10b981,#06b6d4);">Approved</span>
          </div>
          <div style="padding:0 22px 16px;">
            <p class="text-muted mb-2" style="font-size:.8rem;">
              <i class="fas fa-calendar-alt mr-1" style="color:#10b981;"></i>
              <?= esc($flag->created_at ?? '-') ?>
            </p>
            <a href="<?= site_url('hibah/detail/'.($flag->hibah_id ?? '')) ?>" class="dash-btn-icon" style="width:auto;padding:5px 14px;border-radius:8px;font-size:.78rem;font-weight:600;height:auto;">
              <i class="fas fa-eye mr-1"></i> <?= esc($t['view_hibah']) ?>
            </a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="dash-section-divider"><span><i class="fas fa-flag mr-1"></i> <?= esc($t['my_flags']) ?></span></div>
    <div class="row mb-4">
      <div class="col-12">
        <div class="dash-chart-card text-center py-5" style="border:2px dashed #e2e8f0;">
          <i class="fas fa-flag" style="font-size:2.5rem;color:#e2e8f0;margin-bottom:12px;display:block;"></i>
          <p class="font-weight-600 mb-1" style="color:#64748b;"><?= esc($t['no_active_flags']) ?></p>
          <p class="text-muted mb-0" style="font-size:.82rem;"><?= esc($t['flags_appear_msg']) ?></p>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- ===== Statistik Wilayah ===== -->
    <div class="dash-section-divider"><span><i class="fas fa-map-marked-alt mr-1"></i> <?= esc($t['statistik_wilayah']) ?></span></div>
    <div class="row mb-4">
      <?php
      $statItems = [
        ['label'=>$t['total_provinsi'], 'icon'=>'fa-location-arrow', 'color'=>'indigo',  'value'=>countData('tbl_provinsi')],
        ['label'=>$t['total_kota'],     'icon'=>'fa-city',           'color'=>'rose',    'value'=>countData('tbl_kota')],
        ['label'=>$t['total_fakultas'], 'icon'=>'fa-school',         'color'=>'amber',   'value'=>countData('tbl_fakultas')],
        ['label'=>$t['total_prodi'],    'icon'=>'fa-graduation-cap', 'color'=>'emerald', 'value'=>$countJurusanUnik],
      ];
      foreach ($statItems as $i => $s):
      ?>
      <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6 mb-3">
        <div class="dash-stat-card dash-stat-card--<?= $s['color'] ?>" style="animation-delay:<?= ($i * .07 + .2) ?>s;">
          <div class="dash-stat-icon dash-stat-icon--<?= $s['color'] ?>"><i class="fas <?= $s['icon'] ?>"></i></div>
          <div class="dash-stat-body">
            <div class="dash-stat-label"><?= esc($s['label']) ?></div>
            <div class="dash-stat-value" data-count="<?= (int)$s['value'] ?>">0</div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>

<!-- ===== JS ===== -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
<script>
(function() {
  'use strict';
  const I18N = <?= json_encode($i18n) ?>;
  const COLORS = { indigo:'#6366f1', violet:'#8b5cf6', cyan:'#06b6d4', emerald:'#10b981', amber:'#f59e0b', rose:'#f43f5e' };

  function wrapLabel(str, max = 14) {
    if (!str) return '';
    const words = String(str).split(' '); const lines = []; let line = '';
    for (const w of words) {
      if ((line+(line?' ':'')+w).length <= max) { line += (line?' ':'')+w; }
      else { if (line) lines.push(line); line = w; }
    }
    if (line) lines.push(line); return lines;
  }

  // Counter animation
  const obs = new IntersectionObserver(entries => {
    entries.forEach(e => {
      if (!e.isIntersecting) return;
      const el = e.target; const target = parseInt(el.dataset.count,10)||0;
      const start = performance.now(); const dur = 900;
      function tick(now) {
        const p = Math.min((now-start)/dur,1); const ease = p<.5?2*p*p:-1+(4-2*p)*p;
        el.textContent = Math.round(ease*target).toLocaleString('id-ID');
        if (p<1) requestAnimationFrame(tick);
      }
      requestAnimationFrame(tick); obs.unobserve(el);
    });
  }, { threshold:.3 });
  document.querySelectorAll('[data-count]').forEach(el => obs.observe(el));

  Chart.defaults.font.family = "'Inter', -apple-system, sans-serif";
  Chart.defaults.font.size   = 12;
  Chart.defaults.color       = '#64748b';

  // USER Doughnut
  (function() {
    const ctx = document.getElementById('userChart'); if (!ctx) return;
    new Chart(ctx, {
      type: 'doughnut',
      data: { labels: [I18N.dosen, I18N.mitra], datasets: [{ data: [<?= countDataDosen() ?>, <?= countDataMitra() ?>], backgroundColor: [COLORS.indigo, COLORS.amber], borderWidth: 0, hoverOffset: 8 }] },
      options: { responsive:true, maintainAspectRatio:false, cutout:'68%', plugins: { legend:{position:'bottom',labels:{padding:16,usePointStyle:true,pointStyleWidth:10,font:{weight:'600'}}}, datalabels:{color:'#fff',font:{weight:'700',size:13},formatter:v=>v>0?v.toLocaleString('id-ID'):''}, tooltip:{backgroundColor:'rgba(15,23,42,.9)',borderRadius:10,padding:10} }, animation:{animateRotate:true,duration:900} },
      plugins: [ChartDataLabels]
    });
  })();

  // LAPORAN Line
  (function() {
    const ctx = document.getElementById('laporanChart'); if (!ctx) return;
    const periodeLabels = <?= $periodeLabels ?>;
    const periodeTotals = <?= $periodeTotals ?>;
    const grad = ctx.getContext('2d').createLinearGradient(0,0,0,250);
    grad.addColorStop(0,'rgba(6,182,212,.25)'); grad.addColorStop(1,'rgba(6,182,212,.01)');
    new Chart(ctx, {
      type: 'line',
      data: { labels: periodeLabels, datasets: [{ label:I18N.total_laporan, data:periodeTotals, borderColor:COLORS.cyan, backgroundColor:grad, fill:true, tension:.4, pointBackgroundColor:'#fff', pointBorderColor:COLORS.cyan, pointBorderWidth:2, pointRadius:5, pointHoverRadius:8 }] },
      options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{display:false}, datalabels:{color:COLORS.cyan,font:{weight:'700',size:11},anchor:'end',align:'top',offset:4,formatter:v=>v>0?v:''}, tooltip:{backgroundColor:'rgba(15,23,42,.9)',borderRadius:10,padding:10} }, scales:{ y:{beginAtZero:true,grid:{color:'#f1f5f9'},ticks:{precision:0,color:'#94a3b8'}}, x:{grid:{display:false},ticks:{color:'#94a3b8',maxRotation:30}} }, animation:{duration:1000} },
      plugins: [ChartDataLabels]
    });
  })();

  // KETUA & ANGGOTA Bar
  (function() {
    const ctx = document.getElementById('chartProdi'); const selEl = document.getElementById('selectProdi'); if (!ctx) return;
    const dataPerProdi = <?= json_encode($dataPerProdi) ?>;
    const _rawTotals   = <?= json_encode($dataKetuaAnggota ?? []) ?>;
    const dka = Array.isArray(_rawTotals) ? (_rawTotals[0]||{}) : (_rawTotals||{});
    function computeTotal() { let k=0,a=0; (dataPerProdi||[]).forEach(r=>{k+= +r.jumlah_ketua||0;a+= +r.jumlah_anggota||0;}); return{total_ketua:k,total_anggota:a}; }
    function build(id) {
      if (!id) { const s=Object.keys(dka).length?dka:computeTotal(); return{labels:['TOTAL'],ketua:[+s.total_ketua||0],anggota:[+s.total_anggota||0]}; }
      const row=(dataPerProdi||[]).find(r=>String(r.jurusan_id)===String(id));
      return{labels:[row?row.jurusan_name:I18N.not_found],ketua:[row?+row.jumlah_ketua||0:0],anggota:[row?+row.jumlah_anggota||0:0]};
    }
    let chartProdi = null;
    function render(id) {
      const{labels,ketua,anggota}=build(id);
      if (chartProdi) { chartProdi.data.labels=labels; chartProdi.data.datasets[0].data=ketua; chartProdi.data.datasets[1].data=anggota; chartProdi.update('active'); return; }
      chartProdi = new Chart(ctx,{type:'bar',data:{labels,datasets:[{label:I18N.jumlah_ketua,data:ketua,backgroundColor:COLORS.indigo,borderRadius:6,borderSkipped:false},{label:I18N.jumlah_anggota,data:anggota,backgroundColor:COLORS.violet,borderRadius:6,borderSkipped:false}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'bottom',labels:{usePointStyle:true,pointStyleWidth:10,padding:14,font:{weight:'600'}}},datalabels:{color:'#fff',font:{weight:'700',size:12},anchor:'center',align:'center',formatter:v=>v>0?v:''},tooltip:{backgroundColor:'rgba(15,23,42,.9)',borderRadius:10,padding:10}},scales:{x:{grid:{display:false}},y:{beginAtZero:true,grid:{color:'#f1f5f9'},ticks:{precision:0,color:'#94a3b8'}}},animation:{duration:600}},plugins:[ChartDataLabels]});
    }
    render(selEl?.value||'');
    selEl?.addEventListener('change',()=>render(selEl.value));
  })();

  // LUARAN Bar
  (function() {
    const ctx=document.getElementById('luaranChart'); const selEl=document.getElementById('selectJurusanLuaran'); if (!ctx) return;
    const chartData       = <?= json_encode($chartData ?? []) ?>;
    const luaranChartData = <?= json_encode($luaranChartData ?? []) ?>;
    const defLabels = chartData.map(r=>r.luaran_name);
    const defValues = chartData.map(r=>parseInt(r.total_laporan,10)||0);
    function build(id) {
      if (!id) return{labels:defLabels,values:defValues};
      const filtered=luaranChartData.filter(r=>String(r.jurusan_id)===String(id));
      const labels=[...new Set(filtered.map(r=>r.luaran_name))];
      return{labels,values:labels.map(ln=>{const m=filtered.find(r=>r.luaran_name===ln);return m?parseInt(m.total_laporan,10)||0:0;})};
    }
    let chartLuaran=null;
    function render(id) {
      const{labels,values}=build(id);
      if (chartLuaran) { chartLuaran.data.labels=labels; chartLuaran.data.datasets[0].data=values; chartLuaran.update('active'); return; }
      chartLuaran=new Chart(ctx,{type:'bar',data:{labels,datasets:[{label:I18N.jumlah,data:values,backgroundColor:labels.map((_,i)=>`hsl(${160+i*18},70%,55%)`),borderRadius:6,borderSkipped:false}]},options:{responsive:true,maintainAspectRatio:false,indexAxis:labels.length>6?'y':'x',plugins:{legend:{display:false},datalabels:{color:'#fff',font:{weight:'700',size:11},anchor:'center',align:'center',formatter:v=>v>0?v:''},tooltip:{backgroundColor:'rgba(15,23,42,.9)',borderRadius:10,padding:10}},scales:{x:{grid:{display:labels.length>6}},y:{grid:{color:'#f1f5f9'},ticks:{color:'#94a3b8',precision:0,callback:(v,i)=>labels.length>6?wrapLabel(labels[i],18):v}}},animation:{duration:700}},plugins:[ChartDataLabels]});
    }
    render(selEl?.value||'');
    selEl?.addEventListener('change',()=>render(selEl.value));
    document.getElementById('dl-luaran')?.addEventListener('click',e=>{e.preventDefault();const a=document.createElement('a');a.href=chartLuaran.toBase64Image('image/png',1);a.download='grafik-luaran.png';a.click();});
  })();

})();
</script>
<?= $this->endSection() ?>
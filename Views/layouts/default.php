<?php
// --- I18N inline (1 file layout, tanpa folder Language, tanpa ubah UI/UX) ---
$lang = strtolower(trim((string) (service('request')->getCookie('lang') ?? 'id')));
$lang = ($lang === 'en') ? 'en' : 'id';

$I18N = [
  'id' => [
    'logoutText'    => 'Keluar',
    'logoutConfirm' => 'Logout? | Yakin keluar aplikasi?',
  ],
  'en' => [
    'logoutText'    => 'Logout',
    'logoutConfirm' => 'Logout? | Are you sure you want to exit the app?',
  ],
];

$__ = function (string $key, ...$args) use ($I18N, $lang) {
  $text = $I18N[$lang][$key] ?? $I18N['id'][$key] ?? $key;
  return $args ? vsprintf($text, $args) : $text;
};
?>

<?= $this->include('layouts/header') ?>

<div id="app">
  <div class="main-wrapper">
    <div class="navbar-bg"></div>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg main-navbar">
      <?= $this->include('layouts/search') ?>
      <ul class="navbar-nav navbar-right">
        <?= $this->include('layouts/navbar') ?>
      </ul>
    </nav>
    <!-- End Navigation Bar -->

    <!-- Sidebar menu -->
    <div class="main-sidebar">
      <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
          <a href="<?= site_url() ?>">LPM UG</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
          <a href="<?= site_url() ?>">LPM</a>
        </div>
        <ul class="sidebar-menu">
          <?= $this->include('layouts/menu') ?>
        </ul>

        <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
          <a href="<?= site_url('auth/logout'); ?>"
            class="btn btn-danger btn-lg btn-block btn-icon-split"
            id="logout"
            data-confirm="<?= $__('logoutConfirm'); ?>"
            data-confirm-yes="returnLogout()">
            <i class="fas fa-rocket"></i> <span><?= $__('logoutText'); ?></span>
          </a>
        </div>
      </aside>
    </div>
    <!-- End Sidebar menu -->

    <!-- Main Content -->
    <div class="main-content">
      <?= $this->renderSection('content') ?>
    </div>
    <!-- End Main Content -->

    <!-- Copyright -->
    <footer class="main-footer">
      <?= $this->include('layouts/copyright') ?>
    </footer>
    <!-- End Copyright -->

  </div>
</div>

<?= $this->include('layouts/footer') ?>
<?= $this->renderSection('extra_scripts') ?>
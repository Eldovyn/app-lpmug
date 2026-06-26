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

// Load Google Translate Library
$translate = new \App\Libraries\GoogleTranslate();

$TR = [
  'id' => [
    'inbox'        => 'Pesan Masuk',
    'from'         => 'Dari',
    'subject'      => 'Subjek',
    'sent_at'      => 'Dikirim pada',
    'see_all'      => 'Lihat semua',
    'hi'           => 'Hi',
    'my_profile'   => 'Profile saya',
    'change_pass'  => 'Ubah Password',
    'logout'       => 'Keluar',
    'logout_title' => 'Logout?',
    'logout_msg'   => 'Yakin keluar aplikasi?',
    'no_message'   => 'Tidak ada pesan',
  ],
  'en' => [
    'inbox'        => 'Inbox',
    'from'         => 'From',
    'subject'      => 'Subject',
    'sent_at'      => 'Sent at',
    'see_all'      => 'See all',
    'hi'           => 'Hi',
    'my_profile'   => 'My profile',
    'change_pass'  => 'Change password',
    'logout'       => 'Logout',
    'logout_title' => 'Logout?',
    'logout_msg'   => 'Are you sure you want to log out?',
    'no_message'   => 'No messages',
  ],
];

$t = static function (string $key) use ($TR, $lang): string {
    return $TR[$lang][$key] ?? $TR['id'][$key] ?? $key;
};

if (! function_exists('t')) {
  function t(string $key): string
  {
    global $dict, $lang;

    return $dict[$lang][$key]
      ?? $dict['id'][$key]
      ?? $key;
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
?>
<li class="nav-item">
  <div class="d-flex align-items-center" style="padding: 0 10px;">
    <a class="header-lang-btn mr-1 <?= $lang === 'id' ? 'active' : '' ?>" href="<?= lang_url('id') ?>">ID</a>
    <a class="header-lang-btn <?= $lang === 'en' ? 'active' : '' ?>" href="<?= lang_url('en') ?>">EN</a>
  </div>
</li>

<?php if (userLogin()->role_id == 1 || userLogin()->role_id == 2) : ?>
  <!-- Tombol Language Switcher -->

  <!-- Tombol Pesan -->
  <li class="dropdown dropdown-list-toggle">
    <a href="#" data-toggle="dropdown" class="nav-link nav-link-lg message-toggle beep">
      <i class="far fa-envelope"></i>
    </a>

    <div class="dropdown-menu dropdown-list dropdown-menu-right shadow-premium profile-dropdown">
      <div class="dropdown-header">
        <?= esc($t('inbox')) ?>
        <div class="float-right">
          <!-- <a href="#">Mark All As Read</a> -->
        </div>
      </div>

      <div class="dropdown-list-content dropdown-list-message">
        <?php if (isset($pesan) && !empty($pesan)) : ?>
          <?php foreach ($pesan as $key => $v_pesan) : ?>
            <span class="dropdown-item dropdown-item-unread">
              <div class="dropdown-item-avatar">
                <img alt="image" src="<?= base_url() ?>/template/assets/img/avatar/avatar-1.png" class="rounded-circle">
                <div class="is-online"></div>
              </div>
              <div class="dropdown-item-desc">
                <p><b><?= esc($t('from')) ?>:</b> <?= esc($v_pesan->pesan_name) ?></p>
                <p><b><?= esc($t('subject')) ?>:</b> <?= esc($translate->translateByCookie($v_pesan->subject)) ?></p>
                <div class="time"><?= esc($t('sent_at')) ?>: <?= esc($v_pesan->created_at) ?></div>
              </div>
            </span>
          <?php endforeach; ?>
        <?php else : ?>
          <span class="dropdown-item">
            <div class="dropdown-item-desc text-center">
              <p><?= esc($t('no_message')) ?></p>
            </div>
          </span>
        <?php endif; ?>
      </div>

      <div class="dropdown-footer text-center">
        <a href="<?= site_url('pesan'); ?>"><?= esc($t('see_all')) ?> <i class="fas fa-chevron-right"></i></a>
      </div>
    </div>
  </li>
<?php endif; ?>

<!-- User Dropdown - Show for ALL logged in users (role 1, 2, 3, 4, 5) -->
<li class="dropdown">
  <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-user">
    <img alt="image" src="<?= base_url() ?>/template/assets/img/avatar/avatar-1.png" class="rounded-circle mr-1">
    <div class="d-sm-none d-lg-inline-block"><?= esc($t('hi')) ?>, <?= esc(userLogin()->user_name) ?></div>
  </a>

  <div class="dropdown-menu dropdown-menu-right shadow-premium profile-dropdown">
    <div class="dropdown-title"><?= esc($t('hi')) ?>, <?= esc(userLogin()->user_name) ?></div>

    <?php if (userLogin()->role_id == 1) : ?>
      <a href="<?= site_url('profile_user/update/' . userLogin()->user_id); ?>" class="dropdown-item has-icon">
        <i class="far fa-user"></i> <?= esc($t('my_profile')) ?>
      </a>
      <a href="<?= site_url('ubah_password/update/' . userLogin()->user_id); ?>" class="dropdown-item has-icon">
        <i class="fas fa-cog"></i> <?= esc($t('change_pass')) ?>
      </a>

    <?php elseif (userLogin()->role_id == 2) : ?>
      <a href="<?= site_url('profile_user_admin/update/' . userLogin()->user_id); ?>" class="dropdown-item has-icon">
        <i class="far fa-user"></i> <?= esc($t('my_profile')) ?>
      </a>
      <a href="<?= site_url('ubah_password_admin/update/' . userLogin()->user_id); ?>" class="dropdown-item has-icon">
        <i class="fas fa-cog"></i> <?= esc($t('change_pass')) ?>
      </a>

    <?php elseif (userLogin()->role_id == 3) : ?>
      <a href="<?= site_url('profile_user_staff/update/' . userLogin()->user_id); ?>" class="dropdown-item has-icon">
        <i class="far fa-user"></i> <?= esc($t('my_profile')) ?>
      </a>
      <a href="<?= site_url('ubah_password_staff/update/' . userLogin()->user_id); ?>" class="dropdown-item has-icon">
        <i class="fas fa-cog"></i> <?= esc($t('change_pass')) ?>
      </a>

    <?php elseif (userLogin()->role_id == 4) : ?>
      <a href="<?= site_url('profile_user_dosen/update/' . userLogin()->user_id); ?>" class="dropdown-item has-icon">
        <i class="far fa-user"></i> <?= esc($t('my_profile')) ?>
      </a>
      <a href="<?= site_url('ubah_password_dosen/update/' . userLogin()->user_id); ?>" class="dropdown-item has-icon">
        <i class="fas fa-cog"></i> <?= esc($t('change_pass')) ?>
      </a>

    <?php elseif (userLogin()->role_id == 5) : ?>
      <a href="<?= site_url('profile_user_mitra/update/' . userLogin()->user_id); ?>" class="dropdown-item has-icon">
        <i class="far fa-user"></i> <?= esc($t('my_profile')) ?>
      </a>
      <a href="<?= site_url('ubah_password_mitra/update/' . userLogin()->user_id); ?>" class="dropdown-item has-icon">
        <i class="fas fa-cog"></i> <?= esc($t('change_pass')) ?>
      </a>
    <?php endif; ?>

    <div class="dropdown-divider"></div>

    <a href="<?= site_url('auth/logout'); ?>"
      class="dropdown-item has-icon text-danger logout-btn-premium"
      id="logout"
      data-confirm="<?= esc($t('logout_title')) ?> | <?= esc($t('logout_msg')) ?>"
      data-confirm-yes="returnLogout()">
      <i class="fas fa-sign-out-alt"></i> <span><?= esc($t('logout')) ?></span>
    </a>
  </div>
</li>

<?= $this->include('layouts/header') ?>

  <div id="app">
    <div class="main-wrapper">
      <div class="navbar-bg"></div>

      <!-- Main Content -->
      <div>
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
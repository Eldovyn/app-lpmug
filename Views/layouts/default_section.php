<?= $this->include('layouts/homepage/header_home') ?>

<div id="app">
    <!-- Spinner Start -->
    <?= $this->include('layouts/homepage/spinner_home') ?>
    <!-- Spinner End -->

    <!-- Topbar Start -->
    <section id="topbar">
    <?= $this->include('layouts/homepage/topbar_home') ?>
    </section>
    <!-- Topbar End -->

    <!-- Navbar Start -->
    <?= $this->include('layouts/homepage/navbar_home') ?>
    <!-- Navbar End -->

    <!-- Carousel Start -->
    <?= $this->include('layouts/homepage/page_header_home') ?>
    <!-- Carousel End -->

    <!-- staff Start -->
    <section id="staff">
    <?= $this->renderSection('content') ?>
    </section>
    <!-- staff End -->

    <!-- Sitemap Start -->
    <div class="container-fluid bg-dark footer mt-5 pt-5 wow fadeIn" data-wow-delay="0.1s">
        <?= $this->include('layouts/homepage/sitemap_home') ?>
        <?= $this->include('layouts/homepage/copyright_home') ?>
    </div>
    <!-- Sitemap End -->
</div>

<?= $this->include('layouts/homepage/footer_home') ?>
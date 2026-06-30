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
    <?= $this->include('layouts/homepage/carousel_home') ?>
    <!-- Carousel End -->

    <!-- About Start -->
    <section id="about">
        <?= $this->include('layouts/homepage/about_home') ?>
    </section>
    <!-- About End -->

    <!-- staff Start -->
    <section id="staff">
    <?= $this->renderSection('content_staff') ?>
    </section>
    <!-- staff End -->

    <!-- galeri Start -->
    <section id="galeri">
    <?= $this->renderSection('content_galeri') ?>
    </section>
    <!-- galeri End -->


    <!-- Testimonial Start -->
    <?= $this->include('layouts/homepage/testimonial_home') ?>
    <!-- Testimonial End -->

    <!-- Sitemap Start -->
    <div class="container-fluid bg-dark footer mt-5 pt-5 wow fadeIn" data-wow-delay="0.1s">
        <?= $this->include('layouts/homepage/sitemap_home') ?>
        <?= $this->include('layouts/homepage/copyright_home') ?>
    </div>
    <!-- Sitemap End -->
</div>

<?= $this->include('layouts/homepage/footer_home') ?>
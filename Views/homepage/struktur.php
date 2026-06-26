<?= $this->extend('layouts/default_section') ?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>
<?= $this->endSection() ?>

<!-- Render Content untuk struktur Section -->
<?= $this->section('content') ?>
<div class="container-xxl py-5">
    <div class="row g-4">
        <?php foreach($struktur as $pr => $v_struktur): ?>
            <div class="col-lg-12 col-md-6 d-flex align-items-center justify-content-center wow fadeInUp" data-wow-delay="0.1s">
                <img class="img-fluid img-struktur" src="<?= base_url('/img/upload/struktur/' . $v_struktur->gambar) ?>" alt="struktur - <?= $v_struktur->judul; ?>" />
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?= $this->endSection() ?>
<!-- END Render Content untuk struktur Section -->
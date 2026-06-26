<?= $this->extend('layouts/default_section') ?>

<?= $this->section('title') ?>
<title><?= esc($title_tab); ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-xxl py-5">
    <div class="row g-4">
        <div class="col-12 d-flex justify-content-center">
            <embed
                src="<?= base_url('berkas/roadmap/5_ROADMAP_PKM_2022_2026.pdf') ?>"
                type="application/pdf"
                width="1200"
                height="1000" />
        </div>
    </div>
</div>
<?= $this->endSection() ?>
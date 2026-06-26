<?= $this->extend('layouts/default_section') ?>

<?= $this->section('title') ?>
<title><?= $title_tab; ?></title>
<?= $this->endSection() ?>

<!-- Render Content untuk kalender Section -->
<?= $this->section('content') ?>
<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-4">
            <div class="section-body">
                <div class="card mt-0">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Kegiatan</th>
                                        <th>Waktu</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                                    $no = 1 + (10 * ($page - 1)); 
                                    foreach ($kalender as $users => $v_kalender) : ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td class="text-wrap" style="max-width:200px;"><?= $v_kalender->kegiatan; ?></td>
                                            <td class="text-wrap" style="max-width:200px;"><?= $v_kalender->waktu; ?></td>
                                            <td class="text-wrap" style="max-width:200px;"><?= $v_kalender->keterangan; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
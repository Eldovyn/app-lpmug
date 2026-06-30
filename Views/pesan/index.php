<?= $this->extend('layouts/default') ?>

<?php
helper('cookie');
$lang = get_cookie('lang') ?: 'id';

$dict = [
    'id' => [
        'title_tab' => 'Pesan Masuk — LPM UG',
        'title' => 'Pesan Masuk',
        'dashboard' => 'Dashboard',
        'no' => '#',
        'nama_pengirim' => 'Nama Pengirim',
        'subject' => 'Subjek',
        'dikirim_pada' => 'Dikirim pada',
        'action' => 'Aksi',
        'lihat_detail' => 'Lihat detail',
        'detail_pesan' => 'Detail Pesan',
        'nama' => 'Nama',
        'email' => 'Email',
        'phone' => 'No. HP',
        'message' => 'Pesan',
        'no_data' => 'Tidak ada pesan',
    ],
    'en' => [
        'title_tab' => 'Inbox — LPM UG',
        'title' => 'Inbox',
        'dashboard' => 'Dashboard',
        'no' => '#',
        'nama_pengirim' => 'Sender Name',
        'subject' => 'Subject',
        'dikirim_pada' => 'Sent at',
        'action' => 'Action',
        'lihat_detail' => 'View detail',
        'detail_pesan' => 'Message Detail',
        'nama' => 'Name',
        'email' => 'Email',
        'phone' => 'Phone',
        'message' => 'Message',
        'no_data' => 'No messages',
    ]
];

$t = $dict[$lang] ?? $dict['id'];
?>

<?= $this->section('title') ?>
<title><?= esc($t['title_tab']) ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="section-header">
        <h1><i class="fas fa-envelope mr-2"></i><?= esc($t['title']) ?></h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="<?= site_url('dashboard'); ?>"><?= esc($t['dashboard']) ?></a></div>
            <div class="breadcrumb-item"><?= esc($t['title']) ?></div>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b>Congratulation!</b>
                <?= session()->getFlashdata('success'); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert">x</button>
                <b>Warning Error!</b>
                <?= session()->getFlashdata('error'); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="section-body">
        <div class="card shadow-premium">
            <div class="card-body">
                <div class="table-responsive-md">
                    <table class="table table-hover table-bordered" id="table1" style="width:100%;">
                        <thead class="text-white" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);">
                            <tr>
                                <th class="text-white"><?= esc($t['no']) ?></th>
                                <th class="text-white"><?= esc($t['nama_pengirim']) ?></th>
                                <th class="text-white"><?= esc($t['subject']) ?></th>
                                <th class="text-white"><?= esc($t['dikirim_pada']) ?></th>
                                <th class="text-center text-white"><?= esc($t['action']) ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($message)): ?>
                                <tr>
                                    <td colspan="5" class="text-center"><?= esc($t['no_data']) ?></td>
                                </tr>
                            <?php else: ?>
                                <?php
                                $page = isset($_GET['page']) ? $_GET['page'] : 1;
                                $no = 1 + (10 * ($page - 1));
                                foreach ($message as $fgsi => $v_pesan):
                                ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td class="text-wrap" style="max-width:200px;"><?= $v_pesan->pesan_name; ?></td>
                                        <td class="text-wrap" style="max-width:200px;"><?= $v_pesan->subject; ?></td>
                                        <td class="text-wrap" style="max-width:200px;"><?= $v_pesan->created_at; ?></td>
                                        <td class="text-center">
                                            <a id="detail_pesan" class="btn btn-sm text-white"
                                                style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); cursor:pointer; border-radius: 8px;"
                                                data-toggle="modal"
                                                data-target="#modal-detail"
                                                data-pesanname="<?= esc($v_pesan->pesan_name) ?>"
                                                data-email="<?= esc($v_pesan->email) ?>"
                                                data-phone="<?= esc($v_pesan->phone) ?>"
                                                data-subject="<?= esc($v_pesan->subject) ?>"
                                                data-pesan="<?= esc($v_pesan->pesan) ?>">
                                                <i class="fas fa-eye show-item"></i> <?= esc($t['lihat_detail']) ?>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal -->
<div class="modal fade" id="modal-detail">
    <div class="modal-dialog">
        <div class="modal-content shadow-premium border-0" style="border-radius:12px; overflow:hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); padding: 20px;">
                <h4 class="modal-title text-white m-0" style="font-size: 18px;"><i class="fas fa-envelope-open-text mr-2"></i><?= esc($t['detail_pesan']) ?></h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.8; text-shadow:none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4 table-responsive">
                <table class="table table-borderless no-margin">
                    <tbody>
                        <tr>
                            <th style="width:120px; color:#6366f1; border-bottom: 1px dashed #e2e8f0;"><?= esc($t['nama']) ?></th>
                            <td style="border-bottom: 1px dashed #e2e8f0;"><span id="pesan_name" class="font-weight-600 text-dark"></span></td>
                        </tr>
                        <tr>
                            <th style="color:#6366f1; border-bottom: 1px dashed #e2e8f0;"><?= esc($t['email']) ?></th>
                            <td style="border-bottom: 1px dashed #e2e8f0;"><span id="email" class="text-dark"></span></td>
                        </tr>
                        <tr>
                            <th style="color:#6366f1; border-bottom: 1px dashed #e2e8f0;"><?= esc($t['phone']) ?></th>
                            <td style="border-bottom: 1px dashed #e2e8f0;"><span id="phone" class="text-dark"></span></td>
                        </tr>
                        <tr>
                            <th style="color:#6366f1; border-bottom: 1px dashed #e2e8f0;"><?= esc($t['subject']) ?></th>
                            <td style="border-bottom: 1px dashed #e2e8f0;"><span id="subject" class="text-dark"></span></td>
                        </tr>
                        <tr>
                            <th style="color:#6366f1; border-bottom: 1px dashed #e2e8f0;"><?= esc($t['message']) ?></th>
                            <td style="border-bottom: 1px dashed #e2e8f0;"><span id="pesan" class="text-dark" style="white-space: pre-wrap;"></span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
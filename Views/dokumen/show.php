<!DOCTYPE html>
<html lang="<?= (($lang ?? strtolower(trim((string) (service('request')->getCookie('lang') ?? 'id')))) === 'en') ? 'en' : 'id' ?>">

<head>
    <meta charset="UTF-8" />
    <title>Detail Dokumen</title>
    <!-- Panggil CSS eksternal -->
    <link rel="stylesheet" href="<?= base_url('template/assets/css/dokumen.css') ?>">
    <link rel="stylesheet" href="<?= base_url('template/assets/css/style.css') ?>">
</head>

<body>

    <?php
    $request = service('request'); // CI4 service('request') :contentReference[oaicite:1]{index=1}

    $lang = $lang ?? ($request->getCookie('lang') ?? 'id'); // cookie lang :contentReference[oaicite:2]{index=2}
    $lang = strtolower(trim((string) $lang));
    $lang = ($lang === 'en') ? 'en' : 'id';

    $TR = [
        'id' => [
            'page_title'   => '📄 Detail Dokumen',
            'doc_name'     => 'Nama Dokumen',
            'file'         => 'File',
            'view_file'    => 'Lihat File',
            'created_at'   => 'Dibuat pada',
            'updated_at'   => 'Diperbarui pada',
            'back'         => '⬅ Kembali ke Daftar Dokumen',
            'title_tag'    => 'Detail Dokumen',
        ],
        'en' => [
            'page_title'   => '📄 Document Details',
            'doc_name'     => 'Document Name',
            'file'         => 'File',
            'view_file'    => 'View File',
            'created_at'   => 'Created at',
            'updated_at'   => 'Updated at',
            'back'         => '⬅ Back to Document List',
            'title_tag'    => 'Document Details',
        ],
    ];

    $t = static function (string $key) use ($TR, $lang): string {
        return $TR[$lang][$key] ?? $TR['id'][$key] ?? $key;
    };
    ?>

    <!-- kalau mau title tag ikut bahasa, ganti title hardcode di atas jadi ini:
<title><?= esc($t('title_tag')) ?></title>
-->

    <h2><?= esc($t('page_title')) ?></h2>

    <div class="detail">
        <p><strong><?= esc($t('doc_name')) ?>:</strong> <?= esc($dokumen['nama_file']) ?></p>
        <p><strong><?= esc($t('file')) ?>:</strong>
            <a href="<?= base_url($dokumen['file_path']) ?>" target="_blank" rel="noopener noreferrer">
                <?= esc($t('view_file')) ?>
            </a>
        </p>
        <p><strong><?= esc($t('created_at')) ?>:</strong> <?= esc($dokumen['created_at']) ?></p>
        <p><strong><?= esc($t('updated_at')) ?>:</strong> <?= esc($dokumen['updated_at']) ?></p>
    </div>

    <a href="<?= base_url('dokumen') ?>"><?= esc($t('back')) ?></a>

</body>

</html>
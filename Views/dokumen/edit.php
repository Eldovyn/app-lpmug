<!DOCTYPE html>
<<<<<<< HEAD
<html lang="en">
=======
<html lang="<?php
            $request = service('request'); // :contentReference[oaicite:2]{index=2}
            $lang = $request->getCookie('lang') ?? 'id'; // :contentReference[oaicite:3]{index=3}
            $lang = strtolower(trim((string) $lang));
            $lang = ($lang === 'en') ? 'en' : 'id';
            echo $lang;
            ?>">
>>>>>>> 52135088f67f93cecd154237dcb0be5656d81879

<head>
    <meta charset="UTF-8">
    <title><?php
            // ====== I18N (SINGLE FILE) ======
            $TR = [
                'id' => [
                    'title'        => 'Edit Dokumen',
                    'h2'           => '✏️ Edit Dokumen',
                    'doc_name'     => 'Nama Dokumen:',
                    'replace_file' => 'Ganti File (optional):',
                    'update'       => 'Update',
                    'back'         => 'Kembali',
                ],
                'en' => [
                    'title'        => 'Edit Document',
                    'h2'           => '✏️ Edit Document',
                    'doc_name'     => 'Document Name:',
                    'replace_file' => 'Replace File (optional):',
                    'update'       => 'Update',
                    'back'         => 'Back',
                ],
            ];

            $t = static function (string $key) use ($TR, $lang): string {
                return $TR[$lang][$key] ?? $TR['id'][$key] ?? $key;
            };

            echo esc($t('title')); // :contentReference[oaicite:4]{index=4}
            ?></title>

    <link rel="stylesheet" href="<?= base_url('template/assets/css/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('template/assets/css/dokumen.css') ?>">
</head>

<body>

    <div class="container">
<<<<<<< HEAD
        <h2 class="title">✏️ Edit Dokumen</h2>

        <form action="<?= base_url('dokumen/update/' . $dokumen['id']) ?>" method="post" enctype="multipart/form-data" class="form-card">
            <label for="nama_file">Nama Dokumen:</label>
            <input type="text" name="nama_file" id="nama_file" value="<?= esc($dokumen['nama_file']) ?>" required>

            <label for="file_dokumen">Ganti File (optional):</label>
            <input type="file" name="file_dokumen" id="file_dokumen">

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">💾 Update</button>
                <a href="<?= base_url('dokumen') ?>" class="btn btn-secondary">⬅ Kembali</a>
=======
        <h2 class="title"><?= esc($t('h2')) ?></h2>

        <form action="<?= base_url('dokumen/update/' . $dokumen['id']) ?>" method="post" enctype="multipart/form-data" class="form-card">
            <label for="nama_file"><?= esc($t('doc_name')) ?></label>
            <input type="text" name="nama_file" id="nama_file" value="<?= esc($dokumen['nama_file']) ?>" required>

            <label for="file_dokumen"><?= esc($t('replace_file')) ?></label>
            <input type="file" name="file_dokumen" id="file_dokumen">

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">💾 <?= esc($t('update')) ?></button>
                <a href="<?= base_url('dokumen') ?>" class="btn btn-secondary">⬅ <?= esc($t('back')) ?></a>
>>>>>>> 52135088f67f93cecd154237dcb0be5656d81879
            </div>
        </form>
    </div>

</body>

</html>
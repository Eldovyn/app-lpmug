<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengesahan</title>
</head>
<body>
    <h2>Daftar Pengesahan</h2>
    <a href="<?= base_url('pengesahan/form'); ?>">+ Tambah Pengesahan</a>
    <table border="1" cellspacing="0" cellpadding="5">
        <tr>
            <th>No</th>
            <th>Judul Kegiatan</th>
            <th>Ketua Tim</th>
            <th>Aksi</th>
        </tr>
        <?php if (empty($pengesahan)): ?>
            <tr>
                <td colspan="4">Tidak ada data pengesahan yang tersedia.</td>
            </tr>
        <?php else: ?>
            <?php $no = 1; ?>
            <?php foreach ($pengesahan as $p): ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= esc($p['judul_kegiatan']); ?></td>
                <td><?= esc($p['ketua_tim']); ?></td>
                <td>
                    <a href="<?= base_url('pengesahan/generatePdf/' . $p['id']); ?>">Download PDF</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>
</body>
</html>
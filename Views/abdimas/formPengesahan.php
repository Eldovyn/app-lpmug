<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?= csrf_hash() ?>"> <!-- Tambahkan meta tag CSRF -->
    <title>Form Pengesahan</title>
</head>
<body>
    <h2>Form Pengisian Data Pengesahan</h2>
    <form action="<?= site_url('pengesahan/save') ?>" method="post">
        <?= csrf_field() ?> <!-- Tambahkan CSRF token di dalam form -->

        <label>Judul Kegiatan:</label><br>
        <input type="text" name="judul_kegiatan" required><br><br>

        <label>Nama Mitra:</label><br>
        <input type="text" name="nama_mitra" required><br><br>

        <label>Ketua Tim:</label><br>
        <input type="text" name="ketua_tim" required><br><br>

        <label>NIDN:</label><br>
        <input type="text" name="nidn" required><br><br>

        <label>Perguruan Tinggi:</label><br>
        <input type="text" name="perguruan_tinggi" required><br><br>

        <label>Program Studi:</label><br>
        <input type="text" name="program_studi" required><br><br>

        <label>Bidang Keahlian:</label><br>
        <input type="text" name="bidang_keahlian" required><br><br>

        <label>Anggota Tim:</label><br>
        <textarea name="anggota_tim" required></textarea><br><br>

        <label>Lokasi Kegiatan:</label><br>
        <input type="text" name="lokasi_kegiatan" required><br><br>

        <label>Jangka Waktu:</label><br>
        <input type="text" name="jangka_waktu" required><br><br>

        <label>Total Biaya:</label><br>
        <input type="number" name="total_biaya" required><br><br>

        <button type="submit">Simpan & Generate PDF</button>
    </form>
</body>
</html>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Lampiran</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 12pt;
            line-height: 1.5;
        }

        .kop {
            text-align: center;
            margin-bottom: 20px;
            line-height: 1;
        }

        .kop h2 {
            margin: 0;
            font-size: 14pt;
            font-weight: bold;
        }

        .kop h3 {
            margin: 0;
            font-size: 12pt;
            font-weight: bold;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 15px;
        }

        table,
        th,
        td {
            border: 1px solid black;
            padding: 6px;
        }

        th {
            text-align: center;
            background: #f2f2f2;
        }

        td {
            vertical-align: top;
        }
    </style>
</head>

<body>
    <div style="page-break-before: always;"></div>
    <div style="text-align: left; margin-top:20px; font-size: 12pt;">

        <!-- KOP SURAT -->
        <div class="kop" style="border-bottom: 2px solid black; padding-bottom: 10px; text-align: center;">
            <h2>LEMBAGA PENGABDIAN KEPADA MASYARAKAT (LPM)</h2>
            <h3>UNIVERSITAS GUNADARMA</h3>
            <p>Jl. Komjen (Pol) M. Jasin (Jl. Akses UI) Kelapa Dua, Depok Jawa Barat</p>
            <p>Kampus E Gedung 4 Lantai 1 Universitas Gunadarma Telp (021) 8727517</p>
        </div>

        <p>Lampiran</p>
        <p style="text-align:center; font-weight:bold; margin:15px 0 25px 0;">
            PENANGGUNG JAWAB TIM:<br>
            <?= esc($ketua ?? '-') ?><br>
            DAFTAR NAMA-NAMA DOSEN UNIVERSITAS GUNADARMA<br>
            <?= esc($periode_display ?? '-') ?>
        </p>

        <?php
        // Grouping dosen by jurusan
        $grouped = [];
        $total_dosen = 0;
        if (!empty($dosenList) && is_array($dosenList)) {
            foreach ($dosenList as $row) {
                $jurusan = $row['jurusan_name'] ?? 'Jurusan Tidak Tersedia';
                $nama    = $row['user_name'] ?? 'Nama Dosen Tidak Tersedia';
                $grouped[$jurusan][] = $nama;
                $total_dosen++;
            }
        }
        ?>

        <!-- Informasi Total -->
        <p>Total Dosen: <?= esc($total_dosen) ?> orang</p>

        <!-- Tabel Dosen per Jurusan -->
        <table>
            <thead>
                <tr>
                    <th style="width: 50%; font-weight: bold; text-align: center;">Bidang Ilmu</th>
                    <th style="width: 50%; font-weight: bold; text-align: center;">Nama Dosen</th>

                </tr>
            </thead>
            <tbody>
                <?php foreach ($grouped as $jurusan => $namas): ?>
                    <tr>
                        <td><strong><?= esc($jurusan) ?></strong></td>
                        <td>
                            <?php foreach ($namas as $idx => $nama): ?>
                                <?= ($idx + 1) . '. ' . esc($nama) ?><br>
                            <?php endforeach ?>
                        </td>
                    </tr>
                <?php endforeach ?>
                <?php if (empty($grouped)): ?>
                    <tr>
                        <td colspan="2" style="text-align:center;font-style:italic;">
                            Data anggota dosen tidak tersedia.
                        </td>
                    </tr>
                <?php endif ?>
            </tbody>
        </table>

        <!--<?php if (!empty($grouped)): ?>-->
        <!--<p style="margin-top: 20px; font-size: 10pt; font-style: italic;">-->
        <!--    Catatan: Daftar ini dibuat berdasarkan data yang tersimpan dalam sistem pada -->
        <!--    <?= esc(date('d F Y, H:i')) ?> WIB.-->
        <!--</p>-->
        <!--<?php endif; ?>-->
    </div>
</body>

</html>
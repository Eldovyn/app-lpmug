<?php
$lang = $lang ?? 'id';
$l = [
    'id' => [
        'halaman_pengesahan' => 'HALAMAN PENGESAHAN',
        'program_pengabdian' => 'PROGRAM PENGABDIAN KEPADA MASYARAKAT',
        'judul_kegiatan' => '1. JUDUL KEGIATAN',
        'nama_mitra' => '2. NAMA MITRA PROGRAM',
        'ketua_tim' => '3. KETUA TIM PENGUSUL',
        'nama_lengkap' => 'Nama Lengkap',
        'nidn' => 'NIDN',
        'program_studi' => 'Program Studi',
        'perguruan_tinggi' => 'Perguruan Tinggi',
        'bidang_keahlian' => 'Bidang Keahlian',
        'anggota_tim' => '4. ANGGOTA TIM PENGUSUL',
        'terlampir' => '(Terlampir)',
        'tidak_ada_anggota' => 'Tidak ada anggota',
        'nama_anggota' => 'Nama Anggota',
        'bidang_keahlian_label' => 'NIDN / Bidang Keahlian',
        'lokasi_kegiatan' => '5. LOKASI KEGIATAN / MITRA',
        'wilayah_mitra' => 'Wilayah Mitra',
        'kabupaten_kota' => 'Kabupaten/Kota',
        'provinsi' => 'Provinsi',
        'jarak_pt' => 'Jarak PT ke Lokasi Mitra',
        'luaran' => '6. Luaran',
        'tidak_ada_luaran' => 'Tidak ada data luaran untuk laporan ini.',
        'jangka_waktu' => '7. JANGKA WAKTU PELAKSANAAN',
        'total_biaya' => '8. TOTAL BIAYA',
        'sumber_lain' => 'Sumber Lain'
    ],
    'en' => [
        'halaman_pengesahan' => 'ENDORSEMENT PAGE',
        'program_pengabdian' => 'COMMUNITY SERVICE PROGRAM',
        'judul_kegiatan' => '1. ACTIVITY TITLE',
        'nama_mitra' => '2. PROGRAM PARTNER NAME',
        'ketua_tim' => '3. PROPOSED TEAM LEADER',
        'nama_lengkap' => 'Full Name',
        'nidn' => 'NIDN',
        'program_studi' => 'Study Program',
        'perguruan_tinggi' => 'University',
        'bidang_keahlian' => 'Area of Expertise',
        'anggota_tim' => '4. PROPOSED TEAM MEMBERS',
        'terlampir' => '(Attached)',
        'tidak_ada_anggota' => 'No members',
        'nama_anggota' => 'Member Name',
        'bidang_keahlian_label' => 'NIDN / Area of Expertise',
        'lokasi_kegiatan' => '5. ACTIVITY LOCATION / PARTNER',
        'wilayah_mitra' => 'Partner Area',
        'kabupaten_kota' => 'Regency/City',
        'provinsi' => 'Province',
        'jarak_pt' => 'Distance from University to Partner',
        'luaran' => '6. Outcomes',
        'tidak_ada_luaran' => 'No outcome data for this report.',
        'jangka_waktu' => '7. PERIOD OF IMPLEMENTATION',
        'total_biaya' => '8. TOTAL COST',
        'sumber_lain' => 'Other Sources'
    ]
];
$labels = $l[$lang];
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $labels['halaman_pengesahan'] ?> - PROGRAM PENGABDIAN</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            font-size: 10px;
        }

        .judul {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }

        .sub-judul {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        td {
            vertical-align: top;
            padding: 4px;
        }

        nested-label {
            padding-left: 2px;
            margin-left: 2px;
        }

        .label {
            width: 40%;
            padding-left: 30px;
            text-indent: -15px;
            "

        }

        .qr-placeholder {
            width: 80px;
            height: 80px;
            background: #eee;
            margin: 0 auto;
            line-height: 80px;
            color: red;
            text-align: center;
        }
    </style>
</head>

<body>

    <h3 class="judul"><?= $labels['halaman_pengesahan'] ?></h3>
    <h3 class="sub-judul"><?= $labels['program_pengabdian'] ?></h3>

    <table>
        <tr>
            <td><?= $labels['judul_kegiatan'] ?></td>
            <td> : <?= $abdimas->judul_kegiatan ?? '-' ?></td>
        </tr><br>
        <tr>
            <td><?= $labels['nama_mitra'] ?></td>
            <td> : <?= $mitra->user_name ?? '-' ?></td>
        </tr><br>
        <tr>
            <td><?= $labels['ketua_tim'] ?></td>
        </tr>
        <tr>
            <td> <?= $labels['nama_lengkap'] ?></td>
            <td> : <?= $abdimas->ketua_nama ?? '-' ?></td>
        </tr>
        <tr>
            <td> <?= $labels['nidn'] ?></td>
            <td> : <?= $abdimas->ketua_nidn ?? '-' ?></td>
        </tr>
        <tr>
            <td> <?= $labels['program_studi'] ?></td>
            <td> : <?= $abdimas->jurusan_name ?? '-' ?></td>
        </tr>
        <tr>
            <td> <?= $labels['perguruan_tinggi'] ?></td>
            <td> : <?= $perguruan_tinggi ?></td>
        </tr>
        <tr>
            <td> <?= $labels['bidang_keahlian'] ?></td>
            <td> : <?= $abdimas->jurusan_name ?? '-' ?></td>
        </tr><br>
        <tr>
            <td><?= $labels['anggota_tim'] ?></td>
            <td> : <?= $jumlah_anggota ?> <?= $labels['terlampir'] ?></td>
        </tr>

        <?php foreach ($anggota as $a) : ?>
            <?php if ($a->user_id != ($abdimas->ketua_id ?? null)) : ?>
                <?php if ($a->user_id === null) : ?>
                    <tr>
                        <td style="padding-left: 20px;"><?= $labels['tidak_ada_anggota'] ?></td>
                        <td></td>
                    </tr>
                <?php else : ?>
                    <tr>
                        <td style="padding-left: 20px;">
                            <?= $labels['nama_anggota'] ?><?= (isset($a->is_koordinator) && $a->is_koordinator ? ' (Koord)' : '') ?>
                        </td>
                        <td> : <?= $a->user_name ?? '-' ?></td>
                    </tr>
                    <tr>
                        <td style="padding-left: 20px;"><?= $labels['bidang_keahlian_label'] ?></td>
                        <td>: <?= $a->nidn ?? '-' ?> / <?= esc($a->jurusan_name ?? '-') ?></td>

                    </tr>
                <?php endif; ?>
            <?php endif; ?>
        <?php endforeach; ?>

        <br>
        <tr>
            <td><?= $labels['lokasi_kegiatan'] ?></td>
        </tr>
        <tr>
            <td> <?= $labels['wilayah_mitra'] ?></td>
            <td> : <?= $mitra->alamat ?? '-' ?></td>
        </tr>
        <tr>
            <td> <?= $labels['kabupaten_kota'] ?></td>
            <td> : <?= $mitra->kota_name ?? '-' ?></td>
        </tr>
        <tr>
            <td> <?= $labels['provinsi'] ?></td>
            <td> : <?= $mitra->provinsi_name ?? '-' ?></td>
        </tr>
        <tr>
            <td> <?= $labels['jarak_pt'] ?></td>
            <td> : <?= $abdimas->jarak_campus ?? '-' ?></td>
        </tr><br>
        <tr>
            <td><?= $labels['luaran'] ?></td>
            <td colspan="4">
                <?php if (!empty($luaran)): ?>
                    <table>
                        <?php foreach ($luaran as $index => $item): ?>
                            <tr>
                                <td>: <?= esc($item['nama'] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <?= $labels['tidak_ada_luaran'] ?>
                <?php endif; ?>
            </td>
        </tr><br>
        <tr>
            <td><?= $labels['jangka_waktu'] ?></td>
            <td colspan="4"> : <?= esc($waktu_pelaksanaan) ?></td>
        </tr><br>
        <tr>
            <td><?= $labels['total_biaya'] ?><br>
                <span class="nested-label">a. DPPM<br></span>
                <span class="nested-label"> b. <?= $labels['sumber_lain'] ?></span>
            </td>
            <td><br>
                : Rp <?= number_format($abdimas->range_dana ?? 0, 0, ',', '.') ?><br>
                : Rp <?= number_format($abdimas->dana_dprm ?? 0, 0, ',', '.') ?><br>
                : Rp <?= number_format($abdimas->dana_lain ?? 0, 0, ',', '.') ?>
            </td>
        </tr>
    </table>
</body>

</html>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Balasan</title>
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

        .kop img {
            float: left;
            width: 90px;
            margin-right: 10px;
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

        .kop p {
            margin: 0;
            font-size: 10pt;
        }

        .tanggal {
            text-align: right;
            margin-bottom: 20px;
            line-height: 1;
        }

        .judul {
            margin-bottom: 20px;
            line-height: 1;
            font-size: 12pt;
        }

        .isi {
            text-align: justify;
            margin-top: 20px;
            font-size: 12pt;
        }

        .ttd {
            width: 100%;
            margin-top: 40px;
        }

        .ttd .left {
            float: left;
            text-align: center;
            width: 50%;
        }

        .ttd .right {
            float: right;
            text-align: center;
            width: 50%;
        }

        .clear {
            clear: both;
        }
    </style>
</head>

<body>

    <!-- KOP SURAT -->
    <div class="kop" style="border-bottom: 2px solid black; padding-bottom: 10px; text-align: center;">
        <img src="<?= FCPATH . 'img/logo-lpm.jpg' ?>" width="70">

        <h2>LEMBAGA PENGABDIAN KEPADA MASYARAKAT (LPM)</h2>
        <h3>UNIVERSITAS GUNADARMA</h3>
        <p>Jl. Komjen (Pol) M. Jasin (Jl. Akses UI) Kelapa Dua, Depok Jawa Barat</p>
        <p>Kampus E Gedung 4 Lantai 1 Universitas Gunadarma Telp (021) 8727517</p>
    </div>

    <!-- Tanggal -->
    <?php
    // Ambil tanggal upload dari record SPM (kalau ada)
    $spmTanggal = !empty($spmRecord->created_at) ? $spmRecord->created_at : date('Y-m-d');

    // Tambah 1 hari dari tanggal upload (atau dari hari ini kalau gak ada)
    $tanggalSurat = date('Y-m-d', strtotime($spmTanggal . ' +1 day'));

    // Format ke gaya Indonesia
    $bulanIndo = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];

    $day   = date('d', strtotime($tanggalSurat));
    $month = (int)date('m', strtotime($tanggalSurat));
    $year  = date('Y', strtotime($tanggalSurat));

    $tanggalSuratFormatted = $day . ' ' . $bulanIndo[$month] . ' ' . $year;
    ?>

    <div class="tanggal">
        Jakarta, <?= $tanggalSuratFormatted ?>
    </div>

    <div class="judul">
        Nomor: <?= esc($nomor_surat_auto); ?><br>
        Lampiran : 1 Lembar <br>
        Perihal&nbsp;&nbsp;: Surat Balasan Permohonan Abdimas</div>

    <!-- Tujuan -->
    <div class="judul">
        Kepada Yth, <br>
        <?= $mitra->user_name ?? '-' ?> <br>
        Di Tempat
    </div>

    <!-- Isi Surat -->
    <div class="isi">
        Dengan hormat,<br><br>
        Sesuai dengan surat permintaan/permohoan kegiatan Pengabdian Kepada Masyarakat (Abdimas)
        yang telah kami terima dari pihak <?= $mitra->user_name ?? '-' ?> dengan nomor
        surat <?= esc($nomor_surat_mitra); ?> tanggal surat <?= $tanggal_surat ?? '-' ?>
        tentang pengajuan permohonan kegiatan Abdimas berupa <strong><?= esc($judul_kegiatan) ?></strong>, yang berlokasi di <?= $mitra->alamat ?? '-' ?>.
        <br><br>
        Program kegiatan Abdimas ini berkelanjutan selama 1 semester mulai dari
        <?= $tanggal_kegiatan ?? '-' ?> periode <?= esc($periode_display ?? 'Periode tidak tersedia') ?>, maka bersama ini kami bersedia untuk membantu kegiatan tersebut di atas dengan menugaskan
        beberapa tim dosen Universitas Gunadarma sesuai bidang ilmu yang dibutuhkan oleh pihak mitra, yaitu bidang <?= $bidang ?> (terlampir).

        <br><br>
        Demikian surat ini kami buat, semoga dengan adanya kegiatan Pengabdian Kepada Masyarakat (Abdimas) ini dapat membantu acara kegiatan operasional di <?= $mitra->user_name ?? '-' ?>.
        yang berlokasi di <?= esc($lokasi_mitra) ?>. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.

    </div>
    <div class="clear"></div>
    </div>
</body>

</html>
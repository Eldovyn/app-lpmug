<?php
// app/Controllers/Sertifikat.php
namespace App\Controllers;

use App\Models\AbdimasModel;
use App\Models\PeriodeModel;
use App\Models\MitraModel;
use App\Models\PesanModel;

class Sertifikat extends BaseController
{
    protected $abdimasModel;
    protected $periodeModel;
    protected $mitraModel;
    protected $pesanModel;

    public function __construct()
    {
        $this->abdimasModel = new AbdimasModel();
        $this->periodeModel = new PeriodeModel();
        $this->mitraModel   = new MitraModel();
        $this->pesanModel   = new PesanModel();
        helper(['auth', 'url', 'form']);
    }

    public function index()
    {
        if (!session('user_id')) {
            return redirect()->to(site_url('login'));
        }

        $user   = userLogin();
        $userId = $user->user_id;

        $laporanKetua = $this->abdimasModel->getLaporanByKetua($userId);
        $laporanAnggota = $this->abdimasModel->getLaporanByAnggota($userId);

        foreach ($laporanKetua as &$row) {
            $row['peran'] = 'Ketua Tim';
        }
        unset($row);

        foreach ($laporanAnggota as &$row) {
            $row['peran'] = 'Anggota';
        }
        unset($row);

        $allLaporan = $laporanKetua;
        $existingIds = array_column($laporanKetua, 'laporan_id');
        foreach ($laporanAnggota as $lap) {
            if (!in_array($lap['laporan_id'], $existingIds)) {
                $allLaporan[] = $lap;
            }
        }

        $data = [
            'title'      => 'Sertifikat Pengabdian Masyarakat',
            'title_tab'  => 'Sertifikat — LPM UG',
            'laporan'    => $allLaporan,
            'pesan'      => $this->pesanModel->getPesan(),
        ];

        return view('sertifikat/index', $data);
    }

    /**
     * Minimal sertifikat: background template + nama akun + 2 QR
     */
    public function generatePdf($laporanId = null)
    {
        if (!session('user_id')) {
            return redirect()->to(site_url('login'));
        }

        if (empty($laporanId) || !is_numeric($laporanId)) {
            return redirect()->back()->with('error', 'ID laporan tidak valid.');
        }

        $user     = userLogin();
        $namaUser = $user->user_name ?? 'Peserta';
        $nidn     = $user->nidn     ?? '-';

        // Ambil data laporan untuk periode
        $laporan = $this->abdimasModel->asArray()->find($laporanId);
        $periodeObj = $laporan ? $this->periodeModel->find($laporan['periode_id'] ?? 0) : null;
        $periode_display = '-';
        if (!empty($periodeObj)) {
            $periode_display = ($periodeObj->periode_name ?? '-') . ' (' . ($periodeObj->tahun_ajaran ?? '-') . ')';
        }

        try {
            require_once ROOTPATH . 'vendor/tecnickcom/tcpdf/tcpdf.php';
            require_once ROOTPATH . 'vendor/tecnickcom/tcpdf/tcpdf_barcodes_2d.php';
            require_once ROOTPATH . 'vendor/setasign/fpdi/src/autoload.php';

            // Landscape A4: 297 x 210 mm
            $pdf = new \setasign\Fpdi\Tcpdf\Fpdi('L', 'mm', 'A4', true, 'UTF-8', false);
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->SetMargins(0, 0, 0);
            $pdf->SetAutoPageBreak(false);
            $pdf->AddPage();

            // Load template PDF sebagai background
            $templatePath = FCPATH . 'template/template_sertifikat_lpm.pdf';
            if (file_exists($templatePath)) {
                $pdf->setSourceFile($templatePath);
                $tplId = $pdf->importPage(1);
                $pdf->useTemplate($tplId, 0, 0, 297, 210, true);
            }

            // ── Nama peserta dari akun yang login ─────────────────────────────
            $pdf->SetFont('helvetica', 'B', 28);
            $pdf->SetTextColor(31, 78, 121);
            $pdf->SetXY(0, 80);
            $pdf->Cell(297, 12, strtoupper($namaUser), 0, 1, 'C');

            // ── Keterangan Peran dan Judul Kegiatan ───────────────────────────
            $peran = ($laporan['ketua_id'] == $user->user_id) ? 'Ketua Pengusul' : 'Anggota Pengusul';
            
            // Keterangan Judul Kegiatan (karena PESERTA sudah ada di template)
            $pdf->SetFont('times', '', 14); // Font Serif seperti di gambar
            $pdf->SetTextColor(0, 0, 0);
            
            $judul = $laporan['judul_kegiatan'] ?? '-';
            $teksKegiatan = 'Dalam mengikuti kegiatan Pengabdian Kepada Masyarakat (PKM) ' . $periode_display . ' "' . $judul . '"';
            
            // Posisi X diatur ke 20 agar tidak terlalu mepet tepi kertas (margin)
            $pdf->SetXY(20, 115);
            // MultiCell untuk mem-wrap teks menjadi satu paragraf yang rapi
            $pdf->MultiCell(257, 8, $teksKegiatan, 0, 'C', false);

            $yDate = $pdf->GetY() + 8; // Beri sedikit jarak setelah paragraf teks

            // Tanggal Pelaksanaan di tengah (sebelum tanda tangan) - Format Range
            $bulanIndo = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
            
            $rawDate = $laporan['tanggal_kegiatan'] ?? '';
            
            // Sistem menyimpan format daterange menggunakan pemisah ' - '
            if (strpos($rawDate, ' - ') !== false) {
                $parts = explode(' - ', $rawDate);
                $rawStart = trim($parts[0] ?? '');
                $rawEnd   = trim($parts[1] ?? '');
            } else {
                $rawStart = trim($rawDate);
                $rawEnd   = trim($laporan['tanggal_selesai'] ?? '');
            }

            if (empty($rawStart) || strpos($rawStart, '0000') !== false || strtotime($rawStart) <= 0) {
                $rawStart = date('Y-m-d');
            }

            $startTs = strtotime($rawStart);
            $tglStart = date('d', $startTs);
            $blnStart = $bulanIndo[(int)date('m', $startTs)];
            $thnStart = date('Y', $startTs);

            $strTanggal = "$tglStart $blnStart $thnStart";

            // Cek apakah ada tanggal selesai yang valid dan berbeda dengan tanggal mulai
            if (!empty($rawEnd) && strpos($rawEnd, '0000') === false && strtotime($rawEnd) > 0 && $rawEnd !== $rawStart) {
                $endTs = strtotime($rawEnd);
                if ($endTs > $startTs) {
                    $tglEnd = date('d', $endTs);
                    $blnEnd = $bulanIndo[(int)date('m', $endTs)];
                    $thnEnd = date('Y', $endTs);

                    if ($thnStart !== $thnEnd) {
                        $strTanggal = "$tglStart $blnStart $thnStart — $tglEnd $blnEnd $thnEnd";
                    } elseif ($blnStart !== $blnEnd) {
                        $strTanggal = "$tglStart $blnStart — $tglEnd $blnEnd $thnStart";
                    } else {
                        $strTanggal = "$tglStart — $tglEnd $blnEnd $thnStart";
                    }
                }
            }

            $lokasiTanggal = "Depok, $strTanggal";

            $pdf->SetFont('helvetica', 'B', 12); // Tanggal Bold seperti gambar
            $pdf->SetTextColor(49, 39, 102); // Warna Ungu/Biru Gelap
            $pdf->SetXY(0, $yDate);
            $pdf->Cell(297, 6, $lokasiTanggal, 0, 1, 'C');

            // ── Area tanda tangan ──
            $pdf->SetFont('helvetica', '', 9);
            $pdf->SetTextColor(0, 0, 0); // Kembalikan ke hitam untuk tanda tangan

            $y_pos  = $yDate + 12; // Start of signatures dinamis berdasarkan letak tanggal
            $qrSize = 22; // Kurangi sedikit ukurannya agar tidak terlalu mepet bawah

            // Kolom kiri — LPM
            $colLX = 30;
            $colLW = 110;
            // Kolom kanan — Peserta
            $colRX = 157;
            $colRW = 110;

            $qr_lpm_content = "Dr. Aris Budi Setyawan, SE., MM., M.Si\nNIDN: 0326057004\nPeriode: " . $periode_display;

            // === Kolom Kiri : LPM ===
            $pdf->SetXY($colLX, $y_pos);
            $pdf->MultiCell($colLW, 4,
                "Mengetahui,\nKetua Lembaga Pengabdian kepada Masyarakat\nUniversitas Gunadarma",
                0, 'C', false);

            $xQrL = $colLX + ($colLW - $qrSize) / 2;
            $yQr  = $y_pos + 12;
            $pdf->SetXY($xQrL, $yQr);
            $pdf->write2DBarcode($qr_lpm_content, 'QRCODE,L',
                $pdf->GetX(), $pdf->GetY(), $qrSize, $qrSize, [], 'N');

            // Garis Tanda Tangan Kiri (70mm di tengah kolom 110mm)
            $lineStartL = $colLX + 20;
            $lineEndL = $colLX + 90;
            $pdf->Line($lineStartL, $yQr + $qrSize + 2, $lineEndL, $yQr + $qrSize + 2);

            $pdf->SetXY($colLX, $yQr + $qrSize + 4);
            $pdf->MultiCell($colLW, 4,
                "(Dr. Aris Budi Setyawan, SE., MM., M.Si)\nNIDN/NIP: 0326057004 / 930391",
                0, 'C', false);

            // === Kolom Kanan : Peserta ===
            $pdf->SetXY($colRX, $y_pos + 8); // Sejajarkan dengan bagian bawah teks LPM
            $pdf->MultiCell($colRW, 4, $peran . ",", 0, 'C', false);

            $qr_peserta_content = $namaUser .
                "\nNIDN: " . $nidn .
                "\nPeriode: " . $periode_display;

            $xQrR = $colRX + ($colRW - $qrSize) / 2;
            $pdf->SetXY($xQrR, $yQr);
            $pdf->write2DBarcode($qr_peserta_content, 'QRCODE,L',
                $pdf->GetX(), $pdf->GetY(), $qrSize, $qrSize, [], 'N');

            // Garis Tanda Tangan Kanan (70mm di tengah kolom 110mm)
            $lineStartR = $colRX + 20;
            $lineEndR = $colRX + 90;
            $pdf->Line($lineStartR, $yQr + $qrSize + 2, $lineEndR, $yQr + $qrSize + 2);

            $pdf->SetXY($colRX, $yQr + $qrSize + 4);
            $pdf->MultiCell($colRW, 4,
                "(" . strtoupper($namaUser) . ")\nNIDN: " . $nidn,
                0, 'C', false);

            // Bersihkan SEMUA level output buffer CI4 agar PDF tidak tercampur HTML
            while (ob_get_level() > 0) {
                ob_end_clean();
            }

            $filename   = 'sertifikat_' . preg_replace('/[^a-z0-9]/i', '_', $namaUser) . '_' . $laporanId . '.pdf';
            $pdfContent = $pdf->Output($filename, 'S'); // 'S' = string, bukan langsung echo

            return $this->response
                ->setHeader('Content-Type', 'application/pdf')
                ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
                ->setHeader('Content-Length', (string) strlen($pdfContent))
                ->setHeader('Cache-Control', 'private, max-age=0, must-revalidate')
                ->setBody($pdfContent);

        } catch (\Exception $e) {
            log_message('error', 'Sertifikat::generatePdf - ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal generate sertifikat: ' . $e->getMessage());
        }
    }
}


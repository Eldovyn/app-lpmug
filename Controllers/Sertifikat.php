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

            // ── Area tanda tangan — sama persis pola Abdimas::generatePdf() ──
            // Template background (landscape A4 297×210mm) meletakkan kotak:
            //   LPM   : X=20,  lebar=135 → X=20  s/d X=155
            //   Peserta: X=162, lebar=135 → X=162 s/d X=297
            $pdf->SetFont('helvetica', '', 9);
            $pdf->SetTextColor(0, 0, 0);

            $y_pos  = 157;   // Y awal kotak tanda tangan di template
            $qrSize = 25;    // sesuai placeholder 25×25 mm di background

            // Kolom kiri — LPM
            $colLX = 20;
            $colLW = 135;
            // Kolom kanan — Peserta
            $colRX = 162;
            $colRW = 115;

            $qr_lpm_content = "Dr. Aris Budi Setyawan, SE., MM., M.Si\nNIDN: 0326057004";

            // === Kolom Kiri : LPM ===
            // Keterangan di atas QR
            $pdf->SetXY($colLX, $y_pos + -18);
            $pdf->MultiCell($colLW, 5,
                "Mengetahui,\nKetua Lembaga Pengabdian kepada Masyarakat\nUniversitas Gunadarma",
                0, 'C', false);

            // QR — di tengah kolom (sama dengan pola Abdimas)
            $xQrL = $colLX + ($colLW - $qrSize) / 2;   // = 75
            $yQr  = $y_pos + -5;                         // = 177
            $pdf->SetXY($xQrL, $yQr);
            $pdf->write2DBarcode($qr_lpm_content, 'QRCODE,L',
                $pdf->GetX(), $pdf->GetY(), $qrSize, $qrSize, [], 'N');

            // Nama / NIDN di bawah QR
            $pdf->SetXY($colLX, $yQr + $qrSize + 2);
            $pdf->MultiCell($colLW, 5,
                "(Dr. Aris Budi Setyawan, SE., MM., M.Si)\nNIDN/NIP: 0326057004 / 930391",
                0, 'C', false);

            // === Kolom Kanan : Peserta ===
            // Keterangan di atas QR
            $pdf->SetXY($colRX, $y_pos + -12);
            $pdf->MultiCell($colRW, 5, "Ketua Pengusul,", 0, 'C', false);

            // QR — di tengah kolom (isi sama persis pola Abdimas::generatePdf)
            $qr_peserta_content = $namaUser .
                "\nNIDN: " . $nidn .
                "\nPeriode: " . $periode_display;

            $xQrR = $colRX + ($colRW - $qrSize) / 2;   // = 217
            $pdf->SetXY($xQrR, $yQr);
            $pdf->write2DBarcode($qr_peserta_content, 'QRCODE,L',
                $pdf->GetX(), $pdf->GetY(), $qrSize, $qrSize, [], 'N');

            // Nama + NIDN di bawah QR
            $pdf->SetXY($colRX, $yQr + $qrSize + 2);
            $pdf->MultiCell($colRW, 5,
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


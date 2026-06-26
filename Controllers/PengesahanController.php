<?php

namespace App\Controllers;

use App\Models\PengesahanModel;
use TCPDF;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class PengesahanController extends BaseController
{
    protected $pengesahanModel; // Ganti nama variabel agar lebih jelas

    public function __construct()
    {
        $this->pengesahanModel = new PengesahanModel();
    }

    public function view($laporan_id)
    {
        if ($laporan_id === null || !$this->pengesahanModel->isExists($laporan_id)) {
            return redirect()->back()->with('error', 'Laporan ID tidak valid atau tidak ditemukan!');
        }

        $dataPengesahan = $this->pengesahanModel->getPengesahanData($laporan_id);
        $anggota = $this->pengesahanModel->getAnggota($laporan_id);

        if (!$dataPengesahan) {
            return redirect()->back()->with('error', 'Data pengesahan tidak ditemukan!');
        }

        return view('lembar_pengesahan_view', [
            'data' => $dataPengesahan,
            'anggota' => $anggota
        ]);
    }

    public function generatePdf($laporan_id)
    {
        $dataPengesahan = $this->pengesahanModel->getPengesahanData($laporan_id);
        $anggota = $this->pengesahanModel->getAnggota($laporan_id);

        if (!$dataPengesahan) {
            return redirect()->back()->with('error', 'Data pengesahan tidak ditemukan!');
        }

        try {
            // Create unique filenames using timestamps
            $timestamp = time();
            $qrKetuaLPMPath = WRITEPATH . "uploads/qr_ketua_lpm_{$timestamp}.png";
            $qrKetuaTimPath = WRITEPATH . "uploads/qr_ketua_tim_{$timestamp}.png";

            // Generate QR Code for Ketua LPM
            $qrKetuaLPM = (new QrCode($dataPengesahan->pejabat_nama . "\nNIP: " . $dataPengesahan->pejabat_nip))
                ->setSize(120)
                ->setMargin(10);
            (new PngWriter())->write($qrKetuaLPM)->saveToFile($qrKetuaLPMPath);

            // Generate QR Code for Ketua Tim
            $qrKetuaTim = (new QrCode($dataPengesahan->ketua_nama . "\nNIDN: " . $dataPengesahan->ketua_nidn))
                ->setSize(120)
                ->setMargin(10);
            (new PngWriter())->write($qrKetuaTim)->saveToFile($qrKetuaTimPath);

            // Initialize TCPDF
            $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Universitas Gunadarma');
            $pdf->SetTitle('Lembar Pengesahan');
            $pdf->SetSubject('Program Pengabdian Kepada Masyarakat');
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->AddPage();

            // Load HTML template
            $html = view('lembar_pengesahan', [
                'data' => $dataPengesahan,
                'anggota' => $anggota,
                'qrKetuaLPMPath' => $qrKetuaLPMPath,
                'qrKetuaTimPath' => $qrKetuaTimPath
            ]);

            // Write HTML to PDF
            $pdf->writeHTML($html, true, false, true, false, '');

            // Clean up QR code files after generating the PDF
            $response = $pdf->Output('Lembar_Pengesahan.pdf', 'I');

            // Delete temporary QR code files
            if (file_exists($qrKetuaLPMPath)) {
                unlink($qrKetuaLPMPath);
            }
            if (file_exists($qrKetuaTimPath)) {
                unlink($qrKetuaTimPath);
            }

            return $response;
        } catch (\Exception $e) {
            log_message('error', 'Error generating PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal membuat PDF: ' . $e->getMessage());
        }
    }
}

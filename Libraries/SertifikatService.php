<?php

namespace App\Libraries;

use App\Models\AbdimasModel;
use App\Models\UsersModel;
use App\Models\PeriodeModel;
use App\Models\MahasiswaModel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF;

/**
 * SertifikatService - Service Library for Certificate Generation
 * 
 * Handles certificate generation with the following features:
 * - OOP approach with proper error handling
 * - QR Code generation using EndroidQrCode
 * - PDF generation using TCPDF with FPDI for template overlay
 * - Support for single and batch generation
 */
class SertifikatService
{
    /**
     * @var AbdimasModel
     */
    protected $abdimasModel;

    /**
     * @var UsersModel
     */
    protected $usersModel;

    /**
     * @var PeriodeModel
     */
    protected $periodeModel;

    /**
     * @var MahasiswaModel
     */
    protected $mahasiswaModel;

    /**
     * @var string Path to certificate template
     */
    protected $templatePath;

    /**
     * @var array Configuration for certificate
     */
    protected $config;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->abdimasModel = new AbdimasModel();
        $this->usersModel = new UsersModel();
        $this->periodeModel = new PeriodeModel();
        $this->mahasiswaModel = new MahasiswaModel();

        // Set template path - check multiple possible locations
        $possiblePaths = [
            FCPATH . 'template/sertifikat_template.pdf',
            FCPATH . 'public/template/sertifikat_template.pdf',
            dirname(FCPATH) . '/public/template/sertifikat_template.pdf'
        ];

        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                $this->templatePath = $path;
                break;
            }
        }

        // Configuration for certificate
        $this->config = [
            // Nama Ketua LPM - can be changed as needed
            'ketua_lpm' => [
                'nama' => 'Dr. H. Abdul Rachman, S.T., M.T.',
                'nip' => '196708151992031002',
                'jabatan' => 'Ketua LPM Universitas Gunadarma'
            ],
            // PDF settings
            'pdf' => [
                'orientation' => 'L', // Landscape
                'unit' => 'mm',
                'format' => 'A4',
                'unicode' => true,
                'encoding' => 'UTF-8'
            ],
            // Font settings
            'fonts' => [
                'title' => ['family' => 'helvetica', 'style' => 'B', 'size' => 24],
                'name' => ['family' => 'helvetica', 'style' => 'B', 'size' => 36],
                'body' => ['family' => 'helvetica', 'style' => '', 'size' => 12],
                'small' => ['family' => 'helvetica', 'style' => '', 'size' => 10]
            ],
            // Colors (RGB)
            'colors' => [
                'primary' => [31, 78, 121], // Dark blue
                'black' => [0, 0, 0]
            ]
        ];
    }

    /**
     * Get data for certificate generation
     * 
     * @param int $laporanId
     * @return array
     * @throws \Exception
     */
    public function getDataSertifikat(int $laporanId): array
    {
        try {
            // Get laporan data
            $laporan = $this->abdimasModel->find($laporanId);
            
            if (!$laporan) {
                throw new \Exception('Data laporan tidak ditemukan dengan ID: ' . $laporanId);
            }

            // Get periode data
            $periode = $this->periodeModel->find($laporan->periode_id);

            // Get mitra data
            $mitra = $this->usersModel->find($laporan->mitra_id);

            // Get chairman (ketua) data
            $ketua = $this->usersModel->find($laporan->ketua_id);

            if (!$ketua) {
                throw new \Exception('Data ketua tidak ditemukan');
            }

            // Get anggota (excluding chairman)
            $anggota = $this->abdimasModel->getAnggotaByLaporan($laporanId, $laporan->ketua_id);

            // Get mahasiswa
            $mahasiswa = $this->mahasiswaModel->getByLaporan($laporanId);

            return [
                'success' => true,
                'laporan' => $laporan,
                'periode' => $periode,
                'mitra' => $mitra,
                'ketua' => $ketua,
                'anggota' => $anggota,
                'mahasiswa' => $mahasiswa
            ];
        } catch (\Exception $e) {
            log_message('error', 'SertifikatService::getDataSertifikat - ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Get single member data for certificate
     * 
     * @param int $laporanId
     * @param int $userId
     * @return array
     */
    public function getDataAnggota(int $laporanId, int $userId): array
    {
        try {
            $data = $this->getDataSertifikat($laporanId);
            
            if (!$data['success']) {
                return $data;
            }

            // Check if user is chairman
            if ($data['ketua']->user_id == $userId) {
                return [
                    'success' => true,
                    'member' => $data['ketua'],
                    'role' => 'ketua',
                    'laporan' => $data['laporan'],
                    'periode' => $data['periode'],
                    'mitra' => $data['mitra']
                ];
            }

            // Check if user is anggota (dosen)
            foreach ($data['anggota'] as $anggota) {
                if ($anggota->user_id == $userId) {
                    return [
                        'success' => true,
                        'member' => $anggota,
                        'role' => 'anggota',
                        'laporan' => $data['laporan'],
                        'periode' => $data['periode'],
                        'mitra' => $data['mitra']
                    ];
                }
            }

            // Check if user is mahasiswa
            foreach ($data['mahasiswa'] as $mhs) {
                if ($mhs->mahasiswa_id == $userId) {
                    return [
                        'success' => true,
                        'member' => $mhs,
                        'role' => 'anggota',
                        'laporan' => $data['laporan'],
                        'periode' => $data['periode'],
                        'mitra' => $data['mitra']
                    ];
                }
            }

            throw new \Exception('Anggota tidak ditemukan dalam laporan ini');
        } catch (\Exception $e) {
            log_message('error', 'SertifikatService::getDataAnggota - ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Get all members (ketua + anggota + mahasiswa) for a laporan
     * 
     * @param int $laporanId
     * @return array
     */
    public function getAllMembers(int $laporanId): array
    {
        try {
            $data = $this->getDataSertifikat($laporanId);
            
            if (!$data['success']) {
                return $data;
            }

            $members = [];

            // Add chairman
            $members[] = [
                'member' => $data['ketua'],
                'role' => 'ketua'
            ];

            // Add anggota (dosen)
            foreach ($data['anggota'] as $anggota) {
                $members[] = [
                    'member' => $anggota,
                    'role' => 'anggota'
                ];
            }

            // Add mahasiswa
            foreach ($data['mahasiswa'] as $mhs) {
                $members[] = [
                    'member' => $mhs,
                    'role' => 'anggota'
                ];
            }

            return [
                'success' => true,
                'members' => $members,
                'laporan' => $data['laporan'],
                'periode' => $data['periode'],
                'mitra' => $data['mitra']
            ];
        } catch (\Exception $e) {
            log_message('error', 'SertifikatService::getAllMembers - ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Generate QR Code for certificate
     * 
     * @param string $nama
     * @param string $nimNidn
     * @return string Path to QR code image
     * @throws \Exception
     */
    public function generateQRCode(string $nama, string $nimNidn): string
    {
        try {
            // Create QR code content
            $qrContent = $nama . "\n" . $nimNidn;

            // Generate QR code
            $qrCode = new QrCode($qrContent);
            $qrCode->setSize(150);
            $qrCode->setMargin(10);

            // Create writer and save to file
            $writer = new PngWriter();
            $timestamp = time();
            $qrPath = WRITEPATH . 'uploads/qr_sertifikat_' . $timestamp . '_' . md5($nama) . '.png';
            
            $result = $writer->write($qrCode);
            $result->saveToFile($qrPath);

            return $qrPath;
        } catch (\Exception $e) {
            log_message('error', 'SertifikatService::generateQRCode - ' . $e->getMessage());
            throw new \Exception('Gagal menghasilkan QR Code: ' . $e->getMessage());
        }
    }

    /**
     * Generate single certificate PDF
     * 
     * @param array $memberData Member data including role
     * @param object $laporanData Laporan data
     * @param object|null $periodeData Periode data
     * @param object|null $mitraData Mitra data
     * @return string PDF path
     * @throws \Exception
     */
    public function generatePDF(array $memberData, $laporanData, $periodeData = null, $mitraData = null): string
    {
        try {
            $member = $memberData['member'];
            $role = $memberData['role'];

            // Determine name and ID based on object type
            if (is_object($member)) {
                $nama = $member->user_name ?? $member->mahasiswa_name ?? '';
                $nimNidn = $member->nidn ?? $member->mahasiswa_npm ?? '';
            } else {
                $nama = '';
                $nimNidn = '';
            }
            
            // Get timestamp for filename
            $timestamp = time();

            // Generate QR Code
            $qrPath = $this->generateQRCode($nama, $nimNidn);

            // Check if template exists
            if (empty($this->templatePath) || !file_exists($this->templatePath)) {
                throw new \Exception('Template sertifikat tidak ditemukan. Pastikan file ada di public/template/sertifikat_template.pdf');
            }

            // Initialize FPDI
            $pdf = new Fpdi();
            $pdf->AddPage($this->config['pdf']['orientation'], $this->config['pdf']['format']);
            
            // Import template
            $templateId = $pdf->importPage(1);
            $pdf->useTemplate($templateId, 0, 0);

            // Set font for name
            $pdf->SetFont($this->config['fonts']['name']['family'], $this->config['fonts']['name']['style'], $this->config['fonts']['name']['size']);
            $pdf->SetTextColor($this->config['colors']['primary'][0], $this->config['colors']['primary'][1], $this->config['colors']['primary'][2]);

            // Position for nama - adjust based on template
            // A4 Landscape: 297mm x 210mm
            $pdf->SetXY(0, 85);
            $pdf->Cell(297, 20, strtoupper($nama), 0, 1, 'C');

            // Add role label
            $roleLabel = ($role === 'ketua') ? 'Sebagai Ketua Tim' : 'Sebagai Anggota';
            $pdf->SetFont($this->config['fonts']['body']['family'], $this->config['fonts']['body']['style'], $this->config['fonts']['body']['size']);
            $pdf->SetTextColor($this->config['colors']['black'][0], $this->config['colors']['black'][1], $this->config['colors']['black'][2]);
            $pdf->SetXY(0, 105);
            $pdf->Cell(297, 10, $roleLabel, 0, 1, 'C');

            // Add activity title
            $judul = is_object($laporanData) ? ($laporanData->judul_kegiatan ?? '') : '';
            $pdf->SetXY(0, 120);
            $pdf->Cell(297, 10, $judul, 0, 1, 'C');

            // Add date and location
            $tanggalFormatted = $this->formatTanggal(is_object($laporanData) ? ($laporanData->tanggal_kegiatan ?? '') : '');
            $alamatMitra = is_object($mitraData) ? ($mitraData->alamat ?? '') : '';
            
            $pdf->SetXY(0, 135);
            $pdf->Cell(297, 10, $tanggalFormatted . ' - ' . $alamatMitra, 0, 1, 'C');

            // Add periode
            $periodeText = '';
            if (is_object($periodeData)) {
                $periodeText = ($periodeData->nama_periode ?? '') . ' ' . ($periodeData->tahun ?? '');
            }
            $pdf->SetXY(0, 145);
            $pdf->Cell(297, 10, $periodeText, 0, 1, 'C');

            // Add QR Code
            if (file_exists($qrPath)) {
                // Adjust position based on template
                $pdf->Image($qrPath, 230, 150, 30, 30, 'PNG');
            }

            // Add Chairman LPM name at bottom
            $pdf->SetXY(0, 170);
            $pdf->Cell(297, 10, $this->config['ketua_lpm']['nama'], 0, 1, 'C');

            // Save PDF
            $outputPath = WRITEPATH . 'uploads/sertifikat_' . $timestamp . '_' . md5($nama) . '.pdf';
            $pdf->Output($outputPath, 'F');

            // Clean up QR code
            if (file_exists($qrPath)) {
                unlink($qrPath);
            }

            return $outputPath;
        } catch (\Exception $e) {
            log_message('error', 'SertifikatService::generatePDF - ' . $e->getMessage());
            throw new \Exception('Gagal menghasilkan PDF: ' . $e->getMessage());
        }
    }

    /**
     * Format tanggal from "YYYY-MM-DD - YYYY-MM-DD" to readable format
     * 
     * @param string $tanggal
     * @return string
     */
    private function formatTanggal(string $tanggal): string
    {
        if (empty($tanggal)) {
            return '';
        }

        try {
            $parts = explode(' - ', $tanggal);
            $tgl = trim($parts[0] ?? '');
            
            if (empty($tgl)) {
                return $tanggal;
            }

            $date = date_create($tgl);
            if ($date) {
                return date_format($date, 'd F Y');
            }

            return $tanggal;
        } catch (\Exception $e) {
            return $tanggal;
        }
    }

    /**
     * Get configuration
     * 
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * Update chairman LPM configuration
     * 
     * @param string $nama
     * @param string $nip
     * @param string $jabatan
     */
    public function setKetuaLPM(string $nama, string $nip, string $jabatan = '')
    {
        $this->config['ketua_lpm'] = [
            'nama' => $nama,
            'nip' => $nip,
            'jabatan' => $jabatan
        ];
    }
}

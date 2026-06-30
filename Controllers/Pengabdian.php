<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use TCPDF;

class SuratBalasan extends BaseController
{
    public function generatePdf($id)
    {
        // --- Contoh ambil data dari database (model bisa disesuaikan) ---
        $model = new \App\Models\SuratModel();
        $surat = $model->find($id);

        if (!$surat) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Surat dengan ID $id tidak ditemukan");
        }

        // --- Siapkan data untuk view ---
        $data = [
            'nomor_urut'   => $surat['nomor_urut'],
            'tanggal_surat'=> $surat['tanggal_surat'],
            'nama_kegiatan'=> $surat['nama_kegiatan'],
            'lokasi_mitra' => $surat['lokasi_mitra'],
            'jangka_waktu' => $surat['jangka_waktu'],
            'bidang_ilmu'  => $surat['bidang_ilmu'],
            'ketua'        => $surat['ketua'],
            'deskripsi'    => $surat['deskripsi'],
            'status'       => $surat['status'],
            'created_at'   => $surat['created_at'],
            'updated_at'   => $surat['updated_at'],
        ];

        // --- Load view ke dalam HTML ---
        $html = view('pdf/surat_balasan', $data);

        // --- Generate PDF pakai TCPDF ---
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('LPM Universitas Gunadarma');
        $pdf->SetAuthor('LPM UG');
        $pdf->SetTitle('Surat Balasan Permohonan Abdimas');

        // Hilangkan header/footer bawaan TCPDF
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->AddPage();

        // Tulis HTML ke PDF
        $pdf->writeHTML($html, true, false, true, false, '');

        // Output PDF ke browser
        $this->response->setHeader('Content-Type', 'application/pdf');
        $pdf->Output('surat_balasan.pdf', 'I'); // 'I' = inline, 'D' = download
    }
}

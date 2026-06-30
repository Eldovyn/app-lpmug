<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class BerkasController extends Controller
{
    public function laporan($file)
    {
        $filePath = FCPATH . 'berkas/laporan/' . $file; // FCPATH adalah /home/lpmug/public_html/
        log_message('debug', 'Checking file: ' . $filePath);

        if (file_exists($filePath)) {
            // Pastikan header diset dengan benar
            return $this->response
                ->setHeader('Content-Type', 'application/pdf')
                ->setHeader('Content-Disposition', 'inline; filename="' . $file . '"')
                ->setHeader('Content-Length', filesize($filePath))
                ->download($filePath, null)
                ->setFileName($file);
        } else {
            log_message('error', 'File not found: ' . $filePath);
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("File $file tidak ditemukan.");
        }
    }

    public function kegiatan($file)
    {
        $filePath = FCPATH . 'berkas/kegiatan/' . $file;
        log_message('debug', 'Checking file: ' . $filePath);

        if (file_exists($filePath)) {
            return $this->response
                ->setHeader('Content-Type', 'application/pdf')
                ->setHeader('Content-Disposition', 'inline; filename="' . $file . '"')
                ->setHeader('Content-Length', filesize($filePath))
                ->download($filePath, null)
                ->setFileName($file);
        } else {
            log_message('error', 'File not found: ' . $filePath);
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("File $file tidak ditemukan.");
        }
    }
}
<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

//  Router untuk bikin Database dengan nama uglpm
//  $routes->get('create-db', function() {
//     $forge = \Config\Database::forge();
//     if ($forge->createDatabase('uglpm')) {
//         echo 'Database created!';
//     }
//  });

// Router Homepage
$routes->get('/', 'Home::index');
$routes->get('home/galeri', 'Home::galeri');
$routes->get('home/struktur', 'Home::struktur');
$routes->get('home/mitra', 'Home::mitra');
$routes->get('home/kalender', 'Home::kalender');
$routes->get('home/roadmap', 'Home::roadmap');
$routes->get('home/kontak', 'Home::kontak');
$routes->post('home/kontak', 'Home::kontakProcess');

// Router Controller Auth Login
$routes->get('login', 'Auth::login');

// Router Controller Auth Registrasi
$routes->get('registrasi', 'Auth::registrasi');

$routes->get('dashboard', 'Dashboard::index');
// $routes->addRedirect('/', 'dashboard');

// Router Update Profile Superior
$routes->get('profile_user/update/(:num)', 'Dashboard::profile/$1');
$routes->put('profile_user/(:any)', 'Dashboard::update_profile/$1');
$routes->get('ubah_password/update/(:num)', 'Dashboard::ubah_password/$1');
$routes->put('ubah_password/(:any)', 'Dashboard::update_password/$1');

// Router Update Profile Administrator
$routes->get('profile_user_admin/update/(:num)', 'Dashboard::profile_admin/$1');
$routes->put('profile_user_admin/(:any)', 'Dashboard::update_profile_admin/$1');
$routes->get('ubah_password_admin/update/(:num)', 'Dashboard::ubah_password_admin/$1');
$routes->put('ubah_password_admin/(:any)', 'Dashboard::update_password_admin/$1');

// Router Update Profile Staff
$routes->get('profile_user_staff/update/(:num)', 'Dashboard::profile_staff/$1');
$routes->put('profile_user_staff/(:any)', 'Dashboard::update_profile_staff/$1');
$routes->get('ubah_password_staff/update/(:num)', 'Dashboard::ubah_password_staff/$1');
$routes->put('ubah_password_staff/(:any)', 'Dashboard::update_password_staff/$1');

// Router Update Profile Dosen
$routes->get('profile_user_dosen/update/(:num)', 'Dashboard::profile_dosen/$1');
$routes->put('profile_user_dosen/(:any)', 'Dashboard::update_profile_dosen/$1');
$routes->get('ubah_password_dosen/update/(:num)', 'Dashboard::ubah_password_dosen/$1');
$routes->put('ubah_password_dosen/(:any)', 'Dashboard::update_password_dosen/$1');

// Router Update Profile Mitra
$routes->get('profile_user_mitra/update/(:num)', 'Dashboard::profile_mitra/$1');
$routes->put('profile_user_mitra/(:any)', 'Dashboard::update_profile_mitra/$1');
$routes->get('ubah_password_mitra/update/(:num)', 'Dashboard::ubah_password_mitra/$1');
$routes->put('ubah_password_mitra/(:any)', 'Dashboard::update_password_mitra/$1');

// Router Controller HakAkses
// CARA 1 router controller
// $routes->get('hak_akses', 'HakAkses::index');
// $routes->get('hak_akses/tambah', 'HakAkses::create');
// $routes->post('hak_akses', 'HakAkses::store');
// $routes->get('hak_akses/update/(:num)', 'HakAkses::edit/$1');
// $routes->put('hak_akses/(:any)', 'HakAkses::update/$1');
// $routes->delete('hak_akses/(:segment)', 'HakAkses::destroy/$1');
$routes->resource('hak_akses');

// Router Controller Pengguna
// Update password pengguna dari halaman list
$routes->post('pengguna/update-password/(:num)', 'Pengguna::updatePassword/$1');

$routes->resource('pengguna');

// Router List dosen
// $routes->get('dosen', 'Dosen::index');
// Untuk Admin dll
$routes->resource('dosen');

// Untuk User Dosen
$routes->resource('listdosen');

// Router List mitra
<<<<<<< HEAD
// Form upload dokumen (SPM & SKM)
$routes->get('mitra/upload/(:num)', 'Mitra::uploadForm/$1');

// Separate upload forms for SPM and SKM
$routes->get('mitra/upload-spm/(:num)', 'Mitra::uploadFormSpm/$1');
$routes->get('mitra/upload-skm/(:num)', 'Mitra::uploadFormSkm/$1');

// Submit upload (SPM atau SKM)
$routes->post('mitra/upload/(:num)', 'Mitra::uploadSubmit/$1');
$routes->get('download/(:segment)/(:any)', 'Mitra::download/$1/$2');

// Preview and download document routes
$routes->get('mitra/previewDokumen/(:num)/(:segment)', 'Mitra::previewDokumen/$1/$2');
$routes->get('mitra/previewDokumen/(:num)/(:segment)/(:num)', 'Mitra::previewDokumen/$1/$2/$3');
$routes->get('mitra/downloadDokumen/(:num)/(:segment)', 'Mitra::downloadDokumen/$1/$2');
$routes->get('mitra/downloadDokumen/(:num)/(:segment)/(:num)', 'Mitra::downloadDokumen/$1/$2/$3');
$routes->get('mitra/previewTest/(:num)/(:segment)', 'Mitra::previewTest/$1/$2');

// Delete laporan route
$routes->delete('mitra/delete-laporan/(:num)', 'Mitra::deleteLaporan/$1');

// Dokumen Mitra routes
$routes->get('dokumen_mitra/upload/(:num)', 'DokumenMitra::upload/$1');
$routes->post('dokumen_mitra/store', 'DokumenMitra::store');
$routes->get('dokumen_mitra/download/(:num)', 'DokumenMitra::download/$1');
$routes->get('dokumen_mitra/preview/(:num)', 'DokumenMitra::preview/$1');
$routes->delete('dokumen_mitra/delete/(:num)', 'DokumenMitra::delete/$1');

// Main surat balasan page for mitra
$routes->get('mitra/surat-balasan', 'Mitra::suratBalasan');

// Preview surat balasan (existing files)
$routes->get('mitra/preview-surat-balasan/(:num)', 'Mitra::previewSuratBalasan/$1');
$routes->get('mitra/surat-balasan/preview/(:num)', 'Mitra::previewSuratBalasan/$1'); // Alternative route

// Download surat balasan (existing files)
$routes->get('mitra/download-surat-balasan/(:num)', 'Mitra::downloadSuratBalasan/$1');
$routes->get('mitra/surat-balasan/download/(:num)', 'Mitra::downloadSuratBalasan/$1'); // Alternative route

// Generate new surat balasan PDF (DIRECT TO ABDIMAS - UPDATED)
// Instead of redirect, directly route to Abdimas with validation
$routes->get('mitra/surat-balasan/generate/(:num)', 'Mitra::generateSuratBalasanPdf/$1');

// Check laporan status via AJAX (optional)
$routes->get('mitra/laporan-status/(:num)', 'Mitra::checkLaporanStatus/$1');
$routes->get('mahasiswa/check-group-limit/(:num)', 'Mahasiswa::checkGroupLimit/$1');

// Update password mitra dari halaman list
$routes->post('mitra/update-password/(:num)', 'Mitra::updatePassword/$1');

<<<<<<< HEAD
=======
=======
$routes->get('mitra/upload_spm/(:num)', 'Mitra::uploadSPMForm/$1');
$routes->post('mitra/upload_spm/(:num)', 'Mitra::uploadSPMSubmit/$1');
>>>>>>> 55c0835 (refactor: update code)
>>>>>>> 8f61413156a4d90b7797e0e124740d7d842e6332
$routes->resource('mitra');

// Untuk User Dosen
$routes->resource('listmitra');

// Router Controller Provinsi
// $routes->presenter('provinsi', ['filter' => 'isLoggedIn']);
$routes->get('provinsi/trash', 'Provinsi::trash');
$routes->get('provinsi/restore', 'Provinsi::restore');
$routes->get('provinsi/restore/(:any)', 'Provinsi::restore/$1');
$routes->delete('provinsi/delete2/(:any)', 'Provinsi::delete2/$1');
$routes->delete('provinsi/delete2', 'Provinsi::delete2');
$routes->presenter('provinsi');

// Router Kota / Kabupaten
$routes->resource('kota');

// Router Periode
$routes->resource('periode');

// Router Universitas
$routes->resource('universitas');

// Router Jabatan Fungsional
$routes->resource('fungsional');

// Router fakultas
$routes->resource('fakultas');

// Router jurusan
$routes->resource('jurusan');

// Router topik penelitian
$routes->resource('topik');

// Router program
$routes->resource('program');

// Router sub program
$routes->resource('subprogram');

// Router luaran
$routes->resource('luaran');

// Router Pendaftaran abdimas
// $routes->get('abdimas/proposal/(:num)', 'Abdimas::uploadProposal/$1');
// $routes->put('abdimas/proposal/update/(:any)', 'Abdimas::updateProposal/$1');
// $routes->get('pelaporan', 'Abdimas::pelaporan');
$routes->get('abdimas/pdf/(:num)', 'Abdimas::generatePdf/$1');
$routes->get('abdimas/arsip/(:num)', 'Abdimas::arsip/$1');
$routes->get('abdimas/formSuratBalasan', 'Abdimas::formSuratBalasan');
<<<<<<< HEAD

// === SURAT BALASAN PDF ROUTE (MODIFIED - now supports multi-role) ===
$routes->get('abdimas/surat-balasan-pdf/(:num)', 'Abdimas::suratBalasanPdf/$1');

$routes->post('abdimas/generate-surat-balasan-pdf-from-form', 'Abdimas::generateSuratBalasanPdfFromForm');
$routes->get('abdimas/berkas/(:segment)/(:segment)', 'Abdimas::berkas/$1/$2');
$routes->get('abdimas/download/(:segment)/(:segment)', 'Abdimas::download/$1/$2');
$routes->get('abdimas/lihatDokumen/(:segment)/(:num)/(:num)', 'Abdimas::lihatDokumen/$1/$2/$3');
=======
$routes->get('abdimas/surat-balasan-pdf/(:num)', 'Abdimas::suratBalasanPdf/$1');
$routes->post('abdimas/generate-surat-balasan-pdf-from-form', 'Abdimas::generateSuratBalasanPdfFromForm');
>>>>>>> 55c0835 (refactor: update code)
$routes->get('pelaksanaan', 'Pelaksanaan::index');

// Routes untuk upload dan update surat undangan di PelaksanaanController
$routes->get('pelaksanaan/upload-undangan/(:num)', 'Pelaksanaan::uploadUndangan/$1');
$routes->post('pelaksanaan/update-undangan/(:num)', 'Pelaksanaan::updateUndangan/$1');

$routes->resource('abdimas');
$routes->resource('undangan');
$routes->resource('pelaksanaan');
$routes->resource('pelaporan');
$routes->resource('monev');
$routes->resource('monevadmin');

$routes->get('rekapan/download', 'Rekapan::download');
$routes->get('rekapan/proses', 'Rekapan::proses');
$routes->get('rekapan/revisi', 'Rekapan::revisi');
$routes->get('rekapan/setuju', 'Rekapan::setuju');
$routes->resource('rekapan');

// Router kalender
$routes->resource('kalender');

// Router Profile LPM UG
$routes->resource('profilelpm');

// Router Profile Staff LPM UG
$routes->resource('profilestaff');

// Router Struktur
$routes->resource('struktur');

// Router Galeri
$routes->resource('galeri');

// Router Kontak Person
$routes->resource('kontak');

// Router Pesan
$routes->resource('pesan');

// Router Dokumen
$routes->get('dokumen', 'Dokumen::index');
$routes->post('dokumen/upload', 'Dokumen::upload');
$routes->get('dokumen/edit/(:num)', 'Dokumen::edit/$1');
$routes->post('dokumen/update/(:num)', 'Dokumen::update/$1');
$routes->post('dokumen/delete/(:num)', 'Dokumen::delete/$1'); // ubah dari get ke post
$routes->get('dokumen/trash', 'Dokumen::trash');
$routes->post('dokumen/restore/(:num)', 'Dokumen::restore/$1'); // ubah dari get ke post
$routes->get('dokumen/show/(:num)', 'Dokumen::show/$1');
$routes->post('dokumen/deletePermanent/(:num)', 'Dokumen::deletePermanent/$1');

// Hibah routes
$routes->get('hibah/verification-list', 'Admin\HibahVerificationController::index');
$routes->get('hibah/verification-detail/(:num)', 'Admin\HibahVerificationController::show/$1');
$routes->get('hibah/active-flags', 'Admin\HibahVerificationController::activeFlags');
$routes->get('hibah/upload', 'Hibah::upload');
$routes->post('hibah/do-upload', 'Hibah::doUpload');
$routes->get('hibah/myHibah', 'Hibah::myHibah');
$routes->get('hibah/detail/(:num)', 'Hibah::detail/$1');
$routes->get('hibah/download-proposal/(:num)', 'Hibah::downloadProposal/$1');
$routes->get('hibah/download/(:num)', 'Hibah::downloadProposal/$1');
$routes->get('hibah/submit/(:num)', 'Hibah::submit/$1');
$routes->get('hibah/delete/(:num)', 'Hibah::delete/$1');
$routes->get('hibah/edit/(:num)', 'Hibah::edit/$1');
$routes->post('hibah/do-edit/(:num)', 'Hibah::doEdit/$1');
$routes->post('hibah/approve/(:num)', 'Admin\HibahVerificationController::approve/$1');
$routes->post('hibah/reject/(:num)', 'Admin\HibahVerificationController::reject/$1');
$routes->post('hibah/delete-admin/(:num)', 'Admin\HibahVerificationController::delete/$1');
$routes->delete('hibah/delete-admin/(:num)', 'Admin\HibahVerificationController::delete/$1');

// File serving routes
$routes->get('berkas/laporan/(:any)', 'Mitra::laporan/$1');
$routes->get('berkas/kegiatan/(:any)', 'Mitra::kegiatan/$1');
$routes->get('berkas/spm/(:any)', 'Mitra::spm/$1');
$routes->get('berkas/skm/(:any)', 'Mitra::skm/$1');

$routes->resource('data_semester', ['controller' => 'DataSemester']);

$routes->get('sertifikat', 'Sertifikat::index');
$routes->get('sertifikat/generate-pdf/(:num)', 'Sertifikat::generatePdf/$1');
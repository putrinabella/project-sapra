<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('createDatabase', function () {
    $forge = \Config\Database::forge();
    if ($forge->createDatabase('dbmanajemensapra')) {
        echo 'Database created!';
    }
});

// USER ROUTES
// Login 
$routes->get('login', 'Auth::login');
$routes->get('auth', 'Auth::index');
$routes->post('loginProcess', 'Auth::loginProcess');
$routes->get('logout', 'Auth::logout');

// User Logs
$routes->get('viewLogs', 'UserLogs::viewLogs');
$routes->get('viewLogs/generatePDF', 'UserLogs::generatePDF');
$routes->get('viewLogs/export', 'UserLogs::export');


// HOME
$routes->get('/', 'ProfilSekolah::index');
$routes->get('home', 'ProfilSekolah::index');
// $routes->get('home/generate', 'Home::generate');
// $routes->get('/', 'Home::index');
// $routes->get('home', 'Home::index');
// $routes->get('home/generate', 'Home::generate');


// DATA MASTER

// Identitas Sarana
$routes->get('identitasSarana/createTemplate', 'IdentitasSarana::createTemplate');
$routes->get('identitasSarana/generatePDF', 'IdentitasSarana::generatePDF');
$routes->get('identitasSarana/export', 'IdentitasSarana::export');
$routes->post('identitasSarana/import', 'IdentitasSarana::import');
$routes->get('identitasSarana/edit', 'IdentitasSarana::edit');
$routes->get('identitasSarana/trash', 'IdentitasSarana::trash');
$routes->get('identitasSarana/restore/(:any)', 'IdentitasSarana::restore/$1');
$routes->get('identitasSarana/restore', 'IdentitasSarana::restore');
$routes->delete('identitasSarana/deletePermanent/(:any)', 'IdentitasSarana::deletePermanent/$1');
$routes->delete('identitasSarana/deletePermanent', 'IdentitasSarana::deletePermanent');
$routes->presenter('identitasSarana', ['filter' => 'isLoggedIn']);

// Identitas Prasarana
$routes->get('identitasPrasarana/createTemplate', 'IdentitasPrasarana::createTemplate');
$routes->get('identitasPrasarana/generatePDF', 'IdentitasPrasarana::generatePDF');
$routes->get('identitasPrasarana/export', 'IdentitasPrasarana::export');
$routes->post('identitasPrasarana/import', 'IdentitasPrasarana::import');
$routes->get('identitasPrasarana/edit', 'IdentitasPrasarana::edit');
$routes->get('identitasPrasarana/trash', 'IdentitasPrasarana::trash');
$routes->get('identitasPrasarana/restore/(:any)', 'IdentitasPrasarana::restore/$1');
$routes->get('identitasPrasarana/restore', 'IdentitasPrasarana::restore');
$routes->delete('identitasPrasarana/deletePermanent/(:any)', 'IdentitasPrasarana::deletePermanent/$1');
$routes->delete('identitasPrasarana/deletePermanent', 'IdentitasPrasarana::deletePermanent');
$routes->resource('identitasPrasarana', ['filter' => 'isLoggedIn']);

// Identitas Laboratorium
$routes->get('identitasLab/createTemplate', 'IdentitasLab::createTemplate');
$routes->get('identitasLab/generatePDF', 'IdentitasLab::generatePDF');
$routes->get('identitasLab/export', 'IdentitasLab::export');
$routes->post('identitasLab/import', 'IdentitasLab::import');
$routes->get('identitasLab/edit', 'IdentitasLab::edit');
$routes->get('identitasLab/trash', 'IdentitasLab::trash');
$routes->get('identitasLab/restore/(:any)', 'IdentitasLab::restore/$1');
$routes->get('identitasLab/restore', 'IdentitasLab::restore');
$routes->delete('identitasLab/deletePermanent/(:any)', 'IdentitasLab::deletePermanent/$1');
$routes->delete('identitasLab/deletePermanent', 'IdentitasLab::deletePermanent');
$routes->resource('identitasLab', ['filter' => 'isLoggedIn']);

// Sumber Dana
$routes->get('sumberDana/createTemplate', 'SumberDana::createTemplate');
$routes->get('sumberDana/generatePDF', 'SumberDana::generatePDF');
$routes->get('sumberDana/export', 'SumberDana::export');
$routes->post('sumberDana/import', 'SumberDana::import');
$routes->get('sumberDana/edit', 'SumberDana::edit');
$routes->get('sumberDana/trash', 'SumberDana::trash');
$routes->get('sumberDana/restore/(:any)', 'SumberDana::restore/$1');
$routes->get('sumberDana/restore', 'SumberDana::restore');
$routes->delete('sumberDana/deletePermanent/(:any)', 'SumberDana::deletePermanent/$1');
$routes->delete('sumberDana/deletePermanent', 'SumberDana::deletePermanent');
$routes->presenter('sumberDana', ['filter' => 'isLoggedIn']);

// Identitas Gedung
$routes->get('identitasGedung/createTemplate', 'IdentitasGedung::createTemplate');
$routes->get('identitasGedung/generatePDF', 'IdentitasGedung::generatePDF');
$routes->get('identitasGedung/export', 'IdentitasGedung::export');
$routes->post('identitasGedung/import', 'IdentitasGedung::import');
$routes->get('identitasGedung/edit', 'IdentitasGedung::edit');
$routes->get('identitasGedung/trash', 'IdentitasGedung::trash');
$routes->get('identitasGedung/restore/(:any)', 'IdentitasGedung::restore/$1');
$routes->get('identitasGedung/restore', 'IdentitasGedung::restore');
$routes->delete('identitasGedung/deletePermanent/(:any)', 'IdentitasGedung::deletePermanent/$1');
$routes->delete('identitasGedung/deletePermanent', 'IdentitasGedung::deletePermanent');
$routes->presenter('identitasGedung', ['filter' => 'isLoggedIn']);

// Identitas Lantai
$routes->get('identitasLantai/generatePDF', 'IdentitasLantai::generatePDF');
$routes->get('identitasLantai/export', 'IdentitasLantai::export');
$routes->post('identitasLantai/import', 'IdentitasLantai::import');
$routes->get('identitasLantai/edit', 'IdentitasLantai::edit');
$routes->get('identitasLantai/trash', 'IdentitasLantai::trash');
$routes->get('identitasLantai/restore/(:any)', 'IdentitasLantai::restore/$1');
$routes->get('identitasLantai/restore', 'IdentitasLantai::restore');
$routes->delete('identitasLantai/deletePermanent/(:any)', 'IdentitasLantai::deletePermanent/$1');
$routes->delete('identitasLantai/deletePermanent', 'IdentitasLantai::deletePermanent');
$routes->presenter('identitasLantai', ['filter' => 'isLoggedIn']);

// Status Layanan
$routes->get('statusLayanan/generatePDF', 'StatusLayanan::generatePDF');
$routes->get('statusLayanan/export', 'StatusLayanan::export');
$routes->post('statusLayanan/import', 'StatusLayanan::import');
$routes->get('statusLayanan/edit', 'StatusLayanan::edit');
$routes->get('statusLayanan/trash', 'StatusLayanan::trash');
$routes->get('statusLayanan/restore/(:any)', 'StatusLayanan::restore/$1');
$routes->get('statusLayanan/restore', 'StatusLayanan::restore');
$routes->delete('statusLayanan/deletePermanent/(:any)', 'StatusLayanan::deletePermanent/$1');
$routes->delete('statusLayanan/deletePermanent', 'StatusLayanan::deletePermanent');
$routes->presenter('statusLayanan', ['filter' => 'isLoggedIn']);

// Kategori Manajemen
$routes->get('s', 'KategoriManajemen::createTemplate');
$routes->get('kategoriManajemen/generatePDF', 'KategoriManajemen::generatePDF');
$routes->get('kategoriManajemen/export', 'KategoriManajemen::export');
$routes->post('kategoriManajemen/import', 'KategoriManajemen::import');
$routes->get('kategoriManajemen/edit', 'KategoriManajemen::edit');
$routes->get('kategoriManajemen/trash', 'KategoriManajemen::trash');
$routes->get('kategoriManajemen/restore/(:any)', 'KategoriManajemen::restore/$1');
$routes->get('kategoriManajemen/restore', 'KategoriManajemen::restore');
$routes->delete('kategoriManajemen/deletePermanent/(:any)', 'KategoriManajemen::deletePermanent/$1');
$routes->delete('kategoriManajemen/deletePermanent', 'KategoriManajemen::deletePermanent');
$routes->presenter('kategoriManajemen', ['filter' => 'isLoggedIn']);

// Kelas Siswa
$routes->get('identitasKelas/createTemplate', 'IdentitasKelas::createTemplate');
$routes->get('identitasKelas/generatePDF', 'IdentitasKelas::generatePDF');
$routes->get('identitasKelas/export', 'IdentitasKelas::export');
$routes->post('identitasKelas/import', 'IdentitasKelas::import');
$routes->get('identitasKelas/edit', 'IdentitasKelas::edit');
$routes->get('identitasKelas/trash', 'IdentitasKelas::trash');
$routes->get('identitasKelas/restore/(:any)', 'IdentitasKelas::restore/$1');
$routes->get('identitasKelas/restore', 'IdentitasKelas::restore');
$routes->delete('identitasKelas/deletePermanent/(:any)', 'IdentitasKelas::deletePermanent/$1');
$routes->delete('identitasKelas/deletePermanent', 'IdentitasKelas::deletePermanent');
$routes->presenter('identitasKelas', ['filter' => 'isLoggedIn']);

// SARANA

// Rincian Aset
$routes->get('pemusnahanAsetDetail/(:num)', 'RincianAset::pemusnahanAsetDetail/$1');
$routes->get('pemusnahanAset', 'RincianAset::pemusnahanAset');
$routes->get('dataSaranaDetail/(:num)', 'RincianAset::dataSaranaDetail/$1');
$routes->get('dataSarana', 'RincianAset::dataSarana');
$routes->get('dataSarana/generatePDF', 'RincianAset::dataSaranaGeneratePDF');
$routes->get('dataSarana/export', 'RincianAset::dataSaranaExport');
$routes->get('rincianAset/createTemplate', 'RincianAset::createTemplate');
$routes->get('rincianAset/print/(:num)', 'RincianAset::print/$1');
$routes->get('rincianAset/generatePDF', 'RincianAset::generatePDF');
$routes->get('rincianAset/export', 'RincianAset::export');
$routes->post('rincianAset/import', 'RincianAset::import');
$routes->get('rincianAset/edit', 'RincianAset::edit');
$routes->get('rincianAset/editPemusnahan/(:any)', 'RincianAset::editPemusnahan/$1');
$routes->get('rincianAset/trash', 'RincianAset::trash');
$routes->get('rincianAset/restore/(:any)', 'RincianAset::restore/$1');
$routes->get('rincianAset/restore', 'RincianAset::restore');
$routes->post('pemusnahanAset/delete/(:any)', 'RincianAset::pemusnahanAsetDelete/$1');
$routes->patch('pemusnahanAset/updatePemusnahan/(:any)', 'RincianAset::updatePemusnahan/$1');
$routes->delete('rincianAset/deletePermanent/(:any)', 'RincianAset::deletePermanent/$1');
$routes->delete('rincianAset/deletePermanent', 'RincianAset::deletePermanent');
$routes->resource('rincianAset', ['filter' => 'isLoggedIn']);

// Layanan Aset
$routes->get('saranaLayananAset/createTemplate', 'SaranaLayananAset::createTemplate');
$routes->get('saranaLayananAset/generatePDF', 'SaranaLayananAset::generatePDF');
$routes->get('saranaLayananAset/export', 'SaranaLayananAset::export');
$routes->post('saranaLayananAset/import', 'SaranaLayananAset::import');
$routes->get('saranaLayananAset/edit', 'SaranaLayananAset::edit');
$routes->get('saranaLayananAset/trash', 'SaranaLayananAset::trash');
$routes->get('saranaLayananAset/restore/(:any)', 'SaranaLayananAset::restore/$1');
$routes->get('saranaLayananAset/restore', 'SaranaLayananAset::restore');
$routes->delete('saranaLayananAset/deletePermanent/(:any)', 'SaranaLayananAset::deletePermanent/$1');
$routes->delete('saranaLayananAset/deletePermanent', 'SaranaLayananAset::deletePermanent');
$routes->resource('saranaLayananAset', ['filter' => 'isLoggedIn']);

// Layanan Non Aset
$routes->get('saranaLayananNonAset/createTemplate', 'SaranaLayananNonAset::createTemplate');
$routes->get('saranaLayananNonAset/generatePDF', 'SaranaLayananNonAset::generatePDF');
$routes->get('saranaLayananNonAset/export', 'SaranaLayananNonAset::export');
$routes->post('saranaLayananNonAset/import', 'SaranaLayananNonAset::import');
$routes->get('saranaLayananNonAset/edit', 'SaranaLayananNonAset::edit');
$routes->get('saranaLayananNonAset/trash', 'SaranaLayananNonAset::trash');
$routes->get('saranaLayananNonAset/restore/(:any)', 'SaranaLayananNonAset::restore/$1');
$routes->get('saranaLayananNonAset/restore', 'SaranaLayananNonAset::restore');
$routes->delete('saranaLayananNonAset/deletePermanent/(:any)', 'SaranaLayananNonAset::deletePermanent/$1');
$routes->delete('saranaLayananNonAset/deletePermanent', 'SaranaLayananNonAset::deletePermanent');
$routes->resource('saranaLayananNonAset', ['filter' => 'isLoggedIn']);

// PRASARANA

// Ruangan
$routes->get('prasaranaRuangan/print/(:num)', 'PrasaranaRuangan::print/$1');
$routes->get('prasaranaRuangan/showInfo/(:num)', 'PrasaranaRuangan::showInfo/$1');
$routes->resource('prasaranaRuangan', ['filter' => 'isLoggedIn']);

// Non Ruangan
$routes->get('prasaranaNonRuangan/print/(:num)', 'PrasaranaNonRuangan::print/$1');
$routes->get('prasaranaNonRuangan/showInfo/(:num)', 'PrasaranaNonRuangan::showInfo/$1');
$routes->resource('prasaranaNonRuangan', ['filter' => 'isLoggedIn']);


// IT 

// Perangkat IT
$routes->get('perangkatIt/print/(:num)', 'perangkatIt::print/$1');
$routes->get('perangkatIt/createTemplate', 'perangkatIt::createTemplate');
$routes->get('perangkatIt/generatePDF', 'perangkatIt::generatePDF');
$routes->get('perangkatIt/export', 'perangkatIt::export');
$routes->resource('perangkatIt', ['filter' => 'isLoggedIn']);

// Layanan Aset IT
$routes->get('layananAsetIt/createTemplate', 'LayananAsetIt::createTemplate');
$routes->get('layananAsetIt/generatePDF', 'LayananAsetIt::generatePDF');
$routes->get('layananAsetIt/export', 'LayananAsetIt::export');
$routes->post('layananAsetIt/import', 'LayananAsetIt::import');
$routes->get('layananAsetIt/edit', 'LayananAsetIt::edit');
$routes->get('layananAsetIt/trash', 'LayananAsetIt::trash');
$routes->get('layananAsetIt/restore/(:any)', 'LayananAsetIt::restore/$1');
$routes->get('layananAsetIt/restore', 'LayananAsetIt::restore');
$routes->delete('layananAsetIt/deletePermanent/(:any)', 'LayananAsetIt::deletePermanent/$1');
$routes->delete('layananAsetIt/deletePermanent', 'LayananAsetIt::deletePermanent');
$routes->resource('layananAsetIt', ['filter' => 'isLoggedIn']);

// Website
$routes->get('website/createTemplate', 'Website::createTemplate');
$routes->get('website/generatePDF', 'Website::generatePDF');
$routes->get('website/export', 'Website::export');
$routes->post('website/import', 'Website::import');
$routes->get('website/edit', 'Website::edit');
$routes->get('website/trash', 'Website::trash');
$routes->get('website/restore/(:any)', 'Website::restore/$1');
$routes->get('website/restore', 'Website::restore');
$routes->delete('website/deletePermanent/(:any)', 'Website::deletePermanent/$1');
$routes->delete('website/deletePermanent', 'Website::deletePermanent');
$routes->resource('website', ['filter' => 'isLoggedIn']);

// Sosial Media
$routes->get('sosialMedia/createTemplate', 'SosialMedia::createTemplate');
$routes->get('sosialMedia/generatePDF', 'SosialMedia::generatePDF');
$routes->get('sosialMedia/export', 'SosialMedia::export');
$routes->post('sosialMedia/import', 'SosialMedia::import');
$routes->get('sosialMedia/edit', 'SosialMedia::edit');
$routes->get('sosialMedia/trash', 'SosialMedia::trash');
$routes->get('sosialMedia/restore/(:any)', 'SosialMedia::restore/$1');
$routes->get('sosialMedia/restore', 'SosialMedia::restore');
$routes->delete('sosialMedia/deletePermanent/(:any)', 'SosialMedia::deletePermanent/$1');
$routes->delete('sosialMedia/deletePermanent', 'SosialMedia::deletePermanent');
$routes->resource('sosialMedia', ['filter' => 'isLoggedIn']);

// Aplikasi 
$routes->get('aplikasi/createTemplate', 'Aplikasi::createTemplate');
$routes->get('aplikasi/generatePDF', 'Aplikasi::generatePDF');
$routes->get('aplikasi/export', 'Aplikasi::export');
$routes->post('aplikasi/import', 'Aplikasi::import');
$routes->get('aplikasi/edit', 'Aplikasi::edit');
$routes->get('aplikasi/trash', 'Aplikasi::trash');
$routes->get('aplikasi/restore/(:any)', 'Aplikasi::restore/$1');
$routes->get('aplikasi/restore', 'Aplikasi::restore');
$routes->delete('aplikasi/deletePermanent/(:any)', 'Aplikasi::deletePermanent/$1');
$routes->delete('aplikasi/deletePermanent', 'Aplikasi::deletePermanent');
$routes->resource('aplikasi', ['filter' => 'isLoggedIn']);

// Profil Sekolah
$routes->get('profilSekolah/createTemplateDokumen', 'ProfilSekolah::createTemplateDokumen');
$routes->get('profilSekolah/generatePDFDokumen', 'ProfilSekolah::generatePDFDokumen');
$routes->get('profilSekolah/exportDokumen', 'ProfilSekolah::exportDokumen');
$routes->post('profilSekolah/importDokumen', 'ProfilSekolah::importDokumen');
$routes->get('profilSekolah/trashDokumen', 'ProfilSekolah::trashDokumen');
$routes->get('profilSekolah/restoreDokumen/(:any)', 'ProfilSekolah::restoreDokumen/$1');
$routes->get('profilSekolah/restoreDokumen', 'ProfilSekolah::restoreDokumen');
$routes->get('profilSekolah/(:num)/editDokumen', 'ProfilSekolah::editDokumen/$1');
$routes->get('profilSekolah/newDokumen', 'ProfilSekolah::newDokumen');
$routes->get('profilSekolah/print/(:num)', 'ProfilSekolah::print/$1');
$routes->post('profilSekolah/createDokumen', 'ProfilSekolah::createDokumen');
$routes->patch('profilSekolah/updateDokumen/(:segment)', 'ProfilSekolah::updateDokumen/$1');
$routes->delete('profilSekolah/deleteDokumen/(:num)', 'ProfilSekolah::deleteDokumen/$1');
$routes->delete('profilSekolah/deletePermanent/(:any)', 'ProfilSekolah::deletePermanent/$1');
$routes->delete('profilSekolah/deletePermanent', 'ProfilSekolah::deletePermanent');
$routes->resource('profilSekolah', ['filter' => 'isLoggedIn']);


// LABORATORIUM

// Manajemen Aset
$routes->get('rincianLabAset/createTemplate', 'RincianLabAset::createTemplate');
$routes->get('rincianLabAset/print/(:num)', 'RincianLabAset::print/$1');
$routes->get('rincianLabAset/generatePDF', 'RincianLabAset::generatePDF');
$routes->get('rincianLabAset/export', 'RincianLabAset::export');
$routes->post('rincianLabAset/import', 'RincianLabAset::import');
$routes->get('rincianLabAset/edit', 'RincianLabAset::edit');
$routes->get('rincianLabAset/trash', 'RincianLabAset::trash');
$routes->get('rincianLabAset/restore/(:any)', 'RincianLabAset::restore/$1');
$routes->get('rincianLabAset/restore', 'RincianLabAset::restore');
$routes->delete('rincianLabAset/deletePermanent/(:any)', 'RincianLabAset::deletePermanent/$1');
$routes->delete('rincianLabAset/deletePermanent', 'RincianLabAset::deletePermanent');
$routes->resource('rincianLabAset', ['filter' => 'isLoggedIn']);

// Laboratorium
$routes->get('laboratorium/print/(:num)', 'Laboratorium::print/$1');
$routes->get('laboratorium/showInfo/(:num)', 'Laboratorium::showInfo/$1');
$routes->resource('laboratorium', ['filter' => 'isLoggedIn']);

// MANAJEMEN LAYANAN

// Layanan Aset Lab
$routes->get('layananLabAset/createTemplate', 'LayananLabAset::createTemplate');
$routes->get('layananLabAset/generatePDF', 'LayananLabAset::generatePDF');
$routes->get('layananLabAset/export', 'LayananLabAset::export');
$routes->post('layananLabAset/import', 'LayananLabAset::import');
$routes->get('layananLabAset/edit', 'LayananLabAset::edit');
$routes->get('layananLabAset/trash', 'LayananLabAset::trash');
$routes->get('layananLabAset/restore/(:any)', 'LayananLabAset::restore/$1');
$routes->get('layananLabAset/restore', 'LayananLabAset::restore');
$routes->delete('layananLabAset/deletePermanent/(:any)', 'LayananLabAset::deletePermanent/$1');
$routes->delete('layananLabAset/deletePermanent', 'LayananLabAset::deletePermanent');
$routes->resource('layananLabAset', ['filter' => 'isLoggedIn']);

// Layanan Non Aset Lab
$routes->get('layananLabNonAset/createTemplate', 'LayananLabNonAset::createTemplate');
$routes->get('layananLabNonAset/generatePDF', 'LayananLabNonAset::generatePDF');
$routes->get('layananLabNonAset/export', 'LayananLabNonAset::export');
$routes->post('layananLabNonAset/import', 'LayananLabNonAset::import');
$routes->get('layananLabNonAset/edit', 'LayananLabNonAset::edit');
$routes->get('layananLabNonAset/trash', 'LayananLabNonAset::trash');
$routes->get('layananLabNonAset/restore/(:any)', 'LayananLabNonAset::restore/$1');
$routes->get('layananLabNonAset/restore', 'LayananLabNonAset::restore');
$routes->delete('layananLabNonAset/deletePermanent/(:any)', 'LayananLabNonAset::deletePermanent/$1');
$routes->delete('layananLabNonAset/deletePermanent', 'LayananLabNonAset::deletePermanent');
$routes->resource('layananLabNonAset', ['filter' => 'isLoggedIn']);

// MANAJEMEN PEMINJAMAN

// Data Peminjaman
$routes->get('dataPeminjaman/generatePDF', 'DataPeminjaman::generatePDF');
$routes->get('dataPeminjaman/export', 'DataPeminjaman::export');
$routes->get('dataPeminjaman/trash', 'DataPeminjaman::trash');
$routes->get('dataPeminjaman/restore/(:any)', 'DataPeminjaman::restore/$1');
$routes->get('dataPeminjaman/restore', 'DataPeminjaman::restore');
$routes->delete('dataPeminjaman/deletePermanent/(:any)', 'DataPeminjaman::deletePermanent/$1');
$routes->delete('dataPeminjaman/deletePermanent', 'DataPeminjaman::deletePermanent');
$routes->resource('dataPeminjaman', ['filter' => 'isLoggedIn']);

// Manajemen Peminjaman

$routes->get('manajemenPeminjaman/print/(:num)', 'ManajemenPeminjaman::print/$1');
$routes->get('manajemenPeminjaman/getKodeLab/(:num)', 'ManajemenPeminjaman::getKodeLab/$1');
$routes->post('manajemenPeminjaman/addLoan', 'ManajemenPeminjaman::addLoan');
$routes->delete('manajemenPeminjaman/deletePermanent/(:any)', 'ManajemenPeminjaman::deletePermanent/$1');
$routes->delete('manajemenPeminjaman/deletePermanent', 'ManajemenPeminjaman::deletePermanent');
$routes->resource('manajemenPeminjaman', ['filter' => 'isLoggedIn']);


// Backup and Restore
$routes->get('backup', 'DatabaseManagement::backup');
$routes->get('restore', 'DatabaseManagement::restoreView');
$routes->post('restoreDatabase', 'DatabaseManagement::restore');

// Restore



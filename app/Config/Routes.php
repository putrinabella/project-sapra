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
$routes->get('loginProcess', 'Auth::loginProcess');
$routes->get('logout', 'Auth::logout');
$routes->post('updateSessionMode', 'Auth::updateSessionMode');

// User Logs
$routes->get('viewLogs', 'UserLogs::viewLogs');
$routes->get('viewLogs/generatePDF', 'UserLogs::generatePDF');
$routes->get('viewLogs/export', 'UserLogs::export');


// HOME
// $routes->get('/', 'ProfilSekolah::index');
// $routes->get('home', 'ProfilSekolah::index');

$routes->get('/', 'Home::index');
$routes->get('home', 'Home::index');


// DATA MASTER
$routes->get('manajemenUser/createTemplate', 'ManajemenUser::createTemplate');
$routes->get('manajemenUser/generatePDF', 'ManajemenUser::generatePDF');
$routes->get('manajemenUser/export', 'ManajemenUser::export');
$routes->post('manajemenUser/import', 'ManajemenUser::import');
$routes->get('manajemenUser/edit', 'ManajemenUser::edit');
$routes->get('manajemenUser/trash', 'ManajemenUser::trash');
$routes->get('manajemenUser/restore/(:any)', 'ManajemenUser::restore/$1');
$routes->get('manajemenUser/restore', 'ManajemenUser::restore');
$routes->delete('manajemenUser/deletePermanent/(:any)', 'ManajemenUser::deletePermanent/$1');
$routes->delete('manajemenUser/deletePermanent', 'ManajemenUser::deletePermanent');
$routes->resource('manajemenUser', ['filter' => 'isLoggedIn']);

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
$routes->get('identitasLantai/createTemplate', 'IdentitasLantai::createTemplate');
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
$routes->get('statusLayanan/createTemplate', 'StatusLayanan::createTemplate');
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
$routes->get('kategoriManajemen/createTemplate', 'KategoriManajemen::createTemplate');
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

// Kategori MEP
$routes->get('kategoriMep/createTemplate', 'KategoriMep::createTemplate');
$routes->get('kategoriMep/generatePDF', 'KategoriMep::generatePDF');
$routes->get('kategoriMep/export', 'KategoriMep::export');
$routes->post('kategoriMep/import', 'KategoriMep::import');
$routes->get('kategoriMep/edit', 'KategoriMep::edit');
$routes->get('kategoriMep/trash', 'KategoriMep::trash');
$routes->get('kategoriMep/restore/(:any)', 'KategoriMep::restore/$1');
$routes->get('kategoriMep/restore', 'KategoriMep::restore');
$routes->delete('kategoriMep/deletePermanent/(:any)', 'KategoriMep::deletePermanent/$1');
$routes->delete('kategoriMep/deletePermanent', 'KategoriMep::deletePermanent');
$routes->presenter('kategoriMep', ['filter' => 'isLoggedIn']);

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

// Kelas Siswa
$routes->get('kategoriPegawai/createTemplate', 'KategoriPegawai::createTemplate');
$routes->get('kategoriPegawai/generatePDF', 'KategoriPegawai::generatePDF');
$routes->get('kategoriPegawai/export', 'KategoriPegawai::export');
$routes->post('kategoriPegawai/import', 'KategoriPegawai::import');
$routes->get('kategoriPegawai/edit', 'KategoriPegawai::edit');
$routes->get('kategoriPegawai/trash', 'KategoriPegawai::trash');
$routes->get('kategoriPegawai/restore/(:any)', 'KategoriPegawai::restore/$1');
$routes->get('kategoriPegawai/restore', 'KategoriPegawai::restore');
$routes->delete('kategoriPegawai/deletePermanent/(:any)', 'KategoriPegawai::deletePermanent/$1');
$routes->delete('kategoriPegawai/deletePermanent', 'KategoriPegawai::deletePermanent');
$routes->presenter('kategoriPegawai', ['filter' => 'isLoggedIn']);

// Data Siswa
$routes->get('dataSiswa/createTemplate', 'DataSiswa::createTemplate');
$routes->get('dataSiswa/generatePDF', 'DataSiswa::generatePDF');
$routes->get('dataSiswa/export', 'DataSiswa::export');
$routes->post('dataSiswa/import', 'DataSiswa::import');
$routes->get('dataSiswa/edit', 'DataSiswa::edit');
$routes->get('dataSiswa/trash', 'DataSiswa::trash');
$routes->get('dataSiswa/restore/(:any)', 'DataSiswa::restore/$1');
$routes->get('dataSiswa/restore', 'DataSiswa::restore');
$routes->delete('dataSiswa/deletePermanent/(:any)', 'DataSiswa::deletePermanent/$1');
$routes->delete('dataSiswa/deletePermanent', 'DataSiswa::deletePermanent');
$routes->resource('dataSiswa', ['filter' => 'isLoggedIn']);
// SARANA

// Rincian Aset
// $routes->get('QRBarcode', 'QRBarcode::index');
// $routes->get('rincianAset/qrcode/(:num)', 'RincianAset::qrcode/$1');
// $routes->get('generateSelectedQR', 'RincianAset::generateSelectedQR');
$routes->get('generateQRDoc', 'RincianAset::generateQRDoc');
$routes->get('generateItQRDoc', 'RincianAset::generateItQRDoc');
$routes->add('generateSelectedQR/(:any)', 'RincianAset::generateSelectedQR/$1');
$routes->add('generateSelectedItQR/(:any)', 'RincianAset::generateSelectedItQR/$1');

$routes->get('QRBarcode/(:segment)', 'QRBarcode::generateQRCode/$1');
$routes->get('pemusnahanAsetDetail/(:num)', 'RincianAset::pemusnahanAsetDetail/$1');
$routes->get('pemusnahanAset', 'RincianAset::pemusnahanAset');
$routes->get('pemusnahanItAset', 'RincianAset::pemusnahanItAset');
$routes->get('dataSaranaDetail/(:num)', 'RincianAset::dataSaranaDetail/$1');
$routes->get('dataSarana', 'RincianAset::dataSarana');
$routes->get('dataSarana/generatePDF', 'RincianAset::dataSaranaGeneratePDF');
$routes->get('dataSarana/export', 'RincianAset::dataSaranaExport');
$routes->get('rincianItAset/generatePDF', 'RincianAset::generateItPDF');
$routes->get('rincianItAset/export', 'RincianAset::exportIt');
$routes->get('dataItSarana', 'RincianAset::dataItSarana');
$routes->get('dataItSaranaDetail/(:num)', 'PerangkatIt::show/$1');
$routes->get('dataItSarana/generatePDF', 'RincianAset::dataItSaranaGeneratePDF');
$routes->get('dataItSarana/export', 'RincianAset::dataItSaranaExport');
$routes->get('dataItSarana/trash', 'RincianAset::trashIt');
$routes->get('dataRincianItSarana', 'RincianAset::dataRincianItSarana');
$routes->get('dataItSarana/createTemplate', 'RincianAset::createItTemplate');
$routes->get('dataItSarana/new', 'RincianAset::newIt');
$routes->get('dataItSarana/show/(:any)', 'RincianAset::showIt/$1');
$routes->post('dataItSarana/create', 'RincianAset::createIt');
$routes->get('rincianAset/createTemplate', 'RincianAset::createTemplate');
$routes->get('rincianAset/print/(:num)', 'RincianAset::print/$1');
$routes->get('rincianAset/generatePDF', 'RincianAset::generatePDF');
$routes->get('rincianAset/export', 'RincianAset::export');
$routes->get('rincianAset/edit', 'RincianAset::edit');
$routes->get('rincianAset/editPemusnahan/(:any)', 'RincianAset::editPemusnahan/$1');
$routes->get('dataItSarana/editPemusnahanIt/(:any)', 'RincianAset::editPemusnahanIt/$1');
$routes->get('rincianAset/trash', 'RincianAset::trash');
$routes->get('rincianAset/restore/(:any)', 'RincianAset::restore/$1');
$routes->get('rincianAset/restore', 'RincianAset::restore');
$routes->get('dataItSarana/restore/(:any)', 'RincianAset::restoreIt/$1');
$routes->get('dataItSarana/restore', 'RincianAset::restoreIt');
$routes->get('pemusnahanAset/dataDestroyaGeneratePDF', 'RincianAset::dataDestroyaGeneratePDF');
$routes->get('pemusnahanAset/exportDestroyFile', 'RincianAset::exportDestroyFile');
$routes->get('pemusnahanItAset/dataDestroyaGeneratePDF', 'RincianAset::dataDestroyaGenerateItPDF');
$routes->get('pemusnahanItAset/exportDestroyFile', 'RincianAset::exportDestroyItFile');
$routes->get('dataItSarana/(:any)/edit', 'RincianAset::editIt/$1');
$routes->patch('dataItSarana/(:any)', 'RincianAset::updateIt/$1');
$routes->post('rincianAset/import', 'RincianAset::import');
$routes->post('dataItSarana/import', 'RincianAset::importIt');
$routes->post('rincianAset/generateAndSetKodeRincianAset', 'RincianAset::generateAndSetKodeRincianAset');
$routes->post('dataItSarana/generateAndSetKodeRincianItAset', 'RincianAset::generateAndSetKodeRincianItAset');
$routes->post('pemusnahanAset/delete/(:any)', 'RincianAset::pemusnahanAsetDelete/$1');
$routes->post('pemusnahanItAset/delete/(:any)', 'RincianAset::pemusnahanItAsetDelete/$1');
$routes->post('rincianAset/generateKode', 'RincianAset::generateKode');
$routes->post('rincianAset/(:any)/updateKode', 'RincianAset::generateKode');
$routes->post('rincianAset/checkDuplicate', 'RincianAset::checkDuplicate');
$routes->post('rincianAset/(:any)/updateCheckDuplicate', 'RincianAset::checkDuplicate');
$routes->post('dataItSarana/generateKode', 'RincianAset::generateKode');
$routes->post('dataItSarana/(:any)/updateKode', 'RincianAset::generateKode');
$routes->post('dataItSarana/checkDuplicate', 'RincianAset::checkDuplicate');
$routes->post('dataItSarana/(:any)/updateCheckDuplicate', 'RincianAset::checkDuplicate');
$routes->patch('pemusnahanAset/updatePemusnahan/(:any)', 'RincianAset::updatePemusnahan/$1');
$routes->patch('pemusnahanItAset/updatePemusnahan/(:any)', 'RincianAset::updateItPemusnahan/$1');
$routes->delete('rincianAset/deletePermanent/(:any)', 'RincianAset::deletePermanent/$1');
$routes->delete('rincianAset/deletePermanent', 'RincianAset::deletePermanent');
$routes->delete('dataItSarana/deletePermanent/(:any)', 'RincianAset::deletePermanentIt/$1');
$routes->delete('dataItSarana/deletePermanent', 'RincianAset::deletePermanentIt');
$routes->delete('dataItSarana/(:any)', 'RincianAset::deleteIt/$1');
$routes->resource('rincianAset', ['filter' => 'isLoggedIn']);

// Layanan Aset
// Ajax Select2
$routes->post('getKodeRincianAsetBySarana', 'SaranaLayananAset::getKodeRincianAsetBySarana');
$routes->post('getIdentitasPrasaranaByKodeRincianAset', 'SaranaLayananAset::getIdentitasPrasaranaByKodeRincianAset');
$routes->post('getKategoriManajemenByKodeRincianAset', 'SaranaLayananAset::getKategoriManajemenByKodeRincianAset');
$routes->post('getIdRincianAsetByKodeRincianAset', 'SaranaLayananAset::getIdRincianAsetByKodeRincianAset');

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

// Inventaris
$routes->get('inventaris/createTemplate', 'Inventaris::createTemplate');
$routes->get('inventaris/generatePDF', 'Inventaris::generatePDF');
$routes->get('inventaris/export', 'Inventaris::export');
$routes->post('inventaris/import', 'Inventaris::import');
$routes->get('inventaris/edit', 'Inventaris::edit');
$routes->get('inventaris/trash', 'Inventaris::trash');
$routes->get('inventaris/restore/(:any)', 'Inventaris::restore/$1');
$routes->get('inventaris/restore', 'Inventaris::restore');
$routes->delete('inventaris/deletePermanent/(:any)', 'Inventaris::deletePermanent/$1');
$routes->delete('inventaris/deletePermanent', 'Inventaris::deletePermanent');
$routes->resource('inventaris', ['filter' => 'isLoggedIn']);

// Data Inventaris
$routes->get('dataInventaris/createTemplate', 'DataInventaris::createTemplate');
$routes->get('dataInventaris/generatePDF', 'DataInventaris::generatePDF');
$routes->get('dataInventaris/export', 'DataInventaris::export');
$routes->post('dataInventaris/import', 'DataInventaris::import');
$routes->get('dataInventaris/edit', 'DataInventaris::edit');
$routes->get('dataInventaris/trash', 'DataInventaris::trash');
$routes->get('dataInventaris/restore/(:any)', 'DataInventaris::restore/$1');
$routes->get('dataInventaris/restore', 'DataInventaris::restore');
$routes->delete('dataInventaris/deletePermanent/(:any)', 'DataInventaris::deletePermanent/$1');
$routes->delete('dataInventaris/deletePermanent', 'DataInventaris::deletePermanent');
$routes->resource('dataInventaris', ['filter' => 'isLoggedIn']);

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
$routes->get('perangkatIt/rincian/(:num)', 'perangkatIt::rincian/$1');
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


// Tagihan Air
$routes->get('tagihanAir/createTemplate', 'TagihanAir::createTemplate');
$routes->get('tagihanAir/generatePDF', 'TagihanAir::generatePDF');
$routes->get('tagihanAir/export', 'TagihanAir::export');
$routes->post('tagihanAir/import', 'TagihanAir::import');
$routes->get('tagihanAir/edit', 'TagihanAir::edit');
$routes->get('tagihanAir/trash', 'TagihanAir::trash');
$routes->get('tagihanAir/restore/(:any)', 'TagihanAir::restore/$1');
$routes->get('tagihanAir/restore', 'TagihanAir::restore');
$routes->delete('tagihanAir/deletePermanent/(:any)', 'TagihanAir::deletePermanent/$1');
$routes->delete('tagihanAir/deletePermanent', 'TagihanAir::deletePermanent');
$routes->resource('tagihanAir', ['filter' => 'isLoggedIn']);

// Tagihan Listrik
$routes->get('tagihanListrik/createTemplate', 'TagihanListrik::createTemplate');
$routes->get('tagihanListrik/generatePDF', 'TagihanListrik::generatePDF');
$routes->get('tagihanListrik/export', 'TagihanListrik::export');
$routes->post('tagihanListrik/import', 'TagihanListrik::import');
$routes->get('tagihanListrik/edit', 'TagihanListrik::edit');
$routes->get('tagihanListrik/trash', 'TagihanListrik::trash');
$routes->get('tagihanListrik/restore/(:any)', 'TagihanListrik::restore/$1');
$routes->get('tagihanListrik/restore', 'TagihanListrik::restore');
$routes->delete('tagihanListrik/deletePermanent/(:any)', 'TagihanListrik::deletePermanent/$1');
$routes->delete('tagihanListrik/deletePermanent', 'TagihanListrik::deletePermanent');
$routes->resource('tagihanListrik', ['filter' => 'isLoggedIn']);

// Tagihan Internet
$routes->get('tagihanInternet/createTemplate', 'TagihanInternet::createTemplate');
$routes->get('tagihanInternet/generatePDF', 'TagihanInternet::generatePDF');
$routes->get('tagihanInternet/export', 'TagihanInternet::export');
$routes->post('tagihanInternet/import', 'TagihanInternet::import');
$routes->get('tagihanInternet/edit', 'TagihanInternet::edit');
$routes->get('tagihanInternet/trash', 'TagihanInternet::trash');
$routes->get('tagihanInternet/restore/(:any)', 'TagihanInternet::restore/$1');
$routes->get('tagihanInternet/restore', 'TagihanInternet::restore');
$routes->delete('tagihanInternet/deletePermanent/(:any)', 'TagihanInternet::deletePermanent/$1');
$routes->delete('tagihanInternet/deletePermanent', 'TagihanInternet::deletePermanent');
$routes->resource('tagihanInternet', ['filter' => 'isLoggedIn']);

// LABORATORIUM

// Manajemen Aset
$routes->get('generateLabQRDoc', 'RincianLabAset::generateLabQRDoc');
$routes->add('generateSelectedLabQR/(:any)', 'RincianLabAset::generateSelectedLabQR/$1');

$routes->get('QRBarcode/(:segment)', 'QRBarcode::generateQRCode/$1');

$routes->get('pemusnahanLabAsetDetail/(:num)', 'RincianLabAset::pemusnahanLabAsetDetail/$1');
$routes->get('pemusnahanLabAset', 'RincianLabAset::pemusnahanLabAset');
$routes->get('dataSaranaDetail/(:num)', 'RincianLabAset::dataSaranaDetail/$1');
$routes->get('dataSarana', 'RincianLabAset::dataSarana');
$routes->get('dataSarana/generatePDF', 'RincianLabAset::dataSaranaGeneratePDF');
$routes->get('dataSarana/export', 'RincianLabAset::dataSaranaExport');
$routes->get('rincianLabAset/createTemplate', 'RincianLabAset::createTemplate');
$routes->get('rincianLabAset/print/(:num)', 'RincianLabAset::print/$1');
$routes->get('rincianLabAset/generatePDF', 'RincianLabAset::generatePDF');
$routes->get('rincianLabAset/export', 'RincianLabAset::export');
$routes->get('rincianLabAset/edit', 'RincianLabAset::edit');
$routes->get('rincianLabAset/editPemusnahanLab/(:any)', 'RincianLabAset::editPemusnahanLab/$1');
$routes->get('rincianLabAset/trash', 'RincianLabAset::trash');
$routes->get('rincianLabAset/restore/(:any)', 'RincianLabAset::restore/$1');
$routes->get('rincianLabAset/restore', 'RincianLabAset::restore');
$routes->get('pemusnahanLabAset/dataDestroyLabGeneratePDF', 'RincianLabAset::dataDestroyLabGeneratePDF');
$routes->get('pemusnahanLabAset/exportDestroyFile', 'RincianLabAset::exportDestroyFile');
$routes->post('rincianLabAset/import', 'RincianLabAset::import');
$routes->post('pemusnahanLabAset/delete/(:any)', 'RincianLabAset::pemusnahanLabAsetDelete/$1');
$routes->post('rincianLabAset/generateAndSetKodeRincianLabAset', 'RincianLabAset::generateAndSetKodeRincianLabAset');
$routes->post('rincianLabAset/generateKode', 'RincianLabAset::generateKode');
$routes->post('rincianLabAset/(:any)/updateKode', 'RincianLabAset::generateKode');
$routes->post('rincianLabAset/checkDuplicate', 'RincianLabAset::checkDuplicate');
$routes->post('rincianLabAset/(:any)/updateCheckDuplicate', 'RincianLabAset::checkDuplicate');
$routes->patch('pemusnahanLabAset/updatePemusnahanLab/(:any)', 'RincianLabAset::updatePemusnahanLab/$1');
$routes->delete('rincianLabAset/deletePermanent/(:any)', 'RincianLabAset::deletePermanent/$1');
$routes->delete('rincianLabAset/deletePermanent', 'RincianLabAset::deletePermanent');
$routes->resource('rincianLabAset', ['filter' => 'isLoggedIn']);

// Laboratorium
$routes->get('laboratorium/print/(:num)', 'Laboratorium::print/$1');
$routes->get('laboratorium/showInfo/(:num)', 'Laboratorium::showInfo/$1');
$routes->resource('laboratorium', ['filter' => 'isLoggedIn']);

// MANAJEMEN LAYANAN

// Layanan Aset Lab
// Ajax Select2
$routes->post('getAllKodeRincianLabAset', 'LayananLabAset::getAllKodeRincianLabAset');
$routes->post('getKodeRincianLabAsetBySarana', 'LayananLabAset::getKodeRincianLabAsetBySarana');
$routes->post('getIdentitasLabByKodeRincianLabAset', 'LayananLabAset::getIdentitasLabByKodeRincianLabAset');
$routes->post('getKategoriManajemenByKodeRincianLabAset', 'LayananLabAset::getKategoriManajemenByKodeRincianLabAset');
$routes->post('getIdRincianLabAsetByKodeRincianLabAset', 'LayananLabAset::getIdRincianLabAsetByKodeRincianLabAset');

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
$routes->get('dataPeminjaman/print/(:num)', 'DataPeminjaman::print/$1');
$routes->get('dataPeminjaman/generatePDF', 'DataPeminjaman::generatePDF');
$routes->get('dataPeminjaman/export', 'DataPeminjaman::export');
$routes->get('dataPeminjaman/trash', 'DataPeminjaman::trash');
$routes->get('dataPeminjaman/restore/(:any)', 'DataPeminjaman::restore/$1');
$routes->get('dataPeminjaman/restore', 'DataPeminjaman::restore');
$routes->get('peminjamanDataUser', 'DataPeminjaman::user');
$routes->get('dataPeminjaman/history/(:any)', 'DataPeminjaman::getLoanHistory/$1');
$routes->post('returnItems/changeStatus/(:any)', 'DataPeminjaman::changeStatus/$1');
$routes->post('returnItems/changeSectionAset/(:any)', 'DataPeminjaman::changeSectionAset/$1');
$routes->delete('dataPeminjaman/deletePermanent/(:any)', 'DataPeminjaman::deletePermanent/$1');
$routes->delete('dataPeminjaman/deletePermanent', 'DataPeminjaman::deletePermanent');
$routes->resource('dataPeminjaman', ['filter' => 'isLoggedIn']);

// Manajemen Peminjaman

$routes->get('manajemenPeminjaman/loan/(:num)', 'ManajemenPeminjaman::loan/$1');
$routes->get('peminjamanUser/loanUser/(:num)', 'ManajemenPeminjaman::loanUser/$1');
$routes->get('manajemenPeminjaman/print/(:num)', 'ManajemenPeminjaman::print/$1');
$routes->get('manajemenPeminjaman/showUser/(:num)', 'ManajemenPeminjaman::showUser/$1');
$routes->get('peminjamanUser', 'ManajemenPeminjaman::user');
$routes->get('manajemenPeminjaman/getKodeLab/(:num)', 'ManajemenPeminjaman::getKodeLab/$1');
$routes->post('manajemenPeminjaman/getRincianLabAsetByLab', 'ManajemenPeminjaman::getRincianLabAsetByLab');
$routes->post('manajemenPeminjaman/addLoan', 'ManajemenPeminjaman::addLoan');
$routes->post('peminjamanUser/addLoan', 'ManajemenPeminjaman::addLoanUser');
$routes->post('manajemenPeminjaman/getRole', 'ManajemenPeminjaman::getRole');
$routes->post('manajemenPeminjaman/getFilterOptions', 'ManajemenPeminjaman::getFilterOptions');
$routes->post('manajemenPeminjaman/getSaranaByLab', 'ManajemenPeminjaman::getSaranaByLab');
$routes->post('manajemenPeminjaman/getKodeBySarana', 'ManajemenPeminjaman::getKodeBySarana');
$routes->delete('manajemenPeminjaman/deletePermanent/(:any)', 'ManajemenPeminjaman::deletePermanent/$1');
$routes->delete('manajemenPeminjaman/deletePermanent', 'ManajemenPeminjaman::deletePermanent');
$routes->resource('manajemenPeminjaman', ['filter' => 'isLoggedIn']);


// Backup and Restore
$routes->get('backup', 'DatabaseManagement::backup');
$routes->get('restore', 'DatabaseManagement::restoreView');
$routes->post('restoreDatabase', 'DatabaseManagement::restore');

// Restore

<?php

use CodeIgniter\Router\RouteCollection;
// Creating database =============================================================================================== //
$routes->get('createDatabase', function () {
    $forge = \Config\Database::forge();
    if ($forge->createDatabase('dbmanajemensapra')) {
        echo 'Database created!';
    }
});
// End of creating database ======================================================================================== //

// Error handling =============================================================================================== //
$routes->get('404', 'Auth::error');
// End of error handling ======================================================================================== //

// User Routes =============================================================================================== //
// ----------------------------------------------- Auth routes ----------------------------------------------- //
$routes->get('login', 'Auth::login');
$routes->get('auth', 'Auth::index');
$routes->post('loginProcess', 'Auth::loginProcess');
$routes->get('loginProcess', 'Auth::loginProcess');
$routes->get('logout', 'Auth::logout');
$routes->post('updateSessionMode', 'Auth::updateSessionMode');
// -------------------------------------------- End of auth routes -------------------------------------------- //

// ----------------------------------------------- User logs ----------------------------------------------- //
$routes->get('viewLogs', 'UserLogs::viewLogs');
$routes->get('viewLogs/generatePDF', 'UserLogs::generatePDF');
$routes->get('viewLogs/export', 'UserLogs::export');
// -------------------------------------------- End of user logs -------------------------------------------- //

// ----------------------------------------------- User action logs ----------------------------------------------- //
$routes->get('viewActions', 'UserActionLogs::viewActions');
$routes->get('viewActions/generatePDF', 'UserActionLogs::generatePDF');
$routes->get('viewActions/export', 'UserActionLogs::export');
// -------------------------------------------- End of user action logs -------------------------------------------- //
// End of user routes ======================================================================================== //

// ----------------------------------------------- Dashboard home ----------------------------------------------- //
$routes->get('/', 'Home::index');
$routes->get('home', 'Home::index');
// -------------------------------------------- End of dashboard home -------------------------------------------- //

// Data Master =============================================================================================== //
// ----------------------------------------------- Manajemen User ----------------------------------------------- //
$routes->get('manajemenUser/createTemplate', 'ManajemenUser::createTemplate');
$routes->get('manajemenUser/generatePDF', 'ManajemenUser::generatePDF');
$routes->get('manajemenUser/export', 'ManajemenUser::export');
$routes->post('manajemenUser/import', 'ManajemenUser::import');
$routes->get('manajemenUser/edit', 'ManajemenUser::edit');
$routes->get('manajemenUser/restore/(:any)', 'ManajemenUser::restore/$1');
$routes->get('manajemenUser/restore', 'ManajemenUser::restore');
$routes->delete('manajemenUser/deletePermanent/(:any)', 'ManajemenUser::deletePermanent/$1');
$routes->delete('manajemenUser/deletePermanent', 'ManajemenUser::deletePermanent');
$routes->resource('manajemenUser', ['filter' => 'superAdminFilter']);
// -------------------------------------------- End of manajemen user -------------------------------------------- //

// ----------------------------------------------- Identitas sarana ----------------------------------------------- //
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
// -------------------------------------------- End of identitas sarana -------------------------------------------- //

// ----------------------------------------------- Identitas prasarana ----------------------------------------------- //
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
// -------------------------------------------- End of identitas prasarana -------------------------------------------- //

// ----------------------------------------------- Identitas laboratorium ----------------------------------------------- //
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
// -------------------------------------------- End of identitas laboratorium -------------------------------------------- //

// ----------------------------------------------- Sumber dana ----------------------------------------------- //
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
// -------------------------------------------- End of sumber dana -------------------------------------------- //

// ----------------------------------------------- Identitas gedung ----------------------------------------------- //
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
// -------------------------------------------- End of identitas gedung -------------------------------------------- //

// ----------------------------------------------- Identitas lantai ----------------------------------------------- //
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
// -------------------------------------------- End of identitas lantai -------------------------------------------- //

// ----------------------------------------------- Status layanan ----------------------------------------------- //
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
// -------------------------------------------- End of status layanan -------------------------------------------- //

// ----------------------------------------------- Kategori manajemen ----------------------------------------------- //
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
// -------------------------------------------- End of kategori manajemen -------------------------------------------- //

// ----------------------------------------------- Kategori mechanical, electrical, and plubming ----------------------------------------------- //
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
// -------------------------------------------- End of kategori mechanical, electrical, and plubming -------------------------------------------- //

// ----------------------------------------------- Identitas Kelas ----------------------------------------------- //
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
// -------------------------------------------- End of identitas kelas -------------------------------------------- //

// ----------------------------------------------- Data siswa ----------------------------------------------- //
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
// -------------------------------------------- End of data siswa -------------------------------------------- //

// ----------------------------------------------- Data pegawai ----------------------------------------------- //
$routes->get('dataPegawai/createTemplate', 'DataPegawai::createTemplate');
$routes->get('dataPegawai/generatePDF', 'DataPegawai::generatePDF');
$routes->get('dataPegawai/export', 'DataPegawai::export');
$routes->post('dataPegawai/import', 'DataPegawai::import');
$routes->get('dataPegawai/edit', 'DataPegawai::edit');
$routes->get('dataPegawai/trash', 'DataPegawai::trash');
$routes->get('dataPegawai/restore/(:any)', 'DataPegawai::restore/$1');
$routes->get('dataPegawai/restore', 'DataPegawai::restore');
$routes->delete('dataPegawai/deletePermanent/(:any)', 'DataPegawai::deletePermanent/$1');
$routes->delete('dataPegawai/deletePermanent', 'DataPegawai::deletePermanent');
$routes->resource('dataPegawai', ['filter' => 'isLoggedIn']);
// -------------------------------------------- End of data pegawai -------------------------------------------- //

// ----------------------------------------------- Data non inventaris ----------------------------------------------- //
$routes->get('nonInventaris/createTemplate', 'NonInventaris::createTemplate');
$routes->get('nonInventaris/generatePDF', 'NonInventaris::generatePDF');
$routes->get('nonInventaris/export', 'NonInventaris::export');
$routes->post('nonInventaris/import', 'NonInventaris::import');
$routes->get('nonInventaris/edit', 'NonInventaris::edit');
$routes->get('nonInventaris/trash', 'NonInventaris::trash');
$routes->get('nonInventaris/restore/(:any)', 'NonInventaris::restore/$1');
$routes->get('nonInventaris/restore', 'NonInventaris::restore');
$routes->delete('nonInventaris/deletePermanent/(:any)', 'NonInventaris::deletePermanent/$1');
$routes->delete('nonInventaris/deletePermanent', 'NonInventaris::deletePermanent');
$routes->resource('nonInventaris', ['filter' => 'isLoggedIn']);
// -------------------------------------------- End of data non inventaris -------------------------------------------- //
// End of data master ======================================================================================== //

// Sarana - Manajemen Aset =============================================================================================== //
// ----------------------------------------------- Data general aset ----------------------------------------------- //
$routes->get('asetGeneral/dataAset/(:num)', 'AsetGeneral::info/$1');
$routes->get('asetGeneral/(:num)', 'AsetGeneral::show/$1');
$routes->get('asetGeneral/generatePDF', 'AsetGeneral::GeneratePDF');
$routes->get('asetGeneral/export', 'AsetGeneral::Export');
$routes->get('asetGeneral', 'AsetGeneral::view');
// -------------------------------------------- End of data general aset -------------------------------------------- //

// ----------------------------------------------- Data rincian aset ----------------------------------------------- //
$routes->get('generateQRDoc', 'RincianAset::generateQRDoc');
$routes->add('generateSelectedQR/(:any)', 'RincianAset::generateSelectedQR/$1');
$routes->get('rincianAset/createTemplate', 'RincianAset::createTemplate');
// $routes->get('rincianAset/print/(:num)', 'RincianAset::print/$1');
$routes->get('rincianAset/generatePDF', 'RincianAset::generatePDF');
$routes->get('rincianAset/export', 'RincianAset::export');
$routes->get('rincianAset/edit', 'RincianAset::edit');
$routes->get('rincianAset/trash', 'RincianAset::trash');
$routes->get('rincianAset/restore/(:any)', 'RincianAset::restore/$1');
$routes->get('rincianAset/restore', 'RincianAset::restore');
$routes->post('rincianAset/import', 'RincianAset::import');
$routes->post('rincianAset/generateAndSetKodeRincianAset', 'RincianAset::generateAndSetKodeRincianAset');
$routes->post('rincianAset/generateKode', 'RincianAset::generateKode');
$routes->post('rincianAset/(:any)/updateKode', 'RincianAset::generateKode');
$routes->post('rincianAset/checkDuplicate', 'RincianAset::checkDuplicate');
$routes->post('rincianAset/(:any)/updateCheckDuplicate', 'RincianAset::checkDuplicate');
$routes->delete('rincianAset/deletePermanent/(:any)', 'RincianAset::deletePermanent/$1');
$routes->delete('rincianAset/deletePermanent', 'RincianAset::deletePermanent');
$routes->resource('rincianAset', ['filter' => 'isLoggedIn']);
// -------------------------------------------- End of data rincian aset -------------------------------------------- //

// ----------------------------------------------- Pemusnahan Aset ----------------------------------------------- //
$routes->get('pemusnahanAset/print/(:any)', 'PemusnahanAset::print/$1');
$routes->get('pemusnahanAset/generatePDF', 'PemusnahanAset::generatePDF');
$routes->get('pemusnahanAset/export', 'PemusnahanAset::export');
$routes->post('pemusnahanAset/destruction/(:any)', 'PemusnahanAset::destruction/$1');
$routes->resource('pemusnahanAset', ['filter' => 'isLoggedIn']);
// -------------------------------------------- End of pemusnahan -------------------------------------------- //
// End of sarana - manajemen aset ======================================================================================== //

// Sarana - Peminjaman =============================================================================================== //
// ----------------------------------------------- Request peminjaman ----------------------------------------------- //
$routes->get('requestAsetPeminjaman/generatePDF', 'RequestAsetPeminjaman::generatePDF');
$routes->get('requestAsetPeminjaman/export', 'RequestAsetPeminjaman::export');
$routes->get('requestAsetPeminjaman/rejectLoan/(:any)', 'RequestAsetPeminjaman::rejectLoan/$1');
$routes->post('requestAsetPeminjaman/processLoan', 'RequestAsetPeminjaman::processLoan');
$routes->resource('requestAsetPeminjaman', ['filter' => 'isLoggedIn']);
// -------------------------------------------- End of request peminjaman -------------------------------------------- //

// ----------------------------------------------- Data peminjaman ----------------------------------------------- //
$routes->get('dataAsetPeminjaman/print/(:num)', 'DataAsetPeminjaman::print/$1');
$routes->get('dataAsetPeminjaman/printAll', 'DataAsetPeminjaman::printAll');
$routes->get('dataAsetPeminjaman/generatePDF', 'DataAsetPeminjaman::generatePDF');
$routes->get('dataAsetPeminjaman/export', 'DataAsetPeminjaman::export');
$routes->get('dataAsetPeminjaman/trash', 'DataAsetPeminjaman::trash');
$routes->get('dataAsetPeminjaman/restore/(:any)', 'DataAsetPeminjaman::restore/$1');
$routes->get('dataAsetPeminjaman/restore', 'DataAsetPeminjaman::restore');
$routes->get('dataAsetPeminjaman/history/(:any)', 'DataAsetPeminjaman::getLoanHistory/$1');
$routes->post('dataAsetPeminjaman/revokeLoan/(:any)', 'DataAsetPeminjaman::revokeLoan/$1');
$routes->delete('dataAsetPeminjaman/deletePermanent/(:any)', 'DataAsetPeminjaman::deletePermanent/$1');
$routes->delete('dataAsetPeminjaman/deletePermanent', 'DataAsetPeminjaman::deletePermanent');
$routes->resource('dataAsetPeminjaman', ['filter' => 'laboranFilter']);
// -------------------------------------------- End of data peminjaman -------------------------------------------- //

// ----------------------------------------------- Manajemen peminjaman ----------------------------------------------- //
$routes->get('manajemenAsetPeminjaman/loan/(:num)', 'ManajemenAsetPeminjaman::loan/$1');
$routes->post('manajemenAsetPeminjaman/getNama', 'ManajemenAsetPeminjaman::getNama');
$routes->post('manajemenAsetPeminjaman/addLoan', 'ManajemenAsetPeminjaman::addLoan');
$routes->post('manajemenAsetPeminjaman/getRole', 'ManajemenAsetPeminjaman::getRole');
$routes->resource('manajemenAsetPeminjaman', ['filter' => 'isLoggedIn']);
// -------------------------------------------- End of manajemen peminjaman -------------------------------------------- //
// End of sarana - peminjaman ======================================================================================== //

// Sarana - Non Inventaris =============================================================================================== //
$routes->get('dataNonInventaris/createTemplate', 'DataNonInventaris::createTemplate');
$routes->get('dataNonInventaris/generatePDF', 'DataNonInventaris::generatePDF');
$routes->get('dataNonInventaris/export', 'DataNonInventaris::export');
$routes->post('dataNonInventaris/import', 'DataNonInventaris::import');
$routes->get('dataNonInventaris/edit', 'DataNonInventaris::edit');
$routes->get('dataNonInventaris/trash', 'DataNonInventaris::trash');
$routes->get('dataNonInventaris/restore/(:any)', 'DataNonInventaris::restore/$1');
$routes->get('dataNonInventaris/restore', 'DataNonInventaris::restore');
$routes->delete('dataNonInventaris/deletePermanent/(:any)', 'DataNonInventaris::deletePermanent/$1');
$routes->delete('dataNonInventaris/deletePermanent', 'DataNonInventaris::deletePermanent');
$routes->resource('dataNonInventaris', ['filter' => 'isLoggedIn']);
// End of sarana - non inventaris ======================================================================================== //

// Sarana - Layanan Aset =============================================================================================== //
// ----------------------------------------------- Layanan aset ----------------------------------------------- //
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
// -------------------------------------------- End of layanan aset -------------------------------------------- //

// ----------------------------------------------- Layanan non aset ----------------------------------------------- //
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
// -------------------------------------------- End of layanan non aset -------------------------------------------- //
// End of sarana - layanan aset ======================================================================================== //

// Prasarana =============================================================================================== //
// ----------------------------------------------- Ruangan ----------------------------------------------- //
$routes->post('prasaranaRuangan/search', 'PrasaranaRuangan::search');
$routes->get('prasaranaRuangan/print/(:num)', 'PrasaranaRuangan::print/$1');
$routes->get('prasaranaRuangan/showInfo/(:num)', 'PrasaranaRuangan::showInfo/$1');
$routes->resource('prasaranaRuangan', ['filter' => 'isLoggedIn']);
// -------------------------------------------- End of ruangan -------------------------------------------- //

// ----------------------------------------------- Non Ruangan ----------------------------------------------- //
$routes->post('prasaranaNonRuangan/search', 'PrasaranaNonRuangan::search');
$routes->get('prasaranaNonRuangan/print/(:num)', 'PrasaranaNonRuangan::print/$1');
$routes->get('prasaranaNonRuangan/showInfo/(:num)', 'PrasaranaNonRuangan::showInfo/$1');
$routes->resource('prasaranaNonRuangan', ['filter' => 'isLoggedIn']);
// -------------------------------------------- End of non ruangan -------------------------------------------- //
// End of prasarana ======================================================================================== //

// Perangkat IT - Manajemen Aset =============================================================================================== //
// ----------------------------------------------- Data aset general IT ----------------------------------------------- //
$routes->get('asetItGeneral/dataItAset/(:num)', 'AsetItGeneral::info/$1');
$routes->get('asetItGeneral/(:num)', 'AsetItGeneral::show/$1');
$routes->get('asetItGeneral/generatePDF', 'AsetItGeneral::GeneratePDF');
$routes->get('asetItGeneral/export', 'AsetItGeneral::Export');
$routes->get('asetItGeneral', 'AsetItGeneral::view', ['filter' => 'adminItFilter']);
// -------------------------------------------- End of data aset general IT -------------------------------------------- //

// ----------------------------------------------- Rincian aset IT ----------------------------------------------- //
$routes->get('rincianItAset/generateQRDoc', 'RincianItAset::generateQRDoc');
$routes->get('rincianItAset/createTemplate', 'RincianItAset::createTemplate');
$routes->get('rincianItAset/print/(:num)', 'RincianItAset::print/$1');
$routes->get('rincianItAset/generatePDF', 'RincianItAset::generatePDF');
$routes->get('rincianItAset/export', 'RincianItAset::export');
$routes->get('rincianItAset/edit', 'RincianItAset::edit');
$routes->get('rincianItAset/trash', 'RincianItAset::trash');
$routes->get('rincianItAset/restore/(:any)', 'RincianItAset::restore/$1');
$routes->get('rincianItAset/restore', 'RincianItAset::restore');
$routes->post('rincianItAset/import', 'RincianItAset::import');
$routes->post('rincianItAset/generateAndSetKodeRincianItAset', 'RincianItAset::generateAndSetKodeRincianItAset');
$routes->post('rincianItAset/generateKode', 'RincianItAset::generateKode');
$routes->post('rincianItAset/(:any)/updateKode', 'RincianItAset::generateKode');
$routes->post('rincianItAset/checkDuplicate', 'RincianItAset::checkDuplicate');
$routes->post('rincianItAset/(:any)/updateCheckDuplicate', 'RincianItAset::checkDuplicate');
$routes->delete('rincianItAset/deletePermanent/(:any)', 'RincianItAset::deletePermanent/$1');
$routes->delete('rincianItAset/deletePermanent', 'RincianItAset::deletePermanent');
$routes->resource('rincianItAset', ['filter' => 'adminItFilter']);
// -------------------------------------------- End of rincian aset IT -------------------------------------------- //

// ----------------------------------------------- Pemusnahan aset IT ----------------------------------------------- //
$routes->get('pemusnahanItAset/print/(:any)', 'PemusnahanItAset::print/$1');
$routes->get('pemusnahanItAset/generatePDF', 'PemusnahanItAset::generatePDF');
$routes->get('pemusnahanItAset/export', 'PemusnahanItAset::export');
$routes->post('pemusnahanItAset/destruction/(:any)', 'PemusnahanItAset::destruction/$1');
$routes->resource('pemusnahanItAset', ['filter' => 'adminItFilter']);
// -------------------------------------------- End of pemusnahan aset IT -------------------------------------------- //
// End of perangkat IT - manajemen aset ======================================================================================== //

// // Perangkat IT (NOT USE)!!!!
// $routes->get('perangkatIt/print/(:num)', 'perangkatIt::print/$1');
// $routes->get('perangkatIt/createTemplate', 'perangkatIt::createTemplate');
// $routes->get('perangkatIt/generatePDF', 'perangkatIt::generatePDF');
// $routes->get('perangkatIt/export', 'perangkatIt::export');
// $routes->get('perangkatIt/rincian/(:num)', 'perangkatIt::rincian/$1');
// $routes->resource('perangkatIt', ['filter' => 'adminItFilter']);

// Perangkat IT - Layanan Aset =============================================================================================== //
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
$routes->resource('layananAsetIt', ['filter' => 'adminItFilter']);
// End of perangkat IT -  layanan aset ======================================================================================== //

// Perangkat IT - Platform Digital =============================================================================================== //
// ----------------------------------------------- Website ----------------------------------------------- //
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
$routes->resource('website', ['filter' => 'adminItFilter']);
// -------------------------------------------- End of website -------------------------------------------- //

// ----------------------------------------------- Sosial Media ----------------------------------------------- //
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
$routes->resource('sosialMedia', ['filter' => 'adminItFilter']);
// -------------------------------------------- End of sosial media -------------------------------------------- //

// ----------------------------------------------- Aplikasi ----------------------------------------------- //
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
$routes->resource('aplikasi', ['filter' => 'adminItFilter']);
// -------------------------------------------- End of aplikasi -------------------------------------------- //
// End of perangkat IT - platform digital ======================================================================================== //

// Profil Sekolah =============================================================================================== //
// ----------------------------------------------- Profil sekolah ----------------------------------------------- //
$routes->get('profilSekolah/createTemplateDokumen', 'ProfilSekolah::createTemplateDokumen');
$routes->get('profilSekolah/generatePDFDokumen', 'ProfilSekolah::generatePDFDokumen');
$routes->get('profilSekolah/exportDokumen', 'ProfilSekolah::exportDokumen');
$routes->post('profilSekolah/importDokumen', 'ProfilSekolah::importDokumen');
$routes->get('profilSekolah/trashDokumen', 'ProfilSekolah::trashDokumen');
$routes->get('profilSekolah/restore/(:any)', 'ProfilSekolah::restore/$1');
$routes->get('profilSekolah/restore', 'ProfilSekolah::restore');
$routes->get('profilSekolah/(:num)/editDokumen', 'ProfilSekolah::editDokumen/$1');
$routes->get('profilSekolah/newDokumen', 'ProfilSekolah::newDokumen');
$routes->get('profilSekolah/print/(:num)', 'ProfilSekolah::print/$1');
$routes->post('profilSekolah/createDokumen', 'ProfilSekolah::createDokumen');
$routes->patch('profilSekolah/updateDokumen/(:segment)', 'ProfilSekolah::updateDokumen/$1');
$routes->delete('profilSekolah/deleteDokumen/(:num)', 'ProfilSekolah::deleteDokumen/$1');
$routes->delete('profilSekolah/deletePermanent/(:any)', 'ProfilSekolah::deletePermanent/$1');
$routes->delete('profilSekolah/deletePermanent', 'ProfilSekolah::deletePermanent');
$routes->resource('profilSekolah', ['filter' => 'isLoggedIn']);
// -------------------------------------------- End of profil sekolah -------------------------------------------- //

// ----------------------------------------------- Tagihan air ----------------------------------------------- //
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
// -------------------------------------------- End of tagihan air -------------------------------------------- //

// ----------------------------------------------- Tagihan listirk ----------------------------------------------- //
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
// -------------------------------------------- End of tagihan listrik -------------------------------------------- //

// ----------------------------------------------- Tagihan internet ----------------------------------------------- //
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
// -------------------------------------------- End of tagihan internet -------------------------------------------- //
// End of profil sekolah ======================================================================================== //

// Laboratorium - Manajemen Aset =============================================================================================== //
// ----------------------------------------------- Aset general laboratorium ----------------------------------------------- //
$routes->get('asetLabGeneral/dataAset/(:num)', 'AsetLabGeneral::info/$1');
$routes->get('asetLabGeneral/(:num)', 'AsetLabGeneral::show/$1');
$routes->get('asetLabGeneral/generatePDF', 'AsetLabGeneral::GeneratePDF');
$routes->get('asetLabGeneral/export', 'AsetLabGeneral::Export');
$routes->get('asetLabGeneral', 'AsetLabGeneral::view');
// -------------------------------------------- End of aset general laboratorium -------------------------------------------- //

// ----------------------------------------------- Rincian aset laboratorium ----------------------------------------------- //
$routes->get('generateLabQRDoc', 'RincianLabAset::generateLabQRDoc');
$routes->add('generateSelectedLabQR/(:any)', 'RincianLabAset::generateSelectedLabQR/$1');
$routes->get('rincianLabAset/createTemplate', 'RincianLabAset::createTemplate');
// $routes->get('rincianLabAset/print/(:num)', 'RincianLabAset::print/$1');
$routes->get('rincianLabAset/generatePDF', 'RincianLabAset::generatePDF');
$routes->get('rincianLabAset/export', 'RincianLabAset::export');
$routes->get('rincianLabAset/edit', 'RincianLabAset::edit');
$routes->get('rincianLabAset/editPemusnahanLab/(:any)', 'RincianLabAset::editPemusnahanLab/$1');
$routes->get('rincianLabAset/trash', 'RincianLabAset::trash');
$routes->get('rincianLabAset/restore/(:any)', 'RincianLabAset::restore/$1');
$routes->get('rincianLabAset/restore', 'RincianLabAset::restore');
$routes->post('rincianLabAset/import', 'RincianLabAset::import');
$routes->post('rincianLabAset/generateAndSetKodeRincianLabAset', 'RincianLabAset::generateAndSetKodeRincianLabAset');
$routes->post('rincianLabAset/generateKode', 'RincianLabAset::generateKode');
$routes->post('rincianLabAset/(:any)/updateKode', 'RincianLabAset::generateKode');
$routes->post('rincianLabAset/checkDuplicate', 'RincianLabAset::checkDuplicate');
$routes->post('rincianLabAset/(:any)/updateCheckDuplicate', 'RincianLabAset::checkDuplicate');
$routes->delete('rincianLabAset/deletePermanent/(:any)', 'RincianLabAset::deletePermanent/$1');
$routes->delete('rincianLabAset/deletePermanent', 'RincianLabAset::deletePermanent');
$routes->resource('rincianLabAset', ['filter' => 'laboranFilter']);
// -------------------------------------------- End of rincian aset laboratorium -------------------------------------------- //

// ----------------------------------------------- Pemusnahan aset laboratorium ----------------------------------------------- //
$routes->get('pemusnahanLabAset/print/(:any)', 'PemusnahanLabAset::print/$1');
$routes->get('pemusnahanLabAset/generatePDF', 'PemusnahanLabAset::generatePDF');
$routes->get('pemusnahanLabAset/export', 'PemusnahanLabAset::export');
$routes->post('pemusnahanLabAset/destruction/(:any)', 'PemusnahanLabAset::destruction/$1');
$routes->resource('pemusnahanLabAset', ['filter' => 'isLoggedIn']);
// -------------------------------------------- End of pemusnahan aset laboratorium -------------------------------------------- //

// ----------------------------------------------- Laboratorium ----------------------------------------------- //
$routes->get('laboratorium/print/(:num)', 'Laboratorium::print/$1');
$routes->get('laboratorium/showInfo/(:num)', 'Laboratorium::showInfo/$1');
$routes->resource('laboratorium',  ['filter' => 'laboranFilter']);
// -------------------------------------------- End of laboratorium -------------------------------------------- //
// End of Laboratorium - manajemen aset ======================================================================================== //

// Laboratorium - Layanan =============================================================================================== //
// ----------------------------------------------- Layanan aset laboratorium ----------------------------------------------- //
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
// -------------------------------------------- End of layanan aset laboratorium -------------------------------------------- //

// ----------------------------------------------- Layanan non aset laboratorium ----------------------------------------------- //
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
// -------------------------------------------- End of layanan non aset laboratorium -------------------------------------------- //
// End of laboratorium - layanan ======================================================================================== //

// Laboratorium - Peminjaman =============================================================================================== //
// ----------------------------------------------- Data peminjaman laboratorium ----------------------------------------------- //
$routes->get('dataPeminjaman/print/(:num)', 'DataPeminjaman::print/$1');
$routes->get('dataPeminjaman/printAll', 'DataPeminjaman::printAll');
$routes->get('dataPeminjaman/generatePDF', 'DataPeminjaman::generatePDF');
$routes->get('dataPeminjaman/export', 'DataPeminjaman::export');
$routes->get('dataPeminjaman/trash', 'DataPeminjaman::trash');
$routes->get('dataPeminjaman/restore/(:any)', 'DataPeminjaman::restore/$1');
$routes->get('dataPeminjaman/restore', 'DataPeminjaman::restore');
$routes->get('dataPeminjaman/history/(:any)', 'DataPeminjaman::getLoanHistory/$1');
$routes->post('dataPeminjaman/revokeLoan/(:any)', 'DataPeminjaman::revokeLoan/$1');
$routes->delete('dataPeminjaman/deletePermanent/(:any)', 'DataPeminjaman::deletePermanent/$1');
$routes->delete('dataPeminjaman/deletePermanent', 'DataPeminjaman::deletePermanent');
$routes->resource('dataPeminjaman', ['filter' => 'laboranFilter']);
// -------------------------------------------- End of data peminjaman laboratorium -------------------------------------------- //

// ----------------------------------------------- Request peminjaman laboratorium----------------------------------------------- //
$routes->get('requestPeminjaman/generatePDF', 'RequestPeminjaman::generatePDF');
$routes->get('requestPeminjaman/export', 'RequestPeminjaman::export');
$routes->post('requestPeminjaman/processLoan', 'RequestPeminjaman::processLoan');
$routes->get('requestPeminjaman/rejectLoan/(:any)', 'RequestPeminjaman::rejectLoan/$1');
$routes->resource('requestPeminjaman', ['filter' => 'isLoggedIn']);
// -------------------------------------------- End of request peminjaman laboratorium-------------------------------------------- //

// ----------------------------------------------- Manajemen peminjaman laboratorium ----------------------------------------------- //
$routes->post('manajemenPeminjaman/getNama', 'ManajemenPeminjaman::getNama');
$routes->get('manajemenPeminjaman/loan/(:num)', 'ManajemenPeminjaman::loan/$1');
$routes->get('manajemenPeminjaman/print/(:num)', 'ManajemenPeminjaman::print/$1');
$routes->get('manajemenPeminjaman/showUser/(:num)', 'ManajemenPeminjaman::showUser/$1');
$routes->get('manajemenPeminjaman/getKodeLab/(:num)', 'ManajemenPeminjaman::getKodeLab/$1');
$routes->post('manajemenPeminjaman/getRincianLabAsetByLab', 'ManajemenPeminjaman::getRincianLabAsetByLab');
$routes->post('manajemenPeminjaman/addLoan', 'ManajemenPeminjaman::addLoan');
$routes->post('manajemenPeminjaman/getRole', 'ManajemenPeminjaman::getRole');
$routes->post('manajemenPeminjaman/getFilterOptions', 'ManajemenPeminjaman::getFilterOptions');
$routes->post('manajemenPeminjaman/getSaranaByLab', 'ManajemenPeminjaman::getSaranaByLab');
$routes->post('manajemenPeminjaman/getKodeBySarana', 'ManajemenPeminjaman::getKodeBySarana');
$routes->delete('manajemenPeminjaman/deletePermanent/(:any)', 'ManajemenPeminjaman::deletePermanent/$1');
$routes->delete('manajemenPeminjaman/deletePermanent', 'ManajemenPeminjaman::deletePermanent');
$routes->resource('manajemenPeminjaman', ['filter' => 'isLoggedIn']);
// -------------------------------------------- End of manajemen peminjaman laboratorium -------------------------------------------- //

// Backup and Restore =============================================================================================== //
$routes->get('backup', 'DatabaseManagement::backup');
$routes->get('restore', 'DatabaseManagement::restoreView');
$routes->post('restoreDatabase', 'DatabaseManagement::restore');
// End of backup and restore ======================================================================================== //

// User - Laboratorium =============================================================================================== //
// ----------------------------------------------- Data peminjaman laboratorium ----------------------------------------------- //
$routes->post('dataLabPeminjaman/revokeLoan/(:any)', 'UserDataLabPeminjaman::revokeLoan/$1');
$routes->get('dataLabPeminjaman/print/(:num)', 'UserDataLabPeminjaman::print/$1');
$routes->get('dataLabPeminjaman', 'UserDataLabPeminjaman::user');
$routes->get('dataLabPeminjaman/userDetail/(:any)', 'UserDataLabPeminjaman::getUserLoanHistory/$1');
$routes->get('dataLabPeminjaman/userRequestHistory/(:any)', 'UserDataLabPeminjaman::getuserRequestDetail/$1');
// -------------------------------------------- End of data peminjaman laboratorium -------------------------------------------- //

// ----------------------------------------------- Pengajuan peminjaman laboratorium ----------------------------------------------- //
$routes->get('pengajuanLabPeminjaman/loanUser/(:num)', 'UserPengajuanLabPeminjaman::loanUser/$1');
$routes->post('pengajuanLabPeminjaman/addLoan', 'UserPengajuanLabPeminjaman::addLoanUser');
$routes->get('pengajuanLabPeminjaman', 'UserPengajuanLabPeminjaman::user');
// -------------------------------------------- End of pengajuan peminjaman laboratorium -------------------------------------------- //
// End of user - laboratorium ======================================================================================== //

// User - Sarana Prasarana =============================================================================================== //
// ----------------------------------------------- Data peminjaman ----------------------------------------------- //
$routes->post('peminjamanAset/revokeLoan/(:any)', 'UserDataPeminjaman::revokeLoan/$1');
$routes->get('peminjamanAset/print/(:num)', 'UserDataPeminjaman::print/$1');
$routes->get('peminjamanAset', 'UserDataPeminjaman::user');
$routes->get('peminjamanAset/userDetail/(:any)', 'UserDataPeminjaman::getUserLoanHistory/$1');
$routes->get('peminjamanAset/userRequestHistory/(:any)', 'UserDataPeminjaman::getuserRequestDetail/$1');
// -------------------------------------------- End of data peminjaman -------------------------------------------- //

// ----------------------------------------------- Pengajuan peminjaman ----------------------------------------------- //
$routes->get('pengajuanPeminjaman/loanUser/(:num)', 'UserPengajuanPeminjaman::loanUser/$1');
$routes->post('pengajuanPeminjaman/addLoan', 'UserPengajuanPeminjaman::addLoanUser');
$routes->get('pengajuanPeminjaman', 'UserPengajuanPeminjaman::user');
// -------------------------------------------- End of pengajuan peminjaman -------------------------------------------- //
// End of user - sarana peminjaman ======================================================================================== //
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


// Register


// HOME
$routes->get('/', 'Home::index');
$routes->get('home', 'Home::index');
$routes->get('home/generate', 'Home::generate');




// INFORMATION ROUTES

// Identitas Sarana
$routes->get('identitasSarana/edit', 'IdentitasSarana::edit');
$routes->get('identitasSarana/trash', 'IdentitasSarana::trash');
$routes->get('identitasSarana/restore/(:any)', 'IdentitasSarana::restore/$1');
$routes->get('identitasSarana/restore', 'IdentitasSarana::restore');
$routes->delete('identitasSarana/deletePermanent/(:any)', 'IdentitasSarana::deletePermanent/$1');
$routes->delete('identitasSarana/deletePermanent', 'IdentitasSarana::deletePermanent');
$routes->presenter('identitasSarana', ['filter' => 'isLoggedIn']);

// Identitas Prasarana
$routes->get('identitasPrasarana/edit', 'IdentitasPrasarana::edit');
$routes->get('identitasPrasarana/trash', 'IdentitasPrasarana::trash');
$routes->get('identitasPrasarana/restore/(:any)', 'IdentitasPrasarana::restore/$1');
$routes->get('identitasPrasarana/restore', 'IdentitasPrasarana::restore');
$routes->delete('identitasPrasarana/deletePermanent/(:any)', 'IdentitasPrasarana::deletePermanent/$1');
$routes->delete('identitasPrasarana/deletePermanent', 'IdentitasPrasarana::deletePermanent');
$routes->resource('identitasPrasarana', ['filter' => 'isLoggedIn']);

// Sumber Dana
$routes->get('sumberDana/edit', 'SumberDana::edit');
$routes->get('sumberDana/trash', 'SumberDana::trash');
$routes->get('sumberDana/restore/(:any)', 'SumberDana::restore/$1');
$routes->get('sumberDana/restore', 'SumberDana::restore');
$routes->delete('sumberDana/deletePermanent/(:any)', 'SumberDana::deletePermanent/$1');
$routes->delete('sumberDana/deletePermanent', 'SumberDana::deletePermanent');
$routes->presenter('sumberDana', ['filter' => 'isLoggedIn']);

// Identitas Gedung
$routes->get('identitasGedung/edit', 'IdentitasGedung::edit');
$routes->get('identitasGedung/trash', 'IdentitasGedung::trash');
$routes->get('identitasGedung/restore/(:any)', 'IdentitasGedung::restore/$1');
$routes->get('identitasGedung/restore', 'IdentitasGedung::restore');
$routes->delete('identitasGedung/deletePermanent/(:any)', 'IdentitasGedung::deletePermanent/$1');
$routes->delete('identitasGedung/deletePermanent', 'IdentitasGedung::deletePermanent');
$routes->presenter('identitasGedung', ['filter' => 'isLoggedIn']);

// Identitas Lantai
$routes->get('identitasLantai/edit', 'IdentitasLantai::edit');
$routes->get('identitasLantai/trash', 'IdentitasLantai::trash');
$routes->get('identitasLantai/restore/(:any)', 'IdentitasLantai::restore/$1');
$routes->get('identitasLantai/restore', 'IdentitasLantai::restore');
$routes->delete('identitasLantai/deletePermanent/(:any)', 'IdentitasLantai::deletePermanent/$1');
$routes->delete('identitasLantai/deletePermanent', 'IdentitasLantai::deletePermanent');
$routes->presenter('identitasLantai', ['filter' => 'isLoggedIn']);

// Status Layanan
$routes->get('statusLayanan/edit', 'StatusLayanan::edit');
$routes->get('statusLayanan/trash', 'StatusLayanan::trash');
$routes->get('statusLayanan/restore/(:any)', 'StatusLayanan::restore/$1');
$routes->get('statusLayanan/restore', 'StatusLayanan::restore');
$routes->delete('statusLayanan/deletePermanent/(:any)', 'StatusLayanan::deletePermanent/$1');
$routes->delete('statusLayanan/deletePermanent', 'StatusLayanan::deletePermanent');
$routes->presenter('statusLayanan', ['filter' => 'isLoggedIn']);

// Kategori Manajemen
$routes->get('kategoriManajemen/edit', 'KategoriManajemen::edit');
$routes->get('kategoriManajemen/trash', 'KategoriManajemen::trash');
$routes->get('kategoriManajemen/restore/(:any)', 'KategoriManajemen::restore/$1');
$routes->get('kategoriManajemen/restore', 'KategoriManajemen::restore');
$routes->delete('kategoriManajemen/deletePermanent/(:any)', 'KategoriManajemen::deletePermanent/$1');
$routes->delete('kategoriManajemen/deletePermanent', 'KategoriManajemen::deletePermanent');
$routes->presenter('kategoriManajemen', ['filter' => 'isLoggedIn']);

// PRASARANA
// 
$routes->get('dataPrasarana/edit', 'DataPrasarana::edit');
$routes->get('dataPrasarana/trash', 'DataPrasarana::trash');
$routes->get('dataPrasarana/restore/(:any)', 'DataPrasarana::restore/$1');
$routes->get('dataPrasarana/restore', 'DataPrasarana::restore');
$routes->delete('dataPrasarana/deletePermanent/(:any)', 'DataPrasarana::deletePermanent/$1');
$routes->delete('dataPrasarana/deletePermanent', 'DataPrasarana::deletePermanent');
$routes->presenter('dataPrasarana', ['filter' => 'isLoggedIn']);

// SARANA

// Rincian Aset
$routes->get('rincianAset/edit', 'RincianAset::edit');
$routes->get('rincianAset/trash', 'RincianAset::trash');
$routes->get('rincianAset/restore/(:any)', 'RincianAset::restore/$1');
$routes->get('rincianAset/restore', 'RincianAset::restore');
$routes->delete('rincianAset/deletePermanent/(:any)', 'RincianAset::deletePermanent/$1');
$routes->delete('rincianAset/deletePermanent', 'RincianAset::deletePermanent');
$routes->resource('rincianAset', ['filter' => 'isLoggedIn']);


// $routes->get('identitasGedung', 'InformasiController::getIdentitasGedung');
// $routes->get('identitasLantai', 'InformasiController::getIdentitasLantai');
// $routes->get('statusManajemen', 'InformasiController::getStatusManajemen');
// $routes->get('kategoriManajemen', 'InformasiController::getKategoriManajemen');
// $routes->get('profilSekolah', 'InformasiController::getProfilSekolah');

// $routes->get('manajemenUser', 'UserController::getManajemenUser');

// $routes->get('layananAsetIt', 'ItController::getLayananAset');
// $routes->get('rincianAsetIt', 'ItController::getRincianAset');
// $routes->get('perangkatIt', 'ItController::getPerangkatIt');
// $routes->get('websiteSosmed', 'ItController::getWebsiteSosmed');

// $routes->get('manajemenPeminjamanLab', 'LabController::getManajemenPeminjamanLab');
// $routes->get('layananAsetLab', 'LabController::getLayananAsetLab');
// $routes->get('layananNonAsetLab', 'LabController::getLayananNonAsetLab');
// $routes->get('rincianAsetLab', 'LabController::getRincianAsetLab');
// $routes->get('manajemenLab', 'LabController::getManajemenLab');

// $routes->get('kantin', 'PrasaranaController::getKantin');
// $routes->get('lapangan', 'PrasaranaController::getLapangan');
// $routes->get('parkiran', 'PrasaranaController::getParkiran');
// $routes->get('parkiran', 'PrasaranaController::getParkiran');
// $routes->get('ruangan', 'PrasaranaController::getRuangan');
// $routes->get('toilet', 'PrasaranaController::getToilet');

// $routes->get('layananAsetSarana', 'SaranaController::getLayananAsetSarana');
// $routes->get('layananNonAsetSarana', 'SaranaController::getLayananNonAsetSarana');
// $routes->get('rincianAsetSarana', 'SaranaController::getRincianAsetSarana');


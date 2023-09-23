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
$routes->get('identitasSarana', 'InformasiController::getIdentitasSarana');
$routes->get('identitasSarana/add', 'InformasiController::addIdentitasSarana');
$routes->post('identitasSarana', 'InformasiController::saveIdentitasSarana');
$routes->get('identitasSarana/edit', 'InformasiController::editIdentitasSarana');
$routes->get('identitasSarana/edit/(:num)', 'InformasiController::editIdentitasSarana/$1');
$routes->put('identitasSarana/(:any)', 'InformasiController::updateIdentitasSarana/$1');
$routes->delete('identitasSarana/(:segment)', 'InformasiController::deleteIdentitasSarana/$1');

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


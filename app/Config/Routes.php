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
$routes->resource('identitasPrasarana', ['filter' => 'isLoggedIn']);


// Sumber Dana
$routes->get('sumberDana/trash', 'SumberDana::trash');
$routes->get('sumberDana/restore/(:any)', 'SumberDana::restore/$1');
$routes->get('sumberDana/restore', 'SumberDana::restore');
$routes->delete('sumberDana/deletePermanent/(:any)', 'SumberDana::deletePermanent/$1');
$routes->delete('sumberDana/deletePermanent', 'SumberDana::deletePermanent');
$routes->presenter('sumberDana', ['filter' => 'isLoggedIn']);





// $routes->get('identitasPrasarana', 'InformasiController::getIdentitasPrasarana');
$routes->get('identitasGedung', 'InformasiController::getIdentitasGedung');
$routes->get('identitasLantai', 'InformasiController::getIdentitasLantai');
// $routes->get('sumberDana', 'InformasiController::getSumberDana');
$routes->get('statusManajemen', 'InformasiController::getStatusManajemen');
$routes->get('kategoriManajemen', 'InformasiController::getKategoriManajemen');
$routes->get('profilSekolah', 'InformasiController::getProfilSekolah');

$routes->get('manajemenUser', 'UserController::getManajemenUser');

$routes->get('layananAsetIt', 'ItController::getLayananAset');
$routes->get('rincianAsetIt', 'ItController::getRincianAset');
$routes->get('perangkatIt', 'ItController::getPerangkatIt');
$routes->get('websiteSosmed', 'ItController::getWebsiteSosmed');

$routes->get('manajemenPeminjamanLab', 'LabController::getManajemenPeminjamanLab');
$routes->get('layananAsetLab', 'LabController::getLayananAsetLab');
$routes->get('layananNonAsetLab', 'LabController::getLayananNonAsetLab');
$routes->get('rincianAsetLab', 'LabController::getRincianAsetLab');
$routes->get('manajemenLab', 'LabController::getManajemenLab');

$routes->get('kantin', 'PrasaranaController::getKantin');
$routes->get('lapangan', 'PrasaranaController::getLapangan');
$routes->get('parkiran', 'PrasaranaController::getParkiran');
$routes->get('parkiran', 'PrasaranaController::getParkiran');
$routes->get('ruangan', 'PrasaranaController::getRuangan');
$routes->get('toilet', 'PrasaranaController::getToilet');

$routes->get('layananAsetSarana', 'SaranaController::getLayananAsetSarana');
$routes->get('layananNonAsetSarana', 'SaranaController::getLayananNonAsetSarana');
$routes->get('rincianAsetSarana', 'SaranaController::getRincianAsetSarana');


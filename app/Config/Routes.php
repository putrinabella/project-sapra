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


// $routes->addRedirect('/', 'Home');
$routes->get('/', 'Home::index');

$routes->get('identitasSarana', 'InformasiController::getIdentitasSarana');
$routes->get('identitasSarana/add', 'InformasiController::addIdentitasSarana');
$routes->post('identitasSarana', 'InformasiController::saveIdentitasSarana');
$routes->get('identitasSarana/edit/(:num)', 'InformasiController::editIdentitasSarana/$1');
$routes->put('identitasSarana/(:any)', 'InformasiController::updateIdentitasSarana/$1');
$routes->delete('identitasSarana/(:segment)', 'InformasiController::deleteIdentitasSarana/$1');

$routes->get('identitasPrasarana', 'InformasiController::getIdentitasPrasarana');
$routes->get('identitasGedung', 'InformasiController::getIdentitasGedung');
$routes->get('identitasLantai', 'InformasiController::getIdentitasLantai');
$routes->get('sumberDana', 'InformasiController::getSumberDana');
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
$routes->get('ruangan', 'PrasaranaController::getRuangan');
$routes->get('toilet', 'PrasaranaController::getToilet');

$routes->get('layananAsetSarana', 'SaranaController::getLayananAsetSarana');
$routes->get('layananNonAsetSarana', 'SaranaController::getLayananNonAsetSarana');
$routes->get('rincianAsetSarana', 'SaranaController::getRincianAsetSarana');

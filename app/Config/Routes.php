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


// HOME
$routes->get('/', 'Home::index');
$routes->get('home', 'Home::index');
$routes->get('home/generate', 'Home::generate');


// INFORMATION ROUTES

// Identitas Sarana
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

// Sumber Dana
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


// SARANA

// Rincian Aset
$routes->get('rincianAset/createTemplate', 'RincianAset::createTemplate');
$routes->get('rincianAset/print/(:num)', 'RincianAset::print/$1');
$routes->get('rincianAset/generatePDF', 'RincianAset::generatePDF');
$routes->get('rincianAset/export', 'RincianAset::export');
$routes->post('rincianAset/import', 'RincianAset::import');
$routes->get('rincianAset/edit', 'RincianAset::edit');
$routes->get('rincianAset/trash', 'RincianAset::trash');
$routes->get('rincianAset/restore/(:any)', 'RincianAset::restore/$1');
$routes->get('rincianAset/restore', 'RincianAset::restore');
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
$routes->get('prasaranaRuangan/createTemplate', 'PrasaranaRuangan::createTemplate');
$routes->get('prasaranaRuangan/generatePDF', 'PrasaranaRuangan::generatePDF');
$routes->get('prasaranaRuangan/export', 'PrasaranaRuangan::export');
$routes->resource('prasaranaRuangan', ['filter' => 'isLoggedIn']);

// Non Ruangan
$routes->get('prasaranaNonRuangan/print/(:num)', 'PrasaranaNonRuangan::print/$1');
$routes->get('prasaranaNonRuangan/createTemplate', 'PrasaranaNonRuangan::createTemplate');
$routes->get('prasaranaNonRuangan/generatePDF', 'PrasaranaNonRuangan::generatePDF');
$routes->get('prasaranaNonRuangan/export', 'PrasaranaNonRuangan::export');
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

// Website dan Sosmed
$routes->get('websiteSosmed/createTemplate', 'WebsiteSosmed::createTemplate');
$routes->get('websiteSosmed/generatePDF', 'WebsiteSosmed::generatePDF');
$routes->get('websiteSosmed/export', 'WebsiteSosmed::export');
$routes->post('websiteSosmed/import', 'WebsiteSosmed::import');
$routes->get('websiteSosmed/edit', 'WebsiteSosmed::edit');
$routes->get('websiteSosmed/trash', 'WebsiteSosmed::trash');
$routes->get('websiteSosmed/restore/(:any)', 'WebsiteSosmed::restore/$1');
$routes->get('websiteSosmed/restore', 'WebsiteSosmed::restore');
$routes->delete('websiteSosmed/deletePermanent/(:any)', 'WebsiteSosmed::deletePermanent/$1');
$routes->delete('websiteSosmed/deletePermanent', 'WebsiteSosmed::deletePermanent');
$routes->resource('websiteSosmed', ['filter' => 'isLoggedIn']);

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

$routes->get('identitasSarana', 'Identitas::getIdentitasSarana');

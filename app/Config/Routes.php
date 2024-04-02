<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('maestros', 'Maestros::index');
$routes->get('padres', 'Padres::index');
$routes->get('login', 'Auth::login');
$routes->group('admin', function ($routes) {
    $routes->get('licencias', 'AdminLicencias::index');
    $routes->get('licencias/create', 'AdminLicencias::create');
    $routes->post('licencias/store', 'AdminLicencias::store');
    $routes->get('licencias/edit/(:num)', 'AdminLicencias::edit/$1');
    $routes->post('licencias/update/(:num)', 'AdminLicencias::update/$1');
    $routes->post('licencias/delete/(:num)', 'AdminLicencias::delete/$1');
});

// Rutas para el controlador Maestros

$routes->get('maestros/create', '\App\Controllers\Maestros::create');
$routes->post('maestros/store', '\App\Controllers\Maestros::store');
$routes->get('maestros/edit/(:num)', '\App\Controllers\Maestros::edit/$1');
$routes->post('maestros/update/(:num)', '\App\Controllers\Maestros::update/$1');
$routes->get('maestros/delete/(:num)', '\App\Controllers\Maestros::delete/$1');


// Rutas para el controlador Padres

$routes->get('padres/create', '\App\Controllers\padres::create');
$routes->post('padres/store', '\App\Controllers\padres::store');
$routes->get('padres/edit/(:num)', '\App\Controllers\padres::edit/$1');
$routes->post('padres/update/(:num)', '\App\Controllers\padres::update/$1');
$routes->get('padres/delete/(:num)', '\App\Controllers\padres::delete/$1');

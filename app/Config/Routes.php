<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;
use CodeIgniter\Router\Route;


/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
// Rutas para el controlador Maestros


$routes->get('/', 'AuthController::login');
$routes->post('/login', 'AuthController::processLogin');
$routes->get('/logout', 'AuthController::logout');
$routes->group('admin', function ($routes) {
    $routes->get('licencias', 'AdminLicencias::index');
    $routes->get('licencias/create', 'AdminLicencias::create');
    $routes->post('licencias/store', 'AdminLicencias::store');
    $routes->get('licencias/edit/(:num)', 'AdminLicencias::edit/$1');
    $routes->post('licencias/update/(:num)', 'AdminLicencias::update/$1');
    $routes->post('licencias/delete/(:num)', 'AdminLicencias::delete/$1');
});

// Rutas para el controlador Maestros
$routes->get('prueba', 'Maestros::index');
$routes->get('create', 'Maestros::create');
$routes->post('/store', 'Maestros::store');
$routes->get('/maestros/edit/(:num)', 'Maestros::edit/$1');
$routes->post('/maestros/update/(:num)', 'Maestros::update/$1');
$routes->get('/maestros/delete/(:num)', 'Maestros::delete/$1');
<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', function () {
    return redirect()->to('/home');
});

$routes->get('home', 'Home::index');
$routes->get('maestros', 'Maestros::index');
$routes->get('padres', 'Padres::index');
$routes->get('tipo_permiso', 'TipoPermiso::index');
$routes->get('usuario', 'Usuario::index');

$routes->group('admin', function ($routes) {
    $routes->get('licencias', 'AdminLicencias::index');
    $routes->get('licencias/create', 'AdminLicencias::create');
    $routes->post('licencias/store', 'AdminLicencias::store');
    $routes->get('licencias/edit/(:num)', 'AdminLicencias::edit/$1');
    $routes->post('licencias/update/(:num)', 'AdminLicencias::update/$1');
    $routes->post('licencias/delete/(:num)', 'AdminLicencias::delete/$1');
});

// Rutas para el controlador Maestros
$routes->get('maestros/create', 'Maestros::create');
$routes->post('maestros/store', 'Maestros::store');
$routes->get('maestros/edit/(:num)', 'Maestros::edit/$1');
$routes->post('maestros/update/(:num)', 'Maestros::update/$1');
$routes->get('maestros/delete/(:num)', 'Maestros::delete/$1');

// Rutas para el controlador Padres
$routes->get('padres/create', 'Padres::create');
$routes->post('padres/store', 'Padres::store');
$routes->get('padres/edit/(:num)', 'Padres::edit/$1');
$routes->post('padres/update/(:num)', 'Padres::update/$1');
$routes->get('padres/delete/(:num)', 'Padres::delete/$1');
$routes->get('padres/getAlumnosAjax/(:num)', 'Padres::getAlumnosAjax/$1');



// Rutas para el controlador Tipos de permisos

$routes->get('tipo_permiso/create', 'TipoPermiso::create');
$routes->post('tipo_permiso/store', 'TipoPermiso::store');
$routes->get('tipo_permiso/edit/(:num)', 'TipoPermiso::edit/$1');
$routes->post('tipo_permiso/update', 'TipoPermiso::update');
$routes->get('tipo_permiso/delete/(:num)', 'TipoPermiso::delete/$1');


// Rutas para el módulo de permisos magisteriales
$routes->get('permiso_magisterial', 'PermisoMagisterial::index');
$routes->get('permiso_magisterial/create', 'PermisoMagisterial::create');
$routes->post('permiso_magisterial/store', 'PermisoMagisterial::store'); // Ruta para el método store

//LOGIN
$routes->get('login', 'Login::index');
$routes->post('login/signin', 'Login::signIn');
$routes->post('login/logout', 'Login::logout');

// Rutas para el controlador Usuario
$routes->get('usuario/create', 'Usuario::create');
$routes->post('usuario/store', 'Usuario::store');
$routes->get('usuario/edit/(:num)', 'Usuario::edit/$1');
$routes->post('usuario/update/(:num)', 'Usuario::update/$1');
$routes->get('usuario/delete/(:num)', 'Usuario::delete/$1');

$routes->get('bitacora', 'Bitacora::index');

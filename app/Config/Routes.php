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
$routes->get('roles', 'Roles::index');
$routes->get('acceso', 'Acceso::index');
$routes->get('usuario', 'Usuario::index');
$routes->get('donaciones', 'Donaciones::index');
$routes->get('proyectos', 'Proyectos::index');

$routes->group('admin', function ($routes) {
    $routes->get('licencias', 'AdminLicencias::index');
    $routes->get('licencias/create', 'AdminLicencias::create');
    $routes->post('licencias/store', 'AdminLicencias::store');
    $routes->get('licencias/edit/(:num)', 'AdminLicencias::edit/$1');
    $routes->post('licencias/update/(:num)', 'AdminLicencias::update/$1');
    $routes->post('licencias/delete/(:num)', 'AdminLicencias::delete/$1');
});

// Rutas para el controlador Maestros
$routes->get('maestros', 'Maestros::index');
$routes->match(['get', 'post'], 'maestros/index', 'Maestros::index');
$routes->get('maestros/create', 'Maestros::create');
$routes->post('maestros/store', 'Maestros::store');
$routes->get('maestros/edit/(:segment)', 'Maestros::edit/$1');
$routes->post('maestros/update/(:segment)', 'Maestros::update/$1');
$routes->get('maestros/delete/(:segment)', 'Maestros::delete/$1');


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

$routes->get('permiso_magisterial/index', 'PermisoMagisterial::index');
$routes->post('permiso_magisterial/store', 'PermisoMagisterial::store');
$routes->get('permiso_magisterial/create', 'PermisoMagisterial::create');
$routes->get('report', 'ReportController::index');

$routes->get('permiso_magisterial/generarReportePDF', 'PermisoMagisterial::generarReportePDF');
$routes->get('permiso_magisterial/generarReporteExcel', 'PermisoMagisterial::generarReporteExcel');



//LOGIN
$routes->get('login', 'Login::index');
$routes->post('login/signin', 'Login::signIn');
$routes->post('login/logout', 'Login::logout');

// Rutas para el controlador Roles
$routes->get('roles/create', 'Roles::create');
$routes->post('roles/store', 'Roles::store');


// Rutas para el controlador accesos
$routes->post('acceso/update/(:num)', 'Acceso::update/$1');
$routes->get('acceso/delete/(:num)', 'Acceso::delete/$1');
$routes->post('acceso/store', 'Acceso::store');
$routes->get('acceso/edit/(:num)', 'Acceso::edit/$1');


// Rutas para el controlador Usuario
$routes->get('usuario/create', 'Usuario::create');
$routes->post('usuario/store', 'Usuario::store');
$routes->get('usuario/edit/(:num)', 'Usuario::edit/$1');
$routes->post('usuario/update/(:num)', 'Usuario::update/$1');
$routes->get('usuario/delete/(:num)', 'Usuario::delete/$1');

$routes->get('bitacora', 'Bitacora::index');

// Rutas para el controlador Tipos de producto
$routes->get('tipo_producto', 'TipoProducto::index');
$routes->get('tipo_producto/create', 'TipoProducto::create');
$routes->post('tipo_producto/store', 'TipoProducto::store');
$routes->get('tipo_producto/edit/(:num)', 'TipoProducto::edit/$1');
$routes->post('tipo_producto/update/(:num)', 'TipoProducto::update/$1');
$routes->get('tipo_producto/delete/(:num)', 'TipoProducto::delete/$1');

//Rutas donaciones
$routes->post('donaciones/store', 'Donaciones::store');
$routes->get('donaciones/create', 'Donaciones::create');


//Rutas proyectos
$routes->post('proyectos/store', 'Proyectos::store');
$routes->get('proyectos/create', 'Proyectos::create');
$routes->get('proyectos/edit/(:num)', 'Proyectos::edit/$1');
$routes->get('proyectos/delete/(:num)', 'Proyectos::delete/$1');
$routes->post('proyectos/update', 'Proyectos::update/$1');


// Rutas para el controlador Unidades de Medida
$routes->get('unidadesmedida', 'UnidadesMedida::index');
$routes->get('unidadesmedida/create', 'UnidadesMedida::create');
$routes->post('unidadesmedida/store', 'UnidadesMedida::store');
$routes->get('unidadesmedida/edit/(:num)', 'UnidadesMedida::edit/$1');
$routes->post('unidadesmedida/update/(:num)', 'UnidadesMedida::update/$1');
$routes->get('unidadesmedida/delete/(:num)', 'UnidadesMedida::delete/$1');



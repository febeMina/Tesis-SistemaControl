<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', function () {
    return redirect()->to('home');
});

$routes->get('home', 'Home::index');
$routes->get('maestros', 'Maestros::index');
$routes->get('tipo_permiso', 'TipoPermiso::index');
$routes->get('roles', 'Roles::index');


$routes->get('graficos', 'Graficos::index');
$routes->get('report', 'Reportes::index');

//RUTA GRAFICAS
$routes->get('metas/home', 'Home::getMetas');


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
$routes->get('maestros/edit/(:num)', 'Maestros::edit/$1');
$routes->post('maestros/update/(:num)', 'Maestros::update/$1');
$routes->match(['get', 'post'], 'maestros/delete', 'Maestros::delete');




// Rutas para el controlador Padres
$routes->group('padres/', ['filter' => 'Tesorero'], function ($routes) {
$routes->get('create', 'Padres::create');
$routes->post('store', 'Padres::store');
});

$routes->group('padres', ['filter' => 'Administrador'], function ($routes) {
    $routes->get('/', 'Padres::index');
    $routes->get('edit/(:num)', 'Padres::edit/$1');
    $routes->post('update/(:num)', 'Padres::update/$1');
    $routes->get('delete/(:num)', 'Padres::delete/$1');
    $routes->get('getAlumnosAjax/(:num)', 'Padres::getAlumnosAjax/$1');
});




// Rutas para el controlador Tipos de permisos

$routes->get('/tipo_permiso', 'TipoPermiso::index');
$routes->get('/tipo_permiso/create', 'TipoPermiso::create');
$routes->post('/tipo_permiso/store', 'TipoPermiso::store');
$routes->get('/tipo_permiso/edit/(:num)', 'TipoPermiso::edit/$1');
$routes->post('/tipo_permiso/update', 'TipoPermiso::update');
$routes->get('/tipo_permiso/delete/(:num)', 'TipoPermiso::delete/$1');



// Rutas para el módulo de permisos magisteriales

$routes->get('permiso_magisterial/index', 'PermisoMagisterial::index');
$routes->post('permiso_magisterial/store', 'PermisoMagisterial::store');
$routes->get('permiso_magisterial/create', 'PermisoMagisterial::create');
$routes->get('report', 'ReportController::index');







// Rutas para el manejo de permisos personales
$routes->group('permisos_personal', ['filter' => 'SubDireccion'], function ($routes) {
    $routes->get('/', 'PermisosPersonal::index');
    $routes->get('create', 'PermisosPersonal::create');
    $routes->post('store', 'PermisosPersonal::store');
    $routes->get('getSaldoActual/(:num)/(:num)', 'PermisosPersonal::getSaldoActual/$1/$2');
});


$routes->get('/reporte', 'PermisosPersonal::reporte');
$routes->get('reporte-pdf/generar-reporte', 'ReportePDF::generarReporte');




// Rutas para los reportes de permisos magisteriales
$routes->get('reportes/permisos_magisteriales_reporte', 'ReportesController::permisos_magisteriales_reporte');
$routes->post('reportes/generate_report', 'PermisoMagisterialController::generate_report');


//LOGIN
$routes->get('login', 'Login::index');
$routes->post('login/signin', 'Login::signIn');
$routes->post('login/logout', 'Login::logout');

// Rutas para el controlador Roles
$routes->get('roles/create', 'Roles::create');
$routes->post('roles/store', 'Roles::store');


$routes->group('usuario', ['filter' => 'Administrador'], function ($routes) {

                    // Rutas para el controlador Usuario
                    $routes->get('/', 'Usuario::index');
                    $routes->get('create', 'Usuario::create');
                    $routes->post('store', 'Usuario::store');

                    $routes->post('update/(:num)', 'Usuario::update/$1');
                    $routes->get('delete/(:num)', 'Usuario::delete/$1');
                    $routes->post('cambioclave/(:num)', 'Usuario::cambioClave/$1');
                    $routes->get('edit/(:num)', 'Usuario::edit/$1');
});



$routes->get('usuario/editclave/(:num)', 'Usuario::editClave/$1');

// Rutas para el controlador accesos
$routes->group('acceso', ['filter' => 'Administrador'], function ($routes) {
    $routes->get('/', 'Acceso::index');
    $routes->post('update/(:num)', 'Acceso::update/$1');
    $routes->get('delete/(:num)', 'Acceso::delete/$1');
    $routes->post('store', 'Acceso::store');
    $routes->get('edit/(:num)', 'Acceso::edit/$1');
});




// En app/Config/Routes.php
$routes->get('usuario/configuracion', 'Usuario::configuracion'); // Reemplaza 'configuracion' con el método que maneja la configuración del usuario


$routes->group('', ['filter' => 'Administrador'], function ($routes) {

});




$routes->group('donaciones', ['filter' => 'Tesorero'], function ($routes) {
    $routes->get('/', 'Donaciones::index');
    $routes->post('store', 'Donaciones::store');
    $routes->get('create', 'Donaciones::create'); 
    $routes->get('tiket/(:num)', 'Donaciones::GenerarTicket/$1');
    $routes->get('tipoDonador', 'Donaciones::obtener_donadores_por_tipo'); 
});

$routes->group('donaciones/', ['filter' => 'Contador'], function ($routes) {
    $routes->get('reporte', 'Donaciones::GenerarReporte');
    $routes->get('edit/(:num)', 'Donaciones::edit/$1');
    $routes->post('update/(:num)', 'Donaciones::update/$1');
});

$routes->get('bitacora', 'Bitacora::index');

//Rutas donaciones


 
//Rutas proyectos
$routes->group('proyectos', ['filter' => 'Contador'], function ($routes) {
        $routes->get('/', 'Proyectos::index');
        $routes->post('store', 'Proyectos::store');
        $routes->get('create', 'Proyectos::create');
        $routes->get('edit/(:num)', 'Proyectos::edit/$1');
        $routes->post('update/(:num)', 'Proyectos::update/$1');
});

$routes->group('proyectos/delete', ['filter' => 'Administrador'], function ($routes) {
    $routes->get('/(:num)', 'Proyectos::delete/$1');
});



// Rutas para el controlador Unidades de Medida
$routes->get('unidadesmedida', 'UnidadesMedida::index');
$routes->get('unidadesmedida/create', 'UnidadesMedida::create');
$routes->post('unidadesmedida/store', 'UnidadesMedida::store');
$routes->get('unidadesmedida/edit/(:num)', 'UnidadesMedida::edit/$1');
$routes->post('unidadesmedida/update/(:num)', 'UnidadesMedida::update/$1');
$routes->get('unidadesmedida/delete/(:num)', 'UnidadesMedida::delete/$1');


//Consumo por productos
$routes->group('consumo', ['filter' => 'Cocina'], function ($routes) {
    $routes->get('/', 'Consumo::index');          // Listar consumos
    $routes->get('create', 'Consumo::create');    // Mostrar formulario de creación
    $routes->post('store', 'Consumo::store');     // Guardar nuevo consumo
    $routes->get('edit/(:num)', 'Consumo::edit/$1');  // Mostrar formulario de edición
    $routes->post('update/(:num)', 'Consumo::update/$1');    // Actualizar consumo existente
    $routes->get('delete/(:num)', 'Consumo::delete/$1');  // Eliminar consumo existente
    $routes->get('getSaldoInicial/(:num)', 'Consumo::getSaldoInicial/$1'); // Obtener saldo inicial
});




// Requisicion de productos
$routes->group('solicitudproductos', function ($routes) {
    $routes->get('/', 'SolicitudProductos::index');            // Listar solicitudes de productos
    $routes->get('create', 'SolicitudProductos::create');      // Mostrar formulario de creación de solicitud
    $routes->post('store', 'SolicitudProductos::store');       // Guardar nueva solicitud de productos
    $routes->get('edit/(:num)', 'SolicitudProductos::edit/$1');   // Mostrar formulario de edición de solicitud
    $routes->post('update/(:num)', 'SolicitudProductos::update/$1'); // Actualizar solicitud de productos existente
    $routes->get('delete/(:num)', 'SolicitudProductos::delete/$1'); // Eliminar solicitud de productos existente
    $routes->get('cargarModal/(:num)', 'SolicitudProductos::cargarModal/$1');

    /// Ruta para el reporte de requisiciones de productos
    $routes->get('reporteS', 'ReporteSolicitudProductos::index');
    $routes->get('reporte-solicitud-productos', 'ReporteSolicitudProductos::index');
});
 


// Ruta para generar el reporte PDF
$routes->get('reporte-pdf/generar-reporte-solicitud-productos', 'ReporteSolicitudProductos::generarReporte');


// Rutas para el controlador Tipos de producto ------------- 03/07/2024
$routes->get('tipo_producto', 'TipoProducto::index');
$routes->get('tipo_producto/create', 'TipoProducto::create');
$routes->post('tipo_producto/store', 'TipoProducto::store');
$routes->get('tipo_producto/edit/(:num)', 'TipoProducto::edit/$1');
$routes->post('tipo_producto/update/(:num)', 'TipoProducto::update/$1');
$routes->get('tipo_producto/delete/(:num)', 'TipoProducto::delete/$1');
// Rutas para Unidades de Medida Individual ------------- 03/07/2024
$routes->get('unidadesindividuales', 'UnidadesIndividuales::index');
$routes->get('unidadesindividuales/create', 'UnidadesIndividuales::create');
$routes->post('unidadesindividuales/store', 'UnidadesIndividuales::store');
$routes->get('unidadesindividuales/edit/(:num)', 'UnidadesIndividuales::edit/$1');
$routes->post('unidadesindividuales/update/(:num)', 'UnidadesIndividuales::update/$1');
$routes->get('unidadesindividuales/delete/(:num)', 'UnidadesIndividuales::delete/$1');
// Rutas para el controlador UnidadesPorCaja ------------- 03/07/2024
$routes->get('unidadesporcaja', 'UnidadesPorCaja::index');
$routes->get('unidadesporcaja/create', 'UnidadesPorCaja::create');
$routes->post('unidadesporcaja/store', 'UnidadesPorCaja::store');
$routes->get('unidadesporcaja/edit/(:num)', 'UnidadesPorCaja::edit/$1');
$routes->post('unidadesporcaja/update/(:num)', 'UnidadesPorCaja::update/$1');
$routes->get('unidadesporcaja/delete/(:num)', 'UnidadesPorCaja::delete/$1');
// Rutas para el controlador Productos ------------- 03/07/2024
$routes->get('productos', 'Productos::index');
$routes->get('productos/create', 'Productos::create');
$routes->post('productos/store', 'Productos::store');
$routes->get('productos/edit/(:num)', 'Productos::edit/$1');
$routes->post('productos/update/(:num)', 'Productos::update/$1');
$routes->get('productos/delete/(:num)', 'Productos::delete/$1');


// Bitácora
$routes->get('bitacora', 'Bitacora::index');


$routes->get('/registro-diario', 'RegistroDiarioController::index');
$routes->get('/registro-diario/create', 'RegistroDiarioController::create');
$routes->post('/registro-diario/store', 'RegistroDiarioController::store');
$routes->get('/registro-diario/show/(:num)', 'RegistroDiarioController::show/$1');
$routes->get('public/registro-diario/show/(:num)', 'RegistroDiario::show/$1');
$routes->get('registro-diario/getDetails/(:num)', 'RegistroDiarioController::getDetails/$1');

$routes->get('tipo-documento', 'TipoDocumentoController::index');
$routes->get('tipo-documento/create', 'TipoDocumentoController::create');
$routes->post('tipo-documento/store', 'TipoDocumentoController::store');
$routes->get('tipo-documento/edit/(:num)', 'TipoDocumentoController::edit/$1');
$routes->post('tipo-documento/update/(:num)', 'TipoDocumentoController::update/$1');
$routes->post('tipo-documento/delete/(:num)', 'TipoDocumentoController::delete/$1');


// Requisicion de productos
$routes->group('solicitudproductos', function ($routes) {
    $routes->get('/', 'SolicitudProductos::index');            // Listar solicitudes de productos
    $routes->get('create', 'SolicitudProductos::create');      // Mostrar formulario de creación de solicitud
    $routes->post('store', 'SolicitudProductos::store');       // Guardar nueva solicitud de productos
    $routes->get('edit/(:num)', 'SolicitudProductos::edit/$1');   // Mostrar formulario de edición de solicitud
    $routes->get('ver/(:num)', 'SolicitudProductos::viewEdit/$1');   // Mostrar formulario de edición de solicitud
    $routes->get('anular/(:num)', 'SolicitudProductos::anular/$1');   // Mostrar formulario de edición de solicitud
    $routes->post('update/(:num)', 'SolicitudProductos::update/$1'); // Actualizar solicitud de productos existente
    $routes->get('delete/(:num)', 'SolicitudProductos::delete/$1'); // Eliminar solicitud de productos existente
    $routes->get('cargarModal/(:num)', 'SolicitudProductos::cargarModal/$1');
    $routes->post('detalle', 'SolicitudProductos::storeLote');
    $routes->post('finalizar', 'SolicitudProductos::finalizar');
    /// Ruta para el reporte de requisiciones de productos
    $routes->get('reporteS', 'ReporteSolicitudProductos::index');
    $routes->get('reporte-solicitud-productos', 'ReporteSolicitudProductos::index');
});


//Consumo por productos
$routes->group('consumo', function ($routes) {
    $routes->get('/', 'Consumo::index');          // Listar consumos
    $routes->get('create', 'Consumo::create');    // Mostrar formulario de creación
    $routes->post('store', 'Consumo::store');     // Guardar nuevo consumo
    $routes->get('edit/(:num)', 'Consumo::edit/$1');  // Mostrar formulario de edición
    $routes->post('update/(:num)', 'Consumo::update/$1');    // Actualizar consumo existente
    $routes->get('delete/(:num)', 'Consumo::delete/$1');  // Eliminar consumo existente
    $routes->get('getSaldoInicial/(:num)', 'Consumo::getSaldoInicial/$1'); // Obtener saldo inicial
    $routes->get('ver/(:num)', 'Consumo::viewEdit/$1');   // Mostrar formulario de edición de solicitud
    $routes->get('anular/(:num)', 'Consumo::anular/$1');   // Mostrar formulario de edición de solicitud
    $routes->post('detalle', 'Consumo::storeLote');
    $routes->post('finalizar', 'Consumo::finalizar');
});

// Rutas para Unidades de Medida Individual ------------- 03/07/2024
$routes->get('unidadesindividuales', 'UnidadesIndividuales::index');
$routes->get('unidadesindividuales/create', 'UnidadesIndividuales::create');
$routes->post('unidadesindividuales/store', 'UnidadesIndividuales::store');
$routes->get('unidadesindividuales/edit/(:num)', 'UnidadesIndividuales::edit/$1');
$routes->post('unidadesindividuales/update/(:num)', 'UnidadesIndividuales::update/$1');
$routes->get('unidadesindividuales/delete/(:num)', 'UnidadesIndividuales::delete/$1');
// Rutas para el controlador UnidadesPorCaja ------------- 03/07/2024
$routes->get('unidadesporcaja', 'UnidadesPorCaja::index');
$routes->get('unidadesporcaja/create', 'UnidadesPorCaja::create');
$routes->post('unidadesporcaja/store', 'UnidadesPorCaja::store');
$routes->get('unidadesporcaja/edit/(:num)', 'UnidadesPorCaja::edit/$1');
$routes->post('unidadesporcaja/update/(:num)', 'UnidadesPorCaja::update/$1');
$routes->get('unidadesporcaja/delete/(:num)', 'UnidadesPorCaja::delete/$1');


// Rutas para el controlador Productos ------------- 03/07/2024
$routes->get('productos', 'Productos::index');
$routes->get('productos/create', 'Productos::create');
$routes->post('productos/store', 'Productos::store');
$routes->get('productos/edit/(:num)', 'Productos::edit/$1');
$routes->post('productos/update/(:num)', 'Productos::update/$1');
$routes->get('productos/delete/(:num)', 'Productos::delete/$1');
$routes->get('productos/estado/(:num)', 'Productos::estado/$1');
$routes->get('productos/movimientos/(:num)', 'Productos::historialMovimientos/$1');

// Ruta para el controlador ProductosLotes
$routes->get('productos_lotes/(:num)', 'ProductosLotes::index/$1');
$routes->get('productos_lotes/create', 'ProductosLotes::create');
$routes->post('productos_lotes/store', 'ProductosLotes::store');
$routes->get('productos_lotes/edit/(:num)', 'ProductosLotes::edit/$1');
$routes->post('productos_lotes/update/(:num)', 'ProductosLotes::update/$1');
$routes->get('productos_lotes/delete/(:num)', 'ProductosLotes::delete/$1');
$routes->get('productos_lotes/movimientos/(:num)', 'ProductosLotes::historialMovimientos/$1');
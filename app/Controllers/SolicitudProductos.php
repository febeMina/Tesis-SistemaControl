<?php

namespace App\Controllers;

use App\Models\SolicitudProductosModel;
use App\Models\ProductoModel;
use App\Models\ConsumoModel;
use CodeIgniter\Controller;

class SolicitudProductos extends Controller
{
    protected $db;
    protected $request;
    protected $SolicitudProductosModel;
    protected $ProductosModel;

    public function __construct()
    {
        $this->db = db_connect();
        $this->request = service('request');
        $this->SolicitudProductosModel = new SolicitudProductosModel();
        $this->ProductosModel = new ProductoModel();
    }

    public function index()
    {
        $model = new SolicitudProductosModel();
        $solicitudes = $model->obtenerSolicitudesConDetalles();

        $data['solicitudes'] = $solicitudes;
        return view('solicitud_productos/index', $data);
    }

    public function create()
{
    $consumoModel = new \App\Models\ConsumoModel();
    $consumos = $consumoModel->getConsumos(); // Obtener todos los datos de consumo

    // Log para verificar los datos obtenidos
    log_message('info', 'Datos de consumo obtenidos: ' . print_r($consumos, true));

    $data = [
        'consumos' => $consumos,
        // Otros datos necesarios para el formulario
    ];

    return view('solicitud_productos/create', $data);
}

public function store()
{
    $model = new SolicitudProductosModel();
    $request = \Config\Services::request();
    
    // Validación de datos
    $validation = \Config\Services::validation();
    $validation->setRules([
        'Fecha_solicitud' => 'required|valid_date',
        'Comida_a_preparar' => 'required|string',
        'productos.*.idProducto' => 'required|integer',
        'productos.*.cantidad' => 'required|integer',
        'responsable_entrega' => 'required|string',
        'responsable_recibir' => 'required|string',
    ]);
    
    if (!$validation->withRequest($this->request)->run()) {
        return redirect()->back()->withInput()->with('errors', $validation->getErrors());
    }
    
    // Obtener los datos del formulario
    $fecha_solicitud = $request->getPost('Fecha_solicitud');
    $comida_a_preparar = $request->getPost('Comida_a_preparar');
    $productos = $request->getPost('productos');
    $responsable_entrega = $request->getPost('responsable_entrega');
    $responsable_recibir = $request->getPost('responsable_recibir');
    
    foreach ($productos as $producto) {
        // Preparar los datos para guardar
        $data = [
            'Fecha_solicitud' => $fecha_solicitud,
            'Comida_a_preparar' => $comida_a_preparar,
            'idProducto' => $producto['idProducto'],
            'cantidad' => (int) $producto['cantidad'],
            'responsable_entrega' => $responsable_entrega,
            'responsable_recibir' => $responsable_recibir,
        ];

        // Guardar los datos en la base de datos
        if (!$model->insert($data)) {
            return redirect()->back()->withInput()->with('error', 'Hubo un problema al guardar la solicitud de producto.');
        }

        // Registrar la salida en la tabla de consumos
        $this->registrarSalida($producto['idProducto'], $producto['cantidad'], $fecha_solicitud);
    }
    
    return redirect()->to(site_url('solicitud_productos'))->with('success', '¡La solicitud de producto se guardó correctamente!');
}


    public function edit($id)
    {
        $solicitudModel = new SolicitudProductosModel();
        $productoModel = new ProductoModel();
        $consumoModel = new ConsumoModel();

        $data['solicitud'] = $solicitudModel->find($id);
        $data['productos'] = $productoModel->getProductosConDetalles();
        $data['solicitud_productos'] = $solicitudModel->obtenerSolicitudesConDetalles();

        return view('solicitud_productos/edit', $data);
    }

    public function update($id)
    {
        $request = $this->request;
        $solicitudModel = new SolicitudProductosModel();

        $data = [
            'Fecha_solicitud' => $request->getPost('Fecha_solicitud'),
            'Comida_a_preparar' => $request->getPost('Comida_a_preparar'),
            'responsable_entrega' => $request->getPost('responsable_entrega'),
            'responsable_recibir' => $request->getPost('responsable_recibir'),
        ];

        $solicitudModel->update($id, $data);

        $this->db->table('solicitud_productos')
            ->where('idSolicitudProductos', $id)
            ->update($data);

        return redirect()->to(site_url('solicitud_productos'))->with('success', '¡La solicitud de producto se actualizó correctamente!');
    }

    public function delete($id)
    {
        $solicitudModel = new SolicitudProductosModel();
        $solicitudModel->delete($id);

        return redirect()->to(site_url('solicitud_productos'))->with('success', '¡La solicitud de producto se eliminó correctamente!');
    }

    protected function registrarSalida($idProducto, $cantidad, $fecha)
    {
        $consumoModel = new ConsumoModel();

        $data = [
            'idProducto' => $idProducto,
            'cantidad' => $cantidad,
            'fecha' => $fecha,
            'tipo' => 'salida'
        ];

        $consumoModel->insert($data);
    }
}

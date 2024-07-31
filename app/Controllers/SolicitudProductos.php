<?php

namespace App\Controllers;

use App\Models\SolicitudProductosModel;
use App\Models\ProductoModel;
use App\Models\ConsumoModel;
use App\Models\SolicitudProductoDetalleModel;
use CodeIgniter\Controller;

class SolicitudProductos extends Controller
{
    protected $db;
    protected $request;
    protected $SolicitudProductosModel;
    protected $ProductosModel;
    protected $SolicitudProductoDetalleModel;
    protected $ConsumoModel;

    public function __construct()
    {
        $this->db = db_connect();
        $this->request = service('request');
        $this->SolicitudProductosModel = new SolicitudProductosModel();
        $this->ProductosModel = new ProductoModel();
        $this->SolicitudProductoDetalleModel = new SolicitudProductoDetalleModel();
        $this->ConsumoModel = new ConsumoModel();
        $this->logger = \Config\Services::logger(); // Agregar logger
    }

    public function index()
    {
        $model = new SolicitudProductosModel();

        // Configurar la paginación
        $data = [
            'solicitudes' => $model->paginate(10), // 10 es el número de elementos por página
            'pager' => $model->pager,
        ];

        return view('solicitud_productos/index', $data);
    }
    
    public function create()
    {
        $productos = $this->SolicitudProductosModel->getProductosConsumo();
        
        return view('solicitud_productos/create', [
            'productos' => $productos,
        ]);
    }

    public function store()
    {
        $request = \Config\Services::request();
        
        // Validación
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

        // Recoger los datos del formulario
        $fecha_solicitud = $request->getPost('Fecha_solicitud');
        $comida_a_preparar = $request->getPost('Comida_a_preparar');
        $productos = $request->getPost('productos');
        $responsable_entrega = $request->getPost('responsable_entrega');
        $responsable_recibir = $request->getPost('responsable_recibir');

        $solicitudData = [
            'Fecha_solicitud' => $fecha_solicitud,
            'Comida_a_preparar' => $comida_a_preparar,
            'responsable_entrega' => $responsable_entrega,
            'responsable_recibir' => $responsable_recibir,
        ];

        // Insertar la solicitud
        $idSolicitudProductos = $this->SolicitudProductosModel->insert($solicitudData, true);

        if ($idSolicitudProductos) {
            $detalles = [];
            foreach ($productos as $producto) {
                $idProducto = $producto['idProducto'];
                $cantidad = (int) $producto['cantidad'];
                
                $ultimoConsumo = $this->ConsumoModel->obtenerUltimoConsumo($idProducto);
                $saldo_inicial = $ultimoConsumo ? $ultimoConsumo['saldo'] : 0;
                $saldo = $saldo_inicial - $cantidad;

                $detalles[] = [
                    'idSolicitudProductos' => $idSolicitudProductos,
                    'idProducto' => $idProducto,
                    'cantidad' => $cantidad,
                ];

                $this->registrarSalida($idProducto, $saldo_inicial, $cantidad, $saldo, $fecha_solicitud);
            }
            
            $this->SolicitudProductoDetalleModel->insertBatch($detalles);

            return redirect()->to(site_url('solicitudproductos'))->with('success', '¡La solicitud de producto se guardó correctamente!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Hubo un problema al guardar la solicitud de producto.');
        }
    }
    
   public function edit($id)
{
    $solicitudModel = new SolicitudProductosModel();
    $productoModel = new ProductoModel();
    
    $solicitud = $solicitudModel->find($id);
    if (!$solicitud) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException("No se encontró la solicitud con ID: $id");
    }

    // Obtener productos para el select
    $productos = $productoModel->findAll();
    
    // Obtener detalles de productos asociados a la solicitud
    $detalleModel = new SolicitudProductoDetalleModel();
    $productosDetalles = $detalleModel->where('idSolicitudProductos', $id)->findAll();

    // Agregar log para verificar los datos
    $this->logger->info('Datos de la solicitud:', $solicitud);
    $this->logger->info('Detalles de productos:', $productosDetalles);

    $data = [
        'solicitud' => $solicitud,
        'productos' => $productos,
        'productosDetalles' => $productosDetalles
    ];

    return view('solicitud_productos/edit', $data);
}



    protected function registrarSalida($idProducto, $saldo_inicial, $cantidad, $saldo, $fecha)
    {
        $data = [
            'idProducto' => $idProducto,
            'saldo_inicial' => $saldo_inicial,
            'salidas' => $cantidad,
            'saldo' => $saldo,
            'fecha' => $fecha,
            'tipo' => 'salida'
        ];

        if ($this->ConsumoModel->insert($data)) {
            $this->logger->info('Salida registrada para el producto ID: ' . $idProducto);
        } else {
            $this->logger->error('Error al registrar la salida para el producto ID: ' . $idProducto);
        }
    }
    
    public function obtenerDetalles($idSolicitud)
    {
        $detalles = $this->SolicitudProductoDetalleModel->where('idSolicitudProductos', $idSolicitud)->findAll();
    
        // Obtén los nombres de los productos usando el modelo ProductoModel
        $productos = [];
        foreach ($detalles as $detalle) {
            $producto = $this->ProductosModel->find($detalle['idProducto']);
            if ($producto) {
                $productos[] = [
                    'nombre_producto' => $producto['nombre'],
                    'cantidad' => $detalle['cantidad']
                ];
            }
        }
    
        return $this->response->setJSON(['detalles' => $productos]);
    }
    
    public function cargarModal($idSolicitud)
    {
        $detalles = $this->SolicitudProductoDetalleModel->getDetallesBySolicitud($idSolicitud);
        
        $data = [
            'detalles' => $detalles
        ];

        return $this->response->setJSON($data);
    }
}

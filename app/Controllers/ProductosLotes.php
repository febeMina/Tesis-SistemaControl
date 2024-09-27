<?php

namespace App\Controllers;

use App\Models\ProductoModel;
use App\Models\ProductoLoteModel;
use App\Models\ProductosMovimientosModel;
use App\Models\UnidadesIndividualesModel;
use App\Models\UnidadesPorCajaModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class ProductosLotes extends BaseController
{
    protected $productoLoteModel;
    public function __construct()
    {
        $this->productoLoteModel = new ProductoLoteModel(); // Instancia tu modelo
    }

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do not edit this line
        parent::initController($request, $response, $logger);

        // Initialize properties or models
        $this->productoLoteModel = new ProductoLoteModel();

        // Check if user is logged in
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to(base_url('public/login'))->send();
        }
    }

    public function index($id = null)
    {
        helper('url');
    
        // Verifica si se proporcionó un ID
        if ($id === null) {
            // Aquí puedes redirigir a una página o mostrar un mensaje
            return redirect()->to(site_url('productos'))->with('error', 'ID del producto no especificado.');
        }
    
        $productosLotes = $this->productoLoteModel
            ->select('
                productos_lotes.idProductoLote,
                productos.descripcionProducto, 
                productos_lotes.codigoLote, 
                productos_lotes.fechaIngreso,
                productos_lotes.fechaVencimiento,
                productos_lotes.existenciaCaja, 
                udm_caja.nombreCaja as udmCaja, 
                productos_lotes.existenciaIndividual, 
                udm_individual.nombreIndividual as udmIndividual, 
                productos_lotes.existenciaTotal
            ')
            ->join('productos', 'productos.idProducto = productos_lotes.idProducto', 'left')
            ->join('udm_caja', 'productos_lotes.idUdmCaja = udm_caja.idUdmCaja', 'left')
            ->join('udm_individual', 'productos_lotes.idUdmIndividual = udm_individual.idUdmIndividual', 'left')
            ->where('productos_lotes.idProducto', $id)
            ->where('productos.estado', 'Activo') 
            ->orderBy('productos_lotes.idProductoLote', 'ASC')
            ->orderBy('productos_lotes.fechaVencimiento', 'ASC')
            ->get()
            ->getResult();
    
        $productoModel = new ProductoModel();
        $producto = $productoModel->select('descripcionProducto')->where('idProducto', $id)->get()->getRow(); 
    
        $descripcionProducto = $producto ? $producto->descripcionProducto : 'Producto no encontrado';
    
        return view('productos/productosLotes', [
            'productosLotes' => $productosLotes, 
            'descripcionProducto' => $descripcionProducto
        ]);
    }
    

    public function create()
    {
        $productoModel = new ProductoModel();
        $udmCaja = new UnidadesPorCajaModel();
        $udmIndividual = new UnidadesIndividualesModel();
        $data = [
            'productos' => $productoModel->findAll(),
            'udmCaja' => $udmCaja->findAll(),
            'udmIndividual' => $udmIndividual->findAll()
        ];
        return view('productos/createLote', $data);
    }

    public function store()
{
    $productoMovimientosModel = new ProductosMovimientosModel();

    $dataLote = [
        'idProducto'          => $this->request->getPost('idProducto'),     
        'codigoLote'          => $this->request->getPost('codigoLote'),      
        'fechaIngreso'        => $this->request->getPost('fechaIngreso'),    
        'fechaVencimiento'    => $this->request->getPost('fechaVencimiento'),  
        'idUdmCaja'           => $this->request->getPost('idUdmCaja'),         
        'existenciaCaja'      => $this->request->getPost('existenciaCaja'),     
        'idUdmIndividual'     => $this->request->getPost('idUdmIndividual'),     
        'existenciaIndividual'=> $this->request->getPost('existenciaIndividual'),
        'existenciaTotal'     => $this->request->getPost('existenciaTotal'),    
    ];

    if ($this->productoLoteModel->insert($dataLote)) {
        $idProductoLote = $this->productoLoteModel->insertID();

        $dataMovimiento = [
            'idProductoLote'           => $idProductoLote,
            'tipoMovimiento'           => 'Entrada',
            'descripcionMovimiento'    => 'Existencia ingresada desde lote inicial',
            'fechaMovimiento'          => date('Y-m-d'),  
            'existenciaTotalAntes'     => 0,              
            'existenciaTotalMovimiento'=> $dataLote['existenciaTotal'],
            'existenciaTotalDespues'   => $dataLote['existenciaTotal']
        ];

        $productoMovimientosModel->insert($dataMovimiento);

        return redirect()->to(site_url('productos'))->with('success', 'Lote inicial creado con éxito.');
    } else {
        return redirect()->back()->withInput()->with('error', 'Error al crear el lote del producto.');
    }
}

    public function edit($id)
    {
        $productoModel = new ProductoModel();
        $udmCajaModel = new UnidadesPorCajaModel();
        $udmIndividualModel = new UnidadesIndividualesModel();

        // Obtener los datos del lote basado en el ID
        $lote = $this->productoLoteModel->find($id);

        if (!$lote) {
            return redirect()->to('/productos_lotes')->with('error', 'Lote no encontrado.');
        }

        // Obtener todos los productos y unidades de medida para los selects
        $productos = $productoModel->findAll();
        $udmCaja = $udmCajaModel->findAll();
        $udmIndividual = $udmIndividualModel->findAll();

        // Cargar la vista con los datos del lote y los productos/unidades
        return view('productos/editLote', [
            'lote' => $lote,
            'productos' => $productos,
            'udmCaja' => $udmCaja,
            'udmIndividual' => $udmIndividual
        ]);
    }

    // Función para actualizar los datos del lote
    public function update($id)
    {
        $data = $this->request->getPost();
        if (!$this->validate([
            'idProducto' => 'required',
            'codigoLote' => 'required',
            'fechaIngreso' => 'required',
            'fechaVencimiento' => 'required',
            'idUdmCaja' => 'required',
            'existenciaCaja' => 'required|decimal',
            'idUdmIndividual' => 'required',
            'existenciaIndividual' => 'required|decimal',
            'existenciaTotal' => 'required|decimal'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Actualizar los datos del lote
        $this->productoLoteModel->update($id, $data);

        return redirect()->to(site_url('productos_lotes/' . $data['idProducto']))->with('success', 'Información del lote actualizada con éxito.');
    }

    public function delete($id)
    {
        // Validar que el producto no tenga lotes para poder eliminarlo
        // Obtener el producto antes de eliminarlo
        $producto = $this->productoModel->find($id);
        if ($producto) {
            // Eliminar en la tabla movimiento primero
            $this->productoModel->eliminarMovimiento($id);
    
            // Luego eliminar en la tabla productos
            $this->productoModel->delete($id);
    
            return redirect()->to(site_url('productos'))->with('success', 'Producto eliminado exitosamente.');
        } else {
            return redirect()->to(site_url('productos'))->with('error', 'No se pudo encontrar el producto para eliminar.');
        }
    }

    public function historialMovimientos($id) {
        $productosMovimientos = new ProductosMovimientosModel();
        $movimientosLotes = $productosMovimientos
            ->select('
                productos_lotes.codigoLote,
                productos.descripcionProducto, 
                productos_movimientos.tipoMovimiento,
                productos_movimientos.descripcionMovimiento, 
                productos_movimientos.fechaMovimiento, 
                productos_movimientos.existenciaTotalAntes, 
                productos_movimientos.existenciaTotalMovimiento, 
                productos_movimientos.existenciaTotalDespues
            ')
            ->join('productos_lotes', 'productos_movimientos.idProductoLote = productos_lotes.idProductoLote', 'left')
            ->join('productos', 'productos_lotes.idProducto = productos.idProducto', 'left')
            ->where('productos_lotes.idProductoLote', $id)
            ->orderBy('productos_lotes.idProductoLote', 'ASC')
            ->orderBy('productos_movimientos.idProductoMovimiento', 'ASC')
            ->get()
            ->getResult();

        $productosLote = new ProductoLoteModel();
        $producto = $productosLote
            ->select('productos_lotes.codigoLote, productos.descripcionProducto')
            ->join('productos', 'productos_lotes.idProducto = productos.idProducto', 'left')
            ->where('productos_lotes.idProductoLote', $id) 
            ->get()
            ->getRow(); 

        $descripcionProducto = $producto ? $producto->descripcionProducto : 'Producto no encontrado';
        $codigoLote = $producto ? $producto->codigoLote : 'Lote no encontrado';

        return view('productos/movimientosLotes', [
            'movimientosLotes' => $movimientosLotes,
            'descripcionProducto' => $descripcionProducto,
            'codigoLote' => $codigoLote
        ]);
    }
    
    
    public function reportesLotes()
    {
            $productoModel = new ProductoModel();
    
            return view('Reportes/productos_lotes', [
                'productos' => $productoModel->findAll()
            ]);
    }
    
    
    public function getLotes($id)
{
    // Obtener los lotes del producto seleccionado
    $productosLotes = $this->productoLoteModel
        ->select('
            productos_lotes.codigoLote,
            productos_lotes.fechaIngreso,
            productos_lotes.fechaVencimiento,
            productos_lotes.existenciaCaja,
            udm_caja.nombreCaja as udmCaja,
            productos_lotes.existenciaIndividual,
            udm_individual.nombreIndividual as udmIndividual,
            productos_lotes.existenciaTotal
        ')
        ->join('udm_caja', 'productos_lotes.idUdmCaja = udm_caja.idUdmCaja', 'left')
        ->join('udm_individual', 'productos_lotes.idUdmIndividual = udm_individual.idUdmIndividual', 'left')
        ->where('productos_lotes.idProducto', $id)
        ->findAll();

        // Retornar los lotes en formato JSON
    return $this->response->setJSON($productosLotes);
}

}

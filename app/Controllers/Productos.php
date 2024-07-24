<?php

namespace App\Controllers;

use App\Models\ProductoModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Productos extends BaseController
{
    protected $productoModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do not edit this line
        parent::initController($request, $response, $logger);

        // Initialize properties or models
        $this->productoModel = new ProductoModel();

        // Check if user is logged in
        if (!$this->session->get('isLoggedIn')) {
            return redirect()->to(base_url('public/login'))->send();
        }
    }

    public function index()
    {
        helper('url');
        $productos = $this->productoModel
            ->select('productos.*, tipo_producto.nombre as nombre_tipo, unidades_por_caja.tipo_unidad as tipo_unidad_caja, unidades_por_caja.unidades as unidades_caja, unidad_individual.unidadades_individuales as tipo_unidad_individual, nivel_prioridad.Nombre as nombre_prioridad, detalle_solicitud.Detalle as detalle')
            ->join('tipo_producto', 'tipo_producto.idtipoProducto = productos.idtipoProducto', 'left')
            ->join('unidades_por_caja', 'unidades_por_caja.idUnidadesPorCaja = productos.idUnidadesPorCaja', 'left')
            ->join('unidad_individual', 'unidad_individual.idUnidades_individuales = productos.idUnidades_individuales', 'left')
            ->join('nivel_prioridad', 'nivel_prioridad.idPrioridad = productos.idPrioridad', 'left')
            ->join('detalle_solicitud', 'detalle_solicitud.idDetalleSolicitados = productos.idDetalleSolicitados', 'left')
            ->get()
            ->getResult();

        return view('productos/index', ['productos' => $productos]);
    }

    public function create()
    {
        $data = [
            'tiposProducto' => $this->productoModel->getTiposProducto(),
            'unidadIndividual' => $this->productoModel->getUnidadIndividual(),
            'unidadesPorCaja' => $this->productoModel->getUnidadesPorCaja(),
            'prioridades' => $this->productoModel->getPrioridades(),
            'detallesSolicitados' => $this->productoModel->getDetallesSolicitados()
        ];

        return view('productos/create', $data);
    }

    public function store()
{
    $n_unidades_Caja = $this->request->getPost('n_unidades_Caja');
    $unidadesCaja = $n_unidades_Caja == 0 ? 0 : $this->request->getPost('unidades_caja');
    $unidades_extras = $this->request->getPost('unidades_extras');

    // Calcular el total
    $total = ($n_unidades_Caja * $unidadesCaja) + $unidades_extras;

    $productoID = $this->productoModel->saveProducto([
        'idtipoProducto' => $this->request->getPost('idtipoProducto'),
        'codigo_lote' => $this->request->getPost('codigo_lote'),
        'fecha_ingreso' => $this->request->getPost('fecha_ingreso'),
        'fecha_vencimiento' => $this->request->getPost('fecha_vencimiento'),
        'n_unidades_Caja' => $n_unidades_Caja,
        'idUnidadesPorCaja' => $this->request->getPost('idUnidadesPorCaja'),
        'idUnidades_individuales' => $this->request->getPost('idUnidades_individuales'),
        'unidades_extras' => $unidades_extras,
        'total' => $total, // Guardar el total calculado
        'idPrioridad' => $this->request->getPost('idPrioridad'),
        'idDetalleSolicitados' => $this->request->getPost('idDetalleSolicitados'),
        'estado' => $this->request->getPost('estado'),
    ]);

    return redirect()->to(site_url('productos'))->with('success', 'Producto agregado exitosamente.');
}


    public function edit($id)
    {
        $producto = $this->productoModel->find($id);
    
        if (!$producto) {
            return redirect()->to(site_url('productos'))->with('error', 'No se pudo encontrar el producto para editar.');
        }
    
        // Convertir el array a un objeto
        $producto = (object) $producto;
    
        // Asegurar que 'n_unidades_Caja' esté definido en el objeto $producto
        $producto->n_unidades_Caja = $producto->n_unidades_Caja ?? 0; // Definir un valor predeterminado si es NULL
    
        $data = [
            'producto' => $producto,
            'tiposProducto' => $this->productoModel->getTiposProducto(),
            'unidadIndividual' => $this->productoModel->getUnidadIndividual(),
            'unidadesPorCaja' => $this->productoModel->getUnidadesPorCaja(),
            'prioridades' => $this->productoModel->getPrioridades(),
            'detallesSolicitados' => $this->productoModel->getDetallesSolicitados()
        ];
    
        return view('productos/edit', $data);
    }

    public function update($id)
{
    $producto = $this->productoModel->find($id);

    if (!$producto) {
        return redirect()->to(site_url('productos'))->with('error', 'No se pudo encontrar el producto para actualizar.');
    }

    $n_unidades_Caja = $this->request->getPost('n_unidades_Caja');
    $unidadesCaja = $n_unidades_Caja == 0 ? 0 : $this->request->getPost('unidades_caja');
    $unidades_extras = $this->request->getPost('unidades_extras');

    // Calcular el total
    $total = ($n_unidades_Caja * $unidadesCaja) + $unidades_extras;

    $this->productoModel->updateProducto($id, [
        'idtipoProducto' => $this->request->getPost('idtipoProducto'),
        'codigo_lote' => $this->request->getPost('codigo_lote'),
        'fecha_ingreso' => $this->request->getPost('fecha_ingreso'),
        'fecha_vencimiento' => $this->request->getPost('fecha_vencimiento'),
        'n_unidades_Caja' => $n_unidades_Caja,
        'idUnidadesPorCaja' => $this->request->getPost('idUnidadesPorCaja'),
        'idUnidades_individuales' => $this->request->getPost('idUnidades_individuales'),
        'unidades_extras' => $unidades_extras,
        'total' => $total, // Guardar el total calculado
        'idPrioridad' => $this->request->getPost('idPrioridad'),
        'idDetalleSolicitados' => $this->request->getPost('idDetalleSolicitados'),
        'estado' => $this->request->getPost('estado'),
    ]);

    return redirect()->to(site_url('productos'))->with('success', 'Producto actualizado exitosamente.');
}

    public function delete($id)
    {
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
}

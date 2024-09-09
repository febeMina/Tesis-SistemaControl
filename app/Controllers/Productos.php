<?php

namespace App\Controllers;

use App\Models\ProductoModel;
use App\Models\ProductosMovimientosModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use CodeIgniter\Database\Exceptions\DatabaseException;

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
        /*
        $productos = $this->productoModel
            ->select('productos.*, tipo_producto.nombre as nombre_tipo, unidades_por_caja.tipo_unidad as tipo_unidad_caja, unidades_por_caja.unidades as unidades_caja, unidad_individual.unidadades_individuales as tipo_unidad_individual, nivel_prioridad.Nombre as nombre_prioridad, detalle_solicitud.Detalle as detalle')
            ->join('tipo_producto', 'tipo_producto.idtipoProducto = productos.idtipoProducto', 'left')
            ->join('unidades_por_caja', 'unidades_por_caja.idUnidadesPorCaja = productos.idUnidadesPorCaja', 'left')
            ->join('unidad_individual', 'unidad_individual.idUnidades_individuales = productos.idUnidades_individuales', 'left')
            ->join('nivel_prioridad', 'nivel_prioridad.idPrioridad = productos.idPrioridad', 'left')
            ->join('detalle_solicitud', 'detalle_solicitud.idDetalleSolicitados = productos.idDetalleSolicitados', 'left')
            ->get()
            ->getResult();
        */
        $productos = $this->productoModel
            ->select('productos.*, MIN(productos_lotes.fechaVencimiento) as fechaVencimientoProxima, SUM(productos_lotes.existenciaTotal) as totalExistencia')
            ->join('productos_lotes', 'productos.idProducto = productos_lotes.idProducto', 'left')
            ->groupBy('productos.idProducto')
            ->orderBy('fechaVencimientoProxima', 'ASC')
            ->get()
            ->getResult();

        return view('productos/index', ['productos' => $productos]);
    }

    public function create()
    {
        return view('productos/create');
    }

    public function store()
    {
        $data = [
            'descripcionProducto' => $this->request->getPost('descripcionProducto'),
            'estado' => 'Activo'
        ];

        $this->productoModel->save($data);

        return redirect()->to(site_url('productos'))->with('success', 'Producto agregado con éxito.');
    }

    public function edit($id)
    {
        $producto = $this->productoModel->find($id);
    
        if (!$producto) {
            return redirect()->to(site_url('productos'))->with('error', 'No se encontró el producto.');
        }
    
        $data = [
            'producto' => $producto
        ];
    
        return view('productos/edit', $data);
    }

    public function update($id)
    {
        $producto = $this->productoModel->find($id);

        if (!$producto) {
            return redirect()->to(site_url('productos'))->with('error', 'No se encontró el producto.');
        }

        $data = [
            'descripcionProducto' => $this->request->getPost('descripcionProducto')
        ];

        $this->productoModel->update($id, $data);

        return redirect()->to(site_url('productos'))->with('success', 'Producto actualizado con éxito.');
    }

    public function delete($id)
    {
        // Obtener el producto antes de eliminarlo
        $producto = $this->productoModel->find($id);
        if($producto) {
            try {
                $this->productoModel->delete($id);

                return redirect()->to('/productos')->with('success', 'Producto eliminado con éxito.');
            } catch (DatabaseException $e) {
                return redirect()->to('/productos')->with('error', 'No se puede eliminar el producto porque tiene lotes y movimientos asociados.');
            }
        } else {
            return redirect()->to(site_url('productos'))->with('error', 'No se encontró el producto.');
        }
    }

    public function estado($id)
    {
        $producto = $this->productoModel->find($id);

        if ($producto) {
            if ($producto['estado'] == 'Activo') {
                $this->productoModel->update($id, ['estado' => 'Inactivo']);
                $mensaje = 'El producto ha sido cambiado a Inactivo.';
            } else {
                $this->productoModel->update($id, ['estado' => 'Activo']);
                $mensaje = 'El producto ha sido cambiado a Activo.';
            }

            return redirect()->to('/productos')->with('success', $mensaje);
        } else {
            return redirect()->to('/productos')->with('error', 'No se encontró el producto.');
        }
    }

    public function historialMovimientos($id) {
        $productosMovimientos = new ProductosMovimientosModel();
        $movimientosProducto = $productosMovimientos
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
            ->where('productos.idProducto', $id)
            ->orderBy('productos_lotes.idProductoLote', 'ASC')
            ->orderBy('productos_movimientos.idProductoMovimiento', 'ASC')
            ->get()
            ->getResult();

        $productoModel = new ProductoModel();
        $producto = $productoModel->select('descripcionProducto')->where('idProducto', $id)->get()->getRow(); 

        $descripcionProducto = $producto ? $producto->descripcionProducto : 'Producto no encontrado';

        return view('productos/movimientosProducto', [
            'movimientosProducto' => $movimientosProducto,
            'descripcionProducto' => $descripcionProducto
        ]);
    }
}

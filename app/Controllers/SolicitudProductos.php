<?php

namespace App\Controllers;

use App\Models\ProductosIngresosModel;
use App\Models\ProductosIngresosDetalleModel;
use App\Models\ProductoLoteModel;
use App\Models\ProductoModel;
use App\Models\UnidadesIndividualesModel;
use App\Models\ProductosMovimientosModel;
use App\Models\UnidadesPorCajaModel;
use CodeIgniter\Controller;

class SolicitudProductos extends Controller
{
    protected $db;
    protected $request;

    public function __construct()
    {
        $this->db = db_connect();
        $this->request = service('request');
        $this->logger = \Config\Services::logger(); // Agregar logger
    }

    public function index()
    {
        $productoIngresoModel = new ProductosIngresosModel();

        $data = [
            'solicitudesIngreso' => $productoIngresoModel->findAll()
        ];

        return view('solicitud_productos/index', $data);
    }
    
    public function create()
    {
        return view('solicitud_productos/create');
    }

    public function store()
    {
        $productoIngresoModel = new ProductosIngresosModel();

        $data = [
            'fechaIngreso' => $this->request->getPost('fechaIngreso'),
            'responsableEntrega' => $this->request->getPost('responsableEntrega'),
            'responsableRecibe' => $this->request->getPost('responsableRecibe'),
            'estado' => 'Pendiente'
        ];

        if (!$this->validate([
            'fechaIngreso' => 'required|valid_date',
            'responsableEntrega' => 'required|string',
            'responsableRecibe' => 'required|string'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $productoIngresoModel->insert($data);
        $idProductoIngreso = $productoIngresoModel->insertID();

        return redirect()->to(site_url('solicitudproductos/edit/' . $idProductoIngreso));
    }
    
       public function edit($id)
    {
            $productoIngresoModel = new ProductosIngresosModel();
            $productoLoteModel = new ProductoLoteModel();
            $productoIngresoDetalleModel = new ProductosIngresosDetalleModel();
            $productoModel = new ProductoModel();
            $udmCajaModel = new UnidadesPorCajaModel();
            $udmIndividualModel = new UnidadesIndividualesModel();

            $productoIngreso = $productoIngresoModel->find($id);
            $productos = $productoModel->findAll();
            $udmCaja = $udmCajaModel->findAll();
            $udmIndividual = $udmIndividualModel->findAll();

            $detalles = $productoIngresoDetalleModel
                ->select('
                    productos_ingresos_detalle.*, 
                    productos_lotes.idProductoLote, 
                    productos_lotes.idProducto, 
                    productos.descripcionProducto,
                    productos_lotes.codigoLote, 
                    productos_lotes.fechaIngreso, 
                    productos_lotes.fechaVencimiento, 
                    productos_lotes.idUdmCaja, 
                    productos_lotes.existenciaCaja, 
                    productos_lotes.idUdmIndividual, 
                    productos_lotes.existenciaIndividual, 
                    udm_caja.nombreCaja as udmCaja, 
                    udm_individual.nombreIndividual as udmIndividual 
                ')
                ->join('productos_lotes', 'productos_lotes.idProductoLote = productos_ingresos_detalle.idProductoLote', 'left')
                ->join('productos', 'productos.idProducto = productos_lotes.idProducto', 'left')
                ->join('udm_caja', 'productos_lotes.idUdmCaja = udm_caja.idUdmCaja', 'left')
                ->join('udm_individual', 'productos_lotes.idUdmIndividual = udm_individual.idUdmIndividual', 'left')
                ->where('productos_ingresos_detalle.idProductoIngreso', $id)->findAll();

            return view('solicitud_productos/edit', [
                'productoIngreso' => $productoIngreso,
                'productos' => $productos,
                'udmCaja' => $udmCaja,
                'udmIndividual' => $udmIndividual,
                'detalles' => $detalles
            ]);
    }

       public function viewEdit($id)
    {
            $productoIngresoModel = new ProductosIngresosModel();
            $productoLoteModel = new ProductoLoteModel();
            $productoIngresoDetalleModel = new ProductosIngresosDetalleModel();
            $productoModel = new ProductoModel();
            $udmCajaModel = new UnidadesPorCajaModel();
            $udmIndividualModel = new UnidadesIndividualesModel();

            $productoIngreso = $productoIngresoModel->find($id);
            $productos = $productoModel->findAll();
            $udmCaja = $udmCajaModel->findAll();
            $udmIndividual = $udmIndividualModel->findAll();

            $detalles = $productoIngresoDetalleModel
                ->select('
                    productos_ingresos_detalle.*, 
                    productos_lotes.idProductoLote, 
                    productos_lotes.idProducto, 
                    productos.descripcionProducto,
                    productos_lotes.codigoLote, 
                    productos_lotes.fechaIngreso, 
                    productos_lotes.fechaVencimiento, 
                    productos_lotes.idUdmCaja, 
                    productos_lotes.existenciaCaja, 
                    productos_lotes.idUdmIndividual, 
                    productos_lotes.existenciaIndividual, 
                    udm_caja.nombreCaja as udmCaja, 
                    udm_individual.nombreIndividual as udmIndividual 
                ')
                ->join('productos_lotes', 'productos_lotes.idProductoLote = productos_ingresos_detalle.idProductoLote', 'left')
                ->join('productos', 'productos.idProducto = productos_lotes.idProducto', 'left')
                ->join('udm_caja', 'productos_lotes.idUdmCaja = udm_caja.idUdmCaja', 'left')
                ->join('udm_individual', 'productos_lotes.idUdmIndividual = udm_individual.idUdmIndividual', 'left')
                ->where('productos_ingresos_detalle.idProductoIngreso', $id)->findAll();

            return view('solicitud_productos/viewEdit', [
                'productoIngreso' => $productoIngreso,
                'productos' => $productos,
                'udmCaja' => $udmCaja,
                'udmIndividual' => $udmIndividual,
                'detalles' => $detalles
            ]);
    }

    public function update($id)
    {
        $productoIngresoModel = new ProductosIngresosModel();
        
        $data = [
            'fechaIngreso' => $this->request->getPost('fechaIngreso'),
            'responsableEntrega' => $this->request->getPost('responsableEntrega'),
            'responsableRecibe' => $this->request->getPost('responsableRecibe')
        ];

        $productoIngresoModel->update($id, $data);

        return redirect()->to(site_url('solicitudproductos/edit/' . $id))->with('success', 'Información de ingreso actualizada con éxito.');
    }

    public function storeLote()
    {
        $productoLoteModel = new ProductoLoteModel();
        $productoIngresoDetalleModel = new ProductosIngresosDetalleModel();
        $productoMovimientosModel = new ProductosMovimientosModel();

        $dataLote = [
            'idProducto' => $this->request->getPost('idProducto'),
            'codigoLote' => $this->request->getPost('codigoLote'),
            'fechaIngreso' => $this->request->getPost('fechaIngreso'),
            'fechaVencimiento' => $this->request->getPost('fechaVencimiento'),
            'idUdmCaja' => $this->request->getPost('idUdmCaja'),
            'existenciaCaja' => $this->request->getPost('existenciaCaja'),
            'idUdmIndividual' => $this->request->getPost('idUdmIndividual'),
            'existenciaIndividual' => $this->request->getPost('existenciaIndividual'),
            'existenciaTotal' => 0,
        ];

        $productoLoteModel->insert($dataLote);

        $idProductoLote = $productoLoteModel->getInsertID();
        /*
        $dataMovimiento = [
            'idProductoLote'           => $idProductoLote,
            'tipoMovimiento'           => 'Entrada',
            'descripcionMovimiento'    => 'Existencia ingresada desde solicitud de ingreso N°: ' . $this->request->getPost('idProductoIngreso'),
            'fechaMovimiento'          => date('Y-m-d'),  
            'existenciaTotalAntes'     => 0,              
            'existenciaTotalMovimiento'=> $dataLote['existenciaTotal'],
            'existenciaTotalDespues'   => $dataLote['existenciaTotal']
        ];

        $productoMovimientosModel->insert($dataMovimiento);
        */
        $dataDetalle = [
            'idProductoIngreso' => $this->request->getPost('idProductoIngreso'),
            'idProductoLote' => $idProductoLote,
            'existenciaTotal' => $this->request->getPost('existenciaTotal'),
        ];

        $productoIngresoDetalleModel->insert($dataDetalle);

        return redirect()->to(site_url('solicitudproductos/edit/' . $this->request->getPost('idProductoIngreso')))->with('success', 'Lote de producto ingresado con éxito.');
    }

    public function delete($id)
    {
        $productoLoteModel = new ProductoLoteModel();
        $productoIngresoDetalleModel = new ProductosIngresosDetalleModel();

        $detalle = $productoIngresoDetalleModel->find($id);
        $idProductoIngreso = $detalle['idProductoIngreso'];
        if($detalle) {
            try {
                $productoIngresoDetalleModel->delete($id);
                $productoLoteModel->delete($detalle['idProductoLote']);

                return redirect()->to('solicitudproductos/edit/' . $idProductoIngreso)->with('success', 'Detalle de ingreso eliminado con éxito.');
            } catch (DatabaseException $e) {
                return redirect()->to('solicitudproductos/edit/' . $idProductoIngreso)->with('error', 'No se puede eliminar el detalle del ingreso porque ya se generaron movimientos.');
            }
        } else {
            return redirect()->to(site_url('solicitudproductos/edit/' . $idProductoIngreso))->with('error', 'No se encontró el detalle del ingreso.');
        }
    }

    public function finalizar()
    {
        $idProductoIngreso = $this->request->getPost('idProductoIngreso');

        $productoIngresoModel = new ProductosIngresosModel();
        $productoIngresoDetalleModel = new ProductosIngresosDetalleModel();
        $productoLoteModel = new ProductoLoteModel();
        $productoMovimientoModel = new ProductosMovimientosModel();

        // Obtener los detalles del ingreso
        $detalles = $productoIngresoDetalleModel->where('idProductoIngreso', $idProductoIngreso)->findAll();

        // Iterar sobre cada detalle del ingreso
        $n = 0;
        foreach ($detalles as $detalle) {
            $n++;
            $idProductoLote = $detalle['idProductoLote'];
            $existenciaTotalIngreso = $detalle['existenciaTotal'];

            // Actualizar existenciaTotal en productos_lotes
            $productoLoteModel->update($idProductoLote, [
                'existenciaTotal' => $existenciaTotalIngreso
            ]);

            // Insertar en productos_movimientos
            $dataMovimiento = [
                'idProductoLote' => $idProductoLote,
                'tipoMovimiento' => 'Entrada',
                'descripcionMovimiento' => 'Existencia ingresada desde n° ingreso: ' . $idProductoIngreso,
                'fechaMovimiento' => date('Y-m-d H:i:s'),
                'existenciaTotalAntes' => 0, // Asumiendo que antes era 0, puedes ajustar si necesitas obtener el valor real
                'existenciaTotalMovimiento' => $existenciaTotalIngreso,
                'existenciaTotalDespues' => $existenciaTotalIngreso
            ];
            $productoMovimientoModel->insert($dataMovimiento);
        }

        if($n == 0) {
            return redirect()->to(site_url('solicitudproductos/edit/' . $idProductoIngreso))->with('error', 'No puede finalizar el ingreso sin agregar productos.');
        } else {
            // Actualizar estado de productos_ingresos a "Finalizado"
            $productoIngresoModel->update($idProductoIngreso, [
                'estado' => 'Finalizado'
            ]);

            // Redireccionar con mensaje de éxito
            return redirect()->to(site_url('solicitudproductos'))->with('success', 'Ingreso finalizado con éxito.');
        }

    }

    public function anular($idProductoIngreso)
    {
        $productoIngresoModel = new ProductosIngresosModel();
        $productoIngresoDetalleModel = new ProductosIngresosDetalleModel();
        $productoLoteModel = new ProductoLoteModel();

        // Obtener los detalles del ingreso
        $detalles = $productoIngresoDetalleModel->where('idProductoIngreso', $idProductoIngreso)->findAll();

        // Eliminar los lotes asociados
        foreach ($detalles as $detalle) {
            $idProductoLote = $detalle['idProductoLote'];

            $productoIngresoDetalleModel->delete($detalle['idProductoIngresoDetalle']);
            // Eliminar el lote en productos_lotes
            $productoLoteModel->delete($idProductoLote);
        }

        // Actualizar el estado de productos_ingresos a "Anulado"
        $productoIngresoModel->update($idProductoIngreso, [
            'estado' => 'Anulado'
        ]);
        // Redireccionar con un mensaje de éxito
        return redirect()->to(site_url('solicitudproductos'))->with('success', 'Ingreso anulada con éxito.');
    }
    
    
}

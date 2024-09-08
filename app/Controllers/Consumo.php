<?php

namespace App\Controllers;

use App\Models\ProductosRequisicionModel;
use App\Models\ProductosRequisicionDetalleModel;
use App\Models\ProductoLoteModel;
use App\Models\ProductoModel;
use App\Models\UnidadesIndividualesModel;
use App\Models\ProductosMovimientosModel;
use App\Models\UnidadesPorCajaModel;
use App\Models\ProductoMovimientoModel;
use CodeIgniter\Controller;

class Consumo extends Controller
{
    public function index()
    {
        $productoRequisicionModel = new ProductosRequisicionModel();

        $data = [
            'consumoSalida' => $productoRequisicionModel->findAll()
        ];

        return view('Consumo/index', $data);
    }

    public function create()
    {
        return view('Consumo/create');
    }

    public function store()
    {
        $productoRequisicionModel = new ProductosRequisicionModel();

        $data = [
            'fechaRequisicion' => $this->request->getPost('fechaRequisicion'),
            'comidaPreparar' => $this->request->getPost('comidaPreparar'),
            'responsableEntrega' => $this->request->getPost('responsableEntrega'),
            'responsableRecibe' => $this->request->getPost('responsableRecibe'),
            'estado' => 'Pendiente'
        ];

        if (!$this->validate([
            'fechaRequisicion' => 'required|valid_date',
            'comidaPreparar' => 'required|string',
            'responsableEntrega' => 'required|string',
            'responsableRecibe' => 'required|string'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $productoRequisicionModel->insert($data);
        $idProductoRequisicion = $productoRequisicionModel->insertID();

        return redirect()->to(site_url('consumo/edit/' . $idProductoRequisicion));
    }
    
       public function edit($id)
    {
            $productoRequisicionModel = new ProductosRequisicionModel();
            $productoLoteModel = new ProductoLoteModel();
            $productoRequisicionDetalleModel = new ProductosRequisicionDetalleModel();
            $productoModel = new ProductoModel();
            $udmCajaModel = new UnidadesPorCajaModel();
            $udmIndividualModel = new UnidadesIndividualesModel();

            $productoRequisicion = $productoRequisicionModel->find($id);
            $productos = $productoModel->findAll();
            $udmCaja = $udmCajaModel->findAll();
            $udmIndividual = $udmIndividualModel->findAll();

            $detalles = $productoRequisicionDetalleModel
                ->select('
                    productos_requisicion_detalle.*, 
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
                ->join('productos_lotes', 'productos_lotes.idProductoLote = productos_requisicion_detalle.idProductoLote', 'left')
                ->join('productos', 'productos.idProducto = productos_lotes.idProducto', 'left')
                ->join('udm_caja', 'productos_lotes.idUdmCaja = udm_caja.idUdmCaja', 'left')
                ->join('udm_individual', 'productos_lotes.idUdmIndividual = udm_individual.idUdmIndividual', 'left')
                ->where('productos_requisicion_detalle.idProductoRequisicion', $id)->findAll();

            // Obtener productos con lotes que tienen existenciaTotal mayor a 0
            $productosConExistencia = $productoLoteModel
                ->select('productos_lotes.idProductoLote, productos.descripcionProducto, productos_lotes.codigoLote, productos_lotes.existenciaTotal, productos_lotes.fechaVencimiento')
                ->join('productos', 'productos.idProducto = productos_lotes.idProducto')
                ->where('productos_lotes.existenciaTotal >', 0)
                ->orderBy('productos.idProducto')
                ->findAll();

            return view('consumo/edit', [
                'productoRequisicion' => $productoRequisicion,
                'productos' => $productos,
                'udmCaja' => $udmCaja,
                'udmIndividual' => $udmIndividual,
                'detalles' => $detalles,
                'productosConExistencia' => $productosConExistencia
            ]);
    }

       public function viewEdit($id)
    {
            $productoRequisicionModel = new ProductosRequisicionModel();
            $productoLoteModel = new ProductoLoteModel();
            $productoRequisicionDetalleModel = new ProductosRequisicionDetalleModel();
            $productoModel = new ProductoModel();
            $udmCajaModel = new UnidadesPorCajaModel();
            $udmIndividualModel = new UnidadesIndividualesModel();

            $productoRequisicion = $productoRequisicionModel->find($id);
            $productos = $productoModel->findAll();
            $udmCaja = $udmCajaModel->findAll();
            $udmIndividual = $udmIndividualModel->findAll();

            $detalles = $productoRequisicionDetalleModel
                ->select('
                    productos_requisicion_detalle.*, 
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
                ->join('productos_lotes', 'productos_lotes.idProductoLote = productos_requisicion_detalle.idProductoLote', 'left')
                ->join('productos', 'productos.idProducto = productos_lotes.idProducto', 'left')
                ->join('udm_caja', 'productos_lotes.idUdmCaja = udm_caja.idUdmCaja', 'left')
                ->join('udm_individual', 'productos_lotes.idUdmIndividual = udm_individual.idUdmIndividual', 'left')
                ->where('productos_requisicion_detalle.idProductoRequisicion', $id)->findAll();

            // Obtener productos con lotes que tienen existenciaTotal mayor a 0
            $productosConExistencia = $productoLoteModel
                ->select('productos_lotes.idProductoLote, productos.descripcionProducto, productos_lotes.codigoLote, productos_lotes.existenciaTotal')
                ->join('productos', 'productos.idProducto = productos_lotes.idProducto')
                ->where('productos_lotes.existenciaTotal >', 0)
                ->orderBy('productos.idProducto')
                ->findAll();

            return view('consumo/viewEdit', [
                'productoRequisicion' => $productoRequisicion,
                'productos' => $productos,
                'udmCaja' => $udmCaja,
                'udmIndividual' => $udmIndividual,
                'detalles' => $detalles,
                'productosConExistencia' => $productosConExistencia
            ]);
    }

    public function update($id)
    {
        $productoRequisicionModel = new ProductosRequisicionModel();
        
        $data = [
            'fechaRequisicion' => $this->request->getPost('fechaRequisicion'),
            'comidaPreparar' => $this->request->getPost('comidaPreparar'),
            'responsableEntrega' => $this->request->getPost('responsableEntrega'),
            'responsableRecibe' => $this->request->getPost('responsableRecibe')
        ];

        $productoRequisicionModel->update($id, $data);

        return redirect()->to(site_url('consumo/edit/' . $id))->with('success', 'Información de la requisición de salida actualizada con éxito.');
    }

    public function storeLote()
    {
        if ($this->request->getMethod() === 'post') {
            $productoRequisicionDetalleModel = new ProductosRequisicionDetalleModel();
            $productoLoteModel = new ProductoLoteModel();

            $idProductoRequisicion = $this->request->getPost('idProductoRequisicion');
            $idProductoLote = $this->request->getPost('idProducto');
            $existenciaTotal = $this->request->getPost('existenciaTotal');

            // Obtener la existencia actual del lote
            $productoLote = $productoLoteModel->find($idProductoLote);
            $existenciaTotalLote = $productoLote['existenciaTotal'];

            // Verificar si la existencia solicitada es válida
            if ($existenciaTotal > $existenciaTotalLote) {
                // Guardar el error en la sesión flash
                return redirect()->back()->with('error', 'La cantidad solicitada excede la existencia del lote.');
            }

            // Verificar si ya existe un registro para este producto_lote en la requisición
            $detalleExistente = $productoRequisicionDetalleModel
                ->where('idProductoRequisicion', $idProductoRequisicion)
                ->where('idProductoLote', $idProductoLote)
                ->first();

            if ($detalleExistente) {
                // Si ya existe, sumamos la cantidad nueva a la existente
                $nuevaCantidad = $detalleExistente['existenciaTotal'] + $existenciaTotal;

                // Validar que la nueva cantidad no exceda la existencia del lote
                if ($nuevaCantidad > $existenciaTotalLote) {
                    return redirect()->back()->with('error', 'La cantidad total solicitada excede la existencia del lote.');
                }

                // Actualizar el registro existente con la nueva cantidad
                $productoRequisicionDetalleModel->update($detalleExistente['idProductoRequisicionDetalle'], [
                    'existenciaTotal' => $nuevaCantidad
                ]);

                return redirect()->to(site_url('consumo/edit/' . $idProductoRequisicion))
                                 ->with('success', 'Lote para consumo actualizado con éxito.');
            } else {
                // Si no existe, insertar un nuevo registro
                $data = [
                    'idProductoRequisicion' => $idProductoRequisicion,
                    'idProductoLote' => $idProductoLote,
                    'existenciaTotal' => $existenciaTotal
                ];

                $productoRequisicionDetalleModel->insert($data);

                return redirect()->to(site_url('consumo/edit/' . $idProductoRequisicion))
                                 ->with('success', 'Lote para consumo agregado con éxito.');
            }
        }

        return redirect()->to(site_url('consumo'));
    }


    public function delete($id)
    {
        $productoRequisicionDetalleModel = new ProductosRequisicionDetalleModel();

        $detalle = $productoRequisicionDetalleModel->find($id);
        $idProductoRequisicion = $detalle['idProductoRequisicion'];
        if($detalle) {
            try {
                $productoRequisicionDetalleModel->delete($id);

                return redirect()->to('consumo/edit/' . $idProductoRequisicion)->with('success', 'Detalle de consumo eliminado con éxito.');
            } catch (DatabaseException $e) {
                return redirect()->to('consumo/edit/' . $idProductoRequisicion)->with('error', 'No se puede eliminar el detalle del consumo porque ya se generaron movimientos.');
            }
        } else {
            return redirect()->to(site_url('consumo/edit/' . $idProductoRequisicion))->with('error', 'No se encontró el detalle del ingreso.');
        }
    }

    public function finalizar()
    {
        if ($this->request->getMethod() === 'post') {
            $idProductoRequisicion = $this->request->getPost('idProductoRequisicion');

            $productoRequisicionModel = new ProductosRequisicionModel();
            $productoRequisicionDetalleModel = new ProductosRequisicionDetalleModel();
            $productoLoteModel = new ProductoLoteModel();
            $productoMovimientoModel = new ProductosMovimientosModel();

            // Obtener los detalles de la requisición
            $detalles = $productoRequisicionDetalleModel
                ->where('idProductoRequisicion', $idProductoRequisicion)
                ->findAll();

            $n = 0;
            foreach ($detalles as $detalle) {
                $n++;
                $idProductoLote = $detalle['idProductoLote'];
                $existenciaTotalDetalle = $detalle['existenciaTotal'];

                // Obtener el lote actual
                $productoLote = $productoLoteModel->find($idProductoLote);
                $existenciaTotalLoteAntes = $productoLote['existenciaTotal'];

                // Actualizar la existencia del lote
                $nuevaExistenciaTotal = $existenciaTotalLoteAntes - $existenciaTotalDetalle;

                $productoLoteModel->update($idProductoLote, [
                    'existenciaTotal' => $nuevaExistenciaTotal
                ]);

                // Insertar el movimiento en productos_movimientos
                $dataMovimiento = [
                    'idProductoLote' => $idProductoLote,
                    'tipoMovimiento' => 'Salida',
                    'descripcionMovimiento' => 'Salida registrada por requisición de consumo N°' . $idProductoRequisicion,
                    'fechaMovimiento' => date('Y-m-d H:i:s'),
                    'existenciaTotalAntes' => $existenciaTotalLoteAntes,
                    'existenciaTotalMovimiento' => $existenciaTotalDetalle,
                    'existenciaTotalDespues' => $nuevaExistenciaTotal
                ];

                $productoMovimientoModel->insert($dataMovimiento);
            }

            if($n == 0) {
                return redirect()->to(site_url('consumo/edit/'. $idProductoRequisicion))
                    ->with('error', 'No puede finalizar la requisición sin agregar productos.');
            } else {
                // Actualizar el estado de la requisición a 'Finalizado'
                $productoRequisicionModel->update($idProductoRequisicion, [
                    'estado' => 'Finalizado'
                ]);

                // Redirigir con un mensaje de éxito
                return redirect()->to(site_url('consumo'))
                                 ->with('success', 'Requisición de consumo finalizada con éxito.');
            }
        }

        return redirect()->to(site_url('consumo'));
    }

    public function anular($idProductoRequisicion)
    {
        $productoRequisicionModel = new ProductosRequisicionModel();

        // Actualizar el estado de productos_ingresos a "Anulado"
        $productoRequisicionModel->update($idProductoRequisicion, [
            'estado' => 'Anulado'
        ]);
        // Redireccionar con un mensaje de éxito
        return redirect()->to(site_url('consumo'))->with('success', 'Requisición de consumo anulada con éxito.');
    }
}

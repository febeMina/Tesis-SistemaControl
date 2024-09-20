<?php namespace App\Controllers;

require_once APPPATH . 'Libraries/fpdf186/fpdf.php';

class ReporteSolicitudProductos extends BaseController
{
    protected $productosRequisicionModel;
    protected $productoRequisicionDetalleModel;

    public function __construct()
    {
        $this->productosRequisicionModel = new \App\Models\ProductosRequisicionModel();
        $this->productoRequisicionDetalleModel = new \App\Models\ProductosRequisicionDetalleModel(); // Agrega este modelo
    }

    public function index()
    {
        $filters = $this->request->getGet();
        $fecha_solicitud = $filters['fecha_solicitud'] ?? '';

        $data = [];
        if (!empty($fecha_solicitud)) {
            $currentPage = $this->request->getGet('page') ?? 1; // Página actual, por defecto es 1
            $perPage = 10; // Cantidad de elementos por página

            $total = $this->productosRequisicionModel->getRequisicionCountByFecha($fecha_solicitud); // Método para contar total de resultados
            $data['solicitudes'] = $this->productosRequisicionModel->getRequisicionByFecha($fecha_solicitud, $perPage, $currentPage); // Obtener datos con paginación
            
            $pager = \Config\Services::pager();
            $pager->makeLinks($currentPage, $perPage, $total);
            $data['pager'] = $pager;
        }

        $data['filters'] = $filters;
        return view('reportes/reporte-solicitud-productos', $data);
    }

    public function generarReporte()
    {
        $fechaSolicitud = $this->request->getGet('fecha_solicitud');

        // Obtener datos para el reporte
        $solicitudes = $this->productosRequisicionModel->getRequisicionByFecha($fechaSolicitud);

        // Crear el PDF
        $pdf = new \FPDF('P', 'mm', 'A4'); // P para vertical, A4 tamaño carta
        foreach ($solicitudes as $solicitud) {
            
            $pdf->AddPage();
            $pdf->Ln(25);
            $pdf->SetFont('Arial', 'B', 12);
    
            $pdf->Cell(0, 10, utf8_decode('REQUISICIÓN DE ALIMENTOS'), 0, 1, 'C');
            $pdf->Ln(10);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 10, utf8_decode('FECHA: ' . $solicitud['fechaRequisicion']), 0, 1);
            $pdf->Cell(0, 10, utf8_decode('COMIDA A PREPARAR: ' . $solicitud['comidaPreparar']), 0, 1);
            $pdf->Ln(5);

            $pdf->Cell(80, 10, utf8_decode('DESCRIPCIÓN'), 1);
            $pdf->Cell(50, 10, utf8_decode('FECHA DE VENCIMIENTO'), 1);
            $pdf->Cell(40, 10, utf8_decode('CANTIDAD'), 1);
            $pdf->Ln();
            
            $pdf->SetFont('Arial', '', 10);
            
            // Verificar si la clave 'idProductoRequisicion' existe en el array
            if (isset($solicitud['idProductoRequisicion'])) {
                $detalles = $this->productoRequisicionDetalleModel
                    ->where('idProductoRequisicion', $solicitud['idProductoRequisicion'])
                    ->findAll();
                
                foreach ($detalles as $detalle) {
                    $pdf->Cell(80, 10, utf8_decode($detalle['producto_nombre']), 1);
                    $pdf->Cell(50, 10, utf8_decode($detalle['fecha_vencimiento']), 1);
                    $pdf->Cell(40, 10, utf8_decode($detalle['cantidad']), 1);
                    $pdf->Ln();
                }
            } else {
                // Manejo del caso donde 'idProductoRequisicion' no existe
                log_message('error', 'La clave idProductoRequisicion no está presente en el array.');
                $pdf->Cell(0, 10, utf8_decode('ERROR: idProductoRequisicion no disponible'), 0, 1, 'C');
            }

            // Espacios para firma
            $pdf->Ln(60);

            // Firma de Recibido y Entregado en la misma fila
            $pdf->SetFont('Arial', '', 10);

            // Firma de Recibido
            $pdf->Cell(95, 10, utf8_decode('Firma de Recibido: ______________________'), 0, 0, 'L');
            $pdf->Cell(95, 10, utf8_decode('Firma de Entregado: ______________________'), 0, 1, 'R');

            // Nombre de responsable recibir
            $pdf->Cell(95, 10, utf8_decode($solicitud['responsableRecibe']), 0, 0, 'C');
            // Nombre de responsable entregar
            $pdf->Cell(95, 10, utf8_decode($solicitud['responsableEntrega']), 0, 1, 'C');
        }

        $pdf->Output('D', 'reporte_solicitud_productos.pdf');
    }
}

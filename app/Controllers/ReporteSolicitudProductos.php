<?php namespace App\Controllers;

require_once APPPATH . 'Libraries/fpdf186/fpdf.php';

class ReporteSolicitudProductos extends BaseController
{
    protected $solicitudProductosModel;

    public function __construct()
    {
        $this->solicitudProductosModel = new \App\Models\SolicitudProductosModel();
    }

    public function index()
    {
        $filters = $this->request->getGet();
        $fechaSolicitud = $filters['fecha_solicitud'] ?? null;

        // Si la fecha de solicitud es un solo valor, convertirlo en un rango de fechas
        if ($fechaSolicitud) {
            $solicitudes = $this->solicitudProductosModel->getReporteSolicitudProductos($fechaSolicitud, $fechaSolicitud);
        } else {
            $solicitudes = $this->solicitudProductosModel->getReporteSolicitudProductos();
        }

        // Configurar la paginación (si es necesario)
        $pager = \Config\Services::pager();

        echo view('reportes/reporte-solicitud-productos', [
            'filters' => $filters,
            'solicitudes' => $solicitudes,
            'pager' => $pager,
        ]);
    }

    public function generarReporte()
    {
        $fechaSolicitud = $this->request->getGet('fecha_solicitud');

        // Obtener datos para el reporte
        $solicitudes = $this->solicitudProductosModel->getReporteSolicitudProductos($fechaSolicitud, $fechaSolicitud);

        // Crear el PDF
        $pdf = new \FPDF('P', 'mm', 'A4'); // P para vertical, A4 tamaño carta
        foreach ($solicitudes as $solicitud) {
            
            $pdf->AddPage();
            $pdf->Ln(25);
            $pdf->SetFont('Arial', 'B', 12);
    
            $pdf->Cell(0, 10, utf8_decode('REQUISICIÓN DE ALIMENTOS'), 0, 1, 'C');
            $pdf->Ln(10);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 10, utf8_decode('FECHA: ' . $solicitud['Fecha_solicitud']), 0, 1);
            $pdf->Cell(0, 10, utf8_decode('COMIDA A PREPARAR: ' . $solicitud['Comida_a_preparar']), 0, 1);
            $pdf->Ln(5);

            $pdf->Cell(80, 10, utf8_decode('DESCRIPCIÓN'), 1);
            $pdf->Cell(50, 10, utf8_decode('FECHA DE VENCIMIENTO'), 1);
            $pdf->Cell(40, 10, utf8_decode('CANTIDAD'), 1);
            $pdf->Ln();
            
            $pdf->SetFont('Arial', '', 10);
            $detalles = $this->solicitudProductosModel->getDetallesSolicitud($solicitud['idSolicitudProductos']);
            foreach ($detalles as $detalle) {
                $pdf->Cell(80, 10, utf8_decode($detalle['producto_nombre']), 1);
                $pdf->Cell(50, 10, utf8_decode($detalle['fecha_vencimiento']), 1);
                $pdf->Cell(40, 10, utf8_decode($detalle['cantidad']), 1);
                $pdf->Ln();
            }

            // Espacios para firma
            $pdf->Ln(60);

            // Firma de Recibido y Entregado en la misma fila
            $pdf->SetFont('Arial', '', 10);

            // Firma de Recibido
            $pdf->Cell(95, 10, utf8_decode('Firma de Recibido: ______________________'), 0, 0, 'L');
            $pdf->Cell(95, 10, utf8_decode('Firma de Entregado: ______________________'), 0, 1, 'R');

            // Nombre de responsable recibir
            $pdf->Cell(95, 10, utf8_decode($solicitud['responsable_recibir']), 0, 0, 'C');
            // Nombre de responsable entregar
            $pdf->Cell(95, 10, utf8_decode($solicitud['responsable_entrega']), 0, 1, 'C');
        }

        $pdf->Output('D', 'reporte_solicitud_productos.pdf');
    }
}

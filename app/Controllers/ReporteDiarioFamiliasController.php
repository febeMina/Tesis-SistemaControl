<?php namespace App\Controllers;

require_once APPPATH . 'Libraries/fpdf186/fpdf.php';

class ReporteDiarioFamiliasController extends BaseController
{
    protected $registroDiarioModel;
    protected $detalleAsistenciaModel;

    public function __construct()
    {
        $this->registroDiarioModel = new \App\Models\RegistroDiarioModel();
        $this->detalleAsistenciaModel = new \App\Models\DetalleAsistenciaModel();
    }

    public function index()
    {
        $filters = $this->request->getGet();
        $fechaBeneficio = $filters['fecha'] ?? null;

        // Si la fecha de beneficio es un solo valor, convertirlo en un rango de fechas
        if ($fechaBeneficio) {
            $registros = $this->registroDiarioModel
                ->select('registro_diario.idRegistroDiario, registro_diario.fecha, COALESCE(SUM(detalle_asistencia.Total), 0) as familiasBeneficiadas')
                ->join('detalle_asistencia', 'detalle_asistencia.idRegistroDiario = registro_diario.idRegistroDiario', 'left')
                ->where('registro_diario.fecha', $fechaBeneficio)
                ->groupBy('registro_diario.idRegistroDiario, registro_diario.fecha')
                ->findAll();
        } else {
            $registros = $this->registroDiarioModel
                ->select('registro_diario.idRegistroDiario, registro_diario.fecha, COALESCE(SUM(detalle_asistencia.Total), 0) as familiasBeneficiadas')
                ->join('detalle_asistencia', 'detalle_asistencia.idRegistroDiario = registro_diario.idRegistroDiario', 'left')
                ->groupBy('registro_diario.idRegistroDiario, registro_diario.fecha')
                ->findAll();
        }

        echo view('reportes/reporte-diario-familias', [
            'filters' => $filters,
            'registros' => $registros,
        ]);
    }

    public function generarReporte()
    {
        $fechaBeneficio = $this->request->getGet('fecha');

        // Obtener datos para el reporte
        $registros = $this->registroDiarioModel
            ->select('registro_diario.idRegistroDiario, registro_diario.fecha, COALESCE(SUM(detalle_asistencia.Total), 0) as familiasBeneficiadas')
            ->join('detalle_asistencia', 'detalle_asistencia.idRegistroDiario = registro_diario.idRegistroDiario', 'left')
            ->where('registro_diario.fecha', $fechaBeneficio)
            ->groupBy('registro_diario.idRegistroDiario, registro_diario.fecha')
            ->findAll();

        // Crear el PDF
        $pdf = new \FPDF('P', 'mm', 'A4'); // P para vertical, A4 tamaño carta
        foreach ($registros as $registro) {
            
            $pdf->AddPage();
            $pdf->Ln(25);
            $pdf->SetFont('Arial', 'B', 12);
    
            $pdf->Cell(0, 10, utf8_decode('REPORTE DIARIO DE FAMILIAS BENEFICIADAS'), 0, 1, 'C');
            $pdf->Ln(10);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 10, utf8_decode('FECHA: ' . $registro['fecha']), 0, 1);
            $pdf->Ln(5);

            $pdf->Cell(60, 10, utf8_decode('ID REGISTRO'), 1);
            $pdf->Cell(80, 10, utf8_decode('TOTAL FAMILIAS BENEFICIADAS'), 1);
            $pdf->Ln();
            
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(60, 10, utf8_decode($registro['idRegistroDiario']), 1);
            $pdf->Cell(80, 10, utf8_decode($registro['familiasBeneficiadas']), 1);
            $pdf->Ln();

            // Espacios para firma
            $pdf->Ln(60);

            // Firma de Recibido y Entregado en la misma fila
            $pdf->SetFont('Arial', '', 10);

            // Firma de Recibido
            $pdf->Cell(95, 10, utf8_decode('Firma de Recibido: ______________________'), 0, 0, 'L');
            $pdf->Cell(95, 10, utf8_decode('Firma de Entregado: ______________________'), 0, 1, 'R');
        }

        $pdf->Output('D', 'reporte_diario_familias.pdf');
    }
}

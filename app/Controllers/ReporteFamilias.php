<?php namespace App\Controllers;

require_once APPPATH . 'Libraries/fpdf186/fpdf.php';

class ReporteFamilias extends BaseController
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
        $data['registros'] = $this->registroDiarioModel
            ->select('registro_diario.idRegistroDiario, registro_diario.fecha, 
                      COALESCE(SUM(detalle_asistencia.total), 0) as familiasBeneficiadas')
            ->join('detalle_asistencia', 'detalle_asistencia.idRegistroDiario = registro_diario.idRegistroDiario', 'left')
            ->groupBy('registro_diario.idRegistroDiario')
            ->findAll();

        return view('reporte_familias/index', ['registros' => []]); // Asegúrate de que la vista existe
    }

    public function generarReporte()
    {
        $idRegistroDiario = $this->request->getGet('idRegistroDiario');
    
        // Obtener datos para el reporte
        $registros = $this->registroDiarioModel
            ->select('registro_diario.idRegistroDiario, registro_diario.fecha, COALESCE(SUM(detalle_asistencia.total), 0) as familiasBeneficiadas')
            ->join('detalle_asistencia', 'detalle_asistencia.idRegistroDiario = registro_diario.idRegistroDiario', 'left')
            ->where('registro_diario.idRegistroDiario', $idRegistroDiario)
            ->groupBy('registro_diario.idRegistroDiario, registro_diario.fecha')
            ->findAll();
    
        // Crear el PDF
        $pdf = new \FPDF('P', 'mm', 'A4');
        $pdf->SetMargins(20, 20, 20);
        $pdf->SetAutoPageBreak(true, 20);
    
        foreach ($registros as $registro) {
            $pdf->AddPage();
    // Agregar la fecha de generación del reporte (esquina superior derecha)
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 10, 'Fecha de generacion: ' . date('d/m/Y'), 0, 1, 'R');
    
    // Agregar el encabezado "CENTRO ESCOLAR BARRIO LAS DELICIAS" (centro)
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(0, 10, utf8_decode('CENTRO ESCOLAR BARRIO LAS DELICIAS'), 0, 1, 'L');
    $pdf->Ln(5); // Espacio adicional
            // Encabezado
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 10, utf8_decode('REPORTE DIARIO DE FAMILIAS BENEFICIADAS'), 0, 1, 'C');
            $pdf->Ln(10);
    
            // Datos generales
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(40, 10, utf8_decode('Fecha:'), 0, 0);
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(45, 10, utf8_decode($registro->fecha), 0, 1);
            $pdf->Ln(5);
    
            // Tabla de familias beneficiadas
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(80, 10, utf8_decode('Total familias beneficiadas'), 1, 0, 'C');
            $pdf->Ln();
    
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(80, 10, utf8_decode($registro->familiasBeneficiadas), 1, 0, 'C');
            $pdf->Ln(15);
    
            // Detalles de asistencia (si existen)
            $detalles = $this->detalleAsistenciaModel
                ->select('detalle_asistencia.*, docente.nombreCompleto as nombre_docente, grado.nombre as nombre_grado')
                ->join('docente', 'detalle_asistencia.idDocente = docente.idDocente', 'left')
                ->join('grado', 'docente.idGrado = grado.idGrado', 'left')
                ->where('detalle_asistencia.idRegistroDiario', $registro->idRegistroDiario)
                ->asObject()
                ->findAll();

            if (!empty($detalles)) {
                // Encabezado de la tabla de detalles
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(0, 10, utf8_decode('Detalles del registro'), 0, 1, 'L');
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(30, 10, utf8_decode('Grado'), 1, 0, 'C');
                $pdf->Cell(80, 10, utf8_decode('Docente'), 1, 0, 'C');
                $pdf->Cell(20, 10, utf8_decode('Niños'), 1, 0, 'C');
                $pdf->Cell(20, 10, utf8_decode('Niñas'), 1, 0, 'C');
                $pdf->Cell(20, 10, utf8_decode('Total'), 1, 1, 'C');
    
                // Datos de cada detalle
                $pdf->SetFont('Arial', '', 10);
                foreach ($detalles as $detalle) {
                    $pdf->Cell(30, 10, utf8_decode($detalle->nombre_grado), 1, 0, 'C');
                    $pdf->Cell(80, 10, utf8_decode($detalle->nombre_docente), 1, 0, 'C');
                    $pdf->Cell(20, 10, utf8_decode($detalle->cantidadNinos), 1, 0, 'C');
                    $pdf->Cell(20, 10, utf8_decode($detalle->cantidadNinas), 1, 0, 'C');
                    $pdf->Cell(20, 10, utf8_decode($detalle->total), 1, 1, 'C');
                }
            }
    
            // Espacio al final de cada registro
            $pdf->Ln(10);
        }
    
        // Salida del PDF
        $pdf->Output('D', 'reporte_diario_familias.pdf');
    }
}

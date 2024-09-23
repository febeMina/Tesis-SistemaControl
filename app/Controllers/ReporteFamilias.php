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
            ->groupBy('registro_diario.idRegistroDiario, registro_diario.fecha')
            ->paginate(10);

        $data['pager'] = $this->registroDiarioModel->pager;

        return view('registro_diario/index', $data);
    }

    public function generarReporte()
    {
        $registros = $this->registroDiarioModel
            ->select('registro_diario.idRegistroDiario, registro_diario.fecha, 
                      COALESCE(SUM(detalle_asistencia.total), 0) as familiasBeneficiadas')
            ->join('detalle_asistencia', 'detalle_asistencia.idRegistroDiario = registro_diario.idRegistroDiario', 'left')
            ->groupBy('registro_diario.idRegistroDiario, registro_diario.fecha')
            ->findAll();
    
        // Crear el PDF
        $pdf = new \FPDF('P', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, utf8_decode('REPORTE DE FAMILIAS BENEFICIADAS'), 0, 1, 'C');
        $pdf->Ln(10);
        
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(40, 10, utf8_decode('Fecha'), 1);
        $pdf->Cell(60, 10, utf8_decode('Familias Beneficiadas'), 1);
        $pdf->Cell(0, 10, '', 0, 1); // Nueva línea
        
        $pdf->SetFont('Arial', '', 12);
        
        foreach ($registros as $registro) {
            // Obtener detalles adicionales para cada registro
            $detalles = $this->detalleAsistenciaModel
                ->where('idRegistroDiario', $registro->idRegistroDiario)
                ->findAll();
    
            $pdf->Cell(40, 10, utf8_decode($registro->fecha), 1);
            $pdf->Cell(60, 10, utf8_decode($registro->familiasBeneficiadas), 1);
            $pdf->Cell(0, 10, '', 0, 1); // Nueva línea
    
            // Agregar detalles de cada registro
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 10, utf8_decode('Detalles:'), 0, 1);
            $pdf->SetFont('Arial', '', 12);
    
            foreach ($detalles as $detalle) {
                $pdf->Cell(30, 10, utf8_decode($detalle['nombre_grado']), 0, 1);
                $pdf->Cell(40, 10, utf8_decode($detalle->nombre_docente), 1);
                $pdf->Cell(30, 10, utf8_decode($detalle->cantidadNinos), 1);
                $pdf->Cell(30, 10, utf8_decode($detalle->cantidadNinas), 1);
                $pdf->Cell(30, 10, utf8_decode($detalle->total), 1);
                $pdf->Cell(0, 10, '', 0, 1); // Nueva línea
            }
            $pdf->Ln(5); // Espacio entre registros
        }
    
        $pdf->Output('D', 'reporte_familias_beneficiadas.pdf');
    }
    
}

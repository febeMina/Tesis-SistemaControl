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

  
        return view('reporte_familias/index', ['registros' => []]);// Asegúrate de que la vista existe
    }

    public function generarReporte()
    {
        log_message('debug', 'Generando reporte...');
    
        // Obtener el idRegistroDiario desde la URL
        $idRegistroDiario = $this->request->getVar('idRegistroDiario');
        log_message('debug', 'ID Registro Diario: ' . $idRegistroDiario);
    
        // Verifica que idRegistroDiario no esté vacío
        if (empty($idRegistroDiario)) {
            return redirect()->back()->with('error', 'ID de registro diario no válido.');
        }
    
        try {
            $registros = $this->registroDiarioModel
                ->select('detalle_asistencia.*, docente.nombreCompleto as nombre_docente, grado.nombre as nombre_grado')
                ->join('docente', 'detalle_asistencia.idDocente = docente.idDocente', 'left')
                ->join('grado', 'docente.idGrado = grado.idGrado', 'left')
                ->where('detalle_asistencia.idRegistroDiario', $idRegistroDiario)
                ->findAll();
    
            if (empty($registros)) {
                log_message('error', "No se encontraron registros para el ID: $idRegistroDiario");
                return redirect()->back()->with('error', 'No se encontraron registros.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Error en generarReporte: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al generar el reporte.');
        }

    


    // Crear el PDF
    $pdf = new \FPDF('P', 'mm', 'A4');
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, utf8_decode('REPORTE DE FAMILIAS BENEFICIADAS'), 0, 1, 'C');
    $pdf->Ln(10);
    
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(40, 10, utf8_decode('Fecha'), 1);
    $pdf->Cell(60, 10, utf8_decode('Familias Beneficiadas'), 1);
    $pdf->Ln(); // Nueva línea
    
    $pdf->SetFont('Arial', '', 12);

    foreach ($registros as $registro) {
        // Mostrar la fecha y familias beneficiadas
        $pdf->Cell(40, 10, utf8_decode($registro->fecha), 1);
        $pdf->Cell(60, 10, utf8_decode($registro->familiasBeneficiadas), 1);
        $pdf->Ln(); // Nueva línea

        // Obtener detalles adicionales para cada registro
        $detalles = $this->detalleAsistenciaModel
    ->select('detalle_asistencia.*, docente.nombreCompleto as nombre_docente, grado.nombre as nombre_grado')
    ->join('docente', 'detalle_asistencia.idDocente = docente.idDocente', 'left')
    ->join('grado', 'detalle_asistencia.idGrado = grado.idGrado', 'left')
    ->where('detalle_asistencia.idRegistroDiario', $registro->idRegistroDiario)
    ->asObject()
    ->findAll();


        // Si hay detalles, los mostramos
        if (!empty($detalles)) {
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 10, utf8_decode('Detalles del Registro:'), 0, 1);
            $pdf->SetFont('Arial', '', 12);

            foreach ($detalles as $detalle) {
                $pdf->Cell(30, 10, utf8_decode($detalle->nombre_grado), 1);
                $pdf->Cell(40, 10, utf8_decode($detalle->nombre_docente), 1);
                $pdf->Cell(30, 10, utf8_decode($detalle->cantidadNinos), 1);
                $pdf->Cell(30, 10, utf8_decode($detalle->cantidadNinas), 1);
                $pdf->Cell(30, 10, utf8_decode($detalle->total), 1);
                $pdf->Ln(); // Nueva línea
            }
        }
        
        $pdf->Ln(5); // Espacio entre registros
    }

    $pdf->Output('D', 'reporte_familias_beneficiadas.pdf');
}


    
}

<?php namespace App\Controllers;

require_once APPPATH . 'Libraries/fpdf186/fpdf.php';

class ReportePDF extends BaseController
{
    protected $permisosPersonalModel;
    protected $tipoPermisoModel;

    public function __construct()
    {
        $this->permisosPersonalModel = new \App\Models\PermisosPersonalModel();
        $this->tipoPermisoModel = new \App\Models\TipoPermisoModel(); // Añadido para obtener los tipos de permisos
    }
    
    
    public function generarReporte()
    {
        
        // Obtener las fechas del filtro
        $fechaInicio = $this->request->getVar('fecha_inicio');
        $fechaFin = $this->request->getVar('fecha_fin');
        
    

        // Obtener los datos del reporte con filtros
        $data = $this->permisosPersonalModel->getReportePermisos($fechaInicio, $fechaFin);
        $tipoPermisos = $this->tipoPermisoModel->findAll();
        
        // Convertir fechas para obtener mes y año
        $fechaInicioDate = new \DateTime($fechaInicio);
        $fechaFinDate = new \DateTime($fechaFin);
        
        $mesInicio = $this->obtenerMesEnEspanol($fechaInicioDate->format('F')); // Nombre completo del mes en español
        $añoInicio = $fechaInicioDate->format('Y');
        $mesFin = $this->obtenerMesEnEspanol($fechaFinDate->format('F')); // Nombre completo del mes en español
        $añoFin = $fechaFinDate->format('Y');
        
        // L = Horizontal, P = Vertical
        $pdf = new \FPDF('L','mm','Letter');
        $pdf->AddPage();
        
        // Ajustar mes y año del reporte
        $mesReporte = ($mesInicio === $mesFin) ? $mesInicio : $mesInicio . '-' . $mesFin;
        $yearReporte = $añoInicio; // Usar el año de inicio ya que es el mismo para ambos meses


    
        $centroEducativo = "CENTRO ESCOLAR BARRIO LAS DELICIAS";
        $codigoInfra = "1434";
        $distritroEducativo = "0608";
        $zona = "Centro";
        $turno = "Matutino";
        $municipio = "Mejicanos";
        
        // Título
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetXY(10, 10);
        // Ancho de la celda, altura de la celda, contenido/variables, bordes, relleno, alineacion del texto
        $pdf->Cell(259, 5, utf8_decode("DEPARTAMENTAL DE EDUCACIÓN"), 0, 1, 'C');
        
        $pdf->SetXY(10, 15);
        $pdf->Cell(259, 5, utf8_decode("DESARROLLO HUMANO"), 0, 1, 'C');
        $pdf->SetXY(10, 20);
        $pdf->Cell(164, 5, utf8_decode("CONTROL DE PERMISOS INTERNOS CON GOCE DE SUELDO CORRESPONDIENTE AL MES DE:"), 0, 1, 'L');
        $pdf->SetXY(174, 20);
        $pdf->Cell(55, 5, utf8_decode($mesReporte), 'B', 1, 'C');
        $pdf->SetXY(229, 20);
        $pdf->Cell(10, 5, utf8_decode("AÑO:"), 0, 1, 'L');
        $pdf->SetXY(239, 20);
        $pdf->Cell(30, 5, utf8_decode($yearReporte), 'B', 1, 'C');
        
        $pdf->SetXY(10, 25);
        $pdf->Cell(65, 5, utf8_decode("NOMBRE DEL CENTRO EDUCATIVO:"), 0, 1, 'L');
        $pdf->SetXY(75, 25);
        $pdf->Cell(129, 5, utf8_decode($centroEducativo), 'B', 1, 'C');
        $pdf->SetXY(204, 25);
        $pdf->Cell(35, 5, utf8_decode("CÓDIGO DE INFRA:"), 0, 1, 'L');
        $pdf->SetXY(239, 25);
        $pdf->Cell(30, 5, utf8_decode($codigoInfra), 'B', 1, 'C');
        /*
        $distritroEducativo = "0608";
        $zona = "Centro";
        $turno = "Matutino";
        $municipio = "Mejicanos";
        */
        $pdf->SetXY(10, 30);
        $pdf->Cell(41, 5, utf8_decode("DISTRITO EDUCATIVO:"), 0, 1, 'L');
        $pdf->SetXY(51, 30);
        $pdf->Cell(40, 5, utf8_decode($distritroEducativo), 'B', 1, 'C');
        $pdf->SetXY(91, 30);
        $pdf->Cell(12, 5, utf8_decode("ZONA:"), 0, 1, 'L');
        $pdf->SetXY(103, 30);
        $pdf->Cell(40, 5, utf8_decode($zona), 'B', 1, 'C');
        $pdf->SetXY(143, 30);
        $pdf->Cell(15, 5, utf8_decode("TURNO:"), 0, 1, 'L');
        $pdf->SetXY(158, 30);
        $pdf->Cell(40, 5, utf8_decode($turno), 'B', 1, 'C');
        $pdf->SetXY(198, 30);
        $pdf->Cell(23, 5, utf8_decode("MUNICIPIO:"), 0, 1, 'C');
        $pdf->SetXY(221, 30);
        $pdf->Cell(48, 5, utf8_decode($municipio), 'B', 1, 'C');    
        
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(10, 45);
        $pdf->Cell(15, 12, utf8_decode("NIP"), 1, 1, 'C'); 
        $pdf->SetXY(25, 45);
        $pdf->Cell(55, 12, utf8_decode("NOMBRE DEL/A DOCENTE"), 1, 1, 'C'); 
        $pdf->SetXY(80, 45);
        $pdf->Cell(27, 12, utf8_decode("FECHA"), 1, 1, 'C'); 
        
        
         
        $x = 107; // Posición inicial X para los permisos
        $ancho = 27; // Ancho de cada celda de permiso
        $height = 12; // Altura de cada celda de permiso
        $pdf->SetFont('Arial', 'B', 7);
        
        // Generar cabecera de permisos y saldos dinámicamente
        foreach ($tipoPermisos as $index => $tipoPermiso) {
            $pdf->SetXY($x + ($index * $ancho * 2), 45);
            $pdf->MultiCell($ancho, 4,  utf8_decode($tipoPermiso['nombre'] . ' (' . $tipoPermiso['cantidad_dias'] . ' DÍAS AL AÑO)'), 1, 'C');
            $pdf->SetXY($x + ($index * $ancho * 2) + $ancho, 45);
            $pdf->MultiCell($ancho, 4, utf8_decode("SALDO"), 1, 'C');
        }
        
        $pdf->SetXY(10, 57);
        $pdf->Cell(15, 5, '', 1, 1, 'C'); 
        $pdf->SetXY(25, 57);
        $pdf->Cell(55, 5, '', 1, 1, 'C'); 
        
        $pdf->SetXY(80, 57);
        $pdf->Cell(9, 5, utf8_decode("D"), 1, 1, 'C'); 
        $pdf->SetXY(89, 57);
        $pdf->Cell(9, 5, utf8_decode("M"), 1, 1, 'C'); 
        $pdf->SetXY(98, 57);
        $pdf->Cell(9, 5, utf8_decode("A"), 1, 1, 'C'); 
        
        $pdf->SetXY(107, 57);
        $pdf->Cell(9, 5, utf8_decode("D"), 1, 1, 'C'); 
        $pdf->SetXY(116, 57);
        $pdf->Cell(9, 5, utf8_decode("H"), 1, 1, 'C'); 
        $pdf->SetXY(125, 57);
        $pdf->Cell(9, 5, utf8_decode("M"), 1, 1, 'C'); 
        
        $pdf->SetXY(134, 57);
        $pdf->Cell(9, 5, utf8_decode("D"), 1, 1, 'C'); 
        $pdf->SetXY(143, 57);
        $pdf->Cell(9, 5, utf8_decode("H"), 1, 1, 'C'); 
        $pdf->SetXY(152, 57);
        $pdf->Cell(9, 5, utf8_decode("M"), 1, 1, 'C'); 

        $pdf->SetXY(161, 57);
        $pdf->Cell(9, 5, utf8_decode("D"), 1, 1, 'C'); 
        $pdf->SetXY(170, 57);
        $pdf->Cell(9, 5, utf8_decode("H"), 1, 1, 'C'); 
        $pdf->SetXY(179, 57);
        $pdf->Cell(9, 5, utf8_decode("M"), 1, 1, 'C'); 
        
        $pdf->SetXY(188, 57);
        $pdf->Cell(9, 5, utf8_decode("D"), 1, 1, 'C'); 
        $pdf->SetXY(197, 57);
        $pdf->Cell(9, 5, utf8_decode("H"), 1, 1, 'C'); 
        $pdf->SetXY(206, 57);
        $pdf->Cell(9, 5, utf8_decode("M"), 1, 1, 'C'); 
        
        $pdf->SetXY(215, 57);
        $pdf->Cell(9, 5, utf8_decode("D"), 1, 1, 'C'); 
        $pdf->SetXY(224, 57);
        $pdf->Cell(9, 5, utf8_decode("H"), 1, 1, 'C'); 
        $pdf->SetXY(233, 57);
        $pdf->Cell(9, 5, utf8_decode("M"), 1, 1, 'C'); 
        
        $pdf->SetXY(242, 57);
        $pdf->Cell(9, 5, utf8_decode("D"), 1, 1, 'C'); 
        $pdf->SetXY(251, 57);
        $pdf->Cell(9, 5, utf8_decode("H"), 1, 1, 'C'); 
        $pdf->SetXY(260, 57);
        $pdf->Cell(9, 5, utf8_decode("M"), 1, 1, 'C'); 
        
        $altura = 57;
        // foreach de la consulta con los registros
        foreach ($data as $permiso) {
            $nip = esc($permiso['nip']);
            $docente = esc($permiso['nombre_completo']);
            $fechaInicio = $permiso['fechaInicio'];
            
            // Extraer día, mes y año de fechaInicio
            $fechaInicioDate = new \DateTime($fechaInicio);
            $diaInicio = $fechaInicioDate->format('d');
            $mesInicio = $fechaInicioDate->format('m'); // Mes en formato numérico
            $añoInicio = $fechaInicioDate->format('Y');
            
            $altura += 5;
            $pdf->SetXY(10, $altura);
            $pdf->Cell(15, 5, utf8_decode($nip), 1, 1, 'C'); 
            $pdf->SetXY(25, $altura);
            $pdf->Cell(55, 5, utf8_decode($docente), 1, 1, 'C'); 
            
            $pdf->SetXY(80, $altura);
            $pdf->Cell(9, 5, utf8_decode($diaInicio), 1, 1, 'C'); 
            $pdf->SetXY(89, $altura);
            $pdf->Cell(9, 5, utf8_decode($mesInicio), 1, 1, 'C'); 
            $pdf->SetXY(98, $altura);
            $pdf->Cell(9, 5, utf8_decode($añoInicio), 1, 1, 'C');
                    
            
            $pdf->SetXY(107,  $altura);
            $pdf->Cell(9, 5, utf8_decode(""), 1, 1, 'C'); 
            $pdf->SetXY(116,  $altura);
            $pdf->Cell(9, 5, utf8_decode(""), 1, 1, 'C'); 
            $pdf->SetXY(125,  $altura);
            $pdf->Cell(9, 5, utf8_decode(""), 1, 1, 'C'); 
            
            $pdf->SetXY(134,  $altura);
            $pdf->Cell(9, 5, utf8_decode(""), 1, 1, 'C'); 
            $pdf->SetXY(143,  $altura);
            $pdf->Cell(9, 5, utf8_decode(""), 1, 1, 'C'); 
            $pdf->SetXY(152,  $altura);
            $pdf->Cell(9, 5, utf8_decode(""), 1, 1, 'C'); 
    
            $pdf->SetXY(161,  $altura);
            $pdf->Cell(9, 5, utf8_decode(""), 1, 1, 'C'); 
            $pdf->SetXY(170,  $altura);
            $pdf->Cell(9, 5, utf8_decode(""), 1, 1, 'C'); 
            $pdf->SetXY(179,  $altura);
            $pdf->Cell(9, 5, utf8_decode(""), 1, 1, 'C'); 
            
            $pdf->SetXY(188,  $altura);
            $pdf->Cell(9, 5, utf8_decode(""), 1, 1, 'C'); 
            $pdf->SetXY(197,  $altura);
            $pdf->Cell(9, 5, utf8_decode(""), 1, 1, 'C'); 
            $pdf->SetXY(206,  $altura);
            $pdf->Cell(9, 5, utf8_decode(""), 1, 1, 'C'); 
            
            $pdf->SetXY(215,  $altura);
            $pdf->Cell(9, 5, utf8_decode(""), 1, 1, 'C'); 
            $pdf->SetXY(224,  $altura);
            $pdf->Cell(9, 5, utf8_decode(""), 1, 1, 'C'); 
            $pdf->SetXY(233,  $altura);
            $pdf->Cell(9, 5, utf8_decode(""), 1, 1, 'C'); 
            
            $pdf->SetXY(242,  $altura);
            $pdf->Cell(9, 5, utf8_decode(""), 1, 1, 'C'); 
            $pdf->SetXY(251,  $altura);
            $pdf->Cell(9, 5, utf8_decode(""), 1, 1, 'C'); 
            $pdf->SetXY(260,  $altura);
            $pdf->Cell(9, 5, utf8_decode(""), 1, 1, 'C'); 
        }
        // Espacio
        //$pdf->Ln(10);

        // Cabecera de la tabla
        /*
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(30, 10, 'NIP', 1);
        $pdf->Cell(50, 10, 'Nombre del Personal', 1);
        $pdf->Cell(30, 10, 'Fecha Inicio', 1);
        $pdf->Cell(30, 10, 'Fecha Fin', 1);
        foreach ($tipoPermisos as $tipoPermiso) {
            $pdf->Cell(20, 10, $tipoPermiso['nombre'].' Dias', 1);
            $pdf->Cell(20, 10, $tipoPermiso['nombre'].' Horas', 1);
        }
        $pdf->Cell(40, 10, 'Saldo', 1);
        $pdf->Ln();
        */

        // Datos de la tabla
        /*
        $pdf->SetFont('Arial', '', 12);
        foreach ($data as $permiso) {
            $pdf->Cell(30, 10, $permiso['nip'], 1);
            $pdf->Cell(50, 10, $permiso['nombre_completo'], 1);
            $pdf->Cell(30, 10, $permiso['fechaInicio'], 1);
            $pdf->Cell(30, 10, $permiso['fechaFin'], 1);
            foreach ($tipoPermisos as $tipoPermiso) {
                $tipoPermisoId = $tipoPermiso['idTipoPermiso'];
                $dias = isset($permiso['tipoPermiso'][$tipoPermisoId]['dias']) ? $permiso['tipoPermiso'][$tipoPermisoId]['dias'] : '';
                $horas = isset($permiso['tipoPermiso'][$tipoPermisoId]['horas']) ? $permiso['tipoPermiso'][$tipoPermisoId]['horas'] : '';
                $pdf->Cell(20, 10, $dias, 1);
                $pdf->Cell(20, 10, $horas, 1);
            }
            $saldo = $permiso['saldoActualDias'] . ' días, ' . $permiso['saldoActualHoras'] . ' horas';
            $pdf->Cell(40, 10, $saldo, 1);
            $pdf->Ln();
        }
        */
        // Generar el PDF
        $pdf->Output('D', 'reporte_permisos.pdf');
    }

    private function obtenerMesEnEspanol($mesIngles) {
        $meses = array(
            'January' => 'Enero',
            'February' => 'Febrero',
            'March' => 'Marzo',
            'April' => 'Abril',
            'May' => 'Mayo',
            'June' => 'Junio',
            'July' => 'Julio',
            'August' => 'Agosto',
            'September' => 'Septiembre',
            'October' => 'Octubre',
            'November' => 'Noviembre',
            'December' => 'Diciembre'
        );
        return isset($meses[$mesIngles]) ? $meses[$mesIngles] : $mesIngles;
    }

   
}

<?php

namespace App\Controllers;

use App\Models\DetalleSaldosTipoPermisoModel;
use App\Models\MaestroModel;
use App\Models\SaldosDocentesModel;
use App\Models\TipoPermisoModel;
use CodeIgniter\I18n\Time; // Importación correcta de la clase Time
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use TCPDF;

class PermisoMagisterial extends BaseController
{
    public function create()
    {
        $modelMaestro = new MaestroModel();
        $data['maestros'] = $modelMaestro->findAll();

        $modelTipoPermiso = new TipoPermisoModel();
        $data['tipos_permisos'] = $modelTipoPermiso->findAll();

        return view('Permisos/create', $data);
    }

    public function store()
    {
        $rules = [
            'id_maestro' => 'required',
            'id_tipo_permiso' => 'required',
            'fecha_inicio' => 'required|valid_date',
            'fecha_fin' => 'required|valid_date',
            'horas_ocupadas' => 'required|numeric'
        ];
    
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
    
        $idMaestro = $this->request->getVar('id_maestro');
        $idTipoPermiso = $this->request->getVar('id_tipo_permiso');
        $fechaInicio = $this->request->getVar('fecha_inicio');
        $fechaFin = $this->request->getVar('fecha_fin');
        $horasOcupadas = (int)$this->request->getVar('horas_ocupadas');
    
        $fechaInicioDate = new \DateTime($fechaInicio);
        $fechaFinDate = new \DateTime($fechaFin);
        $diasTotales = $fechaInicioDate->diff($fechaFinDate)->days + 1;
    
        $modelTipoPermiso = new TipoPermisoModel();
        $tipoPermiso = $modelTipoPermiso->find($idTipoPermiso);
    
        if (!$tipoPermiso) {
            return redirect()->back()->withInput()->with('error', 'El tipo de permiso no es válido.');
        }
    
        $cantidadDias = $tipoPermiso['cantidad_dias'];
        $diasOcupados = floor($horasOcupadas / 5); // Considerar horas completas como días ocupados
        $horasRestantes = $horasOcupadas % 5; // Horas que no alcanzan a completar un día
    
        $diasDisponibles = $cantidadDias - $diasTotales - $diasOcupados;
        $horasDisponibles = 5 - $horasRestantes;
    
        if ($diasDisponibles < 0) {
            return redirect()->back()->withInput()->with('error', 'No hay suficientes días disponibles.');
        }
    
        $modelDetalleSaldosTipoPermiso = new DetalleSaldosTipoPermisoModel();
        $detalleSaldosTipoPermiso = $modelDetalleSaldosTipoPermiso
            ->where('idTipoPermiso', $idTipoPermiso)
            ->where('anio', date('Y'))
            ->first();
    
        if (!$detalleSaldosTipoPermiso) {
            $detalleSaldosTipoPermiso = [
                'anio' => date('Y'),
                'idTipoPermiso' => $idTipoPermiso,
                'saldo' => 0
            ];
            $modelDetalleSaldosTipoPermiso->insert($detalleSaldosTipoPermiso);
            $detalleSaldosTipoPermiso['idDetallePermiso'] = $modelDetalleSaldosTipoPermiso->getInsertID();
        }
    
        $modelSaldosDocentes = new SaldosDocentesModel();
        $inserted = $modelSaldosDocentes->insert([
            'idDocente' => $idMaestro,
            'idDetallePermiso' => $detalleSaldosTipoPermiso['idDetallePermiso'],
            'saldo_total_dias' => $diasTotales + $diasOcupados,
            'saldo_total_horas' => $horasOcupadas,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'fecha_creacion' => date('Y-m-d H:i:s')
        ]);
    
        if (!$inserted) {
            return redirect()->back()->withInput()->with('error', 'Error al insertar en la tabla saldos_docentes.');
        }
    
        $detalleSaldosTipoPermiso['saldo'] += $diasTotales + $diasOcupados;
        $modelDetalleSaldosTipoPermiso->update($detalleSaldosTipoPermiso['idDetallePermiso'], $detalleSaldosTipoPermiso);
    
        return redirect()->to(site_url('permiso_magisterial/index'))->with('success', 'El permiso magisterial ha sido creado exitosamente.');
    }

    public function index()
    {
        $request = service('request');
        $filters = [
            'nip' => $request->getVar('nip'),
            'nombre_completo' => $request->getVar('nombre_completo'),
            'fecha_creacion' => $request->getVar('fecha_creacion')
        ];
    
        $modelSaldosDocentes = new SaldosDocentesModel();
        $modelTipoPermiso = new TipoPermisoModel();
        $modelMaestro = new MaestroModel();
    
        // Configuración de la paginación
        $perPage = 4; // Número de registros por página
    
        // Obtener los saldos docentes con paginación
        $saldos_docentes = $modelSaldosDocentes->paginate($perPage, 'group1');
        
        // Obtener el paginador
        $pager = $modelSaldosDocentes->pager;
    
        if (!empty($filters['nip']) || !empty($filters['nombre_completo']) || !empty($filters['fecha_creacion'])) {
            $saldos_docentes = $this->filterSaldosDocentes($saldos_docentes, $filters);
        }
    
        $tipos_permisos = $modelTipoPermiso->findAll();
    
        $data['saldos_docentes'] = [];
    
        foreach ($saldos_docentes as $saldo) {
            $maestro = $modelMaestro->find($saldo['idDocente'] ?? null);
    
            $nombre_completo = $maestro['nombre_completo'] ?? '';
            $nip = $maestro['nip'] ?? '';
            $fecha_creacion = isset($saldo['fecha_creacion']) ? $saldo['fecha_creacion'] : '';
    
            // Inicializamos las fechas de inicio y fin como null
            $fecha_inicio = $saldo['fecha_inicio'] ?? null;
            $fecha_fin = $saldo['fecha_fin'] ?? null;
    
            $detalle_saldos_permiso = [];
            foreach ($tipos_permisos as $tipo_permiso) {
                $modelDetalleSaldosTipoPermiso = new DetalleSaldosTipoPermisoModel();
                $detalle_saldo_permiso = $modelDetalleSaldosTipoPermiso
                    ->where('idTipoPermiso', $tipo_permiso['idTipoPermiso'])
                    ->where('anio', date('Y'))
                    ->first();
    
                $dias_ocupados = 0;
                $horas_ocupadas = 0;
                $dias_disponibles = $tipo_permiso['cantidad_dias'];
                $horas_disponibles = 5;
    
                if ($detalle_saldo_permiso) {
                    $modelSaldosDocentes = new SaldosDocentesModel();
                    $saldo_docente = $modelSaldosDocentes
                        ->where('idDocente', $maestro['idDocente'])
                        ->where('idDetallePermiso', $detalle_saldo_permiso['idDetallePermiso'])
                        ->first();
    
                    if ($saldo_docente) {
                        $dias_ocupados = $saldo_docente['saldo_total_dias'];
                        $horas_ocupadas = $saldo_docente['saldo_total_horas'];
                        $dias_disponibles = $tipo_permiso['cantidad_dias'] - $dias_ocupados;
                        $horas_disponibles = 5 - $horas_ocupadas;
                    }
                }
    
                $detalle_saldos_permiso[] = [
                    'dias_ocupados' => $dias_ocupados,
                    'horas_ocupadas' => $horas_ocupadas,
                    'dias_disponibles' => $dias_disponibles,
                    'horas_disponibles' => $horas_disponibles,
                    'idTipoPermiso' => $detalle_saldo_permiso ? $detalle_saldo_permiso['idTipoPermiso'] : null,
                    'limite_dias' => $tipo_permiso['cantidad_dias'],
                ];
            }
    
            $data['saldos_docentes'][] = [
                'nombre_completo' => $nombre_completo,
                'nip' => $nip,
                'fecha_creacion' => $fecha_creacion,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'detalle_saldos_permiso' => $detalle_saldos_permiso
            ];
        }
    
        $data['tipos_permisos'] = $tipos_permisos;  // Asegurarse de que la variable se pase a la vista
        $data['pager'] = $pager;
    
        return view('Permisos/index', $data);
    }
    
    protected function filterSaldosDocentes($saldos_docentes, $filters)
    {
        return array_filter($saldos_docentes, function ($saldo) use ($filters) {
            $maestro = (new MaestroModel())->find($saldo['idDocente'] ?? null);
            if (!$maestro) {
                return false;
            }
    
            if (!empty($filters['nip']) && stripos($maestro['nip'], $filters['nip']) === false) {
                return false;
            }
    
            if (!empty($filters['nombre_completo']) && stripos($maestro['nombre_completo'], $filters['nombre_completo']) === false) {
                return false;
            }
    
            if (!empty($filters['fecha_creacion']) && stripos($saldo['fecha_creacion'], $filters['fecha_creacion']) === false) {
                return false;
            }
    
            return true;
        });
    }
    
    public function generarReporteExcel()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        $sheet->mergeCells('A1:H1');
        $sheet->setCellValue('A1', 'DEPARTAMENTO DE EDUCACION');
    
        $sheet->mergeCells('A2:H2');
        $sheet->setCellValue('A2', 'DESARROLLO HUMANO');
    
        $sheet->mergeCells('A3:H3');
        $sheet->setCellValue('A3', 'CONTROL DE PERMISOS CON GOCE DE SUELDO CORRESPONDIENTE AL MES DE: ______ AÑO 20__');
    
        $sheet->mergeCells('A4:H4');
        $sheet->setCellValue('A4', 'NOMBRE DEL CENTRO EDUCATIVO: ______ CODIGO DE INFRA: ______');
    
        $sheet->mergeCells('A5:H5');
        $sheet->setCellValue('A5', 'DISTRITO EDUCATIVO: ______ ZONA: ______ TURNO: ______ MUNICIPIO: ______');
    
        $sheet->setCellValue('A7', 'NIP');
        $sheet->setCellValue('B7', 'NOMBRE DEL/A DOCENTE');
        $sheet->setCellValue('C7', 'FECHA');
        $sheet->setCellValue('D7', 'TIPO DE PERMISO');
        $sheet->setCellValue('E7', 'DÍAS OCUPADOS');
        $sheet->setCellValue('F7', 'DÍAS DISPONIBLES');
        $sheet->setCellValue('G7', 'HORAS OCUPADAS');
        $sheet->setCellValue('H7', 'HORAS DISPONIBLES');
    
        $modelSaldosDocentes = new SaldosDocentesModel();
        $modelTipoPermiso = new TipoPermisoModel();
        $modelMaestro = new MaestroModel();
        $saldos_docentes = $modelSaldosDocentes->findAll();
        $tipos_permisos = $modelTipoPermiso->findAll();
    
        $row = 8;
        foreach ($saldos_docentes as $saldo) {
            $maestro = $modelMaestro->find($saldo['idDocente']);
            $nombre_completo = $maestro['nombre_completo'];
            $nip = $maestro['nip'];
            $fecha_creacion = $saldo['fecha_creacion'];
    
            // Verificar si existe 'detalle_saldos_permiso' y tiene elementos
            if (isset($saldo['detalle_saldos_permiso']) && is_array($saldo['detalle_saldos_permiso']) && count($saldo['detalle_saldos_permiso']) > 0) {
                foreach ($saldo['detalle_saldos_permiso'] as $detalle) {
                    $tipo_permiso = $modelTipoPermiso->find($detalle['idTipoPermiso']);
    
                    $sheet->setCellValue('A' . $row, $nip);
                    $sheet->setCellValue('B' . $row, $nombre_completo);
                    $sheet->setCellValue('C' . $row, $fecha_creacion);
                    $sheet->setCellValue('D' . $row, $tipo_permiso['nombre']);
                    $sheet->setCellValue('E' . $row, $detalle['dias_ocupados']);
                    $sheet->setCellValue('F' . $row, $detalle['dias_disponibles']);
                    $sheet->setCellValue('G' . $row, $detalle['horas_ocupadas']);
                    $sheet->setCellValue('H' . $row, $detalle['horas_disponibles']);
                    $row++;
                }
            } else {
                // Manejo de casos donde 'detalle_saldos_permiso' no está definido o está vacío
                // Puedes optar por no agregar datos o manejarlo de otra manera según tu lógica de negocio.
                // En este ejemplo, simplemente incrementamos el contador de fila sin añadir datos.
                $row++;
            }
        }
    
        $sheet->mergeCells('A' . ($row + 2) . ':H' . ($row + 2));
        $sheet->setCellValue('A' . ($row + 2), 'IMPORTANTE: ANEXAR AL LISTADO DE PAGO DETALLANDO FECHAS Y SALDOS');
    
        $sheet->mergeCells('A' . ($row + 3) . ':H' . ($row + 3));
        $sheet->setCellValue('A' . ($row + 3), 'LUGAR Y FECHA: ________________________');
    
        $sheet->mergeCells('A' . ($row + 4) . ':H' . ($row + 4));
        $sheet->setCellValue('A' . ($row + 4), 'FIRMAS Y SELLOS');
    
        $writer = new Xlsx($spreadsheet);
        $filename = 'reporte_permisos_' . date('Ymd_His') . '.xlsx';
    
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
    
        $writer->save('php://output');
        exit();
    }
    



    public function generarReportePDF()
    {
        // Limpiar cualquier salida previa
        ob_clean();
    
        // Obtener datos necesarios
        $modelSaldosDocentes = new SaldosDocentesModel();
        $modelTipoPermiso = new TipoPermisoModel();
        $modelMaestro = new MaestroModel(); // Asumiendo que este modelo contiene la información del maestro
    
        $saldos_docentes = $modelSaldosDocentes->findAll();
        $tipos_permisos = $modelTipoPermiso->findAll();
    
        // Crear instancia de TCPDF con orientación horizontal
        $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
        // Establecer metadatos
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('Reporte de Saldos de Docentes');
        $pdf->SetHeaderData('', 0, 'Reporte de Saldos de Docentes', '');
    
        // Agregar una página
        $pdf->AddPage();
    
        // Establecer el título del reporte
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, 'Reporte de Saldos de Docentes', 0, 1, 'C');
    
        // Agregar tabla de datos
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(80, 10, 'Nombre del Docente', 1, 0, 'C');
        $pdf->Cell(60, 10, 'Tipo de Permiso', 1, 0, 'C');
        $pdf->Cell(40, 10, 'Saldos', 1, 1, 'C');
    
        // Obtener datos del modelo y agregar filas de datos
        foreach ($saldos_docentes as $saldo) {
            $maestro = $modelMaestro->find($saldo['idDocente']);
            $nombre_completo = $maestro['nombre_completo'];
    
            if (isset($saldo['detalle_saldos_permiso']) && is_array($saldo['detalle_saldos_permiso']) && count($saldo['detalle_saldos_permiso']) > 0) {
                foreach ($saldo['detalle_saldos_permiso'] as $detalle) {
                    $tipo_permiso = $modelTipoPermiso->find($detalle['idTipoPermiso']);
    
                    $pdf->Cell(80, 10, $nombre_completo, 1, 0, 'L');
                    $pdf->Cell(60, 10, $tipo_permiso['nombre'], 1, 0, 'L');
                    $pdf->Cell(40, 10, $detalle['dias_disponibles'], 1, 1, 'C');
                }
            } else {
                // Si no hay detalles de saldos de permisos, imprimir solo el nombre del docente
                $pdf->Cell(80, 10, $nombre_completo, 1, 0, 'L');
                $pdf->Cell(60, 10, '', 1, 0, 'L'); // Celda vacía para el tipo de permiso
                $pdf->Cell(40, 10, '', 1, 1, 'C'); // Celda vacía para los saldos
            }
        }
    
        // Salida del PDF
        $pdf->Output('reporte_saldos_docentes.pdf', 'D');
        exit;
    }
    

}


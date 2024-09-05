<?php

namespace App\Controllers;

use App\Models\MaestroModel;
use App\Models\TipoPermisoModel;
use App\Models\SaldoPersonalModel;
use App\Models\PermisosPersonalModel;

class PermisosPersonal extends BaseController
{
    protected $maestroModel;
    protected $tipoPermisoModel;
    protected $saldoPersonalModel;
    protected $permisosPersonalModel;

    public function __construct()
    {
        helper('url');
        $this->maestroModel = new MaestroModel();
        $this->tipoPermisoModel = new TipoPermisoModel();
        $this->saldoPersonalModel = new SaldoPersonalModel();
        $this->permisosPersonalModel = new PermisosPersonalModel();
    }

    public function index()
    {
        $pager = \Config\Services::pager();
        $permisos = $this->permisosPersonalModel->getPermisosConSaldos();

        // Obtener parámetros de filtrado de la solicitud
        $nombreCompleto = $this->request->getGet('nombreCompleto');
        $nip = $this->request->getGet('nip');
        $fechaCreacion = $this->request->getGet('fechaCreacion');
        
        // Pasar parámetros de filtrado al modelo
        $permisos = $this->permisosPersonalModel->getPermisosConSaldos($nombreCompleto, $nip, $fechaCreacion);


        $data = [];
        foreach ($permisos as $permiso) {
            if (!isset($permiso['fechaInicio']) || !isset($permiso['fechaFin'])) {
                log_message('error', 'La clave "fechaInicio" o "fechaFin" no está presente en el array de permisos.');
                continue;
            }

            $tipoPermisoNombre = $permiso['tipoPermisoNombre'];
            $diasSolicitados = $this->permisosPersonalModel->calcularDiasEntreFechas($permiso['fechaInicio'], $permiso['fechaFin']);
            $horasSolicitadas = $permiso['horasSolicitadas'];

            $data[] = [
                'nip' => $permiso['nip'],
                'nombreCompleto' => $permiso['nombreCompleto'],
                'tipoPermisoNombre' => $tipoPermisoNombre,
                'cantidadDias' => $permiso['cantidadDias'],
                'fechaInicio' => $permiso['fechaInicio'],
                'fechaFin' => $permiso['fechaFin'],
                'horasSolicitadas' => $horasSolicitadas,
                'diasSolicitados' => $diasSolicitados,
                'saldoHistorialHoras' => $permiso['saldoHistorialHoras'], 
                'saldoHistorialDias' => $permiso['saldoHistorialDias'],   
                'saldoActualDias' => $permiso['saldoActualDias'],
                'saldoActualHoras' => $permiso['saldoActualHoras'],
                'fechaCreacion' => $permiso['fechaCreacion'] // Añadido
            ];
        }

        $currentPage = $this->request->getVar('page') ? $this->request->getVar('page') : 1;
        $perPage = 10;
        $totalRows = count($data);

        $dataPaginated = array_slice($data, ($currentPage - 1) * $perPage, $perPage);

        return view('permisos_personal/index', [
            'data' => $dataPaginated,
            'pager' => $pager->makeLinks($currentPage, $perPage, $totalRows, 'bootstrap_pagination')
        ]);
    }

    public function create()
    {
        $data['docentes'] = $this->maestroModel->findAll();
        $data['tiposPermisos'] = $this->tipoPermisoModel->findAll();

        return view('permisos_personal/create', $data);
    }

    public function store()
    {
        $request = \Config\Services::request();
        
        $idDocente = $request->getPost('idDocente');
        $idTipoPermiso = $request->getPost('idTipoPermiso');
        $tipoSolicitud = $request->getPost('tipoSolicitud');
        $horasSolicitadas = (int)($request->getPost('horasSolicitadas') ?? 0);
        $fechaUnica = $request->getPost('fechaUnica');
        $fechaInicio = $request->getPost('fechaInicio');
        $fechaFin = $request->getPost('fechaFin');
        
        if (empty($idDocente) || empty($idTipoPermiso)) {
            return redirect()->back()->with('error', 'Datos obligatorios no proporcionados.');
        }
        
        // Obtener el saldo actual del docente para el tipo de permiso solicitado
        $saldo = $this->saldoPersonalModel->getSaldoPersonalId($idDocente, $idTipoPermiso);
        if (!$saldo) {
            return redirect()->back()->with('error', 'Saldo no encontrado para el docente y tipo de permiso.');
        }
        
        $saldoHistorialHoras = $saldo['saldoActualHoras'];
        $saldoHistorialDias = $saldo['saldoActualDias'];
        
        // Definir las horas que constituyen un día completo de trabajo
        $horasPorDia = 6;
    
        if ($tipoSolicitud === 'Horas') {
            if ($horasSolicitadas < 1 || $horasSolicitadas > $horasPorDia) {
                return redirect()->back()->with('error', 'Solo se permiten de 1 a ' . $horasPorDia . ' horas.');
            }
    
            if ($horasSolicitadas > $saldoHistorialHoras) {
                return redirect()->back()->with('error', 'No hay suficiente saldo de horas.');
            }
    
            // Calcula cuántos días completos representan las horas solicitadas
            $diasCompletosDesdeHoras = floor($horasSolicitadas / $horasPorDia);
            $horasRestantes = $horasSolicitadas % $horasPorDia;
    
            // Descontar días solo si hay suficientes horas para completar un día
            $nuevoSaldoDias = $saldoHistorialDias - $diasCompletosDesdeHoras;
            $nuevoSaldoHoras = $saldoHistorialHoras - $horasSolicitadas;
    
            if ($nuevoSaldoHoras < 0) {
                // Si las horas son negativas, convierte en días
                $nuevoSaldoDias -= 1;
                $nuevoSaldoHoras += $horasPorDia;
            }
    
            // Redondeo adecuado
            $nuevoSaldoDias = round($nuevoSaldoDias, 2);
            $nuevoSaldoHoras = max(0, $nuevoSaldoHoras);
    
            // Insertar el permiso por horas
            $insertData = [
                'idSaldoPersonal' => $saldo['idSaldoPersonal'],
                'fechaInicio' => $fechaUnica,
                'fechaFin' => $fechaUnica,
                'horasSolicitadas' => $horasSolicitadas,
                'saldoHistorialHoras' => $saldoHistorialHoras,
                'saldoHistorialDias' => $saldoHistorialDias,
                'fechaCreacion' => date('Y-m-d')
            ];
    
            $this->permisosPersonalModel->insert($insertData);
    
            $this->saldoPersonalModel->actualizarSaldoPersonal(
                $saldo['idSaldoPersonal'], 
                'Horas', 
                $horasSolicitadas,
                $diasCompletosDesdeHoras
            );
        } else {
            $diasSolicitados = $this->permisosPersonalModel->calcularDiasEntreFechas($fechaInicio, $fechaFin);
        
            if ($diasSolicitados > $saldoHistorialDias) {
                return redirect()->back()->with('error', 'No hay suficiente saldo de días.');
            }
        
            // Insertar el permiso por días
            $insertData = [
                'idSaldoPersonal' => $saldo['idSaldoPersonal'],
                'fechaInicio' => $fechaInicio,
                'fechaFin' => $fechaFin,
                'saldoHistorialHoras' => $saldoHistorialHoras,
                'saldoHistorialDias' => $saldoHistorialDias
            ];
        
            $this->permisosPersonalModel->insert($insertData);
        
            // Actualizar el saldo usando el método correcto
            $this->saldoPersonalModel->actualizarSaldoPersonal(
                $saldo['idSaldoPersonal'], 
                'Dias', 
                $diasSolicitados
            );
        }
        
        return redirect()->to('/permisos_personal')->with('success', 'Permiso registrado exitosamente.');
    }
    



public function reporte()
{
    $perPage = 10;
    $currentPage = $this->request->getVar('page') ? (int)$this->request->getVar('page') : 1;

    // Obtener parámetros de filtrado
    $fechaInicio = $this->request->getGet('fecha_inicio');
    $fechaFin = $this->request->getGet('fecha_fin');

    // Obtener permisos filtrados
    $permisos = $this->permisosPersonalModel->getReportePermisos($fechaInicio, $fechaFin);

    $data = [];
    foreach ($permisos as $permiso) {
        if (!isset($permiso['fechaInicio']) || !isset($permiso['fechaFin'])) {
            log_message('error', 'La clave "fechaInicio" o "fechaFin" no está presente en el array de permisos.');
            continue;
        }

        $tipoPermisoNombre = $permiso['tipoPermisoNombre'];
        $diasSolicitados = $this->permisosPersonalModel->calcularDiasEntreFechas($permiso['fechaInicio'], $permiso['fechaFin']);
        $horasSolicitadas = $permiso['horasSolicitadas'];

        $data[] = [
            'nip' => $permiso['nip'],
            'nombreCompleto' => $permiso['nombreCompleto'],
            'tipoPermisoNombre' => $tipoPermisoNombre,
            'cantidadDias' => $permiso['cantidadDias'],
            'fechaInicio' => $permiso['fechaInicio'],
            'fechaFin' => $permiso['fechaFin'],
            'horasSolicitadas' => $horasSolicitadas,
            'saldoHistorialHoras' => $permiso['saldoHistorialHoras'], 
            'saldoHistorialDias' => $permiso['saldoHistorialDias'],   
            'saldoActualDias' => $permiso['saldoActualDias'],
            'saldoActualHoras' => $permiso['saldoActualHoras'],
            'fechaCreacion' => $permiso['fechaCreacion']
        ];
    }

    // Calcula la paginación manualmente
    $totalRows = count($data);
    $dataPaginated = array_slice($data, ($currentPage - 1) * $perPage, $perPage);

    // Crea las páginas
    $pager = \Config\Services::pager();
    $pagerLinks = $pager->makeLinks($currentPage, $perPage, $totalRows, 'bootstrap_pagination');

    return view('reportes/permisos_reporte', [
        'data' => $dataPaginated,
        'pager' => $pagerLinks, // Asegúrate de que 'pager' reciba los links generados
        'filters' => [
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
        ],
    ]);
}
}

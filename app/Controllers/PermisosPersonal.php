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
        $nombreCompleto = $this->request->getGet('nombre_completo');
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
                'nombre_completo' => $permiso['nombre_completo'],
                'tipoPermisoNombre' => $tipoPermisoNombre,
                'cantidad_dias' => $permiso['cantidad_dias'],
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

    // Log los datos recibidos
    log_message('info', 'Datos recibidos: ' . print_r([
        'idDocente' => $idDocente,
        'idTipoPermiso' => $idTipoPermiso,
        'tipoSolicitud' => $tipoSolicitud,
        'horasSolicitadas' => $horasSolicitadas,
        'fechaUnica' => $fechaUnica,
        'fechaInicio' => $fechaInicio,
        'fechaFin' => $fechaFin,
    ], true));

    if (empty($idDocente) || empty($idTipoPermiso)) {
        return redirect()->back()->with('error', 'Datos obligatorios no proporcionados.');
    }

    $saldo = $this->saldoPersonalModel->getSaldoPersonalId($idDocente, $idTipoPermiso);
    if (!$saldo) {
        return redirect()->back()->with('error', 'Saldo no encontrado para el docente y tipo de permiso.');
    }

    $saldoHistorialHoras = $saldo['saldoActualHoras'];
    $saldoHistorialDias = $saldo['saldoActualDias'];

    if ($tipoSolicitud === 'Horas') {
        if ($horasSolicitadas < 1 || $horasSolicitadas > 6) {
            return redirect()->back()->with('error', 'Solo se permiten de 1 a 6 horas.');
        }

        if ($horasSolicitadas > $saldo['saldoActualHoras']) {
            return redirect()->back()->with('error', 'No hay suficiente saldo de horas.');
        }

        // Insertar el permiso por horas usando la fecha única
        $insertData = [
            'idSaldoPersonal' => $saldo['idSaldoPersonal'],
            'fechaInicio' => $fechaUnica,
            'fechaFin' => $fechaUnica,
            'horasSolicitadas' => $horasSolicitadas,
            'saldoHistorialHoras' => $saldoHistorialHoras,
            'saldoHistorialDias' => $saldoHistorialDias,
            'fechaCreacion' => date('Y-m-d') // Establecer la fecha de creación
        ];

        log_message('info', 'Insertar permiso por horas: ' . print_r($insertData, true));

        $this->permisosPersonalModel->insert($insertData);
         
        // Actualizar saldo de horas
        $result = $this->saldoPersonalModel->actualizarSaldoPersonal($saldo['idSaldoPersonal'], 'Horas', $horasSolicitadas);

    } else {
        $diasSolicitados = $this->permisosPersonalModel->calcularDiasEntreFechas($fechaInicio, $fechaFin);

        log_message('info', 'Días solicitados: ' . $diasSolicitados);

        if ($diasSolicitados > $saldo['saldoActualDias']) {
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

        log_message('info', 'Insertar permiso por días: ' . print_r($insertData, true));

        $this->permisosPersonalModel->insert($insertData);
         
        // Actualizar saldo de días
        $result = $this->saldoPersonalModel->actualizarSaldoPersonal($saldo['idSaldoPersonal'], 'Dias', $diasSolicitados);
    }

    // Pasar los nuevos saldos a la vista si es necesario
    return redirect()->to('/permisos_personal')->with('success', 'Permiso registrado exitosamente.')
                                               ->with('nuevosSaldos', $result);
}

}

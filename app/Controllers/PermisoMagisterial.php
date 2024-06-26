<?php

namespace App\Controllers;

use App\Models\DetalleSaldosTipoPermisoModel;
use App\Models\MaestroModel;
use App\Models\SaldosDocentesModel;
use App\Models\TipoPermisoModel;

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
        $horasOcupadas = $this->request->getVar('horas_ocupadas') ?: 0;
        $rules = [
            'id_maestro' => 'required',
            'id_tipo_permiso' => 'required',
            'fecha_inicio' => 'required|valid_date',
            'fecha_fin' => 'required|valid_date',
            'horas_ocupadas' => 'permit_empty|integer'
        ];
    
        // Validación extendida para permisos por horas
        if ($horasOcupadas > 0) {
            unset($rules['fecha_fin']); // Eliminar la validación de fecha_fin si es por horas
        }
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
    
        $idMaestro = $this->request->getVar('id_maestro');
        $idTipoPermiso = $this->request->getVar('id_tipo_permiso');
        $fechaInicio = $this->request->getVar('fecha_inicio');
        $fechaFin = $this->request->getVar('fecha_fin');
        $horasOcupadas = $this->request->getVar('horas_ocupadas') ?: 0;
    
        $fechaInicioDate = new \DateTime($fechaInicio);
        $fechaFinDate = new \DateTime($fechaFin);
        $diasOcupados = $fechaInicioDate->diff($fechaFinDate)->days + 1;
    
        // Obtener el tipo de permiso y su cantidad de días
        $modelTipoPermiso = new TipoPermisoModel();
        $tipoPermiso = $modelTipoPermiso->find($idTipoPermiso);
    
        if (!$tipoPermiso) {
            return redirect()->back()->withInput()->with('error', 'El tipo de permiso no es válido.');
        }
    
        $cantidadDias = $tipoPermiso['cantidad_dias'];
    
        // Calcular los días y horas disponibles
        $diasDisponibles = $cantidadDias - $diasOcupados;
        $horasDisponibles = 5 - $horasOcupadas;
    
        // Si se solicitaron horas, el día no está completamente disponible
        if ($horasOcupadas > 0) {
            $diasDisponibles -= 1;
            $diasOcupados = 0; // No se cuenta como día ocupado si solo hay horas parciales
        }
    
        // Guardar en la base de datos
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
            'saldo_total_dias' => $diasOcupados,
            'saldo_total_horas' => $horasOcupadas,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'fecha_creacion' => date('Y-m-d H:i:s')
        ]);
    
        if (!$inserted) {
            return redirect()->back()->withInput()->with('error', 'Error al insertar en la tabla saldos_docentes.');
        }
    
        $detalleSaldosTipoPermiso['saldo'] += $diasOcupados;
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
    $modelDetalleSaldosTipoPermiso = new DetalleSaldosTipoPermisoModel();

    // Configuración de la paginación
    $perPage = 10; // Número de registros por página

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
            // Obtén el detalle del saldo del tipo de permiso actual
            $detalle_saldo_permiso = $modelDetalleSaldosTipoPermiso
                ->where('idTipoPermiso', $tipo_permiso['idTipoPermiso'])
                ->where('anio', date('Y'))
                ->first();

            $dias_ocupados = 0;
            $horas_ocupadas = 0;
            $dias_disponibles = $tipo_permiso['cantidad_dias'];
            $horas_disponibles = 0; // Inicializamos a 0 horas disponibles

            if ($detalle_saldo_permiso) {
                $saldo_docente = $modelSaldosDocentes
                    ->where('idDocente', $maestro['idDocente'])
                    ->where('idDetallePermiso', $detalle_saldo_permiso['idDetallePermiso'])
                    ->first();

                if ($saldo_docente) {
                    $dias_ocupados = $saldo_docente['saldo_total_dias'];
                    $horas_ocupadas = $saldo_docente['saldo_total_horas'];
                    $dias_disponibles = $tipo_permiso['cantidad_dias'] - $dias_ocupados;
                    $horas_disponibles = 5 - $horas_ocupadas;

                    if ($horas_ocupadas > 0 && $horas_ocupadas < 5) {
                        $dias_disponibles -= 1;
                    }
                }
            }

            $detalle_saldos_permiso[] = [
                'idTipoPermiso' => $tipo_permiso['idTipoPermiso'],
                'nombre_tipo_permiso' => $tipo_permiso['nombre'],
                'cantidad_dias' => $tipo_permiso['cantidad_dias'],
                'dias_ocupados' => $dias_ocupados,
                'horas_ocupadas' => $horas_ocupadas,
                'dias_disponibles' => $dias_disponibles,
                'horas_disponibles' => $horas_disponibles,
                'fecha_inicio' => ($tipo_permiso['idTipoPermiso'] == $saldo['idDetallePermiso']) ? $fecha_inicio : null,
                'fecha_fin' => ($tipo_permiso['idTipoPermiso'] == $saldo['idDetallePermiso']) ? $fecha_fin : null,
            ];
        }

        $data['saldos_docentes'][] = [
            'nombre_completo' => $nombre_completo,
            'nip' => $nip,
            'fecha_creacion' => $fecha_creacion,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
            'detalle_saldos_permiso' => $detalle_saldos_permiso,
        ];
    }

    $data['tipos_permisos'] = $tipos_permisos;
    $data['pager'] = $pager; // Pasar el paginador a la vista
    $data['fecha_inicio'] = $fecha_inicio; // Agregar la fecha de inicio al array de datos
    $data['fecha_fin'] = $fecha_fin; // Agregar la fecha de fin al array de datos
    $data['validation'] = \Config\Services::validation();

    return view('Permisos/index', $data);
}

    

    protected function filterSaldosDocentes($saldos_docentes, $filters)
    {
        $filtered = [];
        foreach ($saldos_docentes as $saldo_docente) {
            if (!empty($filters['nip']) && strpos($saldo_docente['nip'], $filters['nip']) === false) {
                continue;
            }
            if (!empty($filters['nombre_completo']) && strpos($saldo_docente['nombre_completo'], $filters['nombre_completo']) === false) {
                continue;
            }
            if (!empty($filters['fecha_creacion']) && strpos($saldo_docente['fecha_creacion'], $filters['fecha_creacion']) === false) {
                continue;
            }
            $filtered[] = $saldo_docente;
        }
        return $filtered;
    }
}
?>

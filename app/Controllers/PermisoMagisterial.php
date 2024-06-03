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
        // Validación de datos
        $rules = [
            'id_maestro' => 'required',
            'id_tipo_permiso' => 'required',
            'fecha_inicio' => 'required',
            'fecha_fin' => 'required'
        ];
    
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
    
        // Procesamiento de datos
        $idMaestro = $this->request->getVar('id_maestro');
        $idTipoPermiso = $this->request->getVar('id_tipo_permiso');
        $fechaInicio = $this->request->getVar('fecha_inicio');
        $fechaFin = $this->request->getVar('fecha_fin');
    
        // Calcular la diferencia en días
        $fechaInicio = new \DateTime($fechaInicio);
        $fechaFin = new \DateTime($fechaFin);
        $intervalo = $fechaInicio->diff($fechaFin);
    
        $diasOcupados = $intervalo->days;
    
        // Obtener la cantidad de días definida en el tipo de permiso
        $modelTipoPermiso = new TipoPermisoModel();
        $tipoPermiso = $modelTipoPermiso->find($idTipoPermiso);
    
        if (!$tipoPermiso) {
            return redirect()->back()->withInput()->with('error', 'El tipo de permiso no es válido.');
        }
    
        $cantidadDias = $tipoPermiso['cantidad_dias'];
    
        // Calcular los días disponibles
        $diasDisponibles = $cantidadDias - $diasOcupados;
    
        // Obtener el detalle de saldos de tipo de permiso
        $modelDetalleSaldosTipoPermiso = new DetalleSaldosTipoPermisoModel();
        $detalleSaldosTipoPermiso = $modelDetalleSaldosTipoPermiso
            ->where('idTipoPermiso', $idTipoPermiso)
            ->where('anio', date('Y'))
            ->first();
    
        // Si el detalle_saldos_tipopermiso no existe, crear uno nuevo
        if (!$detalleSaldosTipoPermiso) {
            $detalleSaldosTipoPermiso = [
                'anio' => date('Y'),
                'idTipoPermiso' => $idTipoPermiso,
                'saldo' => 0
            ];
            $modelDetalleSaldosTipoPermiso->insert($detalleSaldosTipoPermiso);
            $detalleSaldosTipoPermiso['idDetallePermiso'] = $modelDetalleSaldosTipoPermiso->getInsertID();
        }
    
        // Actualizar o insertar en la tabla saldos_docentes
        $modelSaldosDocentes = new SaldosDocentesModel();
    
        // Insertar un nuevo registro
        $modelSaldosDocentes->insert([
            'idDocente' => $idMaestro,
            'idDetallePermiso' => $detalleSaldosTipoPermiso['idDetallePermiso'],
            'saldo_total_dias' => $diasOcupados,
            'fecha_creacion' => date('Y-m-d H:i:s')
        ]);
    
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
        $data['tipos_permisos'] = $modelTipoPermiso->findAll(); // Aquí obtenemos todos los tipos de permiso
        // Obtener todos los saldos docentes
        $saldos_docentes = $modelSaldosDocentes->findAll();
    
        // Filtrar los saldos docentes según los filtros
        if (!empty($filters['nip']) || !empty($filters['nombre_completo']) || !empty($filters['fecha_creacion'])) {
            $saldos_docentes = $this->filterSaldosDocentes($saldos_docentes, $filters);
        }
    
        $tipos_permisos = $modelTipoPermiso->findAll();
    
        $data['saldos_docentes'] = [];
    
        foreach ($saldos_docentes as $saldo) {
            $maestro = $modelMaestro->find($saldo['idDocente'] ?? null);
        
            // Verifica que las claves 'nombre_completo', 'nip' y 'fecha_creacion' existan antes de usarlas
            $nombre_completo = $maestro['nombre_completo'] ?? '';
            $nip = $maestro['nip'] ?? '';
            $fecha_creacion = isset($saldo['fecha_creacion']) ? $saldo['fecha_creacion'] : '';
        
            $detalle_saldos_permiso = [];
            foreach ($tipos_permisos as $tipo_permiso) {
                $modelDetalleSaldosTipoPermiso = new DetalleSaldosTipoPermisoModel();
                $detalle_saldo_permiso = $modelDetalleSaldosTipoPermiso
                    ->where('idTipoPermiso', $tipo_permiso['idTipoPermiso'])
                    ->where('anio', date('Y'))
                    ->first();
        
                    // Calcular días, horas y minutos ocupados y disponibles para el maestro actual
                    $dias_ocupados = 0;
                    $dias_disponibles = $tipo_permiso['cantidad_dias'];
                    
                    if ($detalle_saldo_permiso) {
                        $modelSaldosDocentes = new SaldosDocentesModel();
                        $saldo_docente = $modelSaldosDocentes
                            ->where('idDocente', $maestro['idDocente'])
                            ->where('idDetallePermiso', $detalle_saldo_permiso['idDetallePermiso'])
                            ->first();
                    
                        if ($saldo_docente) {
                            $dias_ocupados = $saldo_docente['saldo_total_dias'];
                            $dias_disponibles = $tipo_permiso['cantidad_dias'] - $dias_ocupados;
                        }
                    }

        
                // Agregar los detalles al array
                $detalle_saldos_permiso[] = [
                    'dias_ocupados' => $dias_ocupados,
                    'dias_disponibles' => $dias_disponibles,
                    'idTipoPermiso' => $detalle_saldo_permiso ? $detalle_saldo_permiso['idTipoPermiso'] : null,
                    'limite_dias' => $tipo_permiso['cantidad_dias'],
                ];
            }
        
            $data['saldos_docentes'][] = [
                'nombre_completo' => $nombre_completo,
                'nip' => $nip,
                'fecha_creacion' => $fecha_creacion,
                'detalle_saldos_permiso' => $detalle_saldos_permiso,
            ];
        }
    
        $data['tipos_permisos'] = $tipos_permisos;
    
        return view('Permisos/index', $data);
    }
    
    
    
    private function filterSaldosDocentes($saldos_docentes, $filters)
    {
        $filtered = [];
    
        foreach ($saldos_docentes as $saldo) {
            $include = true;
    
            if (!empty($filters['nip'])) {
                if (!isset($saldo['nip']) || strpos($saldo['nip'], $filters['nip']) === false) {
                    $include = false;
                }
            }
    
            if (!empty($filters['nombre_completo'])) {
                $modelMaestro = new MaestroModel();
                $maestro = $modelMaestro->find($saldo['idDocente']);
                if (!isset($maestro['nombre_completo']) || strpos($maestro['nombre_completo'], $filters['nombre_completo']) === false) {
                    $include = false;
                }
            }
    
            if (!empty($filters['fecha_creacion'])) {
                if (!isset($saldo['fecha_creacion']) || $filters['fecha_creacion'] != date('Y-m-d', strtotime($saldo['fecha_creacion']))) {
                    $include = false;
                }
            }
    
            if ($include) {
                $filtered[] = $saldo;
            }
        }
    
        return $filtered;
    }    
}

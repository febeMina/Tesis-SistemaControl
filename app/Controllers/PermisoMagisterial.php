<?php

namespace App\Controllers;

use App\Models\DetalleSaldosTipoPermisoModel;
use App\Models\MaestroModel;
use App\Models\SaldosDocentesModel;
use App\Models\TipoPermisoModel;
use App\Models\HistorialPermisosModel;
use App\Models\SolicitudPermisoModel;

class PermisoMagisterial extends BaseController
{
    public function create()
    {
        helper('form');

        $modelMaestro = new MaestroModel();
        $data['maestros'] = $modelMaestro->findAll();

        $modelTipoPermiso = new TipoPermisoModel();
        $data['tipos_permisos'] = $modelTipoPermiso->findAll();

        return view('Permisos/create', $data);
    }

    public function store()
    {
        $request = \Config\Services::request();
        $logger = \Config\Services::logger();
    
        $idTipoPermiso = $request->getPost('id_tipo_permiso');
        $horasOcupadas = $request->getPost('horas_ocupadas');
        $idDocente = $request->getPost('id_docente');
        $fechaInicio = $request->getPost('fecha_inicio');
        $fechaFin = $request->getPost('fecha_fin');
    
        $logger->info('Datos recibidos: ID Tipo Permiso = ' . $idTipoPermiso . ', Horas Ocupadas = ' . $horasOcupadas . ', ID Docente = ' . $idDocente . ', Fecha Inicio = ' . $fechaInicio . ', Fecha Fin = ' . $fechaFin);
    
        // Validaciones
        if (empty($idTipoPermiso) || !is_numeric($idTipoPermiso)) {
            $logger->error('ID de tipo de permiso inválido: ID = ' . $idTipoPermiso);
            return redirect()->back()->withInput()->with('error', 'ID de tipo de permiso inválido.');
        }
    
        if ($horasOcupadas !== '' && !is_numeric($horasOcupadas)) {
            $logger->error('Horas ocupadas inválidas: Horas ocupadas = ' . $horasOcupadas);
            return redirect()->back()->withInput()->with('error', 'Horas ocupadas inválidas.');
        }
    
        if (!strtotime($fechaInicio) || !strtotime($fechaFin)) {
            $logger->error('Fechas inválidas: Inicio = ' . $fechaInicio . ', Fin = ' . $fechaFin);
            return redirect()->back()->withInput()->with('error', 'Fechas inválidas.');
        }
    
        $diasOcupados = $this->calcularDiasEntreFechas($fechaInicio, $fechaFin);
    
        // Recuperar tipo de permiso
        $modelTipoPermiso = new TipoPermisoModel();
        $tipoPermiso = $modelTipoPermiso->find($idTipoPermiso);
    
        if (!$tipoPermiso) {
            $logger->error('Tipo de permiso no encontrado: ID = ' . $idTipoPermiso);
            return redirect()->back()->withInput()->with('error', 'Tipo de permiso no encontrado.');
        }
    
        $cantidadDias = $tipoPermiso['cantidadDias'] ?? 0;
    
        // Recuperar saldo disponible
        $modelDetalleSaldosTipoPermiso = new DetalleSaldosTipoPermisoModel();
        $detalleSaldosTipoPermiso = $modelDetalleSaldosTipoPermiso->where('idTipoPermiso', $idTipoPermiso)
                                                                 ->where('anio', date('Y'))
                                                                 ->first();
    
        if (!$detalleSaldosTipoPermiso) {
            $logger->info('Saldo no encontrado. Se creará un nuevo registro.');
            $saldoInicial = $cantidadDias; // Usamos la cantidad de días del tipo de permiso como saldo inicial
            $idDetallePermiso = $modelDetalleSaldosTipoPermiso->insert([
                'anio' => date('Y'),
                'idTipoPermiso' => $idTipoPermiso,
                'saldo' => $saldoInicial
            ], true);
    
            // Verificar si se obtuvo el idDetallePermiso
            if (!$idDetallePermiso) {
                $logger->error('Error al crear registro en detalleSaldosTipoPermiso.');
                return redirect()->back()->withInput()->with('error', 'Error al crear el registro de saldo.');
            }
    
            $detalleSaldosTipoPermiso = [
                'idDetallePermiso' => $idDetallePermiso,
                'saldo' => $saldoInicial
            ];
        }
    
        $saldoDisponible = $cantidadDias - ($detalleSaldosTipoPermiso['saldo'] ?? 0);
    
        // Verificación del saldo disponible
        if ($diasOcupados > $saldoDisponible) {
            $logger->error('Saldo insuficiente: tipoPermiso = ' . $idTipoPermiso . ', saldoDisponible = ' . $saldoDisponible . ', diasOcupados = ' . $diasOcupados);
            return redirect()->back()->withInput()->with('error', 'No hay suficientes días disponibles para este tipo de permiso.');
        }
    
        // Iniciar transacción
        $db = \Config\Database::connect();
        $db->transBegin();
    
        try {
            // Actualizamos el saldo
            $nuevoSaldo = $saldoDisponible - $diasOcupados;
            if (isset($detalleSaldosTipoPermiso['idDetallePermiso'])) {
                $modelDetalleSaldosTipoPermiso->update($detalleSaldosTipoPermiso['idDetallePermiso'], ['saldo' => $nuevoSaldo]);
            } else {
                throw new \Exception('idDetallePermiso no encontrado en detalleSaldosTipoPermiso.');
            }
    
            // Insertamos el nuevo permiso.
            $modelHistorialPermisos = new HistorialPermisosModel();
            $modelHistorialPermisos->insert([
                'idDocente' => $idDocente,
                'idTipoPermiso' => $idTipoPermiso,
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'dias_ocupados' => $diasOcupados,
                'horas_ocupadas' => $horasOcupadas !== '' ? $horasOcupadas : 0,
                'fecha_creacion' => date('Y-m-d H:i:s')
            ]);
    
            // Actualizar saldos_docentes.
            $modelSaldosDocentes = new SaldosDocentesModel();
            $existingSaldo = $modelSaldosDocentes->where('idDocente', $idDocente)->first();
            
            if ($existingSaldo) {
                $idSaldoDocente = $existingSaldo['idSaldoDocente'] ?? null;
                if ($idSaldoDocente) {
                    $nuevoSaldoDocente = $existingSaldo['saldo'] - $diasOcupados;
                    $modelSaldosDocentes->update($idSaldoDocente, ['saldo' => $nuevoSaldoDocente]);
                } else {
                    throw new \Exception('ID de saldo de docente no encontrado.');
                }
            } else {
                // Insertar un nuevo registro en caso de que no exista
                $modelSaldosDocentes->insert([
                    'idDocente' => $idDocente,
                    'saldo' => $diasOcupados
                ]);
            }
            // Confirmar transacción
            $db->transCommit();
            return redirect()->to('/permisos')->with('success', 'Permiso creado exitosamente.');
        } catch (\Exception $e) {
            $db->transRollback();
            $logger->error('Error al crear el permiso: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error al crear el permiso.');
        }
    }    

    private function calcularDiasEntreFechas($fechaInicio, $fechaFin)
    {
        $inicio = new \DateTime($fechaInicio);
        $fin = new \DateTime($fechaFin);
        $intervalo = $inicio->diff($fin);
        return $intervalo->days + 1; // Sumamos 1 para incluir el último día
    }

    public function index()
    {
        $request = service('request');
        $filters = [
            'nip' => $request->getVar('nip'),
            'nombreCompleto' => $request->getVar('nombreCompleto'),
            'fecha_solicitud' => $request->getVar('fecha_solicitud')
        ];
    
        $modelHistorialPermisos = new HistorialPermisosModel();
        $modelTipoPermiso = new TipoPermisoModel();
        $modelDocente = new MaestroModel();
    
        $perPage = 10;
    
        if (!empty($filters['nip']) || !empty($filters['nombreCompleto']) || !empty($filters['fecha_solicitud'])) {
            $historial_permisos = $this->filterHistorialPermisos($modelHistorialPermisos, $filters);
        } else {
            $historial_permisos = $modelHistorialPermisos->paginate($perPage, 'group1');
        }
    
        $pager = $modelHistorialPermisos->pager;
        $tipos_permisos = $modelTipoPermiso->findAll();
    
        $data['historial_permisos'] = [];
        $data['tipos_permisos'] = $tipos_permisos;
    
        foreach ($historial_permisos as $permiso) {
            $docente = $modelDocente->find($permiso['idDocente'] ?? null);
    
            if ($docente) {
                $nombreCompleto = $docente['nombreCompleto'];
                $nip = $docente['nip'];
                $fecha_creacion = $permiso['fecha_creacion'];
    
                $data['historial_permisos'][] = [
                    'idHistorialPermiso' => $permiso['idHistorialPermiso'],
                    'idDocente' => $permiso['idDocente'],
                    'nombreCompleto' => $nombreCompleto,
                    'nip' => $nip,
                    'fecha_inicio' => $permiso['fecha_inicio'],
                    'fecha_fin' => $permiso['fecha_fin'],
                    'dias_ocupados' => $permiso['dias_ocupados'],
                    'horas_ocupadas' => $permiso['horas_ocupadas'],
                    'fecha_creacion' => $fecha_creacion,
                    'detalle_saldos_permiso' => $this->getDetalleSaldosPermiso($permiso['idHistorialPermiso']),
                    'tipo_permiso' => $this->getTipoPermiso($permiso['idTipoPermiso'], $tipos_permisos)
                ];
            }
        }
        
        $data['pager'] = $pager;
    
        return view('Permisos/index', $data);
    }
    

    public function getDetalleSaldosPermiso($idHistorialPermiso)
{
    $modelHistorialPermisos = new HistorialPermisosModel();
    return $modelHistorialPermisos->select('historial_permisos.fecha_inicio, historial_permisos.fecha_fin, historial_permisos.dias_ocupados, historial_permisos.horas_ocupadas, tipo_permisos.nombre AS nombreTipoPermiso, tipo_permisos.cantidadDias')
                                  ->join('tipo_permisos', 'historial_permisos.idTipoPermiso = tipo_permisos.idTipoPermiso')
                                  ->where('idHistorialPermiso', $idHistorialPermiso)
                                  ->findAll();
}


private function filterHistorialPermisos($modelHistorialPermisos, $filters)
    {
        $builder = $modelHistorialPermisos->builder();
    
        if (!empty($filters['nip'])) {
            $builder->join('maestros', 'maestros.idMaestro = historial_permisos.idDocente')
                    ->where('maestros.nip', $filters['nip']);
        }
    
        if (!empty($filters['nombreCompleto'])) {
            $builder->join('maestros', 'maestros.idMaestro = historial_permisos.idDocente')
                    ->where('maestros.nombreCompleto LIKE', '%' . $filters['nombreCompleto'] . '%');
        }
    
        if (!empty($filters['fecha_solicitud'])) {
            $builder->where('historial_permisos.fecha_creacion', $filters['fecha_solicitud']);
        }
    
        return $builder->paginate(10, 'group1');
    }
   

private function getTipoPermiso($idTipoPermiso, $tipos_permisos)
{
    foreach ($tipos_permisos as $tipo_permiso) {
        if ($tipo_permiso['idTipoPermiso'] == $idTipoPermiso) {
            return [
                'nombre' => $tipo_permiso['nombre'],
                'cantidadDias' => $tipo_permiso['cantidadDias']
            ];
        }
    }
    return [
        'nombre' => 'Desconocido',
        'cantidadDias' => 0
    ];
}

    public function details($id)
    {
        $modelHistorialPermisos = new HistorialPermisosModel();
        $modelDocente = new MaestroModel();
        $modelTipoPermiso = new TipoPermisoModel();

        $permiso = $modelHistorialPermisos->find($id);

        if (!$permiso) {
            return redirect()->back()->with('error', 'Permiso no encontrado.');
        }

        $docente = $modelDocente->find($permiso['idDocente']);
        $tipo_permiso = $modelTipoPermiso->find($permiso['idTipoPermiso']);

        $data['permiso'] = $permiso;
        $data['docente'] = $docente;
        $data['tipo_permiso'] = $tipo_permiso;

        return view('Permisos/details', $data);
    }

    public function delete($id)
    {
        $modelHistorialPermisos = new HistorialPermisosModel();
        $permiso = $modelHistorialPermisos->find($id);

        if ($permiso) {
            $modelHistorialPermisos->delete($id);
            return redirect()->to('/permiso_magisterial/index')->with('success', 'Permiso eliminado exitosamente.');
        }

        return redirect()->back()->with('error', 'No se pudo eliminar el permiso.');
    }
}

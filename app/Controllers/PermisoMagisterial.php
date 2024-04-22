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

        return view('permiso_magisterial/create', $data);
    }

    public function store()
    {
        $modelSaldosDocentes = new SaldosDocentesModel();
        $modelDetalleSaldosTipoPermiso = new DetalleSaldosTipoPermisoModel();
    
        // Validación de datos
        $rules = [
            'id_docente' => 'required',
            'id_tipo_permiso' => 'required',
            'fecha_inicio' => 'required',
            'fecha_fin' => 'required'
        ];
    
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
    
        // Procesamiento de datos
        $idDocente = $this->request->getVar('id_docente');
        $idTipoPermiso = $this->request->getVar('id_tipo_permiso');
        $fechaInicio = $this->request->getVar('fecha_inicio');
        $fechaFin = $this->request->getVar('fecha_fin');
    
        // Verificar si el idDetallePermiso existe
        $detalleSaldosTipoPermiso = $modelDetalleSaldosTipoPermiso->find($idTipoPermiso);
        if (!$detalleSaldosTipoPermiso) {
            return redirect()->back()->withInput()->with('error', 'El tipo de permiso seleccionado no es válido.');
        }
    
        $data = [
            'idDocente' => $idDocente,
            'idDetallePermiso' => $idTipoPermiso,
            'saldo_total_dias' => 5 // Este dato debe ser modificado según tu lógica de negocio
        ];
    
        $modelSaldosDocentes->save($data);
    
        $data = [
            'anio' => date('Y'),
            'idTipoPermiso' => $idTipoPermiso,
            'saldo' => 5 // Este dato debe ser modificado según tu lógica de negocio
        ];
    
        $modelDetalleSaldosTipoPermiso->save($data);
    
        return redirect()->to(base_url('permiso_magisterial/index'));
    }
    

    public function index()
    {
        $modelSaldosDocentes = new SaldosDocentesModel();
        $saldos_docentes = $modelSaldosDocentes->findAll();

        $data['saldos_docentes'] = [];

        foreach ($saldos_docentes as $saldo) {
            $modelMaestro = new MaestroModel();
            $maestro = $modelMaestro->find($saldo->idDocente);

            $modelTipoPermiso = new TipoPermisoModel();
            $tipos_permisos = $modelTipoPermiso->findAll();

            $detalle_saldos_permiso = [];
            foreach ($tipos_permisos as $tipo_permiso) {
                $modelDetalleSaldosTipoPermiso = new DetalleSaldosTipoPermisoModel();
                $detalle_saldo_permiso = $modelDetalleSaldosTipoPermiso->where('idTipoPermiso', $tipo_permiso->idTipoPermiso)->where('anio', date('Y'))->first();

                $detalle_saldos_permiso[] = [
                    'tipo_permiso' => $tipo_permiso->nombre,
                    'dias_disponibles' => $detalle_saldo_permiso ? $detalle_saldo_permiso->saldo : 0,
                ];
            }

            $data['saldos_docentes'][] = [
                'nombre_completo' => $maestro->nombre_completo,
                'nip' => $maestro->nip,
                'fecha_solicitud' => date('Y-m-d'), // Fecha de solicitud (debe ser reemplazada por la fecha real de solicitud)
                'detalle_saldos_permiso' => $detalle_saldos_permiso,
                'saldo_total_dias' => $saldo->saldo_total_dias
            ];
        }

        return view('permiso_magisterial/index', $data);
    }
}

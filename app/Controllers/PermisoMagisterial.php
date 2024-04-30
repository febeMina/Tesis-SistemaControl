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

    // Guardar el permiso
    $modelSaldosDocentes = new SaldosDocentesModel();
    $modelDetalleSaldosTipoPermiso = new DetalleSaldosTipoPermisoModel();

    // Verificar si el idDetallePermiso existe
    $detalleSaldosTipoPermiso = $modelDetalleSaldosTipoPermiso->find($idTipoPermiso);
    if (!$detalleSaldosTipoPermiso) {
        return redirect()->back()->withInput()->with('error', 'El tipo de permiso seleccionado no es válido.');
    }

    // Insertar en la tabla 'saldos_docentes'
    $dataSaldosDocentes = [
        'idDocente' => $idMaestro,
        'idDetallePermiso' => $idTipoPermiso,
        'saldo_total_dias' => $detalleSaldosTipoPermiso['cantidad_dias'] ?? 0
    ];
    $modelSaldosDocentes->insert($dataSaldosDocentes);

    // Insertar en la tabla 'detalle_saldos_tipopermiso'
    $dataDetalleSaldosTipoPermiso = [
        'anio' => date('Y'),
        'idTipoPermiso' => $idTipoPermiso,
        'saldo' => $detalleSaldosTipoPermiso['cantidad_dias'] ?? 0
    ];
    $modelDetalleSaldosTipoPermiso->insert($dataDetalleSaldosTipoPermiso);

    return redirect()->to(base_url('public/permiso_magisterial'))->with('success', 'El permiso magisterial ha sido creado exitosamente.');
}


    public function index()
    {
        $modelSaldosDocentes = new SaldosDocentesModel();
        $saldos_docentes = $modelSaldosDocentes->findAll();

        $modelTipoPermiso = new TipoPermisoModel();
        $tipos_permisos = $modelTipoPermiso->findAll();

        $data['saldos_docentes'] = [];

        foreach ($saldos_docentes as $saldo) {
            $modelMaestro = new MaestroModel();
            $maestro = $modelMaestro->find($saldo['idDocente'] ?? null);

            $detalle_saldos_permiso = [];
            foreach ($tipos_permisos as $tipo_permiso) {
                $modelDetalleSaldosTipoPermiso = new DetalleSaldosTipoPermisoModel();
                $detalle_saldo_permiso = $modelDetalleSaldosTipoPermiso
                    ->where('idTipoPermiso', $tipo_permiso['idTipoPermiso'])
                    ->where('anio', date('Y'))
                    ->first();

                $detalle_saldos_permiso[] = [
                    'dias_ocupados' => 0, // Establecer a 0 por defecto
                    'dias_disponibles' => $detalle_saldo_permiso ? $detalle_saldo_permiso['saldo'] : 0,
                    'idTipoPermiso' => $detalle_saldo_permiso ? $detalle_saldo_permiso['idTipoPermiso'] : null,
                ];
            }

            $data['saldos_docentes'][] = [
                'nombre_completo' => $maestro['nombre_completo'] ?? '',
                'nip' => $maestro['nip'] ?? '',
                'fecha_solicitud' => date('Y-m-d'), // Fecha de solicitud (debe ser modificada según tu lógica de negocio)
                'detalle_saldos_permiso' => $detalle_saldos_permiso,
            ];
        }

        $data['tipos_permisos'] = $tipos_permisos;

        return view('Permisos/index', $data);
    }
}


<?php

namespace App\Controllers;

use App\Models\SaldosDocentesModel;
use App\Models\MaestroModel;
use App\Models\TipoPermisoModel;

class ReportController extends BaseController
{
    public function index()
{
    $request = service('request');
    $fecha_inicio = $request->getVar('fecha_inicio');
    $fecha_fin = $request->getVar('fecha_fin');

    if (empty($fecha_inicio) || empty($fecha_fin)) {
        return redirect()->back()->with('error', 'Por favor selecciona ambas fechas.');
    }

    $modelSaldosDocentes = new SaldosDocentesModel();
    $modelMaestro = new MaestroModel();
    $modelTipoPermiso = new TipoPermisoModel();

    $permisos = $modelSaldosDocentes
        ->where('fecha_inicio >=', $fecha_inicio)
        ->where('fecha_fin <=', $fecha_fin)
        ->findAll();

    if (empty($permisos)) {
        log_message('error', 'No se encontraron permisos para las fechas seleccionadas.');
    }

    $report = [];
    foreach ($permisos as $permiso) {
        $maestro = $modelMaestro->find($permiso['idDocente']);
        $tipo_permiso = $modelTipoPermiso->find($permiso['idDetallePermiso']);

        $report[] = [
            'nip' => $maestro['nip'],
            'nombre_completo' => $maestro['nombre_completo'],
            'fecha_permiso' => $permiso['fecha_inicio'] . ' - ' . $permiso['fecha_fin'],
            'tipo_permiso' => $tipo_permiso['nombre_tipo_permiso'],
            'dias_ocupados' => $permiso['saldo_total_dias']
        ];
    }

    $data['report'] = $report;
    $data['fecha_inicio'] = $fecha_inicio;
    $data['fecha_fin'] = $fecha_fin;

    return view('usuario', $data);
}

}

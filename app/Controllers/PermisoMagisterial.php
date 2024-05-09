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
    
        // Calcular los días ocupados
        $fechaInicio = new \DateTime($fechaInicio);
        $fechaFin = new \DateTime($fechaFin);
        $diasOcupados = $fechaInicio->diff($fechaFin)->days;
    
        // Obtener la cantidad de días definida en el tipo de permiso
        $modelTipoPermiso = new TipoPermisoModel();
        $tipoPermiso = $modelTipoPermiso->find($idTipoPermiso);
        
        if (!$tipoPermiso) {
            return redirect()->back()->withInput()->with('error', 'El tipo de permiso no es válido.');
        }

        $cantidadDias = $tipoPermiso['cantidad_dias'];
    
        // Calcular los días disponibles
        $diasDisponibles = $cantidadDias - $diasOcupados;
    
        // Verificar la existencia del idDetallePermiso
        $modelDetalleSaldosTipoPermiso = new DetalleSaldosTipoPermisoModel();
        $detalleSaldosTipoPermiso = $modelDetalleSaldosTipoPermiso->find($idTipoPermiso);
    
        // Si el detalle_saldos_tipopermiso no existe, mostrar un mensaje de error
        if (!$detalleSaldosTipoPermiso) {
            return redirect()->back()->withInput()->with('error', 'El idDetallePermiso no es válido.');
        }
    
        // Insertar en la tabla saldos_docentes
        $modelSaldosDocentes = new SaldosDocentesModel();
        $saldoTotalDias = $diasOcupados;
        $inserted = $modelSaldosDocentes->insert([
            'idDocente' => $idMaestro,
            'idDetallePermiso' => $idTipoPermiso,
            'saldo_total_dias' => $saldoTotalDias
        ]);

        if (!$inserted) {
            return redirect()->back()->withInput()->with('error', 'Error al insertar en la tabla saldos_docentes.');
        }
    
        // Insertar en la tabla detalle_saldos_tipopermiso
        $dataDetalleSaldosTipoPermiso = [
            'anio' => date('Y'),
            'idTipoPermiso' => $idTipoPermiso,
            'saldo' => $diasDisponibles // Guardar los días disponibles
        ];
        $insertedDetalle = $modelDetalleSaldosTipoPermiso->insert($dataDetalleSaldosTipoPermiso);

        if (!$insertedDetalle) {
            return redirect()->back()->withInput()->with('error', 'Error al insertar en la tabla detalle_saldos_tipopermiso.');
        }
    
        return redirect()->to(base_url('public/permiso_magisterial/index'))->with('success', 'El permiso magisterial ha sido creado exitosamente.');
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

            // Calcular días ocupados y disponibles
            $dias_ocupados = 0; // Inicializar días ocupados en 0
            $dias_disponibles = $detalle_saldo_permiso ? $detalle_saldo_permiso['saldo'] : 0; // Inicializar días disponibles con el saldo

            // Aquí debes realizar los cálculos para los días ocupados
            // Puedes obtener los datos necesarios para calcularlos desde la base de datos o cualquier otra fuente de datos

            // Agregar los detalles al array
            $detalle_saldos_permiso[] = [
                'dias_ocupados' => $dias_ocupados,
                'dias_disponibles' => $dias_disponibles,
                'idTipoPermiso' => $detalle_saldo_permiso ? $detalle_saldo_permiso['idTipoPermiso'] : null,
                'limite_dias' => $tipo_permiso['cantidad_dias'], // Aquí deberías obtener el límite de días del tipo de permiso
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

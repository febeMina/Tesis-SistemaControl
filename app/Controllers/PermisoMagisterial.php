<?php

namespace App\Controllers;

use App\Models\SaldosDocentesModel;
use App\Models\DetalleSaldosTipoPermisoModel;
use CodeIgniter\Controller;

class PermisoMagisterial extends Controller
{
    public function index()
    {
        $saldosDocentesModel = new SaldosDocentesModel();
        $saldos_docentes = $saldosDocentesModel->findAll();
    
        return view('permiso_magisterial/index', ['saldos_docentes' => $saldos_docentes]);
    }
    
    public function registrar_permiso()
    {
        $request = \Config\Services::request();
        $idDocente = $request->getPost('id_docente');
        $idTipoPermiso = $request->getPost('id_tipo_permiso');
        $fechaInicio = $request->getPost('fecha_inicio');
        $fechaFin = $request->getPost('fecha_fin');
    
        $diferenciaFechas = strtotime($fechaFin) - strtotime($fechaInicio);
        $diasPermiso = round($diferenciaFechas / (60 * 60 * 24)) + 1;
    
        $saldosDocentesModel = new SaldosDocentesModel();
        $saldoDocente = $saldosDocentesModel->where('idDocente', $idDocente)->first();
        if ($saldoDocente) {
            $saldoTotalDias = $saldoDocente['saldo_total_dias'];
    
            $detalleSaldosTipoPermisoModel = new DetalleSaldosTipoPermisoModel();
            $detalleSaldo = $detalleSaldosTipoPermisoModel->where('idTipoPermiso', $idTipoPermiso)->first();
            if ($detalleSaldo) {
                $saldoTipoPermiso = $detalleSaldo['saldo'];
                if ($diasPermiso <= $saldoTotalDias && $diasPermiso <= $saldoTipoPermiso) {
                    $nuevoSaldoTotalDias = $saldoTotalDias - $diasPermiso;
                    $nuevoSaldoTipoPermiso = $saldoTipoPermiso - $diasPermiso;
    
                    $saldosDocentesModel->update($saldoDocente['idSaldoDocentes'], ['saldo_total_dias' => $nuevoSaldoTotalDias]);
                    $detalleSaldosTipoPermisoModel->update($detalleSaldo['idDetallePermiso'], ['saldo' => $nuevoSaldoTipoPermiso]);
    
                    return redirect()->to(site_url('permiso_magisterial'))->with('success', 'El permiso se ha registrado correctamente.');
                } else {
                    return redirect()->to(site_url('permiso_magisterial'))->withInput()->with('error', 'El docente no tiene suficientes días de permiso disponibles.');
                }
            } else {
                return redirect()->to(site_url('permiso_magisterial'))->withInput()->with('error', 'No se encontró el saldo del tipo de permiso seleccionado.');
            }
        } else {
            return redirect()->to(site_url('permiso_magisterial'))->withInput()->with('error', 'No se encontró el saldo de días de permiso del docente.');
        }
    }
    
    
    
    
}

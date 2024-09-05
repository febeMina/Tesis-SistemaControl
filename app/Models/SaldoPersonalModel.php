<?php

namespace App\Models;

use CodeIgniter\Model;

class SaldoPersonalModel extends Model
{
    protected $table = 'saldos_personal';
    protected $primaryKey = 'idSaldoPersonal';

    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['idDocente', 'idTipoPermiso', 'saldoActualDias', 'saldoActualHoras'];

    protected $useTimestamps = false;

    protected $validationRules = [
        'idDocente' => 'required|integer',
        'idTipoPermiso' => 'required|integer',
        'saldoActualDias' => 'required|decimal',
        'saldoActualHoras' => 'required|decimal'
    ];

    public function obtenerSaldosPorDocente($idDocente)
    {
        return $this->where('idDocente', $idDocente)->findAll();
    }

    public function getSaldoPersonalId($idDocente, $idTipoPermiso)
    {
        return $this->where('idDocente', $idDocente)
                    ->where('idTipoPermiso', $idTipoPermiso)
                    ->first();
    }

    public function actualizarSaldoDias($idDocente, $idTipoPermiso, $diferenciaDias)
    {
        $this->where(['idDocente' => $idDocente, 'idTipoPermiso' => $idTipoPermiso])
             ->set('saldoActualDias', "saldoActualDias - $diferenciaDias", FALSE)
             ->update();
    }

    public function actualizarSaldoHoras($idDocente, $idTipoPermiso, $diferenciaHoras)
    {
        $this->where(['idDocente' => $idDocente, 'idTipoPermiso' => $idTipoPermiso])
             ->set('saldoActualHoras', "saldoActualHoras - $diferenciaHoras", FALSE)
             ->update();
    }

    public function actualizarSaldoPersonal($idSaldoPersonal, $tipoSolicitud, $cantidadSolicitud)
{
    $horasParametro = 6; // 6 horas = 1 día
    $saldo = $this->find($idSaldoPersonal);
    if (!$saldo) {
        throw new \Exception('Saldo no encontrado.');
    }

    if ($tipoSolicitud == 'Dias') {
        $diasRestar = $cantidadSolicitud;
        /*
            Regla de 3:
            1 día = 6 horas
            X días = ????

            Donde X = cantidadSolicitud
            Por lo que (X * 6) / 1
        */
        $horasRestar = $diasRestar * $horasParametro;
    } else {
        // Horas
        $horasRestar = $cantidadSolicitud;
        /*
            Regla de 3:
            1 día = 6 horas
            ???? = X horas

            Donde X = cantidadSolicitud
            Por lo que (1 * X) / 6
        */
        $diasRestar = $horasRestar / $horasParametro;
    }

    $nuevoSaldoDias = $saldo['saldoActualDias'] - $diasRestar;
    $nuevoSaldoHoras = $saldo['saldoActualHoras'] - $horasRestar;

    $this->update($idSaldoPersonal, [
        'saldoActualDias' => $nuevoSaldoDias,
        'saldoActualHoras' => $nuevoSaldoHoras
    ]);
    

    // Retornar los nuevos saldos
    return [
        'nuevoSaldoDias' => $nuevoSaldoDias,
        'nuevoSaldoHoras' => $nuevoSaldoHoras
    ];
}


}

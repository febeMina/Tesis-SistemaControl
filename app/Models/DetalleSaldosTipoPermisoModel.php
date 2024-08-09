<?php

namespace App\Models;

use CodeIgniter\Model;

class DetalleSaldosTipoPermisoModel extends Model
{
    protected $table = 'detalle_saldos_tipopermiso';
    protected $primaryKey = 'idDetallePermiso';
    protected $allowedFields = ['anio', 'idTipoPermiso', 'saldo'];

    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $useTimestamps = false;
    protected $validationRules = [
        'anio' => 'required',
        'idTipoPermiso' => 'required',
        'saldo' => 'required'
    ];

    public function getSaldoInicial($idTipoPermiso)
    {
        return $this->where('idTipoPermiso', $idTipoPermiso)
                    ->where('anio', date('Y'))
                    ->first()['saldo'] ?? null;
    }

    public function getSaldo($idTipoPermiso, $anio)
    {
        return $this->where('idTipoPermiso', $idTipoPermiso)
                    ->where('anio', $anio)
                    ->first();
    }

    public function setSaldo($idTipoPermiso, $anio, $saldo)
    {
        $data = [
            'anio' => $anio,
            'idTipoPermiso' => $idTipoPermiso,
            'saldo' => $saldo
        ];
        $existing = $this->getSaldo($idTipoPermiso, $anio);
        if ($existing) {
            $this->update($existing['idDetallePermiso'], $data);
        } else {
            $this->insert($data);
        }
    }

    public function getOrCreateSaldo($idTipoPermiso, $anio)
    {
        $saldo = $this->getSaldo($idTipoPermiso, $anio);
        if (!$saldo) {
            $this->setSaldo($idTipoPermiso, $anio, $this->getSaldoInicial($idTipoPermiso));
            $saldo = $this->getSaldo($idTipoPermiso, $anio);
        }
        return $saldo;
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class DetalleSaldosTipoPermisoModel extends Model
{
    protected $table = 'detalle_saldos_tipopermiso';
    protected $primaryKey = 'idDetallePermiso';
    protected $allowedFields = ['anio', 'idTipoPermiso', 'saldo'];

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $useTimestamps = false;

    protected $validationRules = [
        'anio' => 'required',
        'idTipoPermiso' => 'required',
        'saldo' => 'required'
    ];

    // Otras configuraciones...
}

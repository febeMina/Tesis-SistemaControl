<?php

namespace App\Models;

use CodeIgniter\Model;

class DetalleSaldosTipoPermisoModel extends Model
{
    protected $table = 'detalle_saldos_tipopermiso';
    protected $primaryKey = 'idDetallePermiso';
    protected $allowedFields = ['anio', 'idTipoPermiso', 'saldo'];
}


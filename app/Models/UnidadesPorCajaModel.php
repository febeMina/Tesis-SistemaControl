<?php

namespace App\Models;

use CodeIgniter\Model;

class UnidadesPorCajaModel extends Model
{
    protected $table = 'udm_caja';
    protected $primaryKey = 'idUdmCaja';
    protected $allowedFields = ['nombreCaja', 'estado'];
}
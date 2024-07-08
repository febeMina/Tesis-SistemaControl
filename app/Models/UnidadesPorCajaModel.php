<?php

namespace App\Models;

use CodeIgniter\Model;

class UnidadesPorCajaModel extends Model
{
    protected $table = 'unidades_por_caja';
    protected $primaryKey = 'idUnidadesPorCaja';
    protected $allowedFields = ['tipo_unidad', 'unidades', 'estado'];
}
<?php

namespace App\Models;

use CodeIgniter\Model;

class UnidadesIndividualesModel extends Model
{
    protected $table = 'unidad_individual';
    protected $primaryKey = 'idUnidades_individuales';
    protected $allowedFields = ['unidadades_individuales', 'estado'];
}
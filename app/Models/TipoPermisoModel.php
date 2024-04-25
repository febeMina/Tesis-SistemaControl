<?php

namespace App\Models;

use CodeIgniter\Model;

class TipoPermisoModel extends Model
{
    protected $table = 'tipo_permisos';
    protected $primaryKey = 'idTipoPermiso';
    protected $allowedFields = ['nombre', 'cantidad_dias'];

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $useTimestamps = false;

    protected $validationRules = [
        'nombre' => 'required',
        'cantidad_dias' => 'required'
    ];

    // Otras configuraciones...
}

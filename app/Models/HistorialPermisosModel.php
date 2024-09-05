<?php

namespace App\Models;

use CodeIgniter\Model;

class HistorialPermisosModel extends Model
{
    protected $table = 'historial_permisos';
    protected $primaryKey = 'idHistorialPermiso';
    protected $allowedFields = [
        'idDocente', 
        'idTipoPermiso', 
        'fecha_inicio', 
        'fecha_fin', 
        'dias_ocupados', 
        'horas_ocupadas', 
        'fecha_creacion'
    ];

    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $useTimestamps = false;
    protected $validationRules = [
        'idDocente' => 'required|integer',
        'idTipoPermiso' => 'required|integer',
        'fecha_inicio' => 'required|valid_date',
        'fecha_fin' => 'required|valid_date',
        'dias_ocupados' => 'required|integer',
        'horas_ocupadas' => 'permit_empty|integer',
        'fecha_creacion' => 'required|valid_date'
    ];
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class TipoPermisoModel extends Model
{
    protected $table = 'tipo_permisos';
    protected $primaryKey = 'idTipoPermiso';
    protected $allowedFields = ['nombre', 'cantidad_dias', 'estado'];

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $useTimestamps = false;

    protected $validationRules = [
        'nombre' => 'required',
        'cantidad_dias' => 'required'
    ];

    public function getAllTipoPermisos()
    {
        $builder = $this->db->table('tipo_permisos');
        $builder->select('idTipoPermiso, nombre, cantidad_dias, estado');
        $builder->where('estado', 'Activo');  // Filtrar solo los activos
        $builder->orderBy('nombre');
        return $builder->get()->getResultArray();
    }
}

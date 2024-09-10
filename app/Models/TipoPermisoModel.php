<?php

namespace App\Models;

use CodeIgniter\Model;

class TipoPermisoModel extends Model
{
    protected $table = 'tipo_permisos';
    protected $primaryKey = 'idTipoPermiso';
    protected $allowedFields = ['nombre', 'cantidadDias', 'estado', 'usuarioCrea', 'usuarioModifica'];

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $useTimestamps = false;

    protected $validationRules = [
        'nombre' => 'required',
        'cantidadDias' => 'required'
    ];

    public function getAllTipoPermisos()
    {
        $builder = $this->db->table('tipo_permisos');
        $builder->select('idTipoPermiso, nombre, cantidadDias, estado');
        $builder->where('estado', 'Activo');  // Filtrar solo los activos
        $builder->orderBy('nombre');
        return $builder->get()->getResultArray();
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class TipoPermisoModel extends Model
{
    protected $table = 'tipo_permisos';
    protected $primaryKey = 'idTipoPermiso';
    protected $allowedFields = ['nombre', 'cantidadDias', 'estado', 'usuarioCrea', 'usuarioModifica'];

    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;  // En realidad, estamos usando una eliminación lógica.
    protected $useTimestamps = false;

    protected $validationRules = [
        'nombre' => 'required',
        'cantidadDias' => 'required'
    ];

    // Obtener todos los permisos activos
    public function getActivos()
    {
        $builder = $this->db->table($this->table);
        $builder->select('idTipoPermiso, nombre, cantidadDias, estado');
        $builder->where('estado', 'Activo');  // Filtrar solo los activos
        $builder->orderBy('nombre');
        return $builder->get()->getResultArray();
    }

    // Obtener todos los permisos inactivos
    public function getInactivos()
    {
        $builder = $this->db->table($this->table);
        $builder->select('idTipoPermiso, nombre, cantidadDias, estado');
        $builder->where('estado', 'Inactivo');  // Filtrar solo los inactivos
        $builder->orderBy('nombre');
        return $builder->get()->getResultArray();
    }

    // Obtener todos los permisos, excluyendo los eliminados
    public function getAllTipoPermisos()
    {
        $builder = $this->db->table($this->table);
        $builder->select('idTipoPermiso, nombre, cantidadDias, estado');
        $builder->where('estado !=', 'Eliminado');  // Excluir eliminados
        $builder->orderBy('nombre');
        return $builder->get()->getResultArray();
    }

    // Marcar un permiso como eliminado (no se elimina de la base de datos)
    public function setDeleted($idTipoPermiso)
    {
        $data = ['estado' => 'Eliminado'];
        return $this->update($idTipoPermiso, $data);
    }
}

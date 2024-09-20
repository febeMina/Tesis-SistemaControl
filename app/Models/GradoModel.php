<?php

namespace App\Models;

use CodeIgniter\Model;

class GradoModel extends Model
{
    protected $table = 'grado'; // Nombre de la tabla
    protected $primaryKey = 'idGrado'; // Clave primaria
    protected $allowedFields = ['nombre', 'descripcion', 'estado','usuarioCrea', 'usuarioModifica']; 
    protected $useTimestamps = false;

    // Obtener grados activos
    public function getGrados()
    {
        return $this->where('estado', 'Activo')->findAll();
    }

    // Obtener grados con docentes asociados
    public function getGradosConDocentes()
    {
        $builder = $this->builder();
        $builder->select('grado.*, docente.idDocente, docente.nombreCompleto AS nombre_docente');
        $builder->join('docente', 'docente.idGrado = grado.idGrado', 'left');
        return $builder->get()->getResult();
    }

    // Eliminación lógica
    public function deleteGrado($id)
    {
        return $this->update($id, ['estado' => 'Eliminado']);
    }
    
}

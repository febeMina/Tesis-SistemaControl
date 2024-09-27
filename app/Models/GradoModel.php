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


   // Obtener grados activos, no importa si tienen docente o no
public function getGradosConDocentes()
{
    $builder = $this->builder();
    $builder->select('grado.*, docente.idDocente, docente.nombreCompleto AS nombre_docente');
    $builder->where('grado.estado', 'Activo'); // Solo grados activos
    $builder->join('docente', 'docente.idGrado = grado.idGrado', 'left'); // Usamos LEFT JOIN para incluir grados sin docentes
    return $builder->get()->getResult();
}



    // Eliminación lógica
    public function deleteGrado($id)
    {
        return $this->update($id, ['estado' => 'Eliminado']);
    }
    
    
    
}

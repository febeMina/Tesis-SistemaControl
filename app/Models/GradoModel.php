<?php 

namespace App\Models;

use CodeIgniter\Model;

class GradoModel extends Model
{
    protected $table      = 'grado';
    protected $primaryKey = 'idGrado';
    protected $allowedFields = ['nombre', 'descripcion', 'estado'];

    public function getGradosActivos()
    {
        return $this->where('estado', 'Activo')->findAll();
    }
}


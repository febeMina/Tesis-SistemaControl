<?php

namespace App\Models;

use CodeIgniter\Model;

class AlumnoModel extends Model
{
    protected $table = 'datos_alumnos'; 
    protected $primaryKey = 'idAlumno'; 
    protected $allowedFields = ['nombreCompleto', 'Sexo', 'NIE', 'estado']; 

    public function getAlumnosByPadreId($padreId)
    {
        return $this->where('idPadre', $padreId)->findAll();
    }
}


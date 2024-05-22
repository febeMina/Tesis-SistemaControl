<?php

namespace App\Models;

use CodeIgniter\Model;

class AlumnoModel extends Model
{
    protected $table = 'datos_alumnos'; 
    protected $primaryKey = 'idAlumno'; 
    protected $allowedFields = ['nombreAlumno', 'Genero_alumno', 'NIE', 'estado']; 

    public function getAlumnosByPadreId($padreId)
{
    return $this->where('idAlumno', $padreId)->findAll();
}

}


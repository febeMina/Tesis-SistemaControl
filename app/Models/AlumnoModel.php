<?php

namespace App\Models;

use CodeIgniter\Model;

class AlumnoModel extends Model
{
    protected $table = 'datos_alumnos';
    protected $primaryKey = 'idAlumno';
    protected $allowedFields = ['nombreAlumno', 'Genero_alumno', 'NIE', 'estado'];

    public function getAlumnosAsociados($padreId)
    {
        return $this->db->table('responsable_alumno')
            ->where('idDatosResponsable', $padreId)
            ->join('datos_alumnos', 'responsable_alumno.idAlumno = datos_alumnos.idAlumno')
            ->get()
            ->getResultArray();
    }
}

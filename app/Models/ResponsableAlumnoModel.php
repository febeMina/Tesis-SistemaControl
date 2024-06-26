<?php

namespace App\Models;

use CodeIgniter\Model;

class ResponsableAlumnoModel extends Model
{
    protected $table = 'responsable_alumno';
    protected $primaryKey = 'id';
    protected $allowedFields = ['idDatosResponsable', 'idAlumno'];

    public function getAlumnosAsociados($padreId)
    {
        return $this->db->table('responsable_alumno')
            ->join('datos_alumnos', 'datos_alumnos.idAlumno = responsable_alumno.idAlumno')
            ->where('responsable_alumno.idDatosResponsable', $padreId)
            ->get()
            ->getResultArray();
    }
}

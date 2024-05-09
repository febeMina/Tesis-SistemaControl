<?php

namespace App\Models;

use CodeIgniter\Model;

class PadreModel extends Model
{
    protected $table = 'datos_responsable';
    protected $primaryKey = 'idDatosResponsable';
    protected $allowedFields = ['nombreCompleto', 'Sexo', 'DUI', 'telefono', 'estado', 'idAlumno'];

   public function getAlumnosAsociados($idDatosResponsable)
{
    return $this->db->table('padre_alumno')
                    ->select('datos_alumnos.*')
                    ->join('datos_alumnos', 'datos_alumnos.idAlumno = padre_alumno.idAlumno')
                    ->where('padre_alumno.idDatosResponsable', $idDatosResponsable)
                    ->get()
                    ->getResultArray();
}

    
    

    // Otros métodos del modelo
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class PadreModel extends Model
{
    protected $table = 'datos_responsable';
    protected $primaryKey = 'idDatosResponsable';
    protected $allowedFields = ['nombreCompleto', 'Genero', 'DUI', 'telefono', 'estado'];

    public function getFilteredPadres($filters)
{
    $query = $this->where('estado', 'activo'); // Asegúrate de incluir solo padres activos

    if (!empty($filters['nombre_completo'])) {
        $query->like('nombreCompleto', $filters['nombre_completo']);
    }

    if (!empty($filters['dui'])) {
        $query->where('DUI', $filters['dui']);
    }

    if (!empty($filters['genero'])) {
        $query->where('Genero', $filters['genero']);
    }

    return $query->findAll();
}


    public function getAlumnosAsociados($padreId)
    {
        return $this->db->table('responsable_alumno')
            ->where('idDatosResponsable', $padreId)
            ->join('datos_alumnos', 'responsable_alumno.idAlumno = datos_alumnos.idAlumno')
            ->get()
            ->getResultArray();
    }
}

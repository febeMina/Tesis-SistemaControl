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
        $builder = $this->builder();

        if (!empty($filters['nombre_completo'])) {
            $builder->like('nombreCompleto', $filters['nombre_completo']);
        }
        if (!empty($filters['dui'])) {
            $builder->like('DUI', $filters['dui']);
        }
        if (!empty($filters['genero'])) {
            $builder->where('Genero', $filters['genero']);
        }

        return $builder->get()->getResultArray();
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

<?php

namespace App\Models;

use CodeIgniter\Model;

class PadreModel extends Model
{
    protected $table = 'datos_responsable';
    protected $primaryKey = 'idDatosResponsable';
    protected $allowedFields = ['nombreCompleto', 'Genero', 'DUI', 'telefono', 'estado', 'idAlumno'];

    public function getFilteredPadres($filters)
    {
        $builder = $this->builder();

        if (!empty($filters['nombre_completo'])) {
            $builder->like('nombreCompleto', $filters['nombre_completo']);
        }
        if (!empty($filters['dui'])) {
            $builder->like('DUI', $filters['dui']);
        }
        if (!empty($filters['telefono'])) {
            $builder->like('telefono', $filters['telefono']);
        }
        if (!empty($filters['estado'])) {
            $builder->where('estado', $filters['estado']);
        }
        if (!empty($filters['genero'])) {
            $builder->where('Genero', $filters['genero']);
        }

        return $builder->get()->getResultArray();
    }
}

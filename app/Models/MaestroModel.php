<?php

namespace App\Models;

use CodeIgniter\Model;

class MaestroModel extends Model
{
    protected $table = 'docente';
    protected $primaryKey = 'idDocente';
    protected $allowedFields = ['nombre_completo', 'nip', 'escalafon', 'fecha_ingreso', 'estado'];

    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $useTimestamps = false;

    protected $validationRules = [
        'nombre_completo' => 'required',
        'nip' => 'required',
        'escalafon' => 'required',
        'fecha_ingreso' => 'required',
        'estado' => 'required'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function filter($filters)
{
    $builder = $this->builder();

    // Aplicar filtros si están presentes
    if (!empty($filters['nombre_completo'])) {
        $builder->like('nombre_completo', $filters['nombre_completo']);
    }
    if (!empty($filters['nip'])) {
        $builder->like('nip', $filters['nip']);
    }
    if (!empty($filters['escalafon'])) {
        $builder->like('escalafon', $filters['escalafon']);
    }
    if (!empty($filters['fecha_ingreso'])) {
        $builder->like('fecha_ingreso', $filters['fecha_ingreso']);
    }
    if (!empty($filters['estado'])) {
        $builder->where('estado', $filters['estado']);
    }

    // Retornar resultados
    return $builder->get()->getResultArray();
}

}

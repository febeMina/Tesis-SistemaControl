<?php

namespace App\Models;

use CodeIgniter\Model;

class MaestroModel extends Model
{
    protected $table = 'docente';
    protected $primaryKey = 'idDocente';
    protected $allowedFields = ['nombre_completo', 'nip', 'escalafon', 'fecha_ingreso', 'estado', 'deleted', 'tipo', 'cargo'];

    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $useTimestamps = false;

    protected $validationRules = [
        'nombre_completo' => 'required',
        'nip' => 'required',
        'escalafon' => 'required',
        'fecha_ingreso' => 'required',
        'estado' => 'required',
        'tipo' => 'required',
        'cargo' => 'permit_empty'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function setInactive($id)
    {
        $data = ['estado' => 'Inactivo'];
        return $this->update($id, $data);
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
        if (!empty($filters['estado']) && $filters['estado'] !== 'Inactivo') {
            $builder->where('estado', $filters['estado']);
        }
        if (!empty($filters['tipo'])) {
            $builder->where('tipo', $filters['tipo']);
        }
        // Incluir siempre el estado en el filtro
        $builder->where('estado !=', 'Eliminado');
            
        $query = $builder->get();
        return $query->getResultArray();

        // Excluir los maestros inactivos si no se está filtrando por estado inactivo
        if (empty($filters['estado']) || $filters['estado'] !== 'Inactivo') {
            $builder->where('estado !=', 'Inactivo');
        }
    
        // Retornar resultados
        return $builder->get()->getResultArray();
        
        
    }
}

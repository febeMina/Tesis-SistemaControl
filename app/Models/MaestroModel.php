<?php

namespace App\Models;

use CodeIgniter\Model;

class MaestroModel extends Model
{
    protected $table = 'docente';
    protected $primaryKey = 'idDocente';
    protected $allowedFields = ['nombreCompleto', 'nip', 'escalafon', 'fechaIngreso', 'estado', 'deleted', 'tipo', 'cargo', 'idGrado', 'usuarioCrea', 'usuarioModifica'];

    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $useTimestamps = false;

    protected $validationRules = [
        'nombreCompleto' => 'required',
        'nip' => 'required',
        'escalafon' => 'required',
        'fechaIngreso' => 'required',
        'estado' => 'required',
        'tipo' => 'required',
        'cargo' => 'permit_empty',
        'idGrado' => 'permit_empty|integer'
    ];
    
    protected $validationMessages = [
        'nombreCompleto' => [
            'required' => 'El nombre completo es obligatorio.',
        ],
        'nip' => [
            'required' => 'El NIP es obligatorio.',
        ],
        'escalafon' => [
            'required' => 'El escalafón es obligatorio.',
        ],
        'fechaIngreso' => [
            'required' => 'La fecha de ingreso es obligatoria.',
        ],
        'estado' => [
            'required' => 'El estado es obligatorio.',
        ],
        'idGrado' => [
            'integer' => 'El ID de grado debe ser un número entero.'
        ]
    ];

    public function __construct()
    {
        parent::__construct();
    }
    
    public function createDocente($data)
    {
        $this->db->transStart(); // Inicia la transacción
        
        // Inserta el nuevo docente
        $this->insert($data);
        $docenteId = $this->insertID(); // Obtén el ID del nuevo docente
        
        // Si el docente es de tipo 'Docente' y tiene grados asignados
        if ($data['tipo'] === 'Docente' && !empty($data['idGrado'])) {
            // Asegúrate de que idGrado sea un array
            if (!is_array($data['idGrado'])) {
                $data['idGrado'] = [$data['idGrado']]; // Convierte a array si es un solo valor
            }

            foreach ($data['idGrado'] as $idGrado) {
                // Verifica si el idGrado existe en la tabla 'grado'
                $gradoExists = $this->db->table('grado')->where('idGrado', $idGrado)->countAllResults();
                if ($gradoExists > 0) {
                    // Asigna el grado al docente en la tabla intermedia 'docenteGrado'
                    $this->db->table('docenteGrado')->insert([
                        'idDocente' => $docenteId,
                        'idGrado' => $idGrado
                    ]);
                } else {
                    $this->db->transRollback(); // Revierte la transacción si no existe el grado
                    return false;
                }
            }
        }
        
        $this->db->transComplete(); // Completa la transacción

        return $this->db->transStatus();
    }
    
    

    public function setInactive($id)
    {
        $data = ['estado' => 'Inactivo'];
        return $this->update($id, $data);
    }

    public function setDeleted($id)
{
    $data = ['estado' => 'Eliminado'];
    return $this->update($id, $data);
}

public function filter($filters, $limit, $offset)
{
    $builder = $this->db->table($this->table)
        ->select('docente.*, grado.nombre AS grado')
        ->join('grado', 'docente.idGrado = grado.idGrado', 'left');

    // Aplicar filtros
    if (!empty($filters['nombreCompleto'])) {
        $builder->like('docente.nombreCompleto', $filters['nombreCompleto']);
    }
    if (!empty($filters['nip'])) {
        $builder->like('docente.nip', $filters['nip']);
    }
    if (!empty($filters['escalafon'])) {
        $builder->like('docente.escalafon', $filters['escalafon']);
    }
    if (!empty($filters['fechaIngreso'])) {
        $builder->like('docente.fechaIngreso', $filters['fechaIngreso']);
    }
    if (!empty($filters['estado'])) {
        // Aceptar cualquier estado que se haya pasado en el filtro
        $builder->where('docente.estado', $filters['estado']);
    } else {
        // Si no hay un filtro de estado, solo incluir Activo e Inactivo
        $builder->whereIn('docente.estado', ['Activo', 'Inactivo']);
    }

    if (!empty($filters['tipo'])) {
        $builder->where('docente.tipo', $filters['tipo']);
    }
    if (!empty($filters['idGrado'])) {
        $builder->where('docente.idGrado', $filters['idGrado']);
    }

    // Excluir registros con estado 'Eliminado'
    $builder->where('docente.estado !=', 'Eliminado');

    $builder->limit($limit, $offset);
    $query = $builder->get();

    return $query->getResultArray();
}


public function countFiltered($filters)
{
    $builder = $this->db->table($this->table)
        ->select('COUNT(*) AS total')
        ->join('grado', 'docente.idGrado = grado.idGrado', 'left');

    // Aplicar filtros
    if (!empty($filters['nombreCompleto'])) {
        $builder->like('docente.nombreCompleto', $filters['nombreCompleto']);
    }
    if (!empty($filters['nip'])) {
        $builder->like('docente.nip', $filters['nip']);
    }
    if (!empty($filters['escalafon'])) {
        $builder->like('docente.escalafon', $filters['escalafon']);
    }
    if (!empty($filters['fechaIngreso'])) {
        $builder->like('docente.fechaIngreso', $filters['fechaIngreso']);
    }
    if (!empty($filters['estado'])) {
        // Aceptar cualquier estado que se haya pasado en el filtro
        $builder->where('docente.estado', $filters['estado']);
    } else {
        // Excluir solo el estado Eliminado si no hay un filtro
        $builder->where('docente.estado !=', 'Eliminado');
    }
    if (!empty($filters['tipo'])) {
        $builder->where('docente.tipo', $filters['tipo']);
    }
    if (!empty($filters['idGrado'])) {
        $builder->where('docente.idGrado', $filters['idGrado']);
    }

    // Excluir estado 'Eliminado' y 'Inactivo' si no es el filtro
    if (empty($filters['estado']) || $filters['estado'] !== 'Inactivo') {
        $builder->where('docente.estado !=', 'Eliminado');
        $builder->where('docente.estado !=', 'Inactivo');
    }

    return $builder->countAllResults();
}

}

<?php

namespace App\Models;

use CodeIgniter\Model;

class DocenteGradoModel extends Model
{
    protected $table = 'docente_grado';
    protected $primaryKey = 'idDocenteGrado';
    protected $allowedFields = ['idDocente', 'idGrado'];
    
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $useTimestamps = false;

    protected $validationRules = [
        'idDocente' => 'required|integer',
        'idGrado' => 'required|integer'
    ];

    protected $validationMessages = [
        'idDocente' => [
            'required' => 'El ID del docente es obligatorio.',
        ],
        'idGrado' => [
            'required' => 'El ID del grado es obligatorio.',
        ]
    ];

    public function __construct()
    {
        parent::__construct();
    }
}

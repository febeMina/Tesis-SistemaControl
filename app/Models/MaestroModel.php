<?php

namespace App\Models;

use CodeIgniter\Model;

class MaestroModel extends Model
{
    protected $table = 'docente';
    protected $primaryKey = 'idDocente';
    protected $allowedFields = ['nombre_completo', 'nip', 'escalafon', 'fecha_ingreso', 'estado'];

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $useTimestamps = false;

    protected $validationRules = [
        'nombre_completo' => 'required',
        'nip' => 'required',
        'escalafon' => 'required',
        'fecha_ingreso' => 'required',
        'estado' => 'required'
    ];

    // Otras configuraciones...
}

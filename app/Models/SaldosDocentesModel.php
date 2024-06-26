<?php

namespace App\Models;

use CodeIgniter\Model;

class SaldosDocentesModel extends Model
{
    protected $table = 'saldos_docentes';
    protected $primaryKey = 'idSaldoDocentes';
    protected $allowedFields = [
        'idDocente', 
        'idDetallePermiso', 
        'saldo_total_dias', 
        'fecha_creacion', 
        'saldo_total_horas', 
        'fecha_inicio', 
        'fecha_fin'
    ];

    protected $useAutoIncrement = true;

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $useTimestamps = false;

    protected $validationRules = [
        'idDocente' => 'required',
        'idDetallePermiso' => 'required',
        'saldo_total_dias' => 'required',
        'fecha_creacion' => 'required|valid_date'
    ];

    // Otras configuraciones...
}

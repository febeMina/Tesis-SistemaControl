<?php

namespace App\Models;

use CodeIgniter\Model;

class SaldosDocentesModel extends Model
{
    protected $table = 'saldos_docentes';
    protected $primaryKey = 'idSaldoDocentes';
    protected $allowedFields = ['idDocente', 'idDetallePermiso', 'saldo_total_dias'];

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $useTimestamps = false;

    // Otras configuraciones...
}

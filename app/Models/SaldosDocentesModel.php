<?php

namespace App\Models;

use CodeIgniter\Model;

class SaldosDocentesModel extends Model
{
    protected $table = 'saldos_docentes';
    protected $primaryKey = 'idSaldoDocentes';
    protected $allowedFields = [
        'idDocente', 
        'saldo_total_dias', 
        'saldo_total_horas',
        'saldo'
    ];

    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $useTimestamps = false;
    protected $validationRules = [
        'idDocente' => 'required',
        'saldo_total_dias' => 'required',
        'saldo_total_horas' => 'required'
    ];
    
    public function insertarSaldoDocente($data) {
        $result = $this->db->table('saldos_docentes')->insert($data);
        return $this->insert($data);
    }
    
}

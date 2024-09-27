<?php 

namespace App\Models;

use CodeIgniter\Model;

class RegistroDiarioModel extends Model
{
    protected $table = 'registro_diario';
    protected $primaryKey = 'idRegistroDiario';
    protected $allowedFields = ['fecha', 'familiasBeneficiadas', 'createdAt'];
    protected $returnType = 'object';
    
    public function obtenerRegistrosPorFecha($fechaInicio, $fechaFin)
{
    return $this->db->table('registro_diario')
        ->where('fecha >=', $fechaInicio)
        ->where('fecha <=', $fechaFin)
        ->get()
        ->getResult();
}

}

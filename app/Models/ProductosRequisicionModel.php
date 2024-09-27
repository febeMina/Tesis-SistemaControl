<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductosRequisicionModel extends Model
{
    protected $table = 'productos_requisicion';
    protected $primaryKey = 'idProductoRequisicion';
    protected $allowedFields = ['fechaRequisicion', 'comidaPreparar', 'responsableEntrega', 'responsableRecibe', 'estado'];

// Método para obtener las requisiciones por fecha
public function getRequisicionByFecha($fecha)
{
    try {
        $result = $this->select('fechaRequisicion, comidaPreparar , responsableEntrega, responsableRecibe, estado')
                       ->where('fechaRequisicion', $fecha)
                       ->findAll();
        return $result;
    } catch (\Exception $e) {
        log_message('error', 'Error en getRequisicionByFecha: ' . $e->getMessage());
        return [];
    }
    
}

public function getRequisicionCountByFecha($fecha_solicitud)
{
    return $this->where('fechaRequisicion', $fecha_solicitud)
                ->countAllResults();
}

}
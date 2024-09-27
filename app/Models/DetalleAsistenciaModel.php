<?php

namespace App\Models;

use CodeIgniter\Model;

class DetalleAsistenciaModel extends Model
{
    protected $table = 'detalle_asistencia';
    protected $primaryKey = 'idDetalleAsistencia';

    protected $allowedFields = [
        'cantidadNinos',
        'cantidadNinas',
        'total',
        'detalle',
        'idRegistroDiario',
        'idDocente',
        'idProductoRequisicion'
    ];

    public function getDetallesConDocentes()
    {
    
        $builder = $this->builder();
        $builder->select('detalle_asistencia.*, docente.nombreCompleto AS nombre_docente');
        $builder->join('docente', 'detalle_asistencia.idDocente = docente.idDocente', 'left');
        return $builder->get()->getResult();
        
    }
    
    
    public function getDetallesConDocentesYGrados()
{

    $builder = $this->builder();
    $builder->select('detalle_asistencia.*, docente.nombreCompleto AS nombre_docente, grado.nombre AS nombre_grado');
    $builder->join('docente', 'detalle_asistencia.idDocente = docente.idDocente', 'left');
    $builder->join('grado', 'docente.idGrado = grado.idGrado', 'left'); // JOIN con la tabla grado
    $result = $builder->get()->getResultObject();
    
    // Verifica si hay datos
    if (empty($result)) {
        // Manejo de errores o retorno de un array vacío
        return [];
    }
    
    return $result; // Devolver objetos en lugar de arrays
}

    

}

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
        $builder->select('detalle_asistencia.*, docentes.nombre AS nombre_docente');
        $builder->join('docentes', 'detalle_asistencia.idDocente = docentes.idDocente', 'left');
        return $builder->get()->getResult();
    }
}

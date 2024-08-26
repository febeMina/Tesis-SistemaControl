<?php

namespace App\Models;

use CodeIgniter\Model;

class DetalleAsistenciaModel extends Model
{
    protected $table = 'detalle_asistencia';
    protected $primaryKey = 'idDetalleAsistencia';

    protected $allowedFields = [
        'idGrado',
        'cantidad_niños',
        'cantidad_niñas',
        'Total',
        'Detalle',
        'idRegistroDiario',
        'idDocente'
    ];

    // Método para obtener asistencia por grado
    public function getAsistenciaPorGrado($idGrado)
    {
        return $this->where('idGrado', $idGrado)->findAll();
    }

    // Método para obtener detalles de asistencia junto con los docentes
    public function getDetallesConDocentes()
    {
        $builder = $this->builder();
        $builder->select('detalle_asistencia.*, docentes.nombre AS nombre_docente');
        $builder->join('docentes', 'detalle_asistencia.idDocente = docentes.idDocente', 'left');
        return $builder->get()->getResult();
    }
}

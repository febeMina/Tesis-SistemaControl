<?php

namespace App\Models;

use CodeIgniter\Model;

class SolicitudProductosModel extends Model
{
    protected $table = 'solicitud_productos';
    protected $primaryKey = 'idSolicitudProductos';
    protected $allowedFields = [
        'Fecha_solicitud',
        'Comida_a_preparar',
        'responsable_entrega',
        'responsable_recibir'
    ];
}

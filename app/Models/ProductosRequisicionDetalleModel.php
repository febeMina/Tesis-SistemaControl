<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductosRequisicionDetalleModel extends Model
{
    protected $table = 'productos_requisicion_detalle';
    protected $primaryKey = 'idProductoRequisicionDetalle';
    protected $allowedFields = ['idProductoRequisicion', 'idProductoLote', 'existenciaTotal'];
}
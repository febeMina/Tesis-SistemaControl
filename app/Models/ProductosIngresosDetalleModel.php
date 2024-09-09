<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductosIngresosDetalleModel extends Model
{
    protected $table = 'productos_ingresos_detalle';
    protected $primaryKey = 'idProductoIngresoDetalle';
    protected $allowedFields = ['idProductoIngreso', 'idProductoLote', 'existenciaTotal'];
}
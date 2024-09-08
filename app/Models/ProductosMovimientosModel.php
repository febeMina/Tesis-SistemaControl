<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductosMovimientosModel extends Model
{
    protected $table = 'productos_movimientos';
    protected $primaryKey = 'idProductoMovimiento';
    protected $allowedFields = ['idProductoLote', 'tipoMovimiento', 'descripcionMovimiento', 'fechaMovimiento', 'existenciaTotalAntes', 'existenciaTotalMovimiento', 'existenciaTotalDespues'];
}
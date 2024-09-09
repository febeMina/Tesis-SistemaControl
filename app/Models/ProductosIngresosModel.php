<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductosIngresosModel extends Model
{
    protected $table = 'productos_ingresos';
    protected $primaryKey = 'idProductoIngreso';
    protected $allowedFields = ['fechaIngreso', 'responsableEntrega', 'responsableRecibe', 'estado'];
}
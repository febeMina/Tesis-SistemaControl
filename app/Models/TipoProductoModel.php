<?php

namespace App\Models;

use CodeIgniter\Model;

class TipoProductoModel extends Model
{
    protected $table = 'tipo_producto';
    protected $primaryKey = 'idtipoProducto';
    protected $allowedFields = ['nombre', 'descripcion'];

    protected $useAutoIncrement = true;

    protected $returnType = 'array';
}
<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductosRequisicionModel extends Model
{
    protected $table = 'productos_requisicion';
    protected $primaryKey = 'idProductoRequisicion';
    protected $allowedFields = ['fechaRequisicion', 'comidaPreparar', 'responsableEntrega', 'responsableRecibe', 'estado'];
}
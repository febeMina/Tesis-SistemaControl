<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductoLoteModel extends Model
{
    protected $table = 'productos_lotes';
    protected $primaryKey = 'idProductoLote';
    protected $allowedFields = ['idProducto', 'codigoLote', 'fechaIngreso', 'fechaVencimiento', 'idUdmCaja', 'existenciaCaja', 'idUdmIndividual', 'existenciaIndividual', 'existenciaTotal'];
}

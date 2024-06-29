<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductoModel extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'idProducto';
    protected $allowedFields = ['fecha_ingreso', 'fecha_vencimiento', 'tamaño', 'estado', 'idMovimiento', 'IdPrioridad', 'idUnidadesMedida', 'idtipoProducto', 'idDetalleSolicitados'];
}

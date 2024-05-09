<?php

namespace App\Models;

use CodeIgniter\Model;

class BitacoraModel extends Model
{
    protected $table = 'bitacora';
    protected $primaryKey = 'idBitacora';
    protected $allowedFields = ['usuario', 'accion', 'descripcion', 'fecha', 'hora'];
}

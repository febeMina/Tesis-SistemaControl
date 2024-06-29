<?php

namespace App\Models;

use CodeIgniter\Model;

class ConsumoModel extends Model
{
    protected $table = 'consumos';
    protected $primaryKey = 'idConsumo';
    protected $allowedFields = ['fecha', 'producto_id', 'descripcion', 'fecha_vencimiento', 'saldo_inicial', 'salidas'];
}

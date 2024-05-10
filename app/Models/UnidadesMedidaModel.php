<?php

// Archivo: app/Models/UnidadesMedidaModel.php

namespace App\Models;

use CodeIgniter\Model;

class UnidadesMedidaModel extends Model
{
    protected $table = 'unidades_medida';
    protected $primaryKey = 'idUnidadesMedida';
    protected $allowedFields = ['nombre', 'abreviatura', 'descripción', 'estado'];
}

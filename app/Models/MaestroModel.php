<?php

namespace App\Models;

use CodeIgniter\Model;

class MaestroModel extends Model
{
    protected $table = 'maestros';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nombre_completo', 'nip', 'escalafon', 'fecha_ingreso', 'estado'];
}

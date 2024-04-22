<?php

namespace App\Models;

use CodeIgniter\Model;

class MaestroModel extends Model
{
    protected $table = 'docente'; 
    protected $primaryKey = 'idDocente'; 
    protected $allowedFields = ['nombre_completo', 'nip', 'escalafon', 'fecha_ingreso', 'estado'];
}

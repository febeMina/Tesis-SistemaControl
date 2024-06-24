<?php

namespace App\Models;

use CodeIgniter\Model;

class RolesModel extends Model
{
    protected $table = 'rol';
    protected $primaryKey = 'idRol';
    protected $allowedFields = ['nombreRol','descripcion'];

  
}
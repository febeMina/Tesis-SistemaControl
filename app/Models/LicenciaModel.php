<?php

namespace App\Models;

use CodeIgniter\Model;

class LicenciaModel extends Model
{
    protected $table = 'licencias'; // Nombre de la tabla en la base de datos
    protected $primaryKey = 'id'; // Nombre de la clave primaria de la tabla
    protected $allowedFields = ['numero', 'nombre', 'dias', 'estado']; // Campos permitidos para el ingreso masivo

    // Otras configuraciones, como timestamps, si es necesario
}

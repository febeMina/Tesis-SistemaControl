<?php

namespace App\Models;

use CodeIgniter\Model;

class PadreModel extends Model
{
    protected $table = 'datos_responsable';
    protected $primaryKey = 'idDatosResponsable';
    protected $allowedFields = ['nombreCompleto', 'Sexo', 'DUI', 'telefono', 'estado', 'idAlumno'];
}

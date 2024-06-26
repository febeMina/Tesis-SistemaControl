<?php

namespace App\Models;

use CodeIgniter\Model;

class DonacionesModel extends Model
{
    protected $table = 'donaciones';
    protected $primaryKey = 'idDonaciones';
    protected $allowedFields = ['nombreDonante','cantidad','cantidadLetras', 'descripcion', 'fechaDonacion', 'estado', 'idProyectos'];

   
}

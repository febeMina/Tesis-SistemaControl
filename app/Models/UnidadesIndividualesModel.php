<?php

namespace App\Models;

use CodeIgniter\Model;

class UnidadesIndividualesModel extends Model
{
    protected $table = 'udm_individual';
    protected $primaryKey = 'idUdmIndividual';
    protected $allowedFields = ['nombreIndividual', 'estado', 'usuarioCrea', 'usuarioModifica'];
}
<?php

namespace App\Models;

use CodeIgniter\Model;

class PadreModel extends Model
{
    protected $table = 'datos_responsable';
    protected $primaryKey = 'idDatosResponsable';
    protected $allowedFields = ['nombreCompleto', 'Sexo', 'DUI', 'telefono', 'estado', 'idAlumno'];

    // Método para obtener los datos de los alumnos asociados al padre
    public function getAlumnos()
    {
        // Carga los datos de los alumnos asociados al padre
        $db = \Config\Database::connect();
        $builder = $db->table('datos_alumnos');
        $alumnos = $builder->get()->getResult();

        return $alumnos;
    }
}

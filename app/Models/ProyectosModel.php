<?php


namespace App\Models;
use CodeIgniter\Model;


class ProyectosModel extends Model
{
    protected $table = 'proyectos';
    protected $primaryKey = 'idProyectos';
    protected $allowedFields = ['idProyectos', 'nombreProyecto', 'descripcion', 'estado', 'meta','anio', 'valorActual', 'usuarioCrea', 'usuarioModifica'];
}

<?php

namespace App\Controllers;

use App\Models\ProyectosModel;
use CodeIgniter\Controller;

class Graficos extends Controller
{
    public function index()
        {

            $db = \Config\Database::connect();
            $proyectosBuilder = $db->table('proyectos');
            $nombresp = $rolesBuilder->select('nombreProyecto')->get()->getResult();
            return( ['proyectos' =>  $nombresp]);
        }
  
}
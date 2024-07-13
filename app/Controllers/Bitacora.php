<?php

namespace App\Controllers;

use App\Models\BitacoraModel;
use CodeIgniter\Controller;

class Bitacora extends Controller
{
    public function index()
    {
        // Instanciar el modelo de Bitacora
        $model = new BitacoraModel();
        
        // Obtener todos los registros de la tabla bitacora
        $bitacora = $model->findAll();
        
        // Pasar los datos a la vista
        return view('bitacora/index', ['bitacora' => $bitacora]);
    }
}
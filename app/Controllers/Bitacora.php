<?php

namespace App\Controllers;

use App\Models\BitacoraModel;
use CodeIgniter\API\ResponseTrait;

class Bitacora extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        // Obtener todas las entradas de la bitácora
        $bitacoraModel = new BitacoraModel();
        $bitacora = $bitacoraModel->findAll();

        // Devolver la respuesta como JSON
        return $this->respond($bitacora);
    }

    public function registrarAccion($usuario, $accion, $descripcion)
    {
        // Crear una nueva entrada en la bitácora
        $bitacoraModel = new BitacoraModel();
        $bitacoraModel->insert([
            'usuario' => $usuario,
            'accion' => $accion,
            'descripcion' => $descripcion,
            'fecha' => date('Y-m-d'),
            'hora' => date('H:i:s')
        ]);
    }

    // Otros métodos del controlador...
}

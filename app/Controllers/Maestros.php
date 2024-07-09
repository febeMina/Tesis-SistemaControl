<?php

namespace App\Controllers;

use App\Models\MaestroModel;
use CodeIgniter\Controller;

class Maestros extends Controller
{
    protected $maestroModel;

    public function __construct()
    {
        helper('url');
        $this->maestroModel = new MaestroModel();
    }

    public function index()
    {
        $request = service('request');

        // Obtener los datos de filtro del formulario
        $filters = [
            'nombre_completo' => $request->getVar('nombre_completo'),
            'nip' => $request->getVar('nip'),
            'escalafon' => $request->getVar('escalafon'),
            'fecha_ingreso' => $request->getVar('fecha_ingreso'),
            'estado' => $request->getVar('estado')
        ];

        // Obtener los datos filtrados
        $maestrosData = $this->maestroModel->filter($filters);

        // Pasar los datos a la vista
        return view('maestros/index', ['maestros' => $maestrosData]);
    }

    public function create()
    {
        // Muestra el formulario para crear un nuevo maestro
        return view('maestros/create');
    }

    public function store()
    {
        // Capturar los datos del formulario de creación
        $request = \Config\Services::request();
        $nombre_completo = $request->getVar('nombre_completo');
        $nip = $request->getVar('nip');
        $escalafon = $request->getVar('escalafon');
        $fecha_ingreso = $request->getVar('fecha_ingreso');
        $estado = $request->getVar('estado');
        $tipo = $request->getVar('tipo');
        $cargo = '';

    if ($tipo === 'Administrativo') {
        $cargo = $request->getVar('rol');
    }

        // Guardar los datos en la base de datos
        $data = [
            'nombre_completo' => $nombre_completo,
            'nip' => $nip,
            'escalafon' => $escalafon,
            'fecha_ingreso' => $fecha_ingreso,
            'estado' => $estado,
            'tipo' => $tipo,
            'cargo' => $cargo,
        ];
        
        log_message('debug', 'Datos de maestro insertados: ' . print_r($data, true));

        $inserted = $this->maestroModel->insert($data);

        if ($inserted) {
            $response = [
                'success' => true,
                'redirect' => site_url('maestros')
            ];
        } else {
            $response = [
                'success' => false
            ];
        }

        return $this->response->setJSON($response);
    }

    public function edit($id)
    {
        // Cargar los datos del maestro a editar desde la base de datos
        $maestro = $this->maestroModel->find($id);

        // Pasar los datos a la vista de edición
        return view('maestros/edit', ['maestro' => $maestro]);
    }

    public function update($id)
{
    // Capturar los datos del formulario de edición
    $request = \Config\Services::request();
    $nombre_completo = $request->getVar('nombre_completo');
    $nip = $request->getVar('nip');
    $escalafon = $request->getVar('escalafon');
    $fecha_ingreso = $request->getVar('fecha_ingreso');
    $estado = $request->getVar('estado');
    $tipo = $request->getVar('tipo');
    $cargo = ($tipo === 'Administrativo') ? $request->getVar('rol') : '';

    // Actualizar los datos en la base de datos
    $data = [
        'nombre_completo' => $nombre_completo,
        'nip' => $nip,
        'escalafon' => $escalafon,
        'fecha_ingreso' => $fecha_ingreso,
        'estado' => $estado,
        'tipo' => $tipo,
        'cargo' => $cargo,
    ];

    $updated = $this->maestroModel->update($id, $data);

    if ($updated) {
        $response = [
            'success' => true,
            'redirect' => site_url('maestros')
        ];
    } else {
        $response = [
            'success' => false
        ];
    }

    return $this->response->setJSON($response);
}

    

    public function delete($id)
    {
        // Eliminar el maestro de la base de datos
        $this->maestroModel->delete($id);

        // Redireccionar a la página principal o mostrar un mensaje de éxito
        return redirect()->to(site_url('maestros'));
    }
}

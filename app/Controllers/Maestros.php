<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Maestros extends Controller
{
    public function __construct(){
        helper('url');
        if (!session()->get('isLoggedIn')) {
            redirect()->to(base_url('public/login'))->send();
            exit;
        }
    }
    public function index()
    {
        // Cargar todos los maestros desde la base de datos
        $modelMaestro = new \App\Models\MaestroModel();
        $maestros = $modelMaestro->findAll();

        // Pasar los datos a la vista de index
        return view('maestros/index', ['maestros' => $maestros]);
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

        // Guardar los datos en la base de datos
        $modelMaestro = new \App\Models\MaestroModel();
        $data = [
            'nombre_completo' => $nombre_completo,
            'nip' => $nip,
            'escalafon' => $escalafon,
            'fecha_ingreso' => $fecha_ingreso,
            'estado' => $estado
        ];
        $modelMaestro->insert($data);

        // Devolver una respuesta JSON
        return $this->response->setJSON(['success' => true]);
    }

    public function edit($id)
    {
        // Cargar los datos del maestro a editar desde la base de datos
        $modelMaestro = new \App\Models\MaestroModel();
        $maestro = $modelMaestro->find($id);

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

        // Actualizar los datos en la base de datos
        $modelMaestro = new \App\Models\MaestroModel();
        $data = [
            'nombre_completo' => $nombre_completo,
            'nip' => $nip,
            'escalafon' => $escalafon,
            'fecha_ingreso' => $fecha_ingreso,
            'estado' => $estado
        ];
        $modelMaestro->update($id, $data);

        // Redireccionar a la página principal con un mensaje de éxito
        return redirect()->to('/maestros')->with('success', 'El maestro ha sido actualizado exitosamente.');
    }

    public function delete($id)
    {
        // Eliminar el maestro de la base de datos
        $modelMaestro = new \App\Models\MaestroModel();
        $modelMaestro->delete($id);

        // Redireccionar a la página principal o mostrar un mensaje de éxito
        return redirect()->to(site_url('maestros'));
    }
}

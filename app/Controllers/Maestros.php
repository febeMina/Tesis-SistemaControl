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
    
        // Guardar los datos en la base de datos
        $data = [
            'nombre_completo' => $nombre_completo,
            'nip' => $nip,
            'escalafon' => $escalafon,
            'fecha_ingreso' => $fecha_ingreso,
            'estado' => $estado  // Aquí se almacena el estado correctamente
        ];
        $this->maestroModel->insert($data);
    
        // Devolver una respuesta JSON
        return $this->response->setJSON(['success' => true]);
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
    
        // Actualizar los datos en la base de datos
        $data = [
            'nombre_completo' => $nombre_completo,
            'nip' => $nip,
            'escalafon' => $escalafon,
            'fecha_ingreso' => $fecha_ingreso,
            'estado' => $estado  // Aquí se actualiza el estado correctamente
        ];
        $this->maestroModel->update($id, $data);
    
        // Establecer una respuesta JSON de éxito
        return $this->response->setJSON(['success' => true, 'redirect' => site_url('maestros')]);
    }
    

    public function delete($id)
{
    // Verificar si el ID existe
    $maestro = $this->maestroModel->find($id);
    if (!$maestro) {
        return redirect()->to(site_url('maestros'))->with('error', 'Maestro no encontrado.');
    }

    // Marcar el registro como inactivo
    $data = ['estado' => 'Inactivo'];
    if ($this->maestroModel->update($id, $data)) {
        // Recargar la vista sin el maestro eliminado
        return redirect()->to(site_url('maestros'))->with('success', 'El maestro ha sido marcado como inactivo.');
    } else {
        return redirect()->to(site_url('maestros'))->with('error', 'No se pudo marcar el maestro como inactivo.');
    }
}


}

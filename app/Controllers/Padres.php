<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PadreModel; // Importa el modelo de padres

class Padres extends Controller
{
    public function index()
    {
        // Cargar todos los padres desde la base de datos
        $padreModel = new PadreModel(); // Instancia el modelo de padres
        $padres = $padreModel->findAll(); // Obtiene todos los padres
    
        // Cargar todos los alumnos desde el modelo de padres
        $alumnos = $padreModel->getAlumnos();
    
        // Pasar los datos a la vista de index
        return view('padres/index', ['padres' => $padres, 'alumnos' => $alumnos]);
    }

    public function create()
    {
        // Mostrar el formulario para crear un nuevo padre
        return view('padres/create');
    }

    public function store()
    {
        // Capturar los datos del formulario de creación
        $request = \Config\Services::request();
        $nombre_completo = $request->getVar('nombre_completo');
        $DUI = $request->getVar('dui');
        $telefono = $request->getVar('telefono');
        $estado = $request->getVar('estado');
        $sexo = $request->getVar('sexo');
        $idAlumno = $request->getVar('idAlumno');

        // Guardar los datos en la base de datos
        $padreModel = new PadreModel(); // Instancia el modelo de padres
        $data = [
            'nombreCompleto' => $nombre_completo,
            'DUI' => $DUI,
            'telefono' => $telefono,
            'estado' => $estado,
            'Sexo' => $sexo,
            'idAlumno' => $idAlumno
        ];
        $padreModel->insert($data);

        // Devolver una respuesta JSON
        return $this->response->setJSON(['success' => true]);
    }

    public function edit($id)
    {
        // Cargar los datos del padre a editar desde la base de datos
        $padreModel = new PadreModel(); // Instancia el modelo de padres
        $padre = $padreModel->find($id);

        // Pasar los datos a la vista de edición
        return view('padres/edit', ['padre' => $padre]);
    }

    public function update($id)
    {
        // Capturar los datos del formulario de edición
        $request = \Config\Services::request();
        $nombre_completo = $request->getVar('nombre_completo');
        $DUI = $request->getVar('dui');
        $telefono = $request->getVar('telefono');
        $estado = $request->getVar('estado');
        $sexo = $request->getVar('sexo');
        $idAlumno = $request->getVar('idAlumno');
    
        // Actualizar los datos en la base de datos
        $padreModel = new PadreModel(); // Instancia el modelo de padres
        $data = [
            'nombreCompleto' => $nombre_completo,
            'DUI' => $DUI,
            'telefono' => $telefono,
            'estado' => $estado,
            'Sexo' => $sexo,
            'idAlumno' => $idAlumno
        ];
        $padreModel->update($id, $data);
    
        // Redireccionar a la página principal o mostrar un mensaje de éxito
        return redirect()->to(site_url('padres'));
    }

    public function delete($id)
    {
        // Eliminar el padre de la base de datos
        $padreModel = new PadreModel(); // Instancia el modelo de padres
        $padreModel->delete($id);

        // Redireccionar a la página principal o mostrar un mensaje de éxito
        return redirect()->to(site_url('padres'));
    }
}

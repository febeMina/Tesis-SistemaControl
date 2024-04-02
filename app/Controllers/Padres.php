<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Padres extends Controller
{
    public function index()
    {
        // Cargar todos los padres desde la base de datos
        $db = \Config\Database::connect();
        $builder = $db->table('datos_responsable');
        $padres = $builder->get()->getResult();
    
        // Pasar los datos a la vista de index
        return view('padres/index', ['padres' => $padres]);
    }

    public function create()
    {
        // Mostrar el formulario para crear un nuevo padre
        $data['base_url'] = base_url();
        return view('padres/create', $data);
    }

    public function store()
    {
        // Capturar los datos del formulario de creación
        $request = \Config\Services::request();
        $nombre_completo = $request->getVar('nombre_completo');
        $sexo = $request->getVar('sexo');
        $dui = $request->getVar('dui');
        $telefono = $request->getVar('telefono');
        $estado = $request->getVar('estado');
        $id_alumno = $request->getVar('id_alumno');

        // Guardar los datos en la base de datos
        $db = \Config\Database::connect();
        $builder = $db->table('datos_responsable');
        $data = [
            'nombreCompleto' => $nombre_completo,
            'Sexo' => $sexo,
            'DUI' => $dui,
            'telefono' => $telefono,
            'estado' => $estado,
            'idAlumno' => $id_alumno
        ];
        $builder->insert($data);

        // Devolver una respuesta JSON
        return $this->response->setJSON(['success' => true]);
    }

    public function edit($id)
    {
        // Cargar los datos del padre a editar desde la base de datos
        $db = \Config\Database::connect();
        $builder = $db->table('datos_responsable');
        $padre = $builder->getWhere(['idDatosResponsable' => $id])->getRow();

        // Pasar los datos a la vista de edición
        return view('padres/edit', ['padre' => $padre]);
    }

    public function update($id)
    {
        // Capturar los datos del formulario de edición
        $request = \Config\Services::request();
        $nombre_completo = $request->getVar('nombre_completo');
        $sexo = $request->getVar('sexo');
        $dui = $request->getVar('dui');
        $telefono = $request->getVar('telefono');
        $estado = $request->getVar('estado');
        $id_alumno = $request->getVar('id_alumno');
    
        // Actualizar los datos en la base de datos
        $db = \Config\Database::connect();
        $builder = $db->table('datos_responsable');
        $data = [
            'nombreCompleto' => $nombre_completo,
            'Sexo' => $sexo,
            'DUI' => $dui,
            'telefono' => $telefono,
            'estado' => $estado,
            'idAlumno' => $id_alumno
        ];
        $builder->where('idDatosResponsable', $id);
        $builder->update($data);
    
        // Redireccionar a la página principal o mostrar un mensaje de éxito
        return redirect()->to('/');
    }

    public function delete($id)
    {
        // Eliminar el padre de la base de datos
        $db = \Config\Database::connect();
        $builder = $db->table('datos_responsable');
        $builder->where('idDatosResponsable', $id);
        $builder->delete();

        // Redireccionar a la página principal o mostrar un mensaje de éxito
        return redirect()->to('/');
    }
}

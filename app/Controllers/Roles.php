<?php

namespace App\Controllers;


use App\Models\RolesModel;
use CodeIgniter\Controller;

class Roles extends Controller
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
        $model = new RolesModel();
        $data['roles'] = $model->findAll();

        return view('roles/index', $data);
    }
    
     
    public function create()
    {
        return view('roles/create');
    }

    public function store()
    {
        // Capturar los datos del formulario de creación
        $request = \Config\Services::request();
        $nombreRol = $request->getVar('nombreRol');


        // Guardar los datos en la base de datos
        $db = \Config\Database::connect();
        $builder = $db->table('rol');
        $data = [
            'nombreRol' => $nombreRol
        ];
        $builder->insert($data);

        // Devolver una respuesta JSON
        return $this->response->setJSON(['success' => true]);
    }

    public function edit($id)
{
    // Cargar los datos del maestro a editar desde la base de datos
    $db = \Config\Database::connect();
    $builder = $db->table('rol');
    $rol = $builder->getWhere(['idRol' => $id])->getRow();

    // Pasar los datos a la vista de edición
    return view('roles/edit', ['rol' => $rol]);
}

public function update($id)
{
    // Capturar los datos del formulario de edición
    $request = \Config\Services::request();
    $nombre_completo = $request->getVar('nombreRol');
    
    // Actualizar los datos en la base de datos
    $db = \Config\Database::connect();
    $builder = $db->table('rol');
    $data = [
        'nombreRol' => $nombreRol,
       
    ];
    $builder->where('idRol', $id);
    $builder->update($data);
    
    // Redireccionar a la página principal o mostrar un mensaje de éxito
    return redirect()->to(site_url('roles'));
}

public function delete($id)
{
    // Eliminar el maestro de la base de datos
    $db = \Config\Database::connect();
    $builder = $db->table('rol');
    $builder->where('idRol', $id);
    $builder->delete();

    // Redireccionar a la página principal o mostrar un mensaje de éxito
    return redirect()->to(site_url('roles'));
}
}









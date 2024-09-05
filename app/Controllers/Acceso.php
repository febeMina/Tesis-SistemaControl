<?php

namespace App\Controllers;
use App\Controllers\BaseController;

use CodeIgniter\Controller;

class Acceso extends BaseController
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

        $db = \Config\Database::connect();

        // Obtener el usuario a editar
        $builder = $db->table('usuarios');
        $builder->select('usuarios.idUsuarios, usuarios.estado, usuarios.usuario, usuarios.idRol, rol.nombreRol');
        $builder->join('rol', 'rol.idRol = usuarios.idRol', 'inner');
        $usuario = $builder->get()->getResult();

        // Obtener la lista de roles
        $rolesBuilder = $db->table('rol');
        $roles = $rolesBuilder->select('idRol, nombreRol')->get()->getResult();

        return view('accesos/index', ['usuario' => $usuario, 'roles' => $roles]); 


    }
    
    public function store()
    {
       

        $db = \Config\Database::connect();
        $builder = $db->table('usuarios');

        // Datos a insertar
        $data = [
            'usuario' => $this->request->getPost('usuario'),
            'idRol' => $this->request->getPost('idRol'),
            'estado' => $this->request->getPost('estado')
        ];

        $builder->insert($data);

        // Devolver una respuesta JSON indicando éxito
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Usuario creado correctamente'
        ]);
    
    }

    public function edit($id)
    {
        $db = \Config\Database::connect();
        // Obtener el usuario a editar
        $builder = $db->table('usuarios');
        $builder->select('usuarios.idUsuarios, usuarios.estado, usuarios.usuario, usuarios.idRol, rol.nombreRol');
        $builder->join('rol', 'rol.idRol = usuarios.idRol', 'inner');
        $usuario = $builder->where('idUsuarios', $id)->get()->getRow();
 
        if (!$usuario) {
            return redirect()->to(base_url('public/usuario'))->with('error', 'El usuario no existe.');
        }

        // Obtener la lista de roles
        $rolesBuilder = $db->table('rol');
        $roles = $rolesBuilder->select('idRol, nombreRol')->get()->getResult();

        return view('accesos/editar', ['usuario' => $usuario, 'roles' => $roles]);
    }


    public function delete($id)
    {
        
    }

    public function update($id) 
    {
        $db = \Config\Database::connect();
        $builder = $db->table('usuarios');

        // Datos a actualizar
        $data = [
            'idRol' => $this->request->getPost('idRolS')
        ];
          
        $builder->where('idUsuarios', $id);
        $builder->update($data);
        //return view('accesos/index');
        //NO REDIRECCIONA PERO SI HACE EL CAMBIO EN LA BASE DE DATOS
        return redirect()->to(base_url('public/acceso'))->with('success', 'Acceso actualizado correctamente.');
      
    }    
   
}

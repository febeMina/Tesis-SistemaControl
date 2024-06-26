<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Usuario extends Controller
{
    public function __construct()
    {
        helper('url');
        if (!session()->get('isLoggedIn')) {
            redirect()->to(base_url('public/login'))->send();
            exit;
        }
    }

    public function index()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('usuarios');
        $builder->select('usuarios.idUsuarios, usuarios.estado, usuarios.usuario, rol.nombreRol, docente.nombre_completo');
        $builder->join('rol', 'rol.idRol = usuarios.idRol', 'inner');
        $builder->join('docente', 'docente.idDocente = usuarios.idDocente', 'inner');
        $usuarios = $builder->get()->getResult();
        return view('usuario/index', ['usuarios' => $usuarios]);
    }

    public function create()
    {
        $db = \Config\Database::connect();

        // Obtener la lista de docentes
        $docentesBuilder = $db->table('docente');
        $docentes = $docentesBuilder->select('idDocente, nombre_completo')->get()->getResult();

        // Obtener la lista de roles
        $rolesBuilder = $db->table('rol');
        $roles = $rolesBuilder->select('idRol, nombreRol')->get()->getResult();

        return view('usuario/create', ['docentes' => $docentes, 'roles' => $roles]);
    }

    public function store()
    {
        $rules = [
            'usuario' => 'required',
            'clave' => 'required',
            // Agrega aquí más reglas de validación según tus necesidades
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $db = \Config\Database::connect();
        $builder = $db->table('usuarios');

        // Verificar la clave
        $clave = $this->request->getPost('clave');
        if (!is_string($clave)) {
            // La clave no es una cadena de texto válida, manejar el error aquí
            return redirect()->back()->withInput()->with('error', 'Error en la clave del usuario.');
        }

        // Datos a insertar
        $data = [
            'usuario' => $this->request->getPost('usuario'),
            'clave' => password_hash($clave, PASSWORD_DEFAULT),
            'idDocente' => $this->request->getPost('idDocente'),
            'idRol' => $this->request->getPost('idRol'),
            'estado' => $this->request->getPost('estado'),
            'usuarioCrea' => session()->get('usuario'), // Obtener el nombre de usuario de la sesión actual
            'fechaCrea' => date('Y-m-d H:i:s'), // Obtener la fecha y hora actual
            'usuarioModifica' => session()->get('usuario'), // Inicialmente el mismo usuario que crea
            'fechaModifica' => date('Y-m-d H:i:s') // Inicialmente la misma fecha y hora que crea
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
        $builder->select('usuarios.idUsuarios, usuarios.estado, usuarios.usuario, usuarios.idDocente, usuarios.idRol, rol.nombreRol, docente.nombre_completo');
        $builder->join('rol', 'rol.idRol = usuarios.idRol', 'inner');
        $builder->join('docente', 'docente.idDocente = usuarios.idDocente', 'inner');
        $usuario = $builder->where('idUsuarios', $id)->get()->getRow();

        if (!$usuario) {
            return redirect()->to(base_url('public/usuario'))->with('error', 'El usuario no existe.');
        }

        // Obtener la lista de docentes
        $docentesBuilder = $db->table('docente');
        $docentes = $docentesBuilder->select('idDocente, nombre_completo')->get()->getResult();

        // Obtener la lista de roles
        $rolesBuilder = $db->table('rol');
        $roles = $rolesBuilder->select('idRol, nombreRol')->get()->getResult();

        return view('usuario/edit', ['usuario' => $usuario, 'docentes' => $docentes, 'roles' => $roles]);
    }

    public function update($id)
    {
        $rules = [
            'usuario' => 'required',
            'clave' => 'permit_empty',
            // Agrega aquí más reglas de validación según tus necesidades
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $db = \Config\Database::connect();
        $builder = $db->table('usuarios');

        // Datos a actualizar
        $data = [
            'usuario' => $this->request->getPost('usuario'),
            'idDocente' => $this->request->getPost('idDocente'),
            'idRol' => $this->request->getPost('idRol'),
            'estado' => $this->request->getPost('estado'),
            'usuarioModifica' => session()->get('usuario'), // Obtener el nombre de usuario de la sesión actual
            'fechaModifica' => date('Y-m-d H:i:s') // Obtener la fecha y hora actual
        ];

        // Obtener la clave
        $clave = (string) $this->request->getPost('clave');
        if (!empty($clave)) {
            $data['clave'] = password_hash($clave, PASSWORD_DEFAULT);
        }

        $builder->where('idUsuarios', $id);
        $builder->update($data);

        return redirect()->to(base_url('public/usuario'))->with('success', 'Usuario actualizado correctamente.');
    }
    public function delete($id)
    {
        $db = \Config\Database::connect();

        // Verificar si el usuario existe
        $builder = $db->table('usuarios');
        $usuario = $builder->where('idUsuarios', $id)->get()->getRow();

        if (!$usuario) {
            return redirect()->to(base_url('public/usuario'))->with('error', 'El usuario no existe.');
        }

        // Eliminar el usuario
        $builder = $db->table('usuarios');
        $builder->where('idUsuarios', $id);
        $builder->delete();

        return redirect()->to(base_url('public/usuario'))->with('success', 'Usuario eliminado correctamente.');
    }
    
    public function configuracion()
    {
        // Obtener el ID del usuario actualmente logueado
        $userId = session()->get('idUsuarios'); // Asegúrate de usar el nombre correcto del campo
    
        // Redirigir al usuario a su propia página de edición
        return redirect()->to(base_url("public/usuario/edit/{$userId}"));
    }
    
    

}

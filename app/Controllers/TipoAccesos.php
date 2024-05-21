<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Padres extends Controller
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
        $builder = $db->table('usuarios');
        $builder->select('usuarios.idUsuarios, usuarios.estado, usuarios.usuario, rol.nombreRol, docente.nombre_completo');
        $builder->join('rol', 'rol.idRol = usuarios.idRol', 'inner');
        $builder->join('docente', 'docente.idDocente = usuarios.idDocente', 'inner');
        $accesos = $builder->get()->getResult();
        return view('acceso/index', ['usuarios' => $accesos]);
    }
    
    public function store()
    {
    
    

    }
    
    public function delete($id)
    {
        
    }
    
    public function getRolesAjax($rolId)
    {
        $rolmodel = new RolesModel(); 
        $rol = $rolModel->getnombreRolByidRol($rolId); 
        
        return view('Accesos/index', ['roles' => $rol]);
    }
}

<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel; 
use App\Models\RolesModel;

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
        $tipoAcceso = new UserModel(); 
        $accesos = $tipoAcceso->findAll(); 
    
        return view('accesos/index', ['accesos' => $accesos]);
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

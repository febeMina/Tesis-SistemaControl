<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function __construct()
    {
        helper('url');
        if (!session()->get('isLoggedIn')) {
            redirect()->to(base_url('public/login'))->send();
            exit;
        }
    
    }
    public function index(): string
    {
        $db = \Config\Database::connect();
        $projectBuilder = $db->table('proyectos');
        $proyectos = $projectBuilder->select('idProyectos', 'nombreProyecto','estado','meta','valorActual')->get()->getResult();
        //$metas = $projectBuilder->select('meta')->get()->getResult();
      
       // return $this->response->setJSON($proyectos);
        return view('welcome_message', ['proyectos' => $proyectos]);
    }

    public function getMetas()
    {
        $db = \Config\Database::connect();
        $projectBuilder = $db->table('proyectos');
        //$proyectos = $projectBuilder->select('idProyectos', 'nombreProyecto','estado','meta','valorActual')->get()->getResult();
        $projectBuilder->select(['nombreProyecto', 'meta', 'valorActual']);
        $projectBuilder->where('estado!=', 'Eliminado');
        $projectBuilder->where('estado!=', 'Inactivo');
        $projectBuilder->where('estado!=', 'Finalizado');
        $metas =  $projectBuilder->get()->getResult();
        
       
        $json_data = json_encode($metas);
        return $this->response->setJSON($json_data);
    }

}

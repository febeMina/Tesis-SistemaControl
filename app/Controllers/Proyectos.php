<?php

namespace App\Controllers;

use App\Models\ProyectosModel;
use CodeIgniter\Controller;


class Proyectos extends Controller
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
        $projectBuilder = $db->table('proyectos');
        $proyectos = $projectBuilder->select('idProyectos', 'nombreProyecto', 'descripcion', 'estado', 'meta')->get()->getResult();
    
        return view('proyectos/index', $proyectos);
    }

    public function create()
    {

        
        return view('proyectos/create');
    }

    public function store()
    {
        $request = \Config\Services::request();
        $proyectModel = new ProyectosModel();

        $data = [
            'nombreProyecto' => $request->getVar('nombreProyecto'),
            'descripcion' => $request->getVar('descripcion'),
            'estado' => $request->getVar('estado'), // Corregido aquí
            'meta' => $request->getVar('meta')
        ];

        $proyectModel->insert($data);

        return redirect()->to(site_url('proyectos'));
    }

    public function update($id)
    {
        $request = \Config\Services::request();
        $proyectModel = new ProyectosModel();

        $data = [
            'nombreProyecto' => $request->getVar('nombreProyecto'),
            'descripción' => $request->getVar('descripcion'), // Corregido aquí
            'estado' => $request->getVar('estado'),
            'meta' => $request->getVar('meta')
        ];

        $proyectModel->update($id, $data);

        return redirect()->to(site_url('proyectos'));
    }

    public function edit($id)
    {
        $proyectModel = new ProyectosModel();
        $proyecto = $proyectModel->find($id);

        return view('proyecto/edit', ['proyecto' => $proyecto]);
    }

    public function delete($id)
    {
        $proyectModel = new ProyectosModel();
        $proyectModel->delete($id);
        return redirect()->to(site_url('proyecto'));
    }
}
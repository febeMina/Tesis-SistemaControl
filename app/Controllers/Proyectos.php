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
    
        $model = new ProyectosModel();
        $data['proyectos'] = $model->where('estado !=', 'Eliminado')->findAll();

        return view('proyectos/index', $data);
    }

    public function create()
    {
        
        return view('proyectos/create');
    }

    public function store()
    {
        $request = \Config\Services::request();
        $proyectModel = new ProyectosModel();
        $anoActual = date('Y');
        $valorA = 0;

        $data = [
            'nombreProyecto' => $request->getVar('nombreP'),
            'descripcion' => $request->getVar('descripcion'),
            'estado' => $request->getVar('estado'), // Corregido aquí
            'meta' => $request->getVar('metaP'),
            'anio' => $anoActual,
            'valorActual' => $valorA,
            'usuarioCrea' => session()->get('usuario'), // Captura el usuario actual
            
        ];

        $proyectModel->insert($data);

        return redirect()->to(site_url('proyectos'));
    }

    public function update($id)
    {
        $request = \Config\Services::request();
        $proyectModel = new ProyectosModel();

        $data = [
            'nombreProyecto' => $request->getVar('nombrep'),
            'descripcion' => $request->getVar('descripcion'), // Corregido aquí
            'estado' => $request->getVar('estado'),
            'meta' => $request->getVar('meta'),
            'anio' => $request->getVar('year'),
            'usuarioModifica' => session()->get('usuario'), // Captura el usuario que modifica
        ];

        $proyectModel->update($id, $data);

        return redirect()->to(site_url('proyectos'));
    }

    public function edit($id)
    {
        $proyectModel = new ProyectosModel();
        $proyectos = $proyectModel->find($id);

        return view('proyectos/edit', ['proyectos' => $proyectos]);
    }

    public function delete($id)
    {
        $request = \Config\Services::request();
        $proyectModel = new ProyectosModel();
        $proyectModel->update($id, ['estado' => 'Eliminado']);

        return redirect()->to(site_url('proyectos'));
    }
}
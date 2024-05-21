<?php

namespace App\Controllers;

use App\Models\ProyectosModel;
use CodeIgniter\Controller;

class Proyectos extends Controller
{
    public function index()
    {
        $model = new ProyectosModel();
        $data['proyectos'] = $model->findAll();

        return view('proyectos/index', $data);
    }

    public function __construct()
    {
        helper('form');
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
        $unidadModel = new UnidadesMedidaModel();

        $data = [
            'nombre' => $request->getVar('nombre'),
            'abreviatura' => $request->getVar('abreviatura'),
            'descripción' => $request->getVar('descripción'), // Corregido aquí
            'estado' => $request->getVar('estado')
        ];

        $unidadModel->update($id, $data);

        return redirect()->to(site_url('unidadesmedida'));
    }

    public function edit($id)
    {
        $unidadModel = new UnidadesMedidaModel();
        $unidad = $unidadModel->find($id);

        return view('unidades_medida/edit', ['unidad' => $unidad]);
    }

    public function delete($id)
    {
        $model = new UnidadesMedidaModel();
        $model->delete($id);
        return redirect()->to(site_url('unidadesmedida'));
    }
}
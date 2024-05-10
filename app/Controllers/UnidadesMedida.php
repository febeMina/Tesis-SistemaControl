<?php

namespace App\Controllers;

use App\Models\UnidadesMedidaModel;
use CodeIgniter\Controller;

class UnidadesMedida extends Controller
{
    public function index()
    {
        $model = new UnidadesMedidaModel();
        $data['unidades_medida'] = $model->findAll();
        return view('unidades_medida/index', $data);
    }

    public function __construct()
    {
        helper('form');
    }

    public function create()
    {
        return view('unidades_medida/create');
    }

    public function store()
    {
        $request = \Config\Services::request();
        $unidadModel = new UnidadesMedidaModel();

        $data = [
            'nombre' => $request->getVar('nombre'),
            'abreviatura' => $request->getVar('abreviatura'),
            'descripción' => $request->getVar('descripción'), // Corregido aquí
            'estado' => $request->getVar('estado')
        ];

        $unidadModel->insert($data);

        return redirect()->to(site_url('unidadesmedida'));
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
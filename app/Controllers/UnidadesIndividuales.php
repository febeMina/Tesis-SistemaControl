<?php

namespace App\Controllers;

use App\Models\UnidadesIndividualesModel;
use App\Models\UnidadesPorCajaModel;
use CodeIgniter\Controller;

class UnidadesIndividuales extends Controller
{
    public function index()
    {
        $unidadesIndividualesModel = new UnidadesIndividualesModel();
        $unidadesPorCajaModel = new UnidadesPorCajaModel();

        $data = [
            'unidades_medida_individual' => $unidadesIndividualesModel->findAll(),
            'unidades_medida_general' => $unidadesPorCajaModel->findAll()
        ];

        return view('unidades_medida_i/index', $data);
    }

    public function __construct()
    {
        helper('form');
    }

    public function create()
    {
        return view('unidades_medida_i/createUI');
    }

    public function store()
    {
        $request = \Config\Services::request();
        $unidadModel = new UnidadesIndividualesModel();

        $data = [
            'unidadades_individuales' => $request->getVar('unidadades_individuales'),
            'estado' => $request->getVar('estado')
        ];

        $unidadModel->insert($data);

        return redirect()->to(site_url('unidadesindividuales'));
    }

    public function update($id)
    {
        $request = \Config\Services::request();
        $unidadModel = new UnidadesIndividualesModel();

        $data = [
            'unidadades_individuales' => $request->getVar('unidadades_individuales'),
            'estado' => $request->getVar('estado')
        ];

        $unidadModel->update($id, $data);

        return redirect()->to(site_url('unidadesindividuales'));
    }

    public function edit($id)
    {
        $unidadModel = new UnidadesIndividualesModel();
        $unidad = $unidadModel->find($id);

        return view('unidades_medida_i/editUI', ['unidad' => $unidad]);
    }

    public function delete($id)
    {
        $model = new UnidadesIndividualesModel();
        $model->delete($id);
        return redirect()->to(site_url('unidadesindividuales'));
    }
}
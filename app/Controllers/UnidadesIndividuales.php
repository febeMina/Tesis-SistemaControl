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
            'udm_caja' => $unidadesPorCajaModel->findAll(),
            'udm_individual' => $unidadesIndividualesModel->findAll()
        ];
        
        return view('unidades_medida_i/index', $data);  // Asegúrate de que la vista index esté en unidades_medida_i
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
        $unidadModel = new UnidadesIndividualesModel();

        $data = [
            'nombreIndividual' => $this->request->getPost('nombreIndividual'),
            'estado' => 'Activo'
        ];

        $unidadModel->save($data);

        return redirect()->to(site_url('unidadesindividuales'));
    }

    public function update($id)
    {
        $unidadModel = new UnidadesIndividualesModel();

        $data = [
            'nombreIndividual' => $this->request->getPost('nombreIndividual')
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
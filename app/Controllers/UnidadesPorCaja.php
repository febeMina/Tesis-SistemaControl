<?php

namespace App\Controllers;

use App\Models\UnidadesIndividualesModel;
use App\Models\UnidadesPorCajaModel;
use CodeIgniter\Controller;

class UnidadesPorCaja extends Controller
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

    public function create()
    {
        return view('unidades_medida_i/createUPC');  // Cambiar la ruta de la vista a unidades_medida_i
    }

    public function store()
    {
        $unidadesPorCajaModel = new UnidadesPorCajaModel();

        $data = [
            'nombreCaja' => $this->request->getPost('nombreCaja'),
            'estado' => 'Activo'
        ];

        $unidadesPorCajaModel->save($data);
        
        return redirect()->to(site_url('unidadesporcaja'))->with('success', 'Unidad de  medida: Por caja creada con éxito');
    }

    public function edit($id)
    {
        $unidadesPorCajaModel = new UnidadesPorCajaModel();
        $data['unidad'] = $unidadesPorCajaModel->find($id);

        return view('unidades_medida_i/editUPC', $data);  // Cambiar la ruta de la vista a unidades_medida_i
    }

    public function update($id)
{
    $unidadesPorCajaModel = new UnidadesPorCajaModel();

    $data = [
        'nombreCaja' => $this->request->getPost('nombreCaja')
    ];

    $unidadesPorCajaModel->update($id, $data);
    
    return redirect()->to(site_url('unidadesporcaja'))->with('success', 'Unidad medida: Por caja actualizada con éxito');
}

    public function delete($id)
    {
        $unidadesPorCajaModel = new UnidadesPorCajaModel();
        $unidadesPorCajaModel->delete($id);
        
        return redirect()->to(site_url('unidadesporcaja'))->with('success', 'Unidad de medida: Por caja eliminada con éxito');
    }
}
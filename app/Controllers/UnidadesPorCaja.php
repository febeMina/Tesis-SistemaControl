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
            'unidades_medida_individual' => $unidadesIndividualesModel->findAll(),
            'unidades_medida_general' => $unidadesPorCajaModel->findAll()
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
            'tipo_unidad' => $this->request->getPost('tipo_unidad'),
            'unidades' => $this->request->getPost('unidades'),
            'estado' => $this->request->getPost('estado')
        ];

        $unidadesPorCajaModel->save($data);
        
        return redirect()->to(site_url('unidadesporcaja'))->with('success', 'Unidad de Medida General creada exitosamente');
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
        'tipo_unidad' => $this->request->getPost('tipo_unidad'),
        'unidades' => $this->request->getPost('unidades'), // Corregir la clave para capturar el valor correcto
        'estado' => $this->request->getPost('estado')
    ];

    $unidadesPorCajaModel->update($id, $data);
    
    return redirect()->to(site_url('unidadesporcaja'))->with('success', 'Unidad de Medida General actualizada exitosamente');
}

    public function delete($id)
    {
        $unidadesPorCajaModel = new UnidadesPorCajaModel();
        $unidadesPorCajaModel->delete($id);
        
        return redirect()->to(site_url('unidadesporcaja'))->with('success', 'Unidad de Medida General eliminada exitosamente');
    }
}
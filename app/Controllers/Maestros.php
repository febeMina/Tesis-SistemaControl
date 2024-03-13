<?php

namespace App\Controllers;

use App\Models\MaestroModel;

class Maestros extends BaseController
{
    public function index()
    {
        $model = new MaestroModel();
        $data['maestros'] = $model->findAll();
        return view('maestros/index', $data);
    }

    public function create()
    {
        return view('maestros/create');
    }

    public function store()
    {
        $model = new MaestroModel();
        $model->insert($this->request->getPost());
        return redirect()->to('/maestros');
    }

    public function edit($id)
    {
        $model = new MaestroModel();
        $data['maestro'] = $model->find($id);
        return view('maestros/edit', $data);
    }

    public function update($id)
    {
        $model = new MaestroModel();
        $model->update($id, $this->request->getPost());
        return redirect()->to('/maestros');
    }

    public function delete($id)
    {
        $model = new MaestroModel();
        $model->delete($id);
        return redirect()->to('/maestros');
    }
}

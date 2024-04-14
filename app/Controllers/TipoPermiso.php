<?php

namespace App\Controllers;

use App\Models\TipoPermisoModel;
use CodeIgniter\Controller;

class TipoPermiso extends BaseController
{
    public function index()
    {
        $model = new TipoPermisoModel();
        $data['tipos_permisos'] = $model->findAll();
        
        return view('tipo_permiso/index', $data);
    }

    public function create()
    {
        return view('tipo_permiso/create');
    }

    public function store()
    {
        $model = new TipoPermisoModel();

        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'cantidad_dias' => $this->request->getPost('cantidad_dias'),
        ];

        $model->insert($data);

        return redirect()->to('/tipo_permiso');
    }

    public function edit($id = null)
    {
        $model = new TipoPermisoModel();
        $data['tipo_permiso'] = $model->find($id);

        return view('tipo_permiso/edit', $data);
    }

    public function update()
    {
        $model = new TipoPermisoModel();

        $id = $this->request->getPost('id');

        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'cantidad_dias' => $this->request->getPost('cantidad_dias'),
        ];

        $model->update($id, $data);

        return redirect()->to('/tipo_permiso');
    }

    public function delete($id = null)
    {
        $model = new TipoPermisoModel();
        $model->delete($id);

        return redirect()->to('/tipo_permiso');
    }
}

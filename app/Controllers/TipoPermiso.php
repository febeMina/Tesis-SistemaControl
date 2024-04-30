<?php

namespace App\Controllers;

use App\Models\TipoPermisoModel;
use CodeIgniter\Controller;

class TipoPermiso extends BaseController
{
    public function index()
    {
        $tipoPermisoModel = new TipoPermisoModel();
        $tipos_permisos = $tipoPermisoModel->findAll();

        $data = [
            'tipos_permisos' => $tipos_permisos,
        ];

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
        if ($id === null) {
            return redirect()->to('/tipo_permiso');
        }

        $model = new TipoPermisoModel();
        $tipo_permiso = $model->find($id);

        if ($tipo_permiso === null) {
            return redirect()->to('/tipo_permiso');
        }

        $data['tipo_permiso'] = $tipo_permiso;

        return view('tipo_permiso/edit', $data);
    }

    public function update()
    {
        $id = $this->request->getPost('id');

        if ($id === null) {
            return redirect()->to('/tipo_permiso');
        }

        $model = new TipoPermisoModel();

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

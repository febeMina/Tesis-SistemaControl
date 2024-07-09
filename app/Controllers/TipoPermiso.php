<?php

namespace App\Controllers;

use App\Models\TipoPermisoModel;

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

        if ($model->insert($data)) {
            // Establecer mensaje flash de éxito
            $this->session->setFlashdata('success', 'Tipo de permiso creado exitosamente.');
        } else {
            // Establecer mensaje flash de error
            $this->session->setFlashdata('error', 'Error al crear el tipo de permiso.');
        }

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
            return $this->response->setJSON(['success' => false, 'message' => 'ID no encontrado.']);
        }

        $model = new TipoPermisoModel();

        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'cantidad_dias' => $this->request->getPost('cantidad_dias'),
        ];

        $model->update($id, $data);

        // Establecer mensaje flash
        $this->session->setFlashdata('success', 'Tipo de permiso actualizado exitosamente.');

        // Respuesta JSON para AJAX
        return $this->response->setJSON(['success' => true]);
    }

    public function delete($id = null)
    {
        $model = new TipoPermisoModel();
        $model->delete($id);

        $this->session->setFlashdata('success', 'Tipo de permiso eliminado exitosamente.');
        return redirect()->to('/tipo_permiso');
    }
}

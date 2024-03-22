<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LicenciaModel;

class AdminLicencias extends BaseController
{
    protected $licenciaModel;

    public function __construct()
    {
        $this->licenciaModel = new LicenciaModel();
    }

    public function index()
    {
        $data['licencias'] = $this->licenciaModel->findAll();
        return view('admin/licencias', $data);
    }

    public function create()
    {
        return view('admin/licencia_formulario');
    }

    public function store()
    {
        // Lógica para almacenar una nueva licencia en la base de datos
    }

    public function edit($id)
    {
        $data['licencia'] = $this->licenciaModel->find($id);
        return view('admin/licencia_formulario', $data);
    }

    public function update($id)
    {
        // Lógica para actualizar una licencia existente en la base de datos
    }

    public function delete($id)
    {
        // Lógica para eliminar una licencia de la base de datos
    }
}

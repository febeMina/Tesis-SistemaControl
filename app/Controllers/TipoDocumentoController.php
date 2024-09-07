<?php

namespace App\Controllers;

use App\Models\TipoDocumentoModel;
use CodeIgniter\Controller;

class TipoDocumentoController extends BaseController
{
    protected $tipoDocumentoModel;

    public function __construct()
    {
        $this->tipoDocumentoModel = new TipoDocumentoModel();
    }

    public function index()
    {
        $data['tiposDocumento'] = $this->tipoDocumentoModel->getTiposDocumento();

        return view('tipo_documento/index', $data);
    }

    public function create()
    {
        return view('tipo_documento/create');
    }

    public function store()
    {
        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'mascara' => $this->request->getPost('mascara'),
        ];

        if ($this->tipoDocumentoModel->createTipoDocumento($data)) {
            return redirect()->to('/tipo-documento')->with('success', 'Tipo de documento creado exitosamente.');
        } else {
            return redirect()->back()->with('error', 'Hubo un problema al crear el tipo de documento.');
        }
    }

    public function edit($id)
    {
        $data['tipoDocumento'] = $this->tipoDocumentoModel->getTipoDocumentoById($id);

        return view('tipo_documento/edit', $data);
    }

    public function update($id)
    {
        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'mascara' => $this->request->getPost('mascara'),
        ];

        if ($this->tipoDocumentoModel->updateTipoDocumento($id, $data)) {
            return redirect()->to('/tipo-documento')->with('success', 'Tipo de documento actualizado exitosamente.');
        } else {
            return redirect()->back()->with('error', 'Hubo un problema al actualizar el tipo de documento.');
        }
    }

    public function delete($id)
    {
        if ($this->tipoDocumentoModel->deleteTipoDocumento($id)) {
            return redirect()->to('/tipo-documento')->with('success', 'Tipo de documento eliminado exitosamente.');
        } else {
            return redirect()->back()->with('error', 'Hubo un problema al eliminar el tipo de documento.');
        }
    }
}

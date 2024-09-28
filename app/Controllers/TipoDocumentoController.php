<?php

namespace App\Controllers;

use App\Models\PadreModel;
use App\Models\TipoDocumentoModel;
use CodeIgniter\Controller;

class TipoDocumentoController extends Controller
{
    protected $tipoDocumentoModel;

    public function __construct()
    {
        $this->tipoDocumentoModel = new TipoDocumentoModel();
        // Asegúrate de que el usuario esté logueado
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Debes iniciar sesión.');
        }
    }

    public function index()
    {
         // Recuperar solo los documentos que no estén eliminados
    $data['tiposDocumento'] = $this->tipoDocumentoModel->where('estado !=', 'Eliminado')->findAll();

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
        'idTipoDocumento' => $this->request->getPost('idTipoDocumento'),
        'mascara' => $this->request->getPost('mascara'),
        'estado' => 'Activo',  // Estado inicial al crear
        'usuarioCrea' => session()->get('usuario'),
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
        'estado' => $this->request->getPost('estado'), // Asegúrate de capturar el valor del estado
        'usuarioModifica' => session()->get('usuario'), // Captura el usuario que modifica
    ];

    if ($this->tipoDocumentoModel->updateTipoDocumento($id, $data)) {
        return redirect()->to('/tipo-documento');
    } else {
        return redirect()->back()->with('error', 'Hubo un problema al actualizar el tipo de documento.');
    }
}


    public function delete($id = null)
    {
        if ($id) {
            $model = new TipoDocumentoModel();
            
            // Verifica si hay dependencias en datos_responsable
            $responsableModel = new PadreModel();
            $dependencias = $responsableModel->where('idTipoDocumento', $id)->findAll();
    
            if (!empty($dependencias)) {
                return $this->response->setJSON(['success' => false, 'message' => 'No se puede eliminar el tipo de documento, hay dependencias.']);
            }
    
            // Marcar el tipo de documento como "Eliminado" en lugar de borrarlo físicamente
            if ($model->deleteTipoDocumento($id)) {
                return $this->response->setJSON(['success' => true, 'message' => 'Tipo de documento marcado como eliminado con éxito.']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Error al eliminar el tipo de documento.']);
            }
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'ID no válido.']);
        }
    }
    


}

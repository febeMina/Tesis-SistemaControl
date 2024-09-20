<?php

namespace App\Controllers;

use App\Models\TipoPermisoModel;
use App\Models\MaestroModel;
use App\Models\SaldoPersonalModel;

class TipoPermiso extends BaseController
{
    protected $tipoPermisoModel;
    protected $maestroModel;
    protected $saldoPersonalModel;

    public function __construct()
    {
        $this->tipoPermisoModel = new TipoPermisoModel();
        $this->maestroModel = new MaestroModel();
        $this->saldoPersonalModel = new SaldoPersonalModel();

        // Verificación de sesión
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Debes iniciar sesión.');
        }
    }

    public function index()
    {
        $tipos_permisos = $this->tipoPermisoModel->getAllTipoPermisos();

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
        // Capturamos el nombre del usuario desde la sesión
        $usuarioActual = session()->get('usuario');

        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'cantidadDias' => $this->request->getPost('cantidadDias'),
            'estado' => 'Activo',  // Estado por defecto al crear
            'usuarioCrea' => $usuarioActual,  // Captura el usuario que crea
            'usuarioModifica' => $usuarioActual,  // Inicialmente el mismo usuario
        ];
    
        $success = $this->tipoPermisoModel->insert($data);
    
        if ($success) {
            $idTipoPermiso = $this->tipoPermisoModel->getInsertID();
    
            // Obtener todos los docentes
            $docentes = $this->maestroModel->findAll();
    
            // Insertar saldos iniciales para cada docente
            foreach ($docentes as $docente) {
                $this->saldoPersonalModel->insert([
                    'idDocente' => $docente['idDocente'],
                    'idTipoPermiso' => $idTipoPermiso,
                    'saldoActualDias' => $data['cantidadDias'],
                    'saldoActualHoras' => $data['cantidadDias'] * 6,
                ]);
            }
    
            // Guardar mensaje de éxito en Flashdata
            $this->session->setFlashdata('success', 'Tipo de permiso creado exitosamente.');
    
            // Redirigir al índice
            return redirect()->to('/tipo_permiso');
        } else {
            // Guardar mensaje de error en Flashdata
            $this->session->setFlashdata('error', 'Error al crear el tipo de permiso.');
    
            // Redirigir al índice
            return redirect()->to('/tipo_permiso');
        }
    }

    public function edit($id = null)
    {
        if ($id === null) {
            return redirect()->to('/tipo_permiso');
        }

        $tipo_permiso = $this->tipoPermisoModel->find($id);

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

    // Capturamos el nombre del usuario que modifica desde la sesión
    $usuarioActual = session()->get('usuario');

    // Captura los datos del formulario
    $data = [
        'cantidadDias' => $this->request->getPost('cantidadDias'),
        'estado' => $this->request->getPost('estado'),
    ];

    // Si el nombre no está deshabilitado en el formulario de edición
    if ($this->request->getPost('nombre') !== null) {
        $data['nombre'] = $this->request->getPost('nombre');
    }

    $this->tipoPermisoModel->update($id, $data);

    $this->session->setFlashdata('success', 'Tipo de permiso actualizado exitosamente.');

    return $this->response->setJSON(['success' => true]);
}

    
    public function estado($id)
{
    $tipo_permiso = $this->tipoPermisoModel->find($id);

    if ($tipo_permiso) {
        if ($tipo_permiso['estado'] == 'Activo') {
            $this->tipoPermisoModel->update($id, ['estado' => 'Inactivo']);
            error_log("Estado cambiado a Inactivo para ID: $id");
            $mensaje = 'El tipo de permiso ha sido cambiado a Inactivo.';
        } else {
            $this->tipoPermisoModel->update($id, ['estado' => 'Activo']);
            error_log("Estado cambiado a Activo para ID: $id");
            $mensaje = 'El tipo de permiso ha sido cambiado a Activo.';
        }

        return redirect()->to('/tipo_permiso')->with('success', $mensaje);
    } else {
        return redirect()->to('/tipo_permiso')->with('error', 'No se encontró el tipo de permiso.');
    }
}


    public function delete()
{
    $id = $this->request->getPost('id');
    $tipoPermisoModel = new TipoPermisoModel();
    $permiso = $tipoPermisoModel->find($id);

    if ($permiso) {
        // Cambiar el estado del tipo de permiso a "Eliminado" en lugar de eliminarlo
        $tipoPermisoModel->update($id, ['estado' => 'Eliminado']);
        
        // Responder con éxito
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Tipo de permiso eliminado exitosamente.'
        ]);
    } else {
        // Responder con error
        return $this->response->setJSON([
            'success' => false,
            'message' => 'No se encontró el tipo de permiso.'
        ]);
    }
}


    
}

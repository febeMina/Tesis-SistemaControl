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
        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'cantidad_dias' => $this->request->getPost('cantidad_dias'),
            'estado' => 'Activo'  // Estado por defecto al crear
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
                    'saldoActualDias' => $data['cantidad_dias'],
                    'saldoActualHoras' => $data['cantidad_dias'] * 6,
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

    $data = [
        'nombre' => $this->request->getPost('nombre'),
        'cantidad_dias' => $this->request->getPost('cantidad_dias'),
        'estado' => $this->request->getPost('estado') // Mantener el estado del formulario
    ];

    $this->tipoPermisoModel->update($id, $data);

    $this->session->setFlashdata('success', 'Tipo de permiso actualizado exitosamente.');

    return $this->response->setJSON(['success' => true]);
}

    public function delete($id = null)
    {
        if ($id !== null) {
            $this->tipoPermisoModel->update($id, ['estado' => 'inactivo']);

            $this->session->setFlashdata('success', 'Tipo de permiso desactivado exitosamente.');
        } else {
            $this->session->setFlashdata('error', 'No se encontró el ID del tipo de permiso.');
        }

        return redirect()->to('/tipo_permiso');
    }
}

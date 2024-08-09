<?php

namespace App\Controllers;

use App\Models\MaestroModel;
use App\Models\TipoPermisoModel;
use App\Models\SaldoPersonalModel;
use CodeIgniter\Controller;

class Maestros extends Controller
{
    protected $maestroModel;
    protected $tipoPermisoModel;
    protected $saldoPersonalModel;

    public function __construct()
    {
        helper('url');
        $this->maestroModel = new MaestroModel();
        $this->tipoPermisoModel = new TipoPermisoModel();
        $this->saldoPersonalModel = new SaldoPersonalModel();
    }

    public function index()
    {
        $request = service('request');

        // Obtener los datos de filtro del formulario
        $filters = [
            'nombre_completo' => $request->getVar('nombre_completo'),
            'nip' => $request->getVar('nip'),
            'escalafon' => $request->getVar('escalafon'),
            'fecha_ingreso' => $request->getVar('fecha_ingreso'),
            'estado' => $request->getVar('estado')
        ];

        // Obtener los datos filtrados
        $maestrosData = $this->maestroModel->filter($filters);

        // Pasar los datos a la vista
        return view('maestros/index', ['maestros' => $maestrosData]);
    }

    public function create()
    {
        // Muestra el formulario para crear un nuevo maestro
        return view('maestros/create');
    }

    public function store()
    {
        $request = \Config\Services::request();
        
        $nombre_completo = $request->getPost('nombre_completo');
        $nip = $request->getPost('nip');
        $escalafon = $request->getPost('escalafon');
        $fecha_ingreso = $request->getPost('fecha_ingreso');
        $estado = $request->getPost('estado');
        $tipo = $request->getPost('tipo');
        $rol = $request->getPost('rol');
    
        // Validación: Asegurarse de que el campo rol esté lleno para tipo Administrativo
        if ($tipo === 'Administrativo' && empty($rol)) {
            // Redirigir de vuelta con los datos ingresados anteriormente y un mensaje de error
            return redirect()->back()->withInput()->with('error', 'El campo cargo es obligatorio para el tipo Administrativo.');
        }
        
        $docenteData = [
            'nombre_completo' => $nombre_completo,
            'nip' => $nip,
            'escalafon' => $escalafon,
            'fecha_ingreso' => $fecha_ingreso,
            'estado' => $estado,
            'tipo' => $tipo,
            'cargo' => ($tipo === 'Administrativo') ? $rol : null, // Guardar el cargo solo para administrativos
        ];
        
        // Debugging: Verificar los datos recibidos
        log_message('debug', 'Datos del docente: ' . json_encode($docenteData));
    
        try {
            $this->maestroModel->insert($docenteData);
            $idDocente = $this->maestroModel->getInsertID();
    
            if ($idDocente) {
                // Insertar saldo inicial de permisos
                $tiposPermisos = $this->tipoPermisoModel->findAll();
    
                foreach ($tiposPermisos as $tipoPermiso) {
                    $cantidadDias = isset($tipoPermiso['cantidad_dias']) ? $tipoPermiso['cantidad_dias'] : 0;
                    $this->saldoPersonalModel->insert([
                        'idDocente' => $idDocente,
                        'idTipoPermiso' => $tipoPermiso['idTipoPermiso'],
                        'saldoActualDias' => $cantidadDias,
                        'saldoActualHoras' => $cantidadDias * 6
                    ]);
                }
    
                // Redirigir a la lista de maestros con un mensaje de éxito
                return $this->response->setJSON([
                    'success' => true,
                    'redirect' => site_url('maestros/index')
                ]);
            } else {
                // Hubo un error al guardar el maestro
                return $this->response->setJSON(['success' => false]);
            }
        } catch (\Exception $e) {
            // Manejar excepciones y errores
            log_message('error', 'Error al crear el maestro: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false]);
        }
    }

    public function edit($id)
    {
        // Cargar los datos del maestro a editar desde la base de datos
        $maestro = $this->maestroModel->find($id);

        // Pasar los datos a la vista de edición
        return view('maestros/edit', ['maestro' => $maestro]);
    }

    public function update($id)
    {
        // Capturar los datos del formulario de edición
        $request = \Config\Services::request();
        $nombre_completo = $request->getVar('nombre_completo');
        $nip = $request->getVar('nip');
        $escalafon = $request->getVar('escalafon');
        $fecha_ingreso = $request->getVar('fecha_ingreso');
        $estado = $request->getVar('estado');
        $tipo = $request->getVar('tipo');
        $cargo = ($tipo === 'Administrativo') ? $request->getVar('cargo') : null;

        // Actualizar los datos en la base de datos
        $data = [
            'nombre_completo' => $nombre_completo,
            'nip' => $nip,
            'escalafon' => $escalafon,
            'fecha_ingreso' => $fecha_ingreso,
            'estado' => $estado,
            'tipo' => $tipo,
            'cargo' => $cargo,
        ];

        $updated = $this->maestroModel->update($id, $data);

        if ($updated) {
            return redirect()->to(site_url('maestros'))->with('success', 'Docente actualizado con éxito.');
        } else {
            return redirect()->back()->withInput()->with('error', 'No se pudo actualizar el docente.');
        }
    }

    public function delete($id)
    {
        // Primero, elimina los registros relacionados en historial_permisos
        $historialPermisosModel = new \App\Models\HistorialPermisosModel();
        $historialPermisosModel->where('idDocente', $id)->delete();
        
        // Luego, elimina el maestro de la base de datos
        $this->maestroModel->delete($id);
    
        // Redireccionar a la página principal o mostrar un mensaje de éxito
        return redirect()->to(site_url('maestros'))->with('success', 'Docente eliminado con éxito.');
    }
}

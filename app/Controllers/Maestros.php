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
        $pager = \Config\Services::pager(); // Inicializar el servicio de paginación
        $currentPage = $request->getVar('page') ?? 1;
        $perPage = 5; // Número de elementos por página
    
        // Obtener los datos de filtro del formulario
        $filters = [
            'nombreCompleto' => $request->getVar('nombreCompleto'),
            'nip' => $request->getVar('nip'),
            'escalafon' => $request->getVar('escalafon'),
            'fechaIngreso' => $request->getVar('fechaIngreso'),
            'estado' => $request->getVar('estado'),
            'tipo' => $request->getVar('tipo')
        ];
    
        // Obtener el número total de filas después de aplicar los filtros
        $totalRows = $this->maestroModel->countFiltered($filters);
    
        // Obtener los datos filtrados y paginados
        $maestrosData = $this->maestroModel->filter($filters, $perPage, ($currentPage - 1) * $perPage);
    
        // Configurar paginación
        $pagination = $pager->makeLinks($currentPage, $perPage, $totalRows, 'bootstrap_pagination');
    
        // Pasar los datos a la vista
        return view('maestros/index', [
            'maestros' => $maestrosData,
            'pager' => $pagination,
            'filters' => $filters
        ]);
    }
    
    public function create()
{
    // Definir los cargos disponibles
    $cargos = [
        'Director' => 'Director',
        'Subdirector' => 'Subdirector',
        'Secretaria' => 'Secretaria',
        'Contador' => 'Contador',
        'Otro' => 'Otro'
    ];
    
    // Muestra el formulario para crear un nuevo maestro
    return view('maestros/create', ['cargos' => $cargos]);
}


    public function store()
    {
        log_message('debug', 'Método store() llamado');
        $request = \Config\Services::request();
        
        $nombreCompleto = $request->getPost('nombreCompleto');
        $nip = $request->getPost('nip');
        $escalafon = $request->getPost('escalafon');
        $fechaIngreso = $request->getPost('fechaIngreso');
        $estado = $request->getPost('estado');
        $tipo = $request->getPost('tipo');
        $cargo = $request->getPost('cargo');
    
        // Validación: Asegurarse de que el campo cargo esté lleno para tipo Administrativo
        if ($tipo === 'Administrativo' && empty($cargo)) {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'El campo cargo es obligatorio para el tipo Administrativo.'
            ]);
        }
    
        // Validación de unicidad de NIP y Escalafón
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nip' => [
                'label' => 'NIP',
                'rules' => 'required|is_unique[docente.nip]',
                'errors' => [
                    'required' => 'El {field} es obligatorio.',
                    'is_unique' => 'El {field} ya está registrado.'
                ]
            ],
            'escalafon' => [
                'label' => 'Escalafón',
                'rules' => 'required|is_unique[docente.escalafon]',
                'errors' => [
                    'required' => 'El {field} es obligatorio.',
                    'is_unique' => 'El {field} ya está registrado.'
                ]
            ]
        ]);
    
        if (!$validation->withRequest($this->request)->run()) {
            log_message('error', 'Errores de validación: ' . json_encode($validation->getErrors()));
            return $this->response->setJSON([
                'success' => false,
                'error' => $validation->getErrors()
            ]);
        }
        
        $docenteData = [
            'nombreCompleto' => $nombreCompleto,
            'nip' => $nip,
            'escalafon' => $escalafon,
            'fechaIngreso' => $fechaIngreso,
            'estado' => $estado,
            'tipo' => $tipo,
            'cargo' => ($tipo === 'Administrativo') ? $cargo : null // Guardar el cargo solo para administrativos
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
                    $cantidadDias = isset($tipoPermiso['cantidadDias']) ? $tipoPermiso['cantidadDias'] : 0;
                    $this->saldoPersonalModel->insert([
                        'idDocente' => $idDocente,
                        'idTipoPermiso' => $tipoPermiso['idTipoPermiso'],
                        'saldoActualDias' => $cantidadDias,
                        'saldoActualHoras' => $cantidadDias * 6
                    ]);
                }
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'El maestro ha sido creado exitosamente.',
                    'redirect' => site_url('maestros/index')
                ]);
            } else {
                log_message('error', 'La inserción en la tabla "docente" no devolvió un ID.');
                return $this->response->setJSON(['success' => false, 'error' => 'No se pudo crear el maestro.']);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al insertar el maestro: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'error' => 'Hubo un problema al crear el maestro. Por favor, intente nuevamente.'
            ]);
        }
    }
    


    public function edit($id)
{
    // Cargar los datos del maestro a editar desde la base de datos
    $maestro = $this->maestroModel->find($id);
    
    // Definir los cargos disponibles
    $cargos = [
        'Director' => 'Director',
        'Subdirector' => 'Subdirector',
        'Secretaria' => 'Secretaria',
        'Contador' => 'Contador',
        'Otro' => 'Otro'
    ];
    
    // Pasar los datos a la vista
    return view('maestros/edit', [
        'maestro' => $maestro,
        'cargos' => $cargos
    ]);
}


public function update($id)
{
    log_message('debug', 'Método update() llamado');
    $request = \Config\Services::request();

    $nombreCompleto = $request->getPost('nombreCompleto');
    $nip = $request->getPost('nip');
    $escalafon = $request->getPost('escalafon');
    $fechaIngreso = $request->getPost('fechaIngreso');
    $estado = $request->getPost('estado');
    $tipo = $request->getPost('tipo');
    $cargo = $request->getPost('cargo');

    // Validación de campos obligatorios para tipo Administrativo
    if ($tipo === 'Administrativo' && empty($cargo)) {
        return $this->response->setJSON([
            'success' => false,
            'error' => 'El campo cargo es obligatorio para el tipo Administrativo.'
        ]);
    }

    // Obtener el registro existente
    $existingDocente = $this->maestroModel->find($id);

    if (!$existingDocente) {
        log_message('error', 'No se encontró el maestro con el ID: ' . $id);
        return $this->response->setJSON([
            'success' => false,
            'error' => 'No se encontró el maestro con el ID especificado.'
        ]);
    }

    // Validación condicional para NIP y Escalafón
    $validation = \Config\Services::validation();
    $validationRules = [
        'nombreCompleto' => 'required',
        'fechaIngreso' => 'required',
        'estado' => 'required',
        'tipo' => 'required',
        'cargo' => 'permit_empty'
    ];

    // Solo validar NIP si ha cambiado
    if ($nip !== $existingDocente['nip']) {
        $validationRules['nip'] = [
            'rules' => 'required|is_unique[docente.nip]',
            'errors' => [
                'required' => 'El NIP es obligatorio.',
                'is_unique' => 'El NIP ingresado ya existe.'
            ]
        ];
    }

    // Solo validar Escalafón si ha cambiado
    if ($escalafon !== $existingDocente['escalafon']) {
        $validationRules['escalafon'] = [
            'rules' => 'required|is_unique[docente.escalafon]',
            'errors' => [
                'required' => 'El escalafón es obligatorio.',
                'is_unique' => 'El escalafón ingresado ya existe.'
            ]
        ];
    }

    $validation->setRules($validationRules);

    if (!$validation->withRequest($this->request)->run()) {
        log_message('error', 'Errores de validación: ' . json_encode($validation->getErrors()));
        return $this->response->setJSON([
            'success' => false,
            'error' => $validation->getErrors()
        ]);
    }

    $docenteData = [
        'nombreCompleto' => $nombreCompleto,
        'nip' => $nip,
        'escalafon' => $escalafon,
        'fechaIngreso' => $fechaIngreso,
        'estado' => $estado,
        'tipo' => $tipo,
        'cargo' => ($tipo === 'Administrativo') ? $cargo : null
    ];

    log_message('debug', 'Datos del docente para actualizar: ' . json_encode($docenteData));

    try {
        $updated = $this->maestroModel->update($id, $docenteData);

        if ($updated) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'El maestro ha sido actualizado exitosamente.',
                'redirect' => site_url('maestros/index')
            ]);
        } else {
            log_message('error', 'No se realizaron cambios en la base de datos para el ID: ' . $id);
            return $this->response->setJSON([
                'success' => false,
                'error' => 'No se pudo actualizar el maestro. Verifique los datos e intente nuevamente.'
            ]);
        }
    } catch (\Exception $e) {
        log_message('error', 'Error al actualizar el maestro: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'error' => 'Hubo un problema al actualizar el maestro. Por favor, intente nuevamente.'
        ]);
    }
}


public function delete()
{
    $request = \Config\Services::request(); // Obtener la instancia del Request
    $id = $request->getPost('id'); // Obtener el parámetro 'id'

    if ($id) {
        $maestrosModel = new MaestroModel();
        $result = $maestrosModel->setInactive($id); // Usar el método setInactive del modelo
        
        if ($result) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Maestro marcado como inactivo con éxito.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No se pudo marcar el maestro como inactivo.'
            ]);
        }
    }

    return $this->response->setJSON([
        'success' => false,
        'message' => 'ID de maestro no proporcionado.'
    ]);
}


    
}
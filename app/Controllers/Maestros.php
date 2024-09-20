<?php

namespace App\Controllers;

use App\Models\MaestroModel;
use App\Models\TipoPermisoModel;
use App\Models\SaldoPersonalModel;
use App\Models\GradoModel;
use App\Models\DocenteGradoModel;
use CodeIgniter\Controller;

class Maestros extends Controller
{
    protected $maestroModel;
    protected $docenteGradoModel;
    protected $gradoModel;
    protected $tipoPermisoModel;
    protected $saldoPersonalModel;

    public function __construct()
    {
        helper('url');
        $this->maestroModel = new MaestroModel();
        $this->docenteGradoModel = new DocenteGradoModel();
        $this->gradoModel = new GradoModel();
        $this->tipoPermisoModel = new TipoPermisoModel();
        $this->saldoPersonalModel = new SaldoPersonalModel();
    }

    public function index()
    {
        $request = service('request');
        $pager = \Config\Services::pager(); // Inicializar el servicio de paginación
        $currentPage = $request->getVar('page') ?? 1;
        $perPage = 8; // Número de elementos por página
        
        // Obtener los datos de filtro del formulario
        $filters = [
            'nombreCompleto' => $request->getVar('nombreCompleto'),
            'nip' => $request->getVar('nip'),
            'escalafon' => $request->getVar('escalafon'),
            'fechaIngreso' => $request->getVar('fechaIngreso'),
            'estado' => $request->getVar('estado') ?? 'todos', // Mostrar todos si no se selecciona ningún estado
            'tipo' => $request->getVar('tipo')
        ];
    
        // Si el filtro de estado es 'todos', no aplicar filtro de estado
        if ($filters['estado'] === 'todos') {
            unset($filters['estado']);
        }
    
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

    // Obtener los grados activos
    $grados = $this->gradoModel->where('estado', 'Activo')->findAll();

    // Verificar si se ha enviado el formulario
    if ($this->request->getMethod() === 'post') {
        // Obtener los datos del docente desde el formulario
        $dataDocente = [
            'nombreCompleto' => $this->request->getPost('nombreCompleto'),
            'nip' => $this->request->getPost('nip'),
            'escalafon' => $this->request->getPost('escalafon'),
            'fechaIngreso' => $this->request->getPost('fechaIngreso'),
            'estado' => $this->request->getPost('estado'),
            'tipo' => $this->request->getPost('tipo'),
            'cargo' => $this->request->getPost('cargo') // Solo se guardará si es 'Administrativo'
        ];

        // Obtener el id del grado seleccionado (ahora un solo valor)
        $idGrado = $this->request->getPost('idGrado');

        // Validar los datos recibidos
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nombreCompleto' => 'required',
            'nip' => 'required|is_unique[docente.nip]',
            'escalafon' => 'required|is_unique[docente.escalafon]',
            'fechaIngreso' => 'required',
            'estado' => 'required',
            'tipo' => 'required',
            'cargo' => 'permit_empty',
            'idGrado' => 'permit_empty|integer' // Asegúrate de que idGrado sea un entero si es opcional
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Si es 'Docente', verificar selección de grado y validar que sea un entero
        if ($dataDocente['tipo'] === 'Docente') {
            if (empty($idGrado) || !ctype_digit($idGrado)) {
                return redirect()->back()->withInput()->with('error', 'Debe seleccionar un grado válido para el tipo Docente.');
            }
        } else {
            $idGrado = null; // Asegurarse de que idGrado sea null si no es 'Docente'
        }

        // Insertar el docente
        $idDocente = $this->maestroModel->createDocente($dataDocente);

        if ($idDocente) {
            // Asignar grado si el tipo es 'Docente'
            if ($dataDocente['tipo'] === 'Docente') {
                if ($this->gradoModel->find($idGrado)) {
                    $this->docenteGradoModel->insert([
                        'idDocente' => $idDocente,
                        'idGrado' => $idGrado // Solo un grado
                    ]);
                } else {
                    return redirect()->back()->withInput()->with('error', 'El grado seleccionado no existe.');
                }
            }

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

            return redirect()->to('maestros')->with('message', 'Docente creado exitosamente.');
        } else {
            return redirect()->back()->withInput()->with('error', 'No se pudo crear el docente.');
        }
    }

    // Cargar vista con datos
    return view('maestros/create', [
        'cargos' => $cargos,
        'grados' => $grados // Solo grados activos
    ]);
}



public function store()
{
    $request = \Config\Services::request();

    // Obtener los datos del formulario
    $nombreCompleto = $request->getPost('nombreCompleto');
    $nip = $request->getPost('nip');
    $escalafon = $request->getPost('escalafon');
    $fechaIngreso = $request->getPost('fechaIngreso');
    $estado = $request->getPost('estado');
    $tipo = $request->getPost('tipo');
    $cargo = $request->getPost('cargo');
    $idGrado = $request->getPost('idGrado');

    // Validación: Asegurarse de que el campo cargo esté lleno para tipo Administrativo
    if ($tipo === 'Administrativo' && empty($cargo)) {
        return $this->response->setJSON([
            'success' => false,
            'error' => 'El campo cargo es obligatorio para el tipo Administrativo.'
        ]);
    }

    // Validación de datos
    $validation = \Config\Services::validation();
    $validation->setRules([
        'nombreCompleto' => 'required',
        'nip' => 'required|is_unique[docente.nip]',
        'escalafon' => 'required|is_unique[docente.escalafon]',
        'fechaIngreso' => 'required',
        'estado' => 'required',
        'tipo' => 'required',
        'cargo' => 'permit_empty',
        'idGrado' => 'permit_empty|integer'
    ]);

    if (!$validation->withRequest($this->request)->run()) {
        return $this->response->setJSON([
            'success' => false,
            'errors' => $validation->getErrors()
        ]);
    }

    // Prepara los datos del docente
    $docenteData = [
        'nombreCompleto' => $nombreCompleto,
        'nip' => $nip,
        'escalafon' => $escalafon,
        'fechaIngreso' => $fechaIngreso,
        'estado' => $estado,
        'tipo' => $tipo,
        'cargo' => ($tipo === 'Administrativo') ? $cargo : null
    ];

    // Solo añadir el idGrado si el tipo es Docente
    if ($tipo === 'Docente') {
        $docenteData['idGrado'] = $idGrado;
    }

    // Insertar los datos en la base de datos
    if ($this->maestroModel->save($docenteData)) {
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Docente creado exitosamente.'
        ]);
    } else {
        return $this->response->setJSON([
            'success' => false,
            'error' => 'No se pudo crear el docente.'
        ]);
    }
}



public function edit($id)
{
    // Obtener los datos del docente
    $maestro = $this->maestroModel->find($id);

    if (!$maestro) {
        return redirect()->to('/maestros')->with('error', 'Docente no encontrado.');
    }

    // Obtener los grados disponibles
    $grados = $this->gradoModel->findAll();

    // Definir los cargos disponibles
    $cargos = [
        'Director' => 'Director',
        'Subdirector' => 'Subdirector',
        'Secretaria' => 'Secretaria',
        'Contador' => 'Contador',
        'Otro' => 'Otro'
    ];

    // Obtener el grado asociado al docente si es tipo Docente
    $docenteGrado = $maestro['tipo'] === 'Docente' ? $maestro['idGrado'] : null;

    return view('maestros/edit', [
        'docente' => $maestro,
        'cargos' => $cargos,
        'grados' => $grados,
        'docenteGrado' => $docenteGrado // Asegúrate de pasar esto a la vista si es necesario
    ]);
}

public function update($id)
{
    $request = \Config\Services::request();

    $nombreCompleto = $request->getPost('nombreCompleto');
    $nip = $request->getPost('nip');
    $escalafon = $request->getPost('escalafon');
    $fechaIngreso = $request->getPost('fechaIngreso');
    $estado = $request->getPost('estado');
    $tipo = $request->getPost('tipo');
    $cargo = $request->getPost('cargo');
    $grado = $request->getPost('idGrado'); // Grado seleccionado

    // Validación: Asegurarse de que el campo cargo esté lleno para tipo Administrativo
    if ($tipo === 'Administrativo' && empty($cargo)) {
        return $this->response->setJSON([
            'success' => false,
            'error' => 'El campo cargo es obligatorio para el tipo Administrativo.'
        ]);
    }

    $validation = \Config\Services::validation();
    $validation->setRules([
        'nip' => [
            'label' => 'NIP',
            'rules' => 'required|is_unique[docente.nip,idDocente,' . $id . ']',
            'errors' => [
                'required' => 'El {field} es obligatorio.',
                'is_unique' => 'El {field} ya está registrado.'
            ]
        ],
        'escalafon' => [
            'label' => 'Escalafón',
            'rules' => 'required|is_unique[docente.escalafon,idDocente,' . $id . ']',
            'errors' => [
                'required' => 'El {field} es obligatorio.',
                'is_unique' => 'El {field} ya está registrado.'
            ]
        ]
    ]);

    if (!$validation->withRequest($this->request)->run()) {
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
        'cargo' => ($tipo === 'Administrativo') ? $cargo : null,
        'idGrado' => ($tipo === 'Docente') ? $grado : null // Asignar idGrado solo para 'Docente'
    ];

    // Debugging: Verificar los datos recibidos
    log_message('debug', 'Datos del docente a actualizar: ' . json_encode($docenteData));
    if (empty($docenteData['nombreCompleto']) || empty($docenteData['nip']) || empty($docenteData['escalafon'])) {
        return $this->response->setJSON([
            'success' => false,
            'error' => 'Faltan datos obligatorios.'
        ]);
    }

    try {
        // Actualizar el docente
        $this->maestroModel->update($id, $docenteData);

        // Actualizar grado si el tipo es Docente
        if ($tipo === 'Docente') {
            $this->maestroModel->update($id, ['idGrado' => $grado]);
        } else {
            $this->maestroModel->update($id, ['idGrado' => null]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Docente actualizado exitosamente.',
            'redirect' => site_url('maestros')
        ]);
    } catch (\Exception $e) {
        log_message('error', 'Error al actualizar docente: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'error' => 'Error al actualizar el docente.'
        ]);
    }
}



public function delete()
{
    $db = \Config\Database::connect();
    $db->transBegin();  // Iniciar una transacción

    try {
        // Obtener el ID desde la solicitud POST
        $id = $this->request->getPost('id');
        
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'ID no proporcionado.']);
        }

        // Marcar al docente como "Eliminado" en lugar de eliminarlo
        if (!$this->maestroModel->setDeleted($id)) {
            // Si algo falla, revertimos todos los cambios
            $db->transRollback();
            return $this->response->setJSON(['success' => false, 'message' => 'Ocurrió un error al marcar el docente como eliminado.']);
        }

        // Eliminar datos de saldo personal relacionados
        if (!$this->saldoPersonalModel->where('idDocente', $id)->delete()) {
            // Si algo falla, revertimos todos los cambios
            $db->transRollback();
            return $this->response->setJSON(['success' => false, 'message' => 'Ocurrió un error al eliminar los datos de saldo personal.']);
        }

        // Verificar si la transacción fue exitosa
        if ($db->transStatus() === FALSE) {
            // Si algo falla, revertimos todos los cambios
            $db->transRollback();
            return $this->response->setJSON(['success' => false, 'message' => 'Ocurrió un error durante el proceso.']);
        }

        // Si todo fue bien, confirmamos los cambios
        $db->transCommit();
        return $this->response->setJSON(['success' => true, 'message' => 'Docente marcado como eliminado exitosamente.']);
    } catch (\Exception $e) {
        // En caso de excepción, revertimos la transacción
        $db->transRollback();
        log_message('error', 'Error al marcar al docente como eliminado: ' . $e->getMessage());
        return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}





    public function inicializarSaldosPermisos()
{
    // Obtener todos los docentes que no tienen saldos iniciales
    $docentesSinSaldo = $this->maestroModel
        ->select('idDocente')
        ->whereNotIn('idDocente', function($builder) {
            return $builder->select('idDocente')->from('saldos_personal');
        })
        ->findAll();

    // Verificar si hay docentes sin saldo
    if (empty($docentesSinSaldo)) {
        return redirect()->back()->with('message', 'Todos los docentes ya tienen saldos inicializados.');
    }

    // Obtener los tipos de permisos
    $tiposPermisos = $this->tipoPermisoModel->findAll();

    foreach ($docentesSinSaldo as $docente) {
        foreach ($tiposPermisos as $tipoPermiso) {
            $cantidadDias = isset($tipoPermiso['cantidadDias']) ? $tipoPermiso['cantidadDias'] : 0;

            // Insertar el saldo inicial para cada tipo de permiso
            $this->saldoPersonalModel->insert([
                'idDocente' => $docente['idDocente'],
                'idTipoPermiso' => $tipoPermiso['idTipoPermiso'],
                'saldoActualDias' => $cantidadDias,
                'saldoActualHoras' => $cantidadDias * 6
            ]);
        }
    }

    return redirect()->back()->with('message', 'Saldos inicializados correctamente para los docentes que no los tenían.');
}

}


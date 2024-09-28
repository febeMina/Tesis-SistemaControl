<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PadreModel;
use App\Models\AlumnoModel; // Asegúrate de tener este modelo creado y configurado
use App\Models\ResponsableAlumnoModel;
use App\Models\TipoDocumentoModel;

class Padres extends BaseController
{
    public function __construct()
    {
        helper('url');
        if (!session()->get('isLoggedIn')) {
            redirect()->to(base_url('public/login'))->send();
            exit;
        }
    }

    public function index()
    {
        $pager = \Config\Services::pager();
        $padreModel = new PadreModel();
        $tipoDocumentoModel = new TipoDocumentoModel();
        $request = \Config\Services::request();

        // Paginación
        $currentPage = $request->getVar('page') ?? 1;
        $perPage = 5;
        $offset = ($currentPage - 1) * $perPage;

        // Obtener filtros de la solicitud
        $filters = [
            'nombre_completo' => $request->getVar('nombre_completo'),
            'tipo_documento' => $request->getVar('tipo_documento'),
            'genero' => $request->getVar('genero'),
            'estado' => $request->getVar('estado') !== null ? $request->getVar('estado') : '', // Agregar filtro de estado si es necesario
        ];

        // Total de filas con filtros aplicados
        $totalRows = $padreModel->countFilteredPadres($filters);

        // Obtener datos paginados
        $padres = $padreModel->getFilteredPadres($filters, $perPage, $offset);

        // Obtener los tipos de documento
        $tiposDocumento = $tipoDocumentoModel->findAll();

        // Retornar vista con datos paginados
        return view('padres/index', [
            'padres' => $padres,
            'filters' => $filters,
            'tiposDocumento' => $tiposDocumento,
            'pager' => $pager->makeLinks($currentPage, $perPage, $totalRows, 'bootstrap_pagination')
        ]);
    }
    

    public function create()
{
    $data = [
        'alumnos' => session()->getFlashdata('alumnos') ?? []
    ];
    // Cargar los tipos de documentos disponibles desde el modelo
    $tipoDocumentoModel = new TipoDocumentoModel();
    $data['tipos_documento'] = $tipoDocumentoModel->findAll();
    return view('padres/create', $data);
}


public function store()
{
    // Validación
    $validationRules = [
        'nombre_completo' => 'required',
        'idTipoDocumento' => 'required',
        'numeroDocumento' => 'required',
        'telefono' => 'required',
        'genero' => 'required',
        'estado' => 'required',
        'tipoAsociado' => 'required'
    ];

    if ($this->request->getPost('tipoAsociado') === 'INTERNO') {
        $validationRules['alumno_nombre_completo.*'] = 'required';
        $validationRules['alumno_sexo.*'] = 'required';
        $validationRules['alumno_nie.*'] = 'required';
        $validationRules['alumno_estado.*'] = 'required';
    }

    if (!$this->validate($validationRules)) {
        return redirect()->back()->withInput()->with('error', 'Por favor corrige los errores en el formulario.');
    }

    $data = [
        'nombreCompleto' => $this->request->getPost('nombre_completo'),
        'idTipoDocumento' => $this->request->getPost('idTipoDocumento'),
        'numeroDocumento' => $this->request->getPost('numeroDocumento'),
        'telefono' => $this->request->getPost('telefono'),
        'genero' => $this->request->getPost('genero'),
        'estado' => $this->request->getPost('estado'),
        'tipoAsociado' => $this->request->getPost('tipoAsociado'),
        'usuarioCrea' => session()->get('usuario'), // Captura el usuario actual
    ];

    $padreModel = new PadreModel();
    $padreId = $padreModel->insert($data);

    if ($this->request->getPost('tipoAsociado') === 'INTERNO') {
        $alumnos = $this->request->getPost('alumno_nombre_completo');
        $alumnoModel = new AlumnoModel();
        $responsableAlumnoModel = new ResponsableAlumnoModel();

        foreach ($alumnos as $index => $nombre) {
            $alumnoData = [
                'nombreAlumno' => $nombre,
                'generoAlumno' => $this->request->getPost('alumno_sexo')[$index],
                'NIE' => $this->request->getPost('alumno_nie')[$index],
                'estado' => $this->request->getPost('alumno_estado')[$index],
                'padre_id' => $padreId,
                'usuarioCrea' => session()->get('usuario'), // Captura el usuario actual
            ];

            // Depuración
            log_message('debug', 'Datos del alumno: ' . print_r($alumnoData, true));

            // Insertar alumno
            $alumnoId = $alumnoModel->insert($alumnoData);

            // Insertar relación responsable-alumno
            $responsableAlumnoModel->insert([
                'idDatosResponsable' => $padreId,
                'idAlumno' => $alumnoId
            ]);
        }
    }

    return redirect()->to('/padres')->with('success', 'Padre agregado con éxito.');
}


    public function edit($id)
    {
        $padreModel = new PadreModel();
        $tipoDocumentoModel = new TipoDocumentoModel();
        $responsableAlumnoModel = new ResponsableAlumnoModel();
        
        $padre = $padreModel->getDatosResponsableWithTipoDocumento($id);
        
        if (!$padre) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Padre no encontrado');
        }
        
        $tipos_documento = $tipoDocumentoModel->findAll();
        $alumnos = $responsableAlumnoModel->getAlumnosAsociados($id);
        
        $data = [
            'padre' => $padre,
            'tipos_documento' => $tipos_documento,
            'alumnos' => $alumnos
        ];
        
        return view('padres/edit', $data);
    }
    
    public function update($id)
    {
        $validationRules = [
            'nombre_completo' => 'required',
            'idTipoDocumento' => 'required',
            'numeroDocumento' => 'required',
            'telefono' => 'required',
            'genero' => 'required',
            'estado' => 'required',
            'tipoAsociado' => 'required'
        ];
    
        if ($this->request->getPost('tipoAsociado') === 'INTERNO') {
            $validationRules['alumno_nombre_completo.*'] = 'required';
            $validationRules['alumno_sexo.*'] = 'required';
            $validationRules['alumno_nie.*'] = 'required';
            $validationRules['alumno_estado.*'] = 'required';
        }
    
        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('error', 'Por favor corrige los errores en el formulario.');
        }
    
        // Actualiza los datos del padre
        $data = [
            'nombreCompleto' => $this->request->getPost('nombre_completo'),
            'idTipoDocumento' => $this->request->getPost('idTipoDocumento'),
            'numeroDocumento' => $this->request->getPost('numeroDocumento'),
            'telefono' => $this->request->getPost('telefono'),
            'genero' => $this->request->getPost('genero'),
            'estado' => $this->request->getPost('estado'),
            'tipoAsociado' => $this->request->getPost('tipoAsociado'),
            'usuarioModifica' => session()->get('usuario'), // Captura el usuario que modifica
        ];
    
        $padreModel = new PadreModel();
        $padreModel->update($id, $data);
    
        // Elimina asociaciones anteriores
        $responsableAlumnoModel = new ResponsableAlumnoModel();
        $responsableAlumnoModel->deleteAlumnosAsociados($id);
    
        if ($this->request->getPost('tipoAsociado') === 'INTERNO') {
            $alumnos = $this->request->getPost('alumno_nombre_completo');
            $alumnoIds = $this->request->getPost('alumno_id') ?? [];
            $alumnoNIEs = $this->request->getPost('alumno_nie') ?? [];
    
            $alumnoModel = new AlumnoModel();
    
            foreach ($alumnos as $index => $nombre) {
                $alumnoData = [
                    'nombreAlumno' => $nombre,
                    'generoAlumno' => $this->request->getPost('alumno_sexo')[$index] ?? null,
                    'NIE' => $alumnoNIEs[$index] ?? null,
                    'estado' => $this->request->getPost('alumno_estado')[$index] ?? null,
                    'usuarioModifica' => session()->get('usuario'), // Captura el usuario que modifica
                ];
    
                $alumnoId = $alumnoIds[$index] ?? null;
                $existingAlumno = $alumnoModel->where('NIE', $alumnoData['NIE'])->first();
    
                if ($existingAlumno) {
                    // Actualiza el alumno existente
                    $alumnoModel->update($existingAlumno['idAlumno'], $alumnoData);
                    $alumnoId = $existingAlumno['idAlumno'];
                } else {
                    // Inserta un nuevo alumno
                    $alumnoId = $alumnoModel->insert($alumnoData);
                }
    
                // Asocia el alumno al padre
                $responsableAlumnoModel->insertRecord([
                    'idDatosResponsable' => $id,
                    'idAlumno' => $alumnoId
                ]);
            }
        }
    
        return redirect()->to('/padres')->with('success', 'Datos actualizados correctamente');
    }
    
    

    
    public function delete($id)
{
    $padreModel = new PadreModel();
    $alumnoModel = new AlumnoModel();
    $responsableAlumnoModel = new ResponsableAlumnoModel();

    // Cambiar el estado del padre a "Eliminado" en lugar de "inactivo"
    $padreModel->update($id, ['estado' => 'Eliminado']);

    // Obtener los responsables de alumnos asociados al padre
    $responsables = $responsableAlumnoModel->where('idDatosResponsable', $id)->findAll();

    foreach ($responsables as $responsable) {
        // Cambiar el estado de los alumnos asociados a "Eliminado" en lugar de "inactivo"
        $alumnoModel->update($responsable['idAlumno'], ['estado' => 'Eliminado']);
    }

    // También puedes eliminar los registros de responsable_alumno si es necesario
    $responsableAlumnoModel->where('idDatosResponsable', $id)->delete();

    return redirect()->to(site_url('padres'))->with('success', 'El asociado y sus alumnos han sido marcados como eliminados.');
}


    public function getAlumnosAjax($padreId)
    {
        $responsableAlumnoModel = new ResponsableAlumnoModel();
        $alumnos = $responsableAlumnoModel->getAlumnosAsociados($padreId); 

        return view('padres/alumnos_partial', ['alumnos' => $alumnos]);
    }
}

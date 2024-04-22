<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PadreModel; 
use App\Models\AlumnoModel;

class Padres extends Controller
{
    public function __construct(){
        helper('url');
        if (!session()->get('isLoggedIn')) {
            redirect()->to(base_url('public/login'))->send();
            exit;
        }
    }
    public function index()
    {
        $padreModel = new PadreModel(); 
        $padres = $padreModel->findAll(); 
    
        return view('padres/index', ['padres' => $padres]);
    }

    public function create()
    {
        return view('padres/create');
    }

    public function store()
    {
        // Capturar los datos del formulario de creación
        $request = \Config\Services::request();
        $nombre_completo = $request->getVar('nombre_completo');
        $Sexo = $request->getVar('Sexo');
        $DUI = $request->getVar('dui');
        $telefono = $request->getVar('telefono');
        $estado = $request->getVar('estado');
        $alumno_nombre_completo = $request->getVar('alumno_nombre_completo');
        $alumno_sexo = $request->getVar('alumno_sexo');
        $alumno_nie = $request->getVar('alumno_nie');
        $alumno_estado = $request->getVar('alumno_estado');
    
        // Guardar los datos del padre en la base de datos
        $padreModel = new PadreModel(); // Instancia el modelo de padres
        $dataPadre = [
            'nombreCompleto' => $nombre_completo,
            'Sexo' => $Sexo,
            'DUI' => $DUI,
            'telefono' => $telefono,
            'estado' => $estado,
        ];
        $padreId = $padreModel->insert($dataPadre);
    
        // Guardar los datos de los alumnos asociados al padre en la base de datos
        $alumnosData = []; // Arreglo para almacenar los datos de los alumnos
        foreach ($alumno_nombre_completo as $key => $nombreCompleto) {
            $alumnosData[] = [
                'nombreCompleto' => $nombreCompleto,
                'Sexo' => $alumno_sexo[$key],
                'NIE' => $alumno_nie[$key],
                'estado' => $alumno_estado[$key],
                'idPadre' => $padreId // Asociar el alumno al padre recién creado
            ];
        }
        // Guardar los datos de los alumnos en la base de datos
        $alumnoModel = new AlumnoModel(); // Instancia el modelo de alumnos
        $alumnoModel->insertBatch($alumnosData);
    
        // Redireccionar al index de padres
        return redirect()->to(site_url('padres'));
    }

    public function edit($id)
    {
        // Carga el modelo de la tabla de datos_responsable
        $PadreModel = new PadreModel();
        
        // Busca al padre por su ID
        $padre = $PadreModel->find($id);
        
        // Verifica si se encontró al padre
        if ($padre === null) {
            // Maneja el caso en el que no se encuentra al padre
            // Por ejemplo, muestra un mensaje de error o redirige a otra página
        }
        
        // Obtiene los alumnos asociados a este padre
        $alumnos = $PadreModel->where('idDatosResponsable', $padre['idDatosResponsable'])->findAll();
        
        // Carga la vista de edición con los datos del padre y los alumnos asociados
        return view('padres/edit', ['padre' => $padre, 'alumnos' => $alumnos]);
    }
    
    
    
    public function update($id)
{
    $request = \Config\Services::request();
    $nombre_completo = $request->getVar('nombre_completo');
    $Sexo = $request->getVar('sexo');
    $DUI = $request->getVar('dui');
    $telefono = $request->getVar('telefono');
    $estado = $request->getVar('estado');
    $alumno_nombre_completo = $request->getVar('alumno_nombre_completo');
    $alumno_sexo = $request->getVar('alumno_sexo');
    $alumno_nie = $request->getVar('alumno_nie');
    $alumno_estado = $request->getVar('alumno_estado');
    $alumno_id = $request->getVar('alumno_id'); // Agregar esta línea para obtener los IDs de los alumnos
    
    $padreModel = new PadreModel(); 
    $dataPadre = [
        'nombreCompleto' => $nombre_completo,
        'Sexo' => $Sexo,
        'DUI' => $DUI,
        'telefono' => $telefono,
        'estado' => $estado,
    ];
    $padreModel->update($id, $dataPadre);

    // Actualizar los datos de los alumnos asociados al padre
    $alumnoModel = new AlumnoModel();
    foreach ($alumno_nombre_completo as $key => $nombreCompleto) {
        $dataAlumno = [
            'nombreCompleto' => $nombreCompleto,
            'Sexo' => $alumno_sexo[$key],
            'NIE' => $alumno_nie[$key],
            'estado' => $alumno_estado[$key],
        ];
        $alumnoModel->update($alumno_id[$key], $dataAlumno); // Utilizar el ID del alumno correspondiente
    }

    // Redireccionar al index de padres
    return redirect()->to(site_url('padres'))->with('success', 'El padre ha sido actualizado exitosamente.');
}


    
    public function delete($id)
    {
        $padreModel = new PadreModel(); 
        $padreModel->delete($id);

        return redirect()->to(site_url('padres'));
    }
    
    public function getAlumnosAjax($padreId)
    {
        $alumnoModel = new AlumnoModel(); 
        $alumnos = $alumnoModel->getAlumnosByPadreId($padreId); 
        
        return view('padres/alumnos_partial', ['alumnos' => $alumnos]);
    }
}

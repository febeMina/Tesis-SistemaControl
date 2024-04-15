<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PadreModel; 
use App\Models\AlumnoModel;

class Padres extends Controller
{
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
        $padreModel = new PadreModel(); 
        $padre = $padreModel->find($id);

        return view('padres/edit', ['padre' => $padre]);
    }

    public function update($id)
    {
        $request = \Config\Services::request();
        $nombre_completo = $request->getVar('nombre_completo');
        $DUI = $request->getVar('dui');
        $telefono = $request->getVar('telefono');
        $estado = $request->getVar('estado');
        $sexo = $request->getVar('sexo');
        $idAlumno = $request->getVar('idAlumno');
    
        $padreModel = new PadreModel(); 
        $data = [
            'nombreCompleto' => $nombre_completo,
            'DUI' => $DUI,
            'telefono' => $telefono,
            'estado' => $estado,
            'Sexo' => $sexo,
            'idAlumno' => $idAlumno
        ];
        $padreModel->update($id, $data);
    
        return redirect()->to(site_url('padres'));
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

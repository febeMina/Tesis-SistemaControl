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
        $sexo = $request->getVar('sexo');
        $idAlumno = $request->getVar('idAlumno');
    
        // Verificar si se recibió el valor de idAlumno
        if ($idAlumno === null) {
            // Si idAlumno es nulo, mostrar un mensaje de error o realizar alguna acción adecuada
            return redirect()->back()->withInput()->with('error', 'No se recibió el ID del alumno asociado al padre.');
        }
    
        // Guardar los datos del padre en la base de datos
        $padreModel = new PadreModel(); // Instancia el modelo de padres
        $dataPadre = [
            'nombreCompleto' => $nombre_completo,
            'DUI' => $DUI,
            'telefono' => $telefono,
            'estado' => $estado,
            'Sexo' => $sexo,
            'idAlumno' => $idAlumno
        ];
        $padreId = $padreModel->insert($dataPadre);
    
        // Guardar los datos de los alumnos asociados al padre en la base de datos
        $alumnos = $request->getVar('alumnos'); // Obtener los datos de los alumnos del formulario
        $alumnosData = []; // Arreglo para almacenar los datos de los alumnos
        foreach ($alumnos as $alumno) {
            $alumnosData[] = [
                'nombreCompleto' => $alumno['nombreCompleto'],
                'Sexo' => $alumno['sexo'],
                'NIE' => $alumno['nie'],
                'estado' => $alumno['estado'],
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

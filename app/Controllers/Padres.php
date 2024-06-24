<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PadreModel;
use App\Models\AlumnoModel; // Asegúrate de tener este modelo creado y configurado
use App\Models\ResponsableAlumnoModel;

class Padres extends Controller
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
        $padreModel = new PadreModel();
        $request = \Config\Services::request();
        $filters = [
            'nombre_completo' => $request->getVar('nombre_completo'),
            'dui' => $request->getVar('dui'),
            'genero' => $request->getVar('genero')
        ];

        $padres = $padreModel->getFilteredPadres($filters);

        return view('padres/index', ['padres' => $padres, 'filters' => $filters]);
    }

    public function create()
    {
        return view('padres/create');
    }

    public function store()
    {
        $request = \Config\Services::request();

        // Datos del padre
        $dataPadre = [
            'nombreCompleto' => $request->getVar('nombre_completo'),
            'Genero' => $request->getVar('genero'),
            'DUI' => $request->getVar('dui'),
            'telefono' => $request->getVar('telefono'),
            'estado' => $request->getVar('estado'),
        ];

        $padreModel = new PadreModel();
        $padreId = $padreModel->insert($dataPadre);

        // Asociar alumnos
        if (!empty($request->getVar('alumnos'))) {
            $responsableAlumnoModel = new ResponsableAlumnoModel();

            foreach ($request->getVar('alumnos') as $alumno) {
                $responsableAlumnoModel->save([
                    'idDatosResponsable' => $padreId,
                    'idAlumno' => $alumno['id'],
                ]);
            }
        }

        return redirect()->to(site_url('padres'));
    }

    public function edit($id)
    {
        $padreModel = new PadreModel();
        $responsableAlumnoModel = new ResponsableAlumnoModel();
        $alumnoModel = new AlumnoModel();
    
        // Obtener datos del padre
        $padre = $padreModel->find($id);
    
        if ($padre === null) {
            return redirect()->to(site_url('padres'))->with('error', 'Padre no encontrado.');
        }
    
        // Obtener alumnos asociados al padre
        $alumnos = $responsableAlumnoModel->getAlumnosAsociados($id);
    
        foreach ($alumnos as &$alumno) {
            // Obtener datos adicionales de cada alumno (como el género)
            $alumnoData = $alumnoModel->find($alumno['idAlumno']);
            $alumno['Genero_alumno'] = $alumnoData['Genero_alumno']; // Ajusta según el nombre en tu modelo
        }
    
        return view('padres/edit', ['padre' => $padre, 'alumnos' => $alumnos]);
    }
    


    public function update($id)
{
    $request = \Config\Services::request();
    $padreModel = new PadreModel();
    $alumnoModel = new AlumnoModel(); // Ajusta según el nombre de tu modelo

    // Datos del padre
    $dataPadre = [
        'nombreCompleto' => $request->getVar('nombre_completo'),
        'Genero' => $request->getVar('genero'),
        'DUI' => $request->getVar('dui'),
        'telefono' => $request->getVar('telefono'),
        'estado' => $request->getVar('estado'),
    ];

    $padreModel->update($id, $dataPadre);

    // Actualizar alumnos existentes
    if (!empty($request->getVar('alumno_id'))) {
        foreach ($request->getVar('alumno_id') as $index => $alumnoId) {
            $dataAlumno = [
                'nombreAlumno' => $request->getVar('alumno_nombre_completo')[$index],
                'Genero_alumno' => $request->getVar('alumno_sexo')[$index],
                'NIE' => $request->getVar('alumno_nie')[$index],
                'estado' => $request->getVar('alumno_estado')[$index],
            ];
            $alumnoModel->update($alumnoId, $dataAlumno);
        }
    }

    // Agregar nuevos alumnos solo si se proporcionan datos válidos
    if (!empty($request->getVar('nuevo_alumno_nombre_completo'))) {
        foreach ($request->getVar('nuevo_alumno_nombre_completo') as $index => $nuevoAlumnoNombre) {
            if (!empty($nuevoAlumnoNombre) && !empty($request->getVar('nuevo_alumno_nie')[$index])) {
                $dataNuevoAlumno = [
                    'nombreAlumno' => $nuevoAlumnoNombre,
                    'Genero_alumno' => $request->getVar('nuevo_alumno_sexo')[$index],
                    'NIE' => $request->getVar('nuevo_alumno_nie')[$index],
                    'estado' => $request->getVar('nuevo_alumno_estado')[$index],
                ];
                $nuevoAlumnoId = $alumnoModel->insert($dataNuevoAlumno);

                // Asociar nuevo alumno al padre
                $responsableAlumnoModel = new ResponsableAlumnoModel();
                $responsableAlumnoModel->insert([
                    'idDatosResponsable' => $id,
                    'idAlumno' => $nuevoAlumnoId,
                ]);
            }
        }
    }

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
        $responsableAlumnoModel = new ResponsableAlumnoModel();
        $alumnos = $responsableAlumnoModel->getAlumnosAsociados($padreId); // Utilizar el método correcto

        return view('padres/alumnos_partial', ['alumnos' => $alumnos]);
    }
}

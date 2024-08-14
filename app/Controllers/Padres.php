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
            'genero' => $request->getVar('genero'),
            'estado' => 'activo' // Filtro para mostrar solo padres activos
        ];
    
        $padres = $padreModel->getFilteredPadres($filters);
    
        return view('padres/index', ['padres' => $padres, 'filters' => $filters]);
    }
    

    public function create()
{
    $data = [
        'alumnos' => session()->getFlashdata('alumnos') ?? []
    ];
    return view('padres/create', $data);
}


public function store()
{
    $request = \Config\Services::request();
    $padreModel = new PadreModel();
    $alumnoModel = new AlumnoModel();
    $responsableAlumnoModel = new ResponsableAlumnoModel();
    
    // Datos del padre
    $dataPadre = [
        'nombreCompleto' => $request->getVar('nombre_completo'),
        'Genero' => $request->getVar('genero'),
        'DUI' => $request->getVar('dui'),
        'telefono' => $request->getVar('telefono'),
        'estado' => $request->getVar('estado'),
    ];

    // Validar unicidad del DUI
    if ($padreModel->where('DUI', $dataPadre['DUI'])->first()) {
        $alumnos = [
            'nombre_completo' => $request->getVar('alumno_nombre_completo'),
            'genero' => $request->getVar('alumno_sexo'),
            'nie' => $request->getVar('alumno_nie'),
            'estado' => $request->getVar('alumno_estado')
        ];
        return redirect()->back()->with('error', 'El DUI ya está registrado.')->withInput()->with('alumnos', $alumnos);
    }

    // Validar unicidad del NIE de los alumnos
    $errors = [];
    $nies = $request->getVar('alumno_nie');
    foreach ($nies as $index => $nie) {
        if ($alumnoModel->where('NIE', $nie)->first()) {
            $errors[] = "El NIE del alumno (número: $nie) ya está registrado.";
        }
    }

    if (!empty($errors)) {
        $alumnos = [
            'nombre_completo' => $request->getVar('alumno_nombre_completo'),
            'genero' => $request->getVar('alumno_sexo'),
            'nie' => $request->getVar('alumno_nie'),
            'estado' => $request->getVar('alumno_estado')
        ];
        return redirect()->back()->with('error', implode('<br>', $errors))->withInput()->with('alumnos', $alumnos);
    }

    // Si no hay errores, guardar el padre
    $padreId = $padreModel->insert($dataPadre);

    // Asociar alumnos
    if (!empty($request->getVar('alumno_nombre_completo'))) {
        $nombres = $request->getVar('alumno_nombre_completo');
        $generos = $request->getVar('alumno_sexo');
        $nies = $request->getVar('alumno_nie');
        $estados = $request->getVar('alumno_estado');

        foreach ($nombres as $index => $nombre) {
            $dataAlumno = [
                'nombreAlumno' => $nombre,
                'Genero_alumno' => $generos[$index],
                'NIE' => $nies[$index],
                'estado' => $estados[$index],
            ];

            $alumnoId = $alumnoModel->insert($dataAlumno);

            $responsableAlumnoModel->insert([
                'idDatosResponsable' => $padreId,
                'idAlumno' => $alumnoId,
            ]);
        }
    }

    return redirect()->to(site_url('padres'))->with('success', 'El padre ha sido creado exitosamente.');
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
        $alumnoModel = new AlumnoModel();
    
        // Datos del padre
        $dataPadre = [
            'nombreCompleto' => $request->getVar('nombre_completo'),
            'Genero' => $request->getVar('genero'),
            'DUI' => $request->getVar('dui'),
            'telefono' => $request->getVar('telefono'),
            'estado' => $request->getVar('estado'),
        ];
    
        // Validar unicidad del DUI si se está actualizando un DUI existente
        if ($padreModel->where('DUI', $dataPadre['DUI'])->where('idDatosResponsable !=', $id)->first()) {
            return redirect()->back()->with('error', 'El DUI ya está registrado.')->withInput();
        }
    
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
                
                // Validar unicidad del NIE si se está actualizando un NIE existente
                if ($alumnoModel->where('NIE', $dataAlumno['NIE'])->where('idAlumno !=', $alumnoId)->first()) {
                    return redirect()->back()->with('error', 'El NIE del alumno ya está registrado.')->withInput();
                }
    
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
    
                    // Validar unicidad del NIE para nuevos alumnos
                    if ($alumnoModel->where('NIE', $dataNuevoAlumno['NIE'])->first()) {
                        return redirect()->back()->with('error', 'El NIE del nuevo alumno ya está registrado.')->withInput();
                    }
    
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
    $alumnoModel = new AlumnoModel();
    $responsableAlumnoModel = new ResponsableAlumnoModel();

    // Cambiar el estado del padre a "inactivo"
    $padreModel->update($id, ['estado' => 'inactivo']);

    // Cambiar el estado de los alumnos asociados a "inactivo"
    $alumnos = $responsableAlumnoModel->getAlumnosAsociados($id);
    foreach ($alumnos as $alumno) {
        $alumnoModel->update($alumno['idAlumno'], ['estado' => 'inactivo']);
    }

    return redirect()->to(site_url('padres'))->with('success', 'El padre y sus alumnos han sido marcados como inactivos.');
}


    public function getAlumnosAjax($padreId)
    {
        $responsableAlumnoModel = new ResponsableAlumnoModel();
        $alumnos = $responsableAlumnoModel->getAlumnosAsociados($padreId); // Utilizar el método correcto

        return view('padres/alumnos_partial', ['alumnos' => $alumnos]);
    }
}

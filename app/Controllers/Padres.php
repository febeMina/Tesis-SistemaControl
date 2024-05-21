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
        $Genero = $request->getVar('Genero');
        $DUI = $request->getVar('dui');
        $telefono = $request->getVar('telefono');
        $estado = $request->getVar('estado');
        $alumno_nombre_completo = $request->getVar('alumno_nombre_completo');
        $alumno_sexo = $request->getVar('alumno_sexo');
        $alumno_nie = $request->getVar('alumno_nie');
        $alumno_estado = $request->getVar('alumno_estado');
        
        // Debugging: verificar los datos del formulario
        var_dump($alumno_nombre_completo, $alumno_sexo, $alumno_nie, $alumno_estado);
    
        // Guardar los datos del padre en la base de datos
        $padreModel = new PadreModel(); // Instancia el modelo de padres
        $dataPadre = [
            'nombreCompleto' => $nombre_completo,
            'Genero' => $Genero,
            'DUI' => $DUI,
            'telefono' => $telefono,
            'estado' => $estado,
        ];
        $padreId = $padreModel->insert($dataPadre);
    
        // Guardar los datos de los alumnos asociados al padre en la base de datos
        $alumnosData = []; // Arreglo para almacenar los datos de los alumnos
        foreach ($alumno_nombre_completo as $key => $nombreAlumno) {
            // Guardar cada alumno asociado al padre
            $alumnoModel = new AlumnoModel(); // Instancia el modelo de alumnos
            $dataAlumno = [
                'nombreAlumno' => $nombreAlumno,
                'Genero' => $alumno_sexo[$key],
                'NIE' => $alumno_nie[$key],
                'estado' => $alumno_estado[$key],
                'idPadre' => $padreId // Asociar el alumno al padre recién creado
            ];
            var_dump($dataAlumno); // Debugging: verificar los datos del alumno antes de insertarlos
            $alumnoModel->insert($dataAlumno); // Insertar el alumno en la base de datos
        }
    
        // Redireccionar al index de padres
        return redirect()->to(site_url('padres'));
    }
      

    public function edit($id)
{
    $padreModel = new PadreModel();
    $alumnoModel = new AlumnoModel();

    $padre = $padreModel->find($id);

    if ($padre === null) {
        // Manejar el caso en el que no se encuentra al padre
    }

    $alumnos = $alumnoModel->getAlumnosByPadreId($id);

    // Modificamos el nombre del campo 'Genero' a 'sexo_padre' para evitar conflictos
    $padre['sexo_padre'] = $padre['Genero'];

    // Renombrar el campo 'Genero' de los alumnos para evitar conflictos
    foreach ($alumnos as &$alumno) {
        $alumno['sexo_alumno'] = $alumno['Sexo_alumno'];
    }

    return view('padres/edit', ['padre' => $padre, 'alumnos' => $alumnos]);
}

    
    public function update($id)
    {
        $request = \Config\Services::request();
        $nombre_completo = $request->getVar('nombre_completo');
        $genero = $request->getVar('Genero'); // Cambiar a mayúscula 'Genero' si así está definido en tu formulario
        $dui = $request->getVar('dui'); // Cambiar a minúscula 'dui' si así está definido en tu formulario
        $telefono = $request->getVar('telefono');
        $estado = $request->getVar('estado');
        $alumno_nombre_completo = $request->getVar('alumno_nombre_completo');
        $alumno_sexo = $request->getVar('alumno_sexo');
        $alumno_nie = $request->getVar('alumno_nie');
        $alumno_estado = $request->getVar('alumno_estado');
        $alumno_id = $request->getVar('alumno_id');
        
        $padreModel = new PadreModel(); 
        $dataPadre = [
            'nombreCompleto' => $nombre_completo,
            'Genero' => $genero,
            'DUI' => $dui,
            'telefono' => $telefono,
            'estado' => $estado,
        ];
        $padreModel->update($id, $dataPadre);
    
        // Verificar si los datos de los alumnos existen antes de intentar acceder a ellos
        if (!empty($alumno_nombre_completo) && is_array($alumno_nombre_completo)) {
            $alumnoModel = new AlumnoModel();
            foreach ($alumno_nombre_completo as $key => $nombreAlumno) {
                // Verificar si los datos del alumno en esta posición existen antes de acceder a ellos
                if (isset($alumno_sexo[$key], $alumno_nie[$key], $alumno_estado[$key], $alumno_id[$key])) {
                    $dataAlumno = [
                        'nombreAlumno' => $nombreAlumno,
                        'Sexo_alumno' => $alumno_sexo[$key],
                        'NIE' => $alumno_nie[$key],
                        'estado' => $alumno_estado[$key],
                    ];
                    // Actualizar el alumno utilizando el ID del alumno correspondiente
                    $alumnoModel->update($alumno_id[$key], $dataAlumno);
                } else {
                    // Manejar el caso de datos faltantes o nulos
                    // Puedes mostrar un mensaje de error o manejarlo según sea necesario
                }
            }
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
        
        // Carga la vista parcial de los alumnos asociados
        return view('padres/alumnos_partial', ['alumnos' => $alumnos]);
    }
    
    



}

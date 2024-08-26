<?php

namespace App\Controllers;

use App\Models\MaestroModel;
use App\Models\GradoModel;
use App\Models\RegistroDiarioModel;
use App\Models\DetalleAsistenciaModel;

class RegistroDiarioController extends BaseController
{
    public function index()
    {
        $registroDiarioModel = new RegistroDiarioModel();
        $detalleAsistenciaModel = new DetalleAsistenciaModel();

        // Obtener los registros con información relacionada
        $data['registros'] = $registroDiarioModel
            ->select('registro_diario.idRegistroDiario, registro_diario.fecha, 
                      COALESCE(SUM(detalle_asistencia.Total), 0) as familiasBeneficiadas')
            ->join('detalle_asistencia', 'detalle_asistencia.idRegistroDiario = registro_diario.idRegistroDiario', 'left')
            ->groupBy('registro_diario.idRegistroDiario, registro_diario.fecha')
            ->paginate(10);

        $data['pager'] = $registroDiarioModel->pager;

        return view('registro_diario/index', $data);
    }

    public function create()
    {
        $maestroModel = new MaestroModel();
        $gradoModel = new GradoModel();
    
        $data['docentes'] = $maestroModel->where('estado', 1)->where('tipo', 'Docente')->findAll();
        $data['grados'] = $gradoModel->getGradosActivos(); // Obtén grados activos
    
        return view('registro_diario/create', $data);
    }
    
    public function store()
    {
        $registroDiarioModel = new RegistroDiarioModel();
        $detalleAsistenciaModel = new DetalleAsistenciaModel();
    
        // Obtener datos del POST
        $fecha = $this->request->getPost('fecha');
        $grados = $this->request->getPost('idGrado'); // Array de IDs de grados
        $docentes = $this->request->getPost('idDocente'); // Array de IDs de docentes
        $cantidadNinos = $this->request->getPost('cantidad_niños');
        $cantidadNinas = $this->request->getPost('cantidad_niñas');
    
        // Verificar si $grados o $docentes son null
        if ($grados === null || $docentes === null) {
            throw new \RuntimeException('No se recibieron datos de grados o docentes.');
        }
    
        // Calcular el total de familias beneficiadas
        $totalFamiliasBeneficiadas = 0;
        foreach ($cantidadNinos as $index => $cantidadNinosGrado) {
            $cantidadNinasGrado = $cantidadNinas[$index];
            $totalFamiliasBeneficiadas += $cantidadNinosGrado + $cantidadNinasGrado;
        }
    
        // Insertar en la tabla registro_diario
        $dataRegistro = [
            'fecha' => $fecha,
            'familiasBeneficiadas' => $totalFamiliasBeneficiadas,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $registroDiarioModel->insert($dataRegistro);
    
        // Obtener el ID del registro recién insertado
        $registroDiarioId = $registroDiarioModel->getInsertID();
    
        // Insertar en detalle_asistencia
        foreach ($grados as $index => $gradoId) {
            $cantidadNinosGrado = $cantidadNinos[$index];
            $cantidadNinasGrado = $cantidadNinas[$index];
    
            $dataDetalle = [
                'idRegistroDiario' => $registroDiarioId,
                'idGrado' => $gradoId,
                'idDocente' => $docentes[$index],
                'cantidad_niños' => $cantidadNinosGrado,
                'cantidad_niñas' => $cantidadNinasGrado,
                'Total' => $cantidadNinosGrado + $cantidadNinasGrado,
            ];
            $detalleAsistenciaModel->insert($dataDetalle);
        }
    
        return redirect()->to('/registro-diario')->with('success', 'Registro diario creado exitosamente.');
    }
    
    public function show($idRegistroDiario)
    {
        $registroDiarioModel = new RegistroDiarioModel();
        $detalleAsistenciaModel = new DetalleAsistenciaModel();
        
        // Obtener el registro del diario
        $data['registro'] = $registroDiarioModel->find($idRegistroDiario);
        
        if (!$data['registro']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("No se encontró el registro con ID: $idRegistroDiario");
        }
        
        // Obtener los detalles de asistencia relacionados
        $data['detalles'] = $detalleAsistenciaModel->select('detalle_asistencia.*, grado.nombre as nombre_grado')
                                               ->join('grado', 'detalle_asistencia.idGrado = grado.idGrado')
                                               ->where('idRegistroDiario', $idRegistroDiario)
                                               ->findAll();
        
        // Calcular el total de familias beneficiadas
        $data['totalFamiliasBeneficiadas'] = array_sum(array_column($data['detalles'], 'Total'));
        
        return view('registro_diario/show', $data);
    }
    
    
    public function getDetails($idRegistroDiario)
    {
        $registroDiarioModel = new RegistroDiarioModel();
        $detalleAsistenciaModel = new DetalleAsistenciaModel();
    
        // Obtener el registro del diario
        $registro = $registroDiarioModel->find($idRegistroDiario);
    
        if (!$registro) {
            return $this->response->setJSON(['error' => 'Registro no encontrado']);
        }
    
        // Obtener los detalles de asistencia relacionados
        $detalles = $detalleAsistenciaModel->select('detalle_asistencia.*, grado.nombre as nombre_grado')
                                           ->join('grado', 'detalle_asistencia.idGrado = grado.idGrado')
                                           ->where('idRegistroDiario', $idRegistroDiario)
                                           ->findAll();
    
        // Calcular el total de familias beneficiadas
        $totalFamiliasBeneficiadas = array_sum(array_column($detalles, 'Total'));
    
        return $this->response->setJSON([
            'registro' => $registro,
            'detalles' => $detalles,
            'totalFamiliasBeneficiadas' => $totalFamiliasBeneficiadas
        ]);
    }
    
}

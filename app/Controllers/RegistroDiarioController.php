<?php

namespace App\Controllers;

use App\Models\MaestroModel;
use App\Models\GradoModel;
use App\Models\RegistroDiarioModel;
use App\Models\DetalleAsistenciaModel;
use App\Models\ProductosRequisicionModel;

class RegistroDiarioController extends BaseController
{
    public function index()
    {
        $registroDiarioModel = new RegistroDiarioModel();
        $detalleAsistenciaModel = new DetalleAsistenciaModel();

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
        $productosRequisicionModel = new ProductosRequisicionModel(); // Crear instancia del modelo de requisiciones
    
        $fecha = $this->request->getPost('fecha');
    
        // Obtener docentes activos de tipo 'Docente'
        $data['docentes'] = $maestroModel->where('estado', 'Activo')->where('tipo', 'Docente')->findAll();
        
        // Obtener grados activos con docentes asignados
        $data['grados'] = $gradoModel->getGradosConDocentes();
        
        if ($fecha) {
            // Obtener requisiciones para la fecha seleccionada
            $data['requisiciones'] = $productosRequisicionModel->getRequisicionByFecha($fecha);
            // Agregar esto para depurar
            var_dump($data['requisiciones']); exit;
        } else {
            $data['requisiciones'] = [];
        }
    
        return view('registro_diario/create', $data);
    }
    
    public function store()
{
    $registroDiarioModel = new RegistroDiarioModel();
    $detalleAsistenciaModel = new DetalleAsistenciaModel();

    $fecha = $this->request->getPost('fecha');
    $grados = $this->request->getPost('idGrado'); // Array de IDs de grados
    $docentes = $this->request->getPost('idDocente'); // Array de IDs de docentes
    $cantidadNiños = $this->request->getPost('cantidadNiños'); // Array de cantidades de niños
    $cantidadNiñas = $this->request->getPost('cantidadNiñas'); // Array de cantidades de niñas  

 // Depurar datos recibidos
 var_dump($fecha);
 var_dump($grados);
 var_dump($docentes);
 var_dump($cantidadNiños);
 var_dump($cantidadNiñas);
 exit;

if (empty($grados) || empty($docentes)) {
    throw new \RuntimeException('No se recibieron datos de grados o docentes.');
}
    
    
  

    $totalFamiliasBeneficiadas = 0;
    foreach ($cantidadNiños as $index => $cantidadNinosGrado) {
        $cantidadNinasGrado = $cantidadNiñas[$index];
        $totalFamiliasBeneficiadas += $cantidadNinosGrado + $cantidadNinasGrado;
    }

    $dataRegistro = [
        'fecha' => $fecha,
        'familiasBeneficiadas' => $totalFamiliasBeneficiadas,
        'createdAt' => date('Y-m-d H:i:s'),
    ];
    $registroDiarioModel->insert($dataRegistro);

    $registroDiarioId = $registroDiarioModel->getInsertID();

    foreach ($grados as $index => $gradoId) {
        $cantidadNinosGrado = $cantidadNiños[$index];
        $cantidadNinasGrado = $cantidadNiñas[$index];

        $dataDetalle = [
            'idRegistroDiario' => $registroDiarioId, 
            'idGrado' => $gradoId,
            'idDocente' => $docentes[$index],
            'cantidadNiños' => $cantidadNinosGrado,
            'cantidadNiñas' => $cantidadNinasGrado,
            'Total' => $cantidadNinosGrado + $cantidadNinasGrado,
        ];
        $detalleAsistenciaModel->insert($dataDetalle);
    }
    var_dump($this->request->getPost('cantidadNiños')); // Debe mostrar el array de cantidades
    var_dump($this->request->getPost('cantidadNiñas'));
    var_dump($this->request->getPost()); exit;
    // Debe mostrar el array de cantidades
exit;

    return redirect()->to(base_url('registro-diario'))->with('success', 'Registro diario creado exitosamente.');



}

    
    public function show($idRegistroDiario)
    {
        $registroDiarioModel = new RegistroDiarioModel();
        $detalleAsistenciaModel = new DetalleAsistenciaModel();
        
        $data['registro'] = $registroDiarioModel->find($idRegistroDiario);
        
        if (!$data['registro']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("No se encontró el registro con ID: $idRegistroDiario");
        }
        
        $data['detalles'] = $detalleAsistenciaModel->select('detalle_asistencia.*, grado.nombre as nombre_grado')
                                               ->join('grado', 'detalle_asistencia.idGrado = grado.idGrado')
                                               ->where('idRegistroDiario', $idRegistroDiario)
                                               ->findAll();
        
        $data['totalFamiliasBeneficiadas'] = array_sum(array_column($data['detalles'], 'Total'));
        
        return view('registro_diario/show', $data);
    }
    
    public function getDetails($idRegistroDiario)
    {
        $registroDiarioModel = new RegistroDiarioModel();
        $detalleAsistenciaModel = new DetalleAsistenciaModel();
    
        $registro = $registroDiarioModel->find($idRegistroDiario);
    
        if (!$registro) {
            return $this->response->setJSON(['error' => 'Registro no encontrado']);
        }
    
        $detalles = $detalleAsistenciaModel->select('detalle_asistencia.*, grado.nombre as nombre_grado')
                                           ->join('grado', 'detalle_asistencia.idGrado = grado.idGrado')
                                           ->where('idRegistroDiario', $idRegistroDiario)
                                           ->findAll();
    
        $totalFamiliasBeneficiadas = array_sum(array_column($detalles, 'Total'));
    
        return $this->response->setJSON([
            'registro' => $registro,
            'detalles' => $detalles,
            'totalFamiliasBeneficiadas' => $totalFamiliasBeneficiadas
        ]);
    }
    
    public function getRequisicionByFecha($fecha)
{
    $model = new ProductosRequisicionModel();
    $result = $model->getRequisicionByFecha($fecha);

    $this->response->setHeader('Content-Type', 'application/json');
    return $this->response->setJSON($result);
}


}

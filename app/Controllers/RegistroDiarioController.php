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
                      COALESCE(SUM(detalle_asistencia.total), 0) as familiasBeneficiadas')
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
        $productosRequisicionModel = new ProductosRequisicionModel(); 
        
        $data['docentes'] = $maestroModel->where('estado', 'Activo')->where('tipo', 'Docente')->findAll();
        $data['grados'] = $gradoModel->getGradosConDocentes();

        return view('registro_diario/create', $data);
    }

    public function store()
    {
        // Obtener los datos del formulario
        $fecha = $this->request->getPost('fecha');
        
        // Insertar en la tabla registro_diario
        $registroDiarioModel = new RegistroDiarioModel();
        $registroDiarioId = $registroDiarioModel->insert([
            'fecha' => $fecha,
        ]);
    
        // Obtener los arrays del formulario
        $idDocentes = $this->request->getPost('idDocente');
        $cantidadNinos = $this->request->getPost('cantidadNinos');
        $cantidadNinas = $this->request->getPost('cantidadNinas');
        $idProductoRequisicion = $this->request->getPost('idProductoRequisicion'); // single value
    
        // Guardar los detalles de asistencia
        $detalleAsistenciaModel = new DetalleAsistenciaModel();
        $totalNinos = 0;
        $totalNinas = 0;
    
        // Recorrer los arrays y guardar cada detalle de asistencia
        foreach ($idDocentes as $index => $idDocente) {
            // Convertir a enteros
            $cantidadNinosGrado = (int) ($cantidadNinos[$index] ?? 0);
            $cantidadNinasGrado = (int) ($cantidadNinas[$index] ?? 0);
    
            // Solo guardar si al menos uno de los campos de cantidad tiene un valor mayor que cero
            if ($cantidadNinosGrado > 0 || $cantidadNinasGrado > 0) {
                // Insertar el detalle de asistencia
                $detalleAsistenciaModel->insert([
                    'idRegistroDiario' => $registroDiarioId,
                    'idDocente' => $idDocente,
                    'idProductoRequisicion' => $idProductoRequisicion, // Uso único para todos
                    'cantidadNinos' => $cantidadNinosGrado,
                    'cantidadNinas' => $cantidadNinasGrado,
                    'total' => $cantidadNinosGrado + $cantidadNinasGrado,
                ]);
    
                // Acumular los totales
                $totalNinos += $cantidadNinosGrado;
                $totalNinas += $cantidadNinasGrado;
            }
        }
    
        // Actualizar el total de familias beneficiadas solo si se han registrado asistencias
        if ($totalNinos > 0 || $totalNinas > 0) {
            $registroDiarioModel->update($registroDiarioId, [
                'familiasBeneficiadas' => $totalNinos + $totalNinas,
            ]);
        }
    
        return redirect()->to('registro-diario')->with('success', 'Registro creado correctamente.');
    }
    

    public function obtenerGradoPorDocente($idDocente)
    {
        $docenteModel = new MaestroModel();
        $docente = $docenteModel->select('idGrado')
                                ->where('idDocente', $idDocente)
                                ->first();
        
        return $docente['idGrado'] ?? null; // Retorna el idGrado o null si no se encuentra
    }
    
    public function show($idRegistroDiario)
{
    $registroDiarioModel = new RegistroDiarioModel();
    $detalleAsistenciaModel = new DetalleAsistenciaModel();

    $registro = $registroDiarioModel->select('idRegistroDiario, fecha, familiasBeneficiadas')->find($idRegistroDiario);
    
    if (!$registro) {
        return $this->response->setStatusCode(404)->setJSON(['error' => 'Registro no encontrado']);
    }

    $detalles = $detalleAsistenciaModel
        ->select('detalle_asistencia.*, grado.nombre as nombre_grado, docente.nombreCompleto as nombre_docente')
        ->join('docente', 'detalle_asistencia.idDocente = docente.idDocente')
        ->join('grado', 'docente.idGrado = grado.idGrado')
        ->where('idRegistroDiario', $idRegistroDiario)
        ->findAll();

    return $this->response->setJSON([
        'fecha' => $registro->fecha,
        'familiasBeneficiadas' => $registro->familiasBeneficiadas,
        'detalles' => $detalles
    ]);
}

    
    public function getRequisicionByFecha($fecha)
{
    $model = new ProductosRequisicionModel();
    $result = $model->where('fechaRequisicion', $fecha)->first(); // Cambié de findAll() a first() para obtener solo una requisición

    return $this->response->setJSON($result); // Retorna solo un objeto si existe, null si no.
}

    
    public function getRequisicionYDocentes()
{
    $fecha = $this->request->getPost('fecha');
    
    // Obtener la requisición por fecha
    $requisicionModel = new ProductosRequisicionModel();
    $requisicion = $requisicionModel->where('fechaRequisicion', $fecha)->first();

    // Verificar si existe la requisición
    if (!$requisicion) {
        return $this->response->setJSON([
            'requisicion' => null,
            'docentes' => []
        ]);
    }
    
    // Obtener los docentes y sus grados
    $docenteModel = new MaestroModel();
    $docentes = $docenteModel->select('docente.idDocente, docente.nombreCompleto, grado.nombre AS nombreGrado')
                             ->join('grado', 'grado.idGrado = docente.idGrado')
                             ->where('docente.estado', 'Activo')
                             ->findAll();
    
    return $this->response->setJSON([
        'requisicion' => $requisicion,
        'docentes' => $docentes
    ]);
}

public function reporteFamilias()
{
    $registroDiarioModel = new RegistroDiarioModel();
    $detalleAsistenciaModel = new DetalleAsistenciaModel();

    $data['registros'] = $registroDiarioModel
        ->select('registro_diario.idRegistroDiario, registro_diario.fecha, 
                  COALESCE(SUM(detalle_asistencia.total), 0) as familiasBeneficiadas')
        ->join('detalle_asistencia', 'detalle_asistencia.idRegistroDiario = registro_diario.idRegistroDiario', 'left')
        ->groupBy('registro_diario.idRegistroDiario, registro_diario.fecha')
        ->findAll(); // Puedes usar findAll() si no necesitas paginación

    return view('Reportes/reporte_familias', $data);
}



}

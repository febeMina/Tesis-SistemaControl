<?php

namespace App\Controllers;

use App\Models\GradoModel;
use CodeIgniter\Controller;

class GradoController extends Controller
{
    protected $gradoModel;

    public function __construct()
    {
        $this->gradoModel = new GradoModel();

        // Verificación de sesión
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Debes iniciar sesión.');
        }
    }

    // Listar todos los grados activos
    public function index()
{
    // Inicializar el servicio de paginación
    $pager = \Config\Services::pager();

    // Obtener el número de página actual
    $currentPage = $this->request->getVar('page') ? $this->request->getVar('page') : 1;

    // Definir el número de registros por página
    $perPage = 5;

    // Obtener los grados desde el modelo con paginación
    $grados = $this->gradoModel->paginate($perPage, 'bootstrap_pagination', $currentPage);

    // Obtener el total de filas (si lo necesitas)
    $totalRows = $this->gradoModel->countAllResults();

    // Preparar los datos para la vista
    $data = [
        'grados' => $grados,
        'pager' => $pager->makeLinks($currentPage, $perPage, $totalRows, 'bootstrap_pagination'),
        'currentPage' => $currentPage
    ];

    // Añadir mensajes flash si existen
    if (session()->getFlashdata('success')) {
        $data['success'] = session()->getFlashdata('success');
    }
    if (session()->getFlashdata('error')) {
        $data['error'] = session()->getFlashdata('error');
    }

    // Cargar la vista
    return view('grado/index', $data);
}

    // Mostrar formulario de creación
    public function create()
    {
        return view('grado/create');
    }

    // Guardar nuevo grado
    public function store()
    {
        // Capturar el nombre del usuario desde la sesión
        $usuarioActual = session()->get('usuario');

        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'descripcion' => $this->request->getPost('descripcion'),
            'estado' => 'Activo',  // Por defecto, activo
            'usuarioCrea' => $usuarioActual,  // Captura el usuario que crea
        ];

        $success = $this->gradoModel->insert($data);

        if ($success) {
            session()->setFlashdata('success', 'Grado creado exitosamente.');
        } else {
            session()->setFlashdata('error', 'Error al crear el grado.');
        }

        return redirect()->to('/grado');
    }

    // Mostrar formulario de edición
    public function edit($id)
    {
        $grado = $this->gradoModel->find($id);

        if ($grado === null) {
            session()->setFlashdata('error', 'No se encontró el grado.');
            return redirect()->to('/grado');
        }

        $data['grado'] = $grado;
        return view('grado/edit', $data);
    }

    // Actualizar grado
    public function update($id)
    {
        // Capturar el nombre del usuario que modifica desde la sesión
        $usuarioActual = session()->get('usuario');

        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'descripcion' => $this->request->getPost('descripcion'),
            'estado' => $this->request->getPost('estado'),
            'usuarioModifica' => $usuarioActual,  // Captura el usuario que modifica
        ];

        $success = $this->gradoModel->update($id, $data);

        if ($success) {
            session()->setFlashdata('success', 'Grado actualizado exitosamente.');
        } else {
            session()->setFlashdata('error', 'Error al actualizar el grado.');
        }

        return redirect()->to('/grado');
    }


    public function delete($id = null)
    {
        log_message('info', 'Iniciando proceso de eliminación para el grado con ID: ' . $id);  // Log del ID
    
        if ($this->request->isAJAX()) {
            if ($id) {
                log_message('info', 'Solicitud AJAX válida, eliminando el grado con ID: ' . $id);  // Log del proceso de eliminación
                
                if ($this->gradoModel->update($id, ['estado' => 'Eliminado'])) {
                    log_message('info', 'Grado eliminado correctamente.');  // Log de éxito
                    return $this->response->setJSON(['success' => true, 'message' => 'Grado eliminado exitosamente.']);
                } else {
                    log_message('error', 'Error al eliminar el grado.');  // Log de error
                    return $this->response->setJSON(['success' => false, 'message' => 'Error al eliminar el grado.']);
                }
            } else {
                log_message('error', 'ID de grado no válido.');  // Log si el ID no es válido
                return $this->response->setJSON(['success' => false, 'message' => 'ID no válido.']);
            }
        }
    
        return redirect()->to('/grado');
    }
    
    
}

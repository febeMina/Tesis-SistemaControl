<?php

namespace App\Controllers;

use App\Models\SolicitudProductosModel;
use App\Models\ProductoModel;
use CodeIgniter\Controller;

class SolicitudProductos extends Controller
{
    protected $db;

    public function __construct()
    {
        $this->db = db_connect(); // Cargar la base de datos en el constructor
    }

    public function index()
    {
        $solicitudModel = new SolicitudProductosModel();
        $data['solicitudes'] = $solicitudModel->findAll();

        return view('solicitud_productos/index', $data);
    }

    public function create()
    {
        $productoModel = new ProductoModel();
        $data['productos'] = $productoModel->findAll();

        return view('solicitud_productos/create', $data);
    }

    public function store()
    {
        $solicitudModel = new SolicitudProductosModel();

        $data = [
            'Fecha_solicitud' => $this->request->getPost('Fecha_solicitud'),
            'Comida_a_preparar' => $this->request->getPost('Comida_a_preparar'),
            'responsable_entrega' => $this->request->getPost('responsable_entrega'),
            'responsable_recibir' => $this->request->getPost('responsable_recibir'),
        ];

        // Guardar solicitud
        $solicitudModel->save($data);
        $idSolicitudProductos = $solicitudModel->insertID();

        // Guardar productos solicitados
        $productos = $this->request->getPost('productos');
        foreach ($productos as $producto) {
            // Guardar en la tabla de relación productos_solicitados
            $this->db->table('productos_solicitados')->insert([
                'idSolicitudProductos' => $idSolicitudProductos,
                'idProducto' => $producto['idProducto'],
                'cantidad' => $producto['cantidad'],
            ]);
        }

        return redirect()->to('/solicitud_productos');
    }

    public function edit($id)
    {
        $solicitudModel = new SolicitudProductosModel();
        $productoModel = new ProductoModel();

        $data['solicitud'] = $solicitudModel->find($id);
        $data['productos'] = $productoModel->findAll();

        // Obtener productos solicitados
        $data['productos_solicitados'] = $this->db->table('productos_solicitados')
            ->where('idSolicitudProductos', $id)
            ->get()
            ->getResultArray();

        return view('solicitud_productos/edit', $data);
    }

    public function update($id)
    {
        $solicitudModel = new SolicitudProductosModel();

        $data = [
            'Fecha_solicitud' => $this->request->getPost('Fecha_solicitud'),
            'Comida_a_preparar' => $this->request->getPost('Comida_a_preparar'),
            'responsable_entrega' => $this->request->getPost('responsable_entrega'),
            'responsable_recibir' => $this->request->getPost('responsable_recibir'),
        ];

        // Actualizar solicitud
        $solicitudModel->update($id, $data);

        // Actualizar productos solicitados
        $productos = $this->request->getPost('productos');
        $this->db->table('productos_solicitados')->where('idSolicitudProductos', $id)->delete();
        foreach ($productos as $producto) {
            // Guardar en la tabla de relación productos_solicitados
            $this->db->table('productos_solicitados')->insert([
                'idSolicitudProductos' => $id,
                'idProducto' => $producto['idProducto'],
                'cantidad' => $producto['cantidad'],
            ]);
        }

        return redirect()->to('/solicitud_productos');
    }

    public function delete($id)
    {
        $solicitudModel = new SolicitudProductosModel();
        $solicitudModel->delete($id);

        // Eliminar productos solicitados
        $this->db->table('productos_solicitados')->where('idSolicitudProductos', $id)->delete();

        return redirect()->to('/solicitud_productos');
    }
}

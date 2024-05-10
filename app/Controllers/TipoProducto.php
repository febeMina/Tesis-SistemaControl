<?php

namespace App\Controllers;

use App\Models\TipoProductoModel;
use CodeIgniter\Controller;

class TipoProducto extends Controller
{
    public function index()
    {
        $model = new TipoProductoModel();
        $tiposProducto = $model->findAll();

        return view('tipo_producto/index', ['tiposProducto' => $tiposProducto]);
    }

    public function create()
    {
        // Cargar la vista de creación
        return view('tipo_producto/create');
    }

    public function store()
    {
        // Validar los datos del formulario de creación si es necesario

        // Capturar los datos del formulario de creación
        $request = \Config\Services::request();
        $nombre = $request->getPost('nombre');
        $descripcion = $request->getPost('descripcion');

        // Insertar el nuevo tipo de producto en la base de datos
        $tipoProductoModel = new \App\Models\TipoProductoModel();
        $data = [
            'nombre' => $nombre,
            'descripcion' => $descripcion
        ];
        $tipoProductoModel->insert($data);

        // Redireccionar a la página principal con un mensaje de éxito
        return redirect()->to('/tipo_producto')->with('success', 'El tipo de producto ha sido creado exitosamente.');
    }

    public function edit($id)
    {
        // Cargar el modelo de TipoProducto
        $tipoProductoModel = new \App\Models\TipoProductoModel();

        // Verificar si el tipo de producto existe
        $tipoProducto = $tipoProductoModel->find($id);
        if (!$tipoProducto) {
            // Si no se encuentra el tipo de producto, redirigir con un mensaje de error
            return redirect()->to('/tipo_producto')->with('error', 'El tipo de producto no existe.');
        }

        // Pasar los datos del tipo de producto a la vista de edición
        return view('tipo_producto/edit', ['tipoProducto' => $tipoProducto]);
    }

    public function update($id)
    {
        // Validar los datos del formulario de edición si es necesario

        // Capturar los datos del formulario de edición
        $request = \Config\Services::request();
        $nombre = $request->getPost('nombre');
        $descripcion = $request->getPost('descripcion');

        // Actualizar el tipo de producto en la base de datos
        $tipoProductoModel = new \App\Models\TipoProductoModel();
        $data = [
            'nombre' => $nombre,
            'descripcion' => $descripcion
        ];
        $tipoProductoModel->update($id, $data);

        // Redireccionar a la página principal con un mensaje de éxito
        return redirect()->to('/tipo_producto')->with('success', 'El tipo de producto ha sido actualizado exitosamente.');
    }

    public function delete($id)
    {
        // Cargar el modelo de TipoProducto
        $tipoProductoModel = new \App\Models\TipoProductoModel();

        // Verificar si el tipo de producto existe
        $tipoProducto = $tipoProductoModel->find($id);
        if (!$tipoProducto) {
            // Si no se encuentra el tipo de producto, redirigir con un mensaje de error
            return redirect()->to('/tipo_producto')->with('error', 'El tipo de producto no existe.');
        }

        // Eliminar el tipo de producto de la base de datos
        $tipoProductoModel->delete($id);

        // Redireccionar a la página principal con un mensaje de éxito
        return redirect()->to('/tipo_producto')->with('success', 'El tipo de producto ha sido eliminado exitosamente.');
    }
}

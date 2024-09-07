<?php

namespace App\Controllers;

use App\Models\ConsumoModel;
use App\Models\ProductoModel;
use CodeIgniter\Controller;
use CodeIgniter\API\ResponseTrait;

class Consumo extends BaseController
{
    use ResponseTrait;

    public function create()
    {
        $productoModel = new ProductoModel();
        $productos = $productoModel->getProductosParaConsumo();

        // Agregar la descripción del producto al array de productos
        foreach ($productos as &$producto) {
            $descripcion = $productoModel->obtenerDescripcionProducto($producto['idtipoProducto']);
            $producto['descripcion'] = $descripcion;
        }

        return view('Consumo/create', ['productos' => $productos]);
    }

    public function store()
    {
        $model = new ConsumoModel();
        $productoModel = new ProductoModel();
        $request = \Config\Services::request();
    
        // Validación de datos
        $validation = \Config\Services::validation();
        $validation->setRules([
            'fecha' => 'required|valid_date',
            'idProducto' => 'required|integer',
            'saldo_inicial' => 'permit_empty|integer',
            'salidas' => 'permit_empty|integer',
        ]);
    
        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
    
        // Obtener los datos del formulario
        $fecha = $request->getPost('fecha');
        $idProducto = $request->getPost('idProducto');
        $saldo_inicial = (int) $request->getPost('saldo_inicial') ?: 0;
        $salidas = (int) $request->getPost('salidas') ?: 0;
    
        // Obtener el último consumo del producto
        $ultimoConsumo = $model->obtenerUltimoConsumo($idProducto);
        $saldo_actual = $ultimoConsumo ? $ultimoConsumo['saldo'] : 0;
    
        // Calcular el saldo final
        $saldo_final = $saldo_inicial - $salidas;
    
        // Preparar los datos para guardar
        $data = [
            'fecha' => $fecha,
            'idProducto' => $idProducto,
            'saldo_inicial' => $saldo_inicial,
            'salidas' => $salidas,
            'saldo' => $saldo_final,
        ];
    
        // Guardar los datos en la base de datos
        if (!$model->insert($data)) {
            // Si no se pudo guardar, mostrar mensaje de error
            return redirect()->back()->withInput()->with('error', 'Hubo un problema al guardar el consumo por producto.');
        }
    
        // Redirigir con mensaje de éxito
        return redirect()->to(site_url('consumo'))->with('success', '¡El consumo por producto se guardó correctamente!');
    }

    public function index()
    {
        $consumoModel = new ConsumoModel();
        $productosModel = new ProductoModel();

        // Filtros
        $filters = [
            'producto_nombre' => $this->request->getGet('producto_nombre'),
            'producto_descripcion' => $this->request->getGet('producto_descripcion'),
            'producto_fecha_vencimiento' => $this->request->getGet('producto_fecha_vencimiento'),
        ];

        // Obtener los consumos con los detalles del producto
        $consumos = $consumoModel->filtrarConsumosConDetalles($filters);

        $data = [
            'consumos' => $consumos,
            'filters' => $filters,
        ];

        return view('Consumo/index', $data);
    }
    
    public function edit($id)
{
    $productoModel = new ProductoModel();
    $consumoModel = new ConsumoModel();

    // Obtener el consumo por su ID
    $consumo = $consumoModel->find($id);

    // Si no se encuentra el consumo, redirigir con mensaje de error
    if (!$consumo) {
        return redirect()->to(site_url('consumo'))->with('error', 'El consumo por producto no existe.');
    }

    // Obtener todos los productos para mostrar en el select
    $productos = $productoModel->getProductosParaConsumo();

    // Agregar la descripción del producto al array de productos
    foreach ($productos as &$producto) {
        $descripcion = $productoModel->obtenerDescripcionProducto($producto['idtipoProducto']);
        $producto['descripcion'] = $descripcion;
    }

    // Preparar los datos para pasar a la vista
    $data = [
        'productos' => $productos,
        'consumo' => $consumo, // Pasar el consumo para prellenar el formulario
    ];

    return view('Consumo/edit', $data);
}

public function update($id)
{
    $model = new ConsumoModel();
    $productoModel = new ProductoModel();
    $request = \Config\Services::request();

    // Validación de datos
    $validation = \Config\Services::validation();
    $validation->setRules([
        'fecha' => 'required|valid_date',
        'idProducto' => 'required|integer',
        'saldo_inicial' => 'permit_empty|integer',
        'salidas' => 'permit_empty|integer',
    ]);

    if (!$validation->withRequest($this->request)->run()) {
        return redirect()->back()->withInput()->with('errors', $validation->getErrors());
    }

    // Obtener los datos del formulario
    $fecha = $request->getPost('fecha');
    $idProducto = $request->getPost('idProducto');
    $saldo_inicial = (int) $request->getPost('saldo_inicial') ?: 0;
    $salidas = (int) $request->getPost('salidas') ?: 0;

    // Obtener el consumo por su ID
    $consumo = $model->find($id);

    // Si no se encuentra el consumo, redirigir con mensaje de error
    if (!$consumo) {
        return redirect()->to(site_url('consumo'))->with('error', 'El consumo por producto no existe.');
    }

    // Calcular el saldo final
    $saldo_final = $saldo_inicial - $salidas;

    // Preparar los datos para actualizar
    $data = [
        'fecha' => $fecha,
        'idProducto' => $idProducto,
        'saldo_inicial' => $saldo_inicial,
        'salidas' => $salidas,
        'saldo' => $saldo_final,
    ];
    log_message('debug', 'Datos del formulario para actualizar: ' . print_r($data, true));
    // Actualizar los datos en la base de datos
    if (!$model->update($id, $data)) {
    // Agregar logging del error específico
    log_message('error', 'Error al actualizar el consumo por producto: ' . $model->errors());
        // Si no se pudo actualizar, mostrar mensaje de error
        return redirect()->back()->withInput()->with('error', 'Hubo un problema al actualizar el consumo por producto.');
    }

    // Redirigir con mensaje de éxito
    return redirect()->to(site_url('consumo'))->with('success', '¡El consumo por producto se actualizó correctamente!');
}

public function delete($id)
{
    $model = new ConsumoModel();
    $consumo = $model->find($id);

    // Verificar si el consumo existe
    if (!$consumo) {
        return redirect()->to(site_url('consumo'))->with('error', 'El consumo por producto no existe.');
    }

    // Eliminar el consumo
    if (!$model->delete($id)) {
        return redirect()->to(site_url('consumo'))->with('error', 'Hubo un problema al eliminar el consumo por producto.');
    }

    // Redirigir con mensaje de éxito
    return redirect()->to(site_url('consumo'))->with('success', '¡El consumo por producto se eliminó correctamente!');
}


    public function getSaldoInicial($idProducto)
    {
        log_message('debug', 'getSaldoInicial called with idProducto: ' . $idProducto);
        $consumoModel = new ConsumoModel();
        $ultimoConsumo = $consumoModel->obtenerUltimoConsumo($idProducto);

        $saldo_inicial = $ultimoConsumo ? $ultimoConsumo['saldo'] : 0;

        return $this->response->setJSON(['saldo_inicial' => $saldo_inicial]);
    }
}

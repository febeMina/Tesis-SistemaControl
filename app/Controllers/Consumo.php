<?php

namespace App\Controllers;

use App\Models\ConsumoModel;
use App\Models\ProductoModel;
use CodeIgniter\Controller;

class Consumo extends Controller
{
    public function index()
    {
        $consumoModel = new ConsumoModel();
        $productoModel = new ProductoModel();

        $data['consumos'] = $consumoModel->findAll();
        $data['productos'] = $productoModel->findAll();

        return view('Consumo/index', $data);
    }

    public function create()
    {
        $productoModel = new ProductoModel();
        $data['productos'] = $productoModel->findAll();

        return view('Consumo/create', $data);
    }

    public function store()
    {
        $consumoModel = new ConsumoModel();

        $data = [
            'fecha' => $this->request->getPost('fecha'),
            'producto_id' => $this->request->getPost('producto_id'),
            'descripcion' => $this->request->getPost('descripcion'),
            'fecha_vencimiento' => $this->request->getPost('fecha_vencimiento'),
            'saldo_inicial' => $this->request->getPost('saldo_inicial'),
            'salidas' => $this->request->getPost('salidas'),
        ];

        $consumoModel->save($data);

        return redirect()->to('/consumo');
    }

    public function edit($id)
    {
        $consumoModel = new ConsumoModel();
        $productoModel = new ProductoModel();

        $data['consumo'] = $consumoModel->find($id);
        $data['productos'] = $productoModel->findAll();

        return view('Consumo/edit', $data);
    }

    public function update($id)
    {
        $consumoModel = new ConsumoModel();

        $data = [
            'fecha' => $this->request->getPost('fecha'),
            'producto_id' => $this->request->getPost('producto_id'),
            'descripcion' => $this->request->getPost('descripcion'),
            'fecha_vencimiento' => $this->request->getPost('fecha_vencimiento'),
            'saldo_inicial' => $this->request->getPost('saldo_inicial'),
            'salidas' => $this->request->getPost('salidas'),
        ];

        $consumoModel->update($id, $data);

        return redirect()->to('/consumo');
    }

    public function delete($id)
    {
        $consumoModel = new ConsumoModel();
        $consumoModel->delete($id);

        return redirect()->to('/consumo');
    }
}

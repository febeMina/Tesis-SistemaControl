<?php

namespace App\Models;

use CodeIgniter\Model;

class ConsumoModel extends Model
{
    protected $table = 'consumos';
    protected $primaryKey = 'idConsumo';
    protected $allowedFields = ['fecha', 'idProducto', 'saldo_inicial', 'salidas', 'saldo'];

    public function obtenerUltimoConsumo($idProducto)
    {
        return $this->where('idProducto', $idProducto)
                    ->orderBy('idConsumo', 'DESC')
                    ->first();
    }

    public function obtenerConsumosConDetalles()
    {
        return $this->select('consumos.*, tipo_producto.nombre as producto_nombre, tipo_producto.descripcion as producto_descripcion, productos.fecha_vencimiento as producto_fecha_vencimiento')
                    ->join('productos', 'productos.idProducto = consumos.idProducto')
                    ->join('tipo_producto', 'tipo_producto.idtipoProducto = productos.idtipoProducto')
                    ->orderBy('consumos.fecha', 'desc')
                    ->findAll();
    }

    public function filtrarConsumosConDetalles($filters)
    {
        $query = $this->select('consumos.*, tipo_producto.nombre as producto_nombre, tipo_producto.descripcion as producto_descripcion, productos.fecha_vencimiento as producto_fecha_vencimiento')
                      ->join('productos', 'productos.idProducto = consumos.idProducto')
                      ->join('tipo_producto', 'tipo_producto.idtipoProducto = productos.idtipoProducto');

        if (!empty($filters['producto_nombre'])) {
            $query->like('tipo_producto.nombre', $filters['producto_nombre']);
        }

        if (!empty($filters['producto_descripcion'])) {
            $query->like('tipo_producto.descripcion', $filters['producto_descripcion']);
        }

        if (!empty($filters['producto_fecha_vencimiento'])) {
            $query->where('productos.fecha_vencimiento', $filters['producto_fecha_vencimiento']);
        }

        return $query->orderBy('consumos.fecha', 'desc')
                     ->findAll();
    }
    
    // En el modelo ConsumoModel.php

public function getSaldoProductos()
{
    // Consulta para obtener el saldo actual de los productos desde la tabla consumos
    $query = $this->db->table('consumos')
        ->select('productos.idProducto, tipo_producto.nombre, tipo_producto.descripcion, productos.fecha_vencimiento, 
                  COALESCE(SUM(consumos.saldo_inicial - consumos.salidas), 0) as saldo_actual')
        ->join('productos', 'productos.idProducto = consumos.idProducto', 'left')
        ->join('tipo_producto', 'tipo_producto.idtipoProducto = productos.idtipoProducto', 'left')
        ->groupBy('productos.idProducto, tipo_producto.nombre, tipo_producto.descripcion, productos.fecha_vencimiento')
        ->get();

    return $query->getResultArray();
}

public function getConsumos()
{
    // Consulta para obtener todos los registros de consumo con detalles
    $query = $this->db->table('consumos')
        ->select('consumos.idConsumo, consumos.idProducto, consumos.descripcion, 
                  consumos.saldo_inicial, consumos.salidas, consumos.saldo, productos.fecha_vencimiento, 
                  tipo_producto.nombre as producto_nombre, tipo_producto.descripcion as producto_descripcion')
        ->join('productos', 'productos.idProducto = consumos.idProducto', 'left')
        ->join('tipo_producto', 'tipo_producto.idtipoProducto = productos.idtipoProducto', 'left')
        ->get();

    return $query->getResultArray();
}
}

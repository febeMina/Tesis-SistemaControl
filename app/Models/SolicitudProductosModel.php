<?php

namespace App\Models;

use CodeIgniter\Model;

class SolicitudProductosModel extends Model
{
    protected $table = 'solicitud_productos';
    protected $primaryKey = 'idSolicitudProductos';
    protected $allowedFields = [
        'Fecha_solicitud',
        'Comida_a_preparar',
        'responsable_entrega',
        'responsable_recibir',
        'cantidad',
        'idProducto'
    ];

    public function get_solicitud_con_productos($idSolicitud)
    {
        $builder = $this->db->table('solicitud_productos');
        $builder->select('solicitud_productos.*, productos.idProducto, tipo_producto.nombre AS producto_nombre, tipo_producto.descripcion AS producto_descripcion');
        $builder->join('productos', 'productos.idProducto = solicitud_productos.idProducto');
        $builder->join('tipo_producto', 'tipo_producto.idtipoProducto = productos.idtipoProducto');
        $builder->where('solicitud_productos.idSolicitudProductos', $idSolicitud);
        
        $result = $builder->get()->getResultArray();
        
        foreach ($result as &$item) {
            $ultimoConsumo = $this->db->table('consumos')
                ->where('idProducto', $item['idProducto'])
                ->orderBy('fecha', 'desc')
                ->limit(1)
                ->get()
                ->getRowArray();

            $item['producto_saldo'] = $ultimoConsumo ? $ultimoConsumo['saldo'] : 0;
        }
        
        return $result;
    }

    public function obtenerSolicitudesConDetalles()
    {
        $builder = $this->db->table('solicitud_productos sp');
        $builder->select('sp.idSolicitudProductos, sp.Fecha_solicitud, sp.Comida_a_preparar, sp.responsable_entrega, sp.responsable_recibir, sp.cantidad, p.idProducto, tp.nombre AS producto_nombre, tp.descripcion AS producto_descripcion, p.fecha_vencimiento, IFNULL(c.saldo, 0) AS producto_saldo');
        $builder->join('productos p', 'sp.idProducto = p.idProducto', 'left');
        $builder->join('tipo_producto tp', 'tp.idtipoProducto = p.idtipoProducto', 'left');
        $builder->join('(SELECT idProducto, saldo FROM consumos WHERE fecha = (SELECT MAX(fecha) FROM consumos WHERE idProducto = consumos.idProducto)) c', 'c.idProducto = p.idProducto', 'left');
        return $builder->get()->getResultArray();
    }

    public function getSaldoActual($idProducto)
    {
        $consumoModel = new \App\Models\ConsumoModel();
        $ultimoConsumo = $consumoModel->obtenerUltimoConsumo($idProducto);
        return $ultimoConsumo ? $ultimoConsumo['saldo'] : 0;
    }
    
    public function obtenerProductosConSaldo()
    {
        $builder = $this->db->table('productos p');
        $builder->select('p.idProducto, tp.nombre AS producto_nombre, tp.descripcion AS producto_descripcion, IFNULL(c.saldo, 0) AS saldo_actual');
        $builder->join('tipo_producto tp', 'tp.idtipoProducto = p.idtipoProducto', 'left');
        $builder->join('(SELECT idProducto, saldo FROM consumos WHERE fecha = (SELECT MAX(fecha) FROM consumos WHERE idProducto = consumos.idProducto)) c', 'c.idProducto = p.idProducto', 'left');
        return $builder->get()->getResultArray();
    }
}

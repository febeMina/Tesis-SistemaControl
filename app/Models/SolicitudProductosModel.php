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
        'responsable_recibir'
    ];

    public function get_solicitud_con_productos($idSolicitud)
{
    $builder = $this->db->table('solicitud_productos sp');
    $builder->select('sp.*, spd.*, p.idProducto, tp.nombre AS producto_nombre, tp.descripcion AS producto_descripcion, p.fecha_vencimiento, IFNULL(c.saldo, 0) AS producto_saldo');
    $builder->join('solicitud_productos_detalle spd', 'spd.idSolicitudProductos = sp.idSolicitudProductos', 'left');
    $builder->join('productos p', 'p.idProducto = spd.idProducto', 'left');
    $builder->join('tipo_producto tp', 'tp.idtipoProducto = p.idtipoProducto', 'left');
    $builder->join('(SELECT idProducto, saldo FROM consumos WHERE fecha = (SELECT MAX(fecha) FROM consumos WHERE idProducto = consumos.idProducto)) c', 'c.idProducto = p.idProducto', 'left');
    $builder->where('sp.idSolicitudProductos', $idSolicitud);
    $query = $builder->get();

    return $query->getResultArray();
}


public function obtenerSolicitudesConDetalles()
{
    return $this->db->table('solicitud_productos sp')
        ->select('sp.*, spd.*, p.idProducto, tp.nombre AS producto_nombre, tp.descripcion AS producto_descripcion, p.fecha_vencimiento, IFNULL(c.saldo, 0) AS producto_saldo')
        ->join('solicitud_productos_detalle spd', 'spd.idSolicitudProductos = sp.idSolicitudProductos', 'left')
        ->join('productos p', 'p.idProducto = spd.idProducto', 'left')
        ->join('tipo_producto tp', 'tp.idtipoProducto = p.idtipoProducto', 'left')
        ->join('(SELECT idProducto, MAX(fecha) as max_fecha FROM consumos GROUP BY idProducto) ultimo_consumo', 'p.idProducto = ultimo_consumo.idProducto', 'left')
        ->join('consumos c', 'p.idProducto = c.idProducto AND c.fecha = ultimo_consumo.max_fecha', 'left')
        ->get()->getResult();
}


    public function getSaldoActual($idProducto)
    {
        $consumoModel = new \App\Models\ConsumoModel();
        $ultimoConsumo = $consumoModel->obtenerUltimoConsumo($idProducto);
        return $ultimoConsumo ? $ultimoConsumo['saldo'] : 0;
    }
    
    public function obtenerProductosConSaldo()
    {
        $builder = $this->db->table('consumos');
        $builder->select('consumos.idProducto, tp.nombre as producto_nombre, tp.descripcion as producto_descripcion, consumos.saldo, productos.fecha_vencimiento');
        $builder->join('productos', 'productos.idProducto = consumos.idProducto');
        $builder->join('tipo_producto tp', 'tp.idtipoProducto = productos.idtipoProducto'); // JOIN para obtener la descripción del producto
        $query = $builder->get();
        return $query->getResult();
    }
    

    
    public function insertarSolicitudConDetalles($data, $detalles)
    {
        $this->db->transStart();
        
        $this->insert($data);
        $idSolicitudProductos = $this->getInsertID();
        
        $detalleModel = new \App\Models\SolicitudProductoDetalleModel();
        $detalleModel->insertarDetalles($idSolicitudProductos, $detalles);
        
        $this->db->transComplete();
        
        return $this->db->transStatus();
    }
    public function getAllSolicitudes()
{
    return $this->findAll(); // Asegúrate de que esto devuelve objetos
}

public function getProductosConsumo()
{
    $builder = $this->db->table('productos p');
    $builder->select('p.idProducto, tp.nombre AS producto_nombre, tp.descripcion AS producto_descripcion, p.fecha_vencimiento, c.saldo AS producto_saldo');
    $builder->join('tipo_producto tp', 'tp.idtipoProducto = p.idtipoProducto', 'left');
    $builder->join(
        '(SELECT idProducto, MAX(fecha) as max_fecha FROM consumos GROUP BY idProducto) ultimo_consumo', 
        'p.idProducto = ultimo_consumo.idProducto', 
        'left'
    );
    $builder->join(
        'consumos c', 
        'p.idProducto = c.idProducto AND c.fecha = ultimo_consumo.max_fecha', 
        'left'
    );
    $builder->where('c.saldo IS NOT NULL'); // Asegúrate de que hay un saldo asociado
    $query = $builder->get();

    return $query->getResultArray();
}


    
    public function getDetalles($idSolicitudProductos)
    {
        $builder = $this->db->table('solicitud_productos_detalle spd');
        $builder->select('spd.*, p.idProducto, tp.nombre AS producto_nombre, tp.descripcion AS producto_descripcion');
        $builder->join('productos p', 'p.idProducto = spd.idProducto', 'left');
        $builder->join('tipo_producto tp', 'tp.idtipoProducto = p.idtipoProducto', 'left');
        $builder->where('spd.idSolicitudProductos', $idSolicitudProductos);
        $query = $builder->get();
        
        return $query->getResultArray();
    }
    
    // Método para obtener el reporte de solicitudes de productos
    public function getReporteSolicitudProductos($fechaInicio = null, $fechaFin = null)
{
    $builder = $this->builder();
    
    if ($fechaInicio && $fechaFin) {
        $builder->where('Fecha_solicitud >=', $fechaInicio);
        $builder->where('Fecha_solicitud <=', $fechaFin);
    } elseif ($fechaInicio) {
        $builder->where('Fecha_solicitud', $fechaInicio); // Si solo se especifica una fecha, filtra por esa fecha
    }

    return $builder->get()->getResultArray();
}

    
    public function getDetallesSolicitud($idSolicitudProductos)
{
    $builder = $this->db->table('solicitud_productos_detalle spd');
        $builder->select('spd.*, p.idProducto, tp.nombre AS producto_nombre, tp.descripcion AS producto_descripcion, p.fecha_vencimiento');
        $builder->join('productos p', 'p.idProducto = spd.idProducto', 'left');
        $builder->join('tipo_producto tp', 'tp.idtipoProducto = p.idtipoProducto', 'left');
        $builder->where('spd.idSolicitudProductos', $idSolicitudProductos);
    $query = $builder->get();
    return $query->getResultArray();
}

}

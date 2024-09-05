<?php

namespace App\Models;

use CodeIgniter\Model;

class SolicitudProductoDetalleModel extends Model
{
    protected $table = 'solicitud_productos_detalle'; // Nombre actualizado de la tabla
    protected $primaryKey = 'idDetalle'; // Clave primaria actualizada
    protected $allowedFields = [
        'idSolicitudProductos',
        'idProducto',
        'cantidad'
    ];

    public function obtenerDetallesPorSolicitud($idSolicitudProductos)
    {
        return $this->where('idSolicitudProductos', $idSolicitudProductos)->findAll();
    }

    public function insertarDetalles($idSolicitudProductos, $detalles)
    {
        foreach ($detalles as $detalle) {
            $detalle['idSolicitudProductos'] = $idSolicitudProductos;
            $this->insert($detalle);
        }
    }
    
    public function getDetallesBySolicitud($idSolicitud)
    {
        return $this->select('solicitud_productos_detalle.*, tipo_producto.nombre as nombre_producto')
                    ->join('productos', 'productos.idProducto = solicitud_productos_detalle.idProducto')
                    ->join('tipo_producto', 'tipo_producto.idtipoProducto = productos.idtipoProducto')
                    ->where('solicitud_productos_detalle.idSolicitudProductos', $idSolicitud)
                    ->findAll();
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductoModel extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'idProducto';
    protected $allowedFields = ['descripcionProducto', 'estado'];

    
    public function getTiposProducto()
    {
        return $this->db->table('tipo_producto')->get()->getResult();
    }

    public function getUnidadIndividual()
    {
        return $this->db->table('unidad_individual')->get()->getResult();
    }

    public function getUnidadesPorCaja()
    {
        return $this->db->table('unidades_por_caja')->get()->getResult();
    }

    public function getMovimientos()
    {
        return $this->db->table('movimiento')->get()->getResult();
    }

    public function getPrioridades()
    {
        return $this->db->table('nivel_prioridad')->get()->getResult();
    }

    public function getDetallesSolicitados()
    {
        return $this->db->table('detalle_solicitud')->get()->getResult();
    }

    public function saveProducto($data)
    {
        // Guardar en la tabla productos
        $productoID = $this->insert($data);

        // Guardar en la tabla movimiento
        $movimientoData = [
            'productos_idProducto' => $productoID,
            'total' => $data['total']
            // Agrega otros campos si es necesario
        ];

        $this->db->table('movimiento')->insert($movimientoData);

        return $productoID; // Retornar el ID del producto guardado
    }

    public function updateProducto($id, $data)
    {
        // Actualizar en la tabla productos
        $this->update($id, $data);

        // Actualizar en la tabla movimiento
        $movimientoData = [
            'total' => $data['total']
            // Agrega otros campos si es necesario
        ];

        $this->db->table('movimiento')->where('productos_idProducto', $id)->update($movimientoData);
    }

    public function eliminarMovimiento($idProducto)
    {
        return $this->db->table('movimiento')
            ->where('productos_idProducto', $idProducto)
            ->delete();
    }

    public function getProductosParaConsumo()
{
    return $this->select('productos.idProducto, productos.idtipoProducto, tipo_producto.nombre, productos.fecha_vencimiento, productos.unidades_extras')
                ->join('tipo_producto', 'productos.idtipoProducto = tipo_producto.idtipoProducto')
                ->findAll();
}

    
    public function obtenerDescripcionProducto($idtipoProducto)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tipo_producto');
        $builder->select('descripcion');
        $builder->where('idtipoProducto', $idtipoProducto);
        $result = $builder->get()->getRowArray();
        
        return $result ? $result['descripcion'] : null;
    }
    
    public function getProductosConTipo()
{
    return $this->select('productos.idProducto, tipo_producto.nombre, tipo_producto.descripcion')
                ->join('tipo_producto', 'productos.idtipoProducto = tipo_producto.idtipoProducto')
                ->findAll();
}
// Método para obtener productos con detalles
public function getProductosConDetalles()
    {
        return $this->select('productos.*, tipo_producto.nombre AS tipo_nombre, tipo_producto.descripcion AS tipo_descripcion')
                    ->join('tipo_producto', 'tipo_producto.idtipoProducto = productos.idtipoProducto')
                    ->findAll();
    }

}

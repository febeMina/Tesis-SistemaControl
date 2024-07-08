<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductoModel extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'idProducto';
    protected $allowedFields = [
        'idtipoProducto', 'codigo_lote', 'fecha_ingreso', 'fecha_vencimiento', 'n_unidades_Caja', 'idUnidadesPorCaja', 'idUnidades_individuales', 'unidades_extras', 'total', 'idMovimiento', 'idPrioridad', 'idDetalleSolicitados', 'estado'
    ];

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
    
}
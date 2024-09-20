<?php

namespace App\Models;

use CodeIgniter\Model;

class PadreModel extends Model
{
    protected $table = 'datos_responsable';
    protected $primaryKey = 'idDatosResponsable';
    protected $allowedFields = [
        'nombreCompleto', 'genero', 'telefono', 'estado', 
        'idTipoDocumento', 'numeroDocumento', 'tipoAsociado'
    ];

    // Método para obtener datos de responsable junto con su tipo de documento
    public function getDatosResponsableWithTipoDocumento($idDatosResponsable = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('datos_responsable.*, tipo_documento.nombre as tipo_documento, tipo_documento.mascara');
        $builder->join('tipo_documento', 'tipo_documento.idTipoDocumento = datos_responsable.idTipoDocumento', 'left');
        
        if ($idDatosResponsable) {
            $builder->where('datos_responsable.idDatosResponsable', $idDatosResponsable);
            return $builder->get()->getRowArray();
        }
        
        return $builder->get()->getResultArray();
    }

    // Método para filtrar responsables por estado y otros filtros
    public function getFilteredPadres($filters, $limit = null, $offset = null)
    {
        $builder = $this->db->table($this->table);
    
        // Selección de columnas
        $builder->select('datos_responsable.*, tipo_documento.nombre as tipo_documento');
        $builder->join('tipo_documento', 'tipo_documento.idTipoDocumento = datos_responsable.idTipoDocumento', 'left');
    
        // Aplicar filtros si existen
        if (!empty($filters['nombre_completo'])) {
            $builder->like('nombreCompleto', $filters['nombre_completo']);
        }
        if (!empty($filters['tipo_documento'])) {
            $builder->where('datos_responsable.idTipoDocumento', $filters['tipo_documento']); // Calificar la columna
        }
        if (!empty($filters['genero'])) {
            $builder->where('genero', $filters['genero']);
        }
    
        // Eliminar el filtro de estado para que muestre ambos, activos e inactivos
        // Solo aplicar filtro si está explícitamente especificado en los filtros
        if (isset($filters['estado']) && $filters['estado'] !== '') {
            $builder->where('estado', $filters['estado']);
        }
    
        // Excluir registros con estado 'Eliminado'
        $builder->where('estado !=', 'Eliminado');
    
        // Si se especifican límites para la paginación
        if ($limit !== null) {
            $builder->limit($limit, $offset);
        }
    
        return $builder->get()->getResultArray();
    }
    

    public function countFilteredPadres($filters)
    {
        $builder = $this->db->table($this->table);
    
        // Aplicar los mismos filtros que en el método de obtención de datos
        if (!empty($filters['nombre_completo'])) {
            $builder->like('nombreCompleto', $filters['nombre_completo']);
        }
        if (!empty($filters['tipo_documento'])) {
            $builder->where('datos_responsable.idTipoDocumento', $filters['tipo_documento']); // Calificar la columna
        }
        if (!empty($filters['genero'])) {
            $builder->where('genero', $filters['genero']);
        }
    
        // Eliminar el filtro de estado para que cuente ambos, activos e inactivos
        if (isset($filters['estado']) && $filters['estado'] !== '') {
            $builder->where('estado', $filters['estado']);
        }
    
        // Excluir registros con estado 'Eliminado'
        $builder->where('estado !=', 'Eliminado');
    
        return $builder->countAllResults();
    }
    

    // Método para marcar un registro como eliminado en lugar de eliminarlo físicamente
    public function setDeleted($id)
    {
        $data = ['estado' => 'Eliminado'];
        return $this->update($id, $data);
    }
}

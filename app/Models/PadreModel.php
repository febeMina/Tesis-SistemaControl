<?php

namespace App\Models;

use CodeIgniter\Model;

class PadreModel extends Model
{
    protected $table = 'datos_responsable';
    protected $primaryKey = 'idDatosResponsable';
    protected $allowedFields = [
        'nombreCompleto', 'Genero', 'telefono', 'estado', 
        'idTipoDocumento', 'numero_documento', 'tipo_asociado'
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
    public function getFilteredPadres($filters = [], $limit = 10, $offset = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select('datos_responsable.*, tipo_documento.nombre as tipo_documento');
        $builder->join('tipo_documento', 'tipo_documento.idTipoDocumento = datos_responsable.idTipoDocumento', 'left');
        
        // Excluir registros con estado "inactivo"
        $builder->where('datos_responsable.estado !=', 'inactivo');
        
        if (!empty($filters['nombre_completo'])) {
            $builder->like('nombreCompleto', $filters['nombre_completo']);
        }
    
        if (!empty($filters['tipo_documento'])) {
            $builder->where('datos_responsable.idTipoDocumento', $filters['tipo_documento']);
        }
    
        if (!empty($filters['genero'])) {
            $builder->where('datos_responsable.Genero', $filters['genero']);
        }
    
        if (isset($filters['estado'])) {
            $builder->where('datos_responsable.estado', $filters['estado']);
        }
        
        // Aplicar limit y offset para la paginación
        $builder->limit($limit, $offset);
        
        return $builder->get()->getResultArray();
    }

    // Método para contar todos los registros para la paginación
    public function countFilteredPadres($filters = [])
    {
        $builder = $this->db->table($this->table);
        $builder->where('estado !=', 'inactivo');
        
        if (!empty($filters['nombre_completo'])) {
            $builder->like('nombreCompleto', $filters['nombre_completo']);
        }
    
        if (!empty($filters['tipo_documento'])) {
            $builder->where('idTipoDocumento', $filters['tipo_documento']);
        }
    
        if (!empty($filters['genero'])) {
            $builder->where('Genero', $filters['genero']);
        }
    
        if (isset($filters['estado'])) {
            $builder->where('estado', $filters['estado']);
        }
        
        return $builder->countAllResults();
    }
}

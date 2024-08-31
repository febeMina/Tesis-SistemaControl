<?php

namespace App\Models;

use CodeIgniter\Model;

class TipoDocumentoModel extends Model
{
    protected $table = 'tipo_documento';
    protected $primaryKey = 'idTipoDocumento';
    protected $allowedFields = ['nombre', 'mascara'];

    // Método para obtener todos los tipos de documentos
    public function getTiposDocumento()
    {
        return $this->findAll();
    }

    // Método para obtener un tipo de documento por su ID
    public function getTipoDocumentoById($id)
    {
        return $this->where('idTipoDocumento', $id)->first();
    }

    // Método para crear un nuevo tipo de documento
    public function createTipoDocumento($data)
    {
        return $this->insert($data);
    }

    // Método para actualizar un tipo de documento
    public function updateTipoDocumento($id, $data)
    {
        return $this->update($id, $data);
    }

    // Método para eliminar un tipo de documento
    public function deleteTipoDocumento($id)
    {
        return $this->delete($id);
    }
}

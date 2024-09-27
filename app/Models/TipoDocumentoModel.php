<?php

namespace App\Models;

use CodeIgniter\Model;

class TipoDocumentoModel extends Model
{
    protected $table = 'tipo_documento';
    protected $primaryKey = 'idTipoDocumento';
    protected $allowedFields = ['nombre', 'mascara', 'estado', 'usuarioCrea', 'usuarioModifica'];

    // Obtener todos los tipos de documentos (excepto los eliminados)
    public function getTiposDocumento()
    {
        return $this->where('estado !=', 'Eliminado')->findAll();
    }

    // Obtener un tipo de documento por su ID
    public function getTipoDocumentoById($id)
    {
        return $this->where('idTipoDocumento', $id)->first();
    }

    // Crear un nuevo tipo de documento
    public function createTipoDocumento($data)
    {
        return $this->insert($data);
    }

    // Actualizar un tipo de documento
    public function updateTipoDocumento($id, $data)
    {
        return $this->update($id, $data); // Llama al método update de CodeIgniter
    }
    

    // Marcar un tipo de documento como eliminado (eliminación lógica)
    public function deleteTipoDocumento($id)
    {
        $data = ['estado' => 'Eliminado'];
        return $this->update($id, $data);
    }
}

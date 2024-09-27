<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'usuarios'; // Nombre de la tabla en la base de datos
    protected $primaryKey = 'idUsuarios'; // Nombre de la clave primaria
    protected $allowedFields = [ 'usuario', 'clave', 'estado', 'idRol']; // Campos permitidos para la asignación masiva

    // Puedes agregar métodos para manejar la autenticación aquí
    public function CambioEstadoInactivo($id)
    {
        $data = [
            'estado' => 'Inactivo',
        ];

        return $this->db->table('usuarios')
                        ->where('idUsuarios', $id)
                        ->update($data);
    }

   
}

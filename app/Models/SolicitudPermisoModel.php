<?php

namespace App\Models;

use CodeIgniter\Model;

class SolicitudPermisoModel extends Model
{
    protected $table = 'solicitud_permiso';
    protected $primaryKey = 'idSolicitudPermiso';
    protected $allowedFields = ['id_maestro', 'id_tipo_permiso', 'fecha_inicio', 'fecha_fin', 'dias_ocupados', 'fecha_creacion'];


    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $useTimestamps = false;

    protected $validationRules = [
        'idDocente' => 'required',
        'idTipoPermiso' => 'required',
        'fecha_inicio' => 'required',
        'fecha_fin' => 'required',
        'dias_ocupados' => 'required'
    ];
    
    public function getSolicitudesByMaestro($idMaestro)
    {
        return $this->where('id_maestro', $idMaestro)->findAll();
    }
}

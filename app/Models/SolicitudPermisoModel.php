<?php

namespace App\Models;

use CodeIgniter\Model;

class SolicitudPermisoModel extends Model
{
    protected $table = 'solicitud_permiso';
    protected $primaryKey = 'idSolicitudPermiso';
    protected $allowedFields = ['idDocente', 'idTipoPermiso', 'fecha_inicio', 'fecha_fin', 'dias_ocupados', 'horas_ocupadas', 'fecha_creacion'];

    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $useTimestamps = false;

    protected $validationRules = [
        'idDocente' => 'required|integer',
        'idTipoPermiso' => 'required|integer',
        'fecha_inicio' => 'required|valid_date',
        'fecha_fin' => 'required|valid_date',
        'dias_ocupados' => 'required|integer',
        'horas_ocupadas' => 'permit_empty|integer',
        'fecha_creacion' => 'required|valid_date'
    ];

    public function getSolicitudesByMaestro($idMaestro)
    {
        return $this->where('idDocente', $idMaestro)->findAll();
    }

    public function createSolicitud($data)
    {
        return $this->insert($data);
    }

    public function findAllWithDetails()
    {
        // Ejemplo de consulta para obtener detalles de permisos
        return $this->select('solicitud_permiso.*, maestro.nombre_completo, tipo_permiso.nombre')
                    ->join('maestro', 'maestro.idDocente = solicitud_permiso.idDocente')
                    ->join('tipo_permiso', 'tipo_permiso.idTipoPermiso = solicitud_permiso.idTipoPermiso')
                    ->findAll();
    }
}

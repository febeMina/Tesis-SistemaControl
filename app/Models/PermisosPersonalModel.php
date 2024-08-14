<?php

namespace App\Models;

use CodeIgniter\Model;

class PermisosPersonalModel extends Model
{
    protected $table = 'permisos_personal';
    protected $primaryKey = 'idPermisoPersonal';

    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'idSaldoPersonal',
        'fechaInicio',
        'fechaFin',
        'horasSolicitadas',
        'diasSolicitados',
        'saldoHistorialHoras',
        'saldoHistorialDias',
        'fechaCreacion' // Añadido
    ];

    protected $useTimestamps = false;

    protected $validationRules = [
        'idSaldoPersonal' => 'required|integer',
        'fechaInicio' => 'permit_empty|valid_date',
        'fechaFin' => 'permit_empty|valid_date',
        'horasSolicitadas' => 'permit_empty|integer|greater_than_equal_to[1]|less_than_equal_to[6]',
        'diasSolicitados' => 'permit_empty|integer|greater_than_equal_to[1]',
        'fechaCreacion' => 'permit_empty|valid_date' // Añadido
    ];

    protected $validationMessages = [
        'idSaldoPersonal' => [
            'required' => 'El campo idSaldoPersonal es obligatorio.',
            'integer' => 'El campo idSaldoPersonal debe ser un entero.',
        ],
        'fechaInicio' => [
            'valid_date' => 'El campo fechaInicio debe ser una fecha válida.',
        ],
        'fechaFin' => [
            'valid_date' => 'El campo fechaFin debe ser una fecha válida.',
        ],
        'horasSolicitadas' => [
            'integer' => 'El campo horasSolicitadas debe ser un entero.',
        ],
        'diasSolicitados' => [
            'integer' => 'El campo diasSolicitados debe ser un entero.',
        ],
        'fechaCreacion' => [
            'valid_date' => 'El campo fechaCreacion debe ser una fecha válida.',
        ],
    ];

    public function getPermisosConSaldos($nombreCompleto = null, $nip = null, $fechaCreacion = null)
    {
        $builder = $this->db->table('permisos_personal');
        $builder->select('permisos_personal.*, docente.nip, docente.nombre_completo, saldos_personal.saldoActualDias, saldos_personal.saldoActualHoras, tipo_permisos.nombre as tipoPermisoNombre, tipo_permisos.cantidad_dias');
        $builder->join('saldos_personal', 'saldos_personal.idSaldoPersonal = permisos_personal.idSaldoPersonal');
        $builder->join('docente', 'docente.idDocente = saldos_personal.idDocente');
        $builder->join('tipo_permisos', 'tipo_permisos.idTipoPermiso = saldos_personal.idTipoPermiso');
        $builder->orderBy('permisos_personal.fechaCreacion', 'DESC'); // Ordenar por fecha de creación
        
            // Aplicar filtros
        if ($nombreCompleto) {
            $builder->like('docente.nombre_completo', $nombreCompleto);
        }
        if ($nip) {
            $builder->where('docente.nip', $nip);
        }
        if ($fechaCreacion) {
            $builder->where('permisos_personal.fechaCreacion', $fechaCreacion);
        }
    
        $builder->orderBy('permisos_personal.fechaCreacion', 'DESC');
        
        return $builder->get()->getResultArray();
    }
    
    public function getReportePermisos($fechaInicio = null, $fechaFin = null)
{
    $builder = $this->db->table('permisos_personal');
    $builder->select('
        permisos_personal.*, 
        docente.nip, 
        docente.nombre_completo, 
        saldos_personal.saldoActualDias, 
        saldos_personal.saldoActualHoras, 
        tipo_permisos.nombre as tipoPermisoNombre, 
        tipo_permisos.cantidad_dias,
        permisos_personal.fechaInicio,
        permisos_personal.fechaFin
    ');
    $builder->join('saldos_personal', 'saldos_personal.idSaldoPersonal = permisos_personal.idSaldoPersonal');
    $builder->join('docente', 'docente.idDocente = saldos_personal.idDocente');
    $builder->join('tipo_permisos', 'tipo_permisos.idTipoPermiso = saldos_personal.idTipoPermiso');
    $builder->orderBy('permisos_personal.fechaCreacion', 'DESC'); // Ordenar por fecha de creación
    
    // Filtrar por fechaInicio si se proporciona
    if ($fechaInicio) {
        $builder->where('fechaInicio >=', $fechaInicio);
    }

    // Filtrar por fechaFin si se proporciona
    if ($fechaFin) {
        $builder->where('fechaFin <=', $fechaFin);
    }

    // Obtener los resultados
    return $builder->get()->getResultArray();
}


    
    public function getPermisosReporte( $fechaInicio = null, $fechaFin = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('permisos_personal.*, maestros.nombre_completo, tipo_permisos.nombre as tipoPermisoNombre, saldos_personal.saldoActualDias, saldos_personal.saldoActualHoras, saldos_personal.saldoHistorialDias, saldos_personal.saldoHistorialHoras');
        $builder->join('maestros', 'maestros.idMaestro = permisos_personal.idMaestro');
        $builder->join('tipo_permisos', 'tipo_permisos.idTipoPermiso = permisos_personal.idTipoPermiso');
        $builder->join('saldos_personal', 'saldos_personal.idSaldoPersonal = permisos_personal.idSaldoPersonal');
    
        if ($nombreCompleto) {
            $builder->like('maestros.nombre_completo', $nombreCompleto);
        }
        if ($nip) {
            $builder->where('maestros.nip', $nip);
        }
        if ($fechaInicio) {
            $builder->where('permisos_personal.fechaInicio >=', $fechaInicio);
        }
        if ($fechaFin) {
            $builder->where('permisos_personal.fechaFin <=', $fechaFin);
        }
    
        return $builder->get()->getResultArray();
    }
    

    
    

    public function getPermisosPorDocente($idDocente)
    {
        $builder = $this->db->table('permisos_personal p');
        $builder->select('p.idPermisoPersonal, p.fechaInicio, p.fechaFin, p.horasSolicitadas, p.saldoHistorialDias, p.saldoHistorialHoras, s.saldoActualDias, s.saldoActualHoras, tp.nombre as tipoPermisoNombre, tp.cantidad_dias');
        $builder->join('saldos_personal s', 'p.idSaldoPersonal = s.idSaldoPersonal');
        $builder->join('tipo_permisos tp', 'tp.idTipoPermiso = s.idTipoPermiso');
        $builder->where('s.idDocente', $idDocente);
        $builder->orderBy('p.fechaCreacion', 'DESC'); // Ordenar por fecha de creación

        $query = $builder->get();

        return $query->getResultArray();
    }

    public function calcularDiasEntreFechas($fechaInicio, $fechaFin)
    {
        $inicio = new \DateTime($fechaInicio);
        $fin = new \DateTime($fechaFin);
        $diferencia = $inicio->diff($fin);
        
        return $diferencia->days + 1;
    }

    public function actualizarSaldoPersonal($idSaldoPersonal, $tipo, $cantidad)
{
    $saldo = $this->where('idSaldoPersonal', $idSaldoPersonal)->first();

    if (!$saldo) {
        throw new \Exception('Saldo no encontrado.');
    }

    if ($tipo === 'Dias') {
        $nuevoSaldo = $saldo['saldoActualDias'] - $cantidad;
        $this->update($idSaldoPersonal, ['saldoActualDias' => $nuevoSaldo]);
    } elseif ($tipo === 'Horas') {
        $nuevoSaldo = $saldo['saldoActualHoras'] - $cantidad;
        $this->update($idSaldoPersonal, ['saldoActualHoras' => $nuevoSaldo]);
    }
}
public function getTipoPermisos()
{
    return $this->db->table('tipo_permisos')->get()->getResultArray();
}


}

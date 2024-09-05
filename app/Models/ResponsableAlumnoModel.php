<?php

namespace App\Models;

use CodeIgniter\Model;

class ResponsableAlumnoModel extends Model
{
    protected $table = 'responsable_alumno';
    protected $primaryKey = 'idResponsableAlumno';
    protected $allowedFields = ['idDatosResponsable', 'idAlumno'];

    public function getAlumnosAsociados($padreId)
    {
        return $this->db->table($this->table)
            ->where('idDatosResponsable', $padreId)
            ->join('datos_alumnos', 'responsable_alumno.idAlumno = datos_alumnos.idAlumno')
            ->get()
            ->getResultArray();
    }

    public function deleteAlumnosAsociados($padreId)
    {
        return $this->db->table($this->table)
            ->where('idDatosResponsable', $padreId)
            ->delete();
    }

    public function insertRecord(array $data): bool
    {
        return $this->insert($data);
    }

    public function updateRecord(int $id, array $data): bool
    {
        return $this->update($id, $data);
    }
}

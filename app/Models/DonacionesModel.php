<?php

namespace App\Models;

use CodeIgniter\Model;

class DonacionesModel extends Model
{
    protected $table = 'donaciones';
    protected $primaryKey = 'idDonaciones';
    protected $allowedFields = ['cantidad', 'descripcion', 'fechaDonacion', 'estado', 'idResponsable'];

   public function getResponsable($idDonaciones)
{
    return $this->db->table('proyectos')
                    ->select('proyectos_datos.*')
                    ->join('proyectos_datos', 'proyectos_datos.idDonaciones= proyectos.idDonaciones')
                    ->where('proyectos.idDonaciones', $idDonaciones)
                    ->get()
                    ->getResultArray();
}

    
    

    // Otros métodos del modelo
}

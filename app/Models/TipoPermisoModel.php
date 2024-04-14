<?php
namespace App\Models;

use CodeIgniter\Model;

class TipoPermisoModel extends Model
{
    protected $table = 'tipo_permisos';
    protected $primaryKey = 'idTipoPermiso';
    protected $allowedFields = ['nombre', 'cantidad_dias'];
    protected $returnType = 'object'; // Configura el retorno como objetos

    // Opcionalmente, puedes configurar la clase de entidad
    protected $useEntity = true;
    protected $useSoftDeletes = false;
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
}


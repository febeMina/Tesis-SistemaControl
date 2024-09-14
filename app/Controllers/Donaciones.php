<?php
namespace App\Controllers;
//namespace App\third_party;

use CodeIgniter\Controller;
use App\Models\DonacionesModel;
use Dompdf\Dompdf;

class Donaciones extends Controller 
{

    protected $db;

    public function __construct(){
        helper('url');
        if (!session()->get('isLoggedIn')) {
            redirect()->to(base_url('public/login'))->send();
            exit;
        }
    }
    
    public function index()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('donaciones');
        $builder->select('donaciones.idDonaciones, datos_responsable.nombreCompleto, donaciones.cantidad, donaciones.descripcion, donaciones.fechaDonacion, proyectos.nombreProyecto' );
        $builder->join('proyectos', 'proyectos.idProyectos = donaciones.idProyectos', 'inner');
        $builder->join('datos_responsable', 'datos_responsable.idDatosResponsable = donaciones.idDatosResponsable', 'inner');
        $donaciones = $builder->get()->getResult();
        return view('donaciones/index', ['donaciones' => $donaciones]);
    
        //return view('donaciones/index');
    }

    public function create()
    {
        $db = \Config\Database::connect();
        $projectBuilder = $db->table('proyectos');
        $proyectos = $projectBuilder->select('idProyectos, nombreProyecto')->where('estado !=', 'Eliminado')->get()->getResult();
        
        $responsableBuilder = $db->table('datos_responsable');
        $responsables = $responsableBuilder->select('idDatosResponsable, nombreCompleto, tipoAsociado')->get()->getResult();

        return view('donaciones/create', ['proyectos' => $proyectos,'responsables' => $responsables]);
    }
    
    public function sociosTipo()
    {
        $db = \Config\Database::connect();
        $projectBuilder = $db->table('proyectos');
        $proyectos = $projectBuilder->select('idProyectos, nombreProyecto')->get()->getResult();
        

        return view('donaciones/create', ['proyectos' => $proyectos]);  
    }

    public function store()
    {
        $request = \Config\Services::request();
        $donacionModel = new DonacionesModel();

        $data = [
            
            'cantidad' => $request->getVar('cantidad'),
            'cantidadLetras' => $request->getVar('cantidadLetras'), // Corregido aquí
            'fechaDonacion' => $request->getVar('fecha'),
            'descripcion' => $request->getVar('descripcion'),
            'estado' => "Activo",
            'idProyectos' => $request->getVar('idProyecto'),
            'idDatosResponsable' => $request->getVar('NombreDonante')
        ];

        $donacionModel->insert($data);

        return redirect()->to(site_url('donaciones'));
    }

    public function edit($id)
    {
       
    }

    public function update($id)
    {
       
    }

    public function delete($id)
    {
        $request = \Config\Services::request();
        $donacionModel = new DonacionesModel();
        $donacionModel->update($id, ['estado' => 'inactivo']);

    }

    public function GenerarReporte()
    {
        $dompdf = new Dompdf();
        //$dompdf->loadHTML('<h1>Hola Mundo</h1><br><p>Otro contenido</p>');
        $db = \Config\Database::connect();
        $builder = $db->table('donaciones');
        $builder->select('donaciones.idDonaciones, donaciones.nombreDonante, donaciones.cantidad, donaciones.descripcion, donaciones.fechaDonacion, proyectos.nombreProyecto');
        $builder->join('proyectos', 'proyectos.idProyectos = donaciones.idProyectos', 'inner');
        $donaciones = $builder->get()->getResult();
        $dompdf->loadHTML(
                    view('reportes/donacionesReporte', ['donaciones' => $donaciones])
        );
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream();
    }

    public function GenerarTicket($id)
    {
        $dompdf = new Dompdf();
        //$dompdf->loadHTML('<h1>Hola Mundo</h1><br><p>Otro contenido</p>');
        $db = \Config\Database::connect();
        $builder = $db->table('donaciones');
        $builder->select('donaciones.idDonaciones, datos_responsable.nombreCompleto, donaciones.cantidadLetras, donaciones.cantidad, donaciones.descripcion, donaciones.fechaDonacion, proyectos.nombreProyecto' );
        $builder->join('proyectos', 'proyectos.idProyectos = donaciones.idProyectos', 'inner');
        $builder->join('datos_responsable', 'datos_responsable.idDatosResponsable = donaciones.idDatosResponsable', 'inner');
        $donaciones = $builder->where('idDonaciones', $id)->get()->getResult();
        $dompdf->loadHTML(
                    view('reportes/reciboDonacion', ['donaciones' => $donaciones])
        );
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream();
    }


    public function obtener_donadores_por_tipo()
    {
        $tipoDonador = $this->request->getVar('TipoDonante');

        $db = \Config\Database::connect();
        $responsableBuilder = $db->table('datos_responsable');
        $donadores = $responsableBuilder->select('idDatosResponsable, nombreCompleto')->where('tipoAsociado', $tipoDonador)->get()->getResult();
        
        $json_data = json_encode($donadores);
        return $this->response->setJSON($json_data);
    }
    

}

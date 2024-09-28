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
        $donaciones = $builder->where('donaciones.estado !=', 'Eliminado')->get()->getResult();
        return view('donaciones/index', ['donaciones' => $donaciones]);
    
        //return view('donaciones/index');
    }

    public function create()
    {
        $db = \Config\Database::connect();
        $projectBuilder = $db->table('proyectos');
       $projectBuilder->select('idProyectos, nombreProyecto');
       $projectBuilder->where('estado !=', 'Eliminado')->Where('estado !=', 'Inactivo');
       $proyectos = $projectBuilder->get()->getResult();
       
        $responsableBuilder = $db->table('datos_responsable');
         $responsableBuilder->select('idDatosResponsable, nombreCompleto, estado, tipoAsociado');
         $responsableBuilder->where('estado =', 'Activo'); // estado de persona 
         $responsables = $responsableBuilder->get()->getResult();
        
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

        $db = \Config\Database::connect();
        $projectBuilder = $db->table('proyectos');
        $projectBuilder->select('valorActual')->where('idProyectos', $request->getVar('idProyecto'));
        $vActual = $projectBuilder->get()->getRow()->valorActual;

    
        $nuevoValor = $vActual + ($request->getVar('cantidad'));

        $data = [
            'valorActual' => $nuevoValor
        ];

        $projectBuilder->where('idProyectos', $request->getVar('idProyecto'))->update($data);
    
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Donación ingresada correctamente'
        ]);
    }

    public function edit($id)
    {
    
        $db = \Config\Database::connect();
        $builder = $db->table('donaciones');
       // $builder->select('donaciones.idDonaciones, datos_responsable.nombreCompleto, donaciones.cantidad, donaciones.descripcion, donaciones.fechaDonacion, proyectos.nombreProyecto' );
        //$builder->join('proyectos', 'proyectos.idProyectos = donaciones.idProyectos', 'inner');
        //$builder->join('datos_responsable', 'datos_responsable.idDatosResponsable = donaciones.idDatosResponsable', 'inner');
        $donacion = $builder->select('idDonaciones, cantidad, cantidadLetras, fechaDonacion, estado, descripcion, idProyectos, idDatosResponsable ')->where('idDonaciones', $id)->get()->getRow();

        //$usuario = $builder->where('idUsuarios', $id)->get()->getRow();
       

        if (!$donacion) {
            return redirect()->to(base_url('public/donaciones'))->with('error', 'El registro de Donación no existe.');
        }
        
        $projectBuilder = $db->table('proyectos');
        $proyectos = $projectBuilder->select('idProyectos, nombreProyecto')->where('estado !=', 'Eliminado')->get()->getResult();

        $responsablesBuilder = $db->table('datos_responsable');
        $responsables = $responsablesBuilder->select('idDatosResponsable, nombreCompleto, tipoAsociado')->where('estado =', 'Activo')->get()->getResult();
         
       

        return view('donaciones/edit', ['donacion' => $donacion, 'responsables' => $responsables, 'proyectos' => $proyectos]);
    }
    

    public function update($id)
    {
        $rules = [
            'cantidad'  => 'required',
            'cantidadLetras' => 'required',
            'fecha'  => 'required',
            'descripcion'  => 'required',
            'idProyecto'  => 'required',
            'estado'  => 'required',
            'NombreDonante'  => 'required'
            // Agrega aquí más reglas de validación según tus necesidades
        ];
           
          
        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                  'success' => false,
                   'error' => $this->validator->listErrors()
                    ]);
        }

        $request = \Config\Services::request();
        $donacionModel = new DonacionesModel();

        $data = [
            'cantidad' => $request->getVar('cantidad'),
            'cantidadLetras' => $request->getVar('cantidadLetras'), // Corregido aquí
            'fechaDonacion' => $request->getVar('fecha'),
            'descripcion' => $request->getVar('descripcion'),
            'estado' => $request->getVar('estado'),
            'idProyectos' => $request->getVar('idProyecto'),
            'idDatosResponsable' => $request->getVar('NombreDonante')
        ];
     
             $donacionModel->update($id, $data);
        

             return $this->response->setJSON([
                'success' => true,
                'message' => 'Donación ingresada correctamente'
            ]);

    
       

    }

    public function delete($id)
    {
        $request = \Config\Services::request();
        $donacionModel = new DonacionesModel();
        $donacionModel->update($id, ['estado' => 'Eliminado']);

    }

    public function GenerarReporte()
    {
        $dompdf = new Dompdf();
        $fechaHoraActual = date('Ymd_His');
        //$dompdf->loadHTML('<h1>Hola Mundo</h1><br><p>Otro contenido</p>');
        $db = \Config\Database::connect();
        $builder = $db->table('donaciones');
        $builder->select('donaciones.idDonaciones, datos_responsable.nombreCompleto, donaciones.cantidad, donaciones.descripcion, donaciones.fechaDonacion, proyectos.nombreProyecto' );
        $builder->join('proyectos', 'proyectos.idProyectos = donaciones.idProyectos', 'inner');
        $builder->join('datos_responsable', 'datos_responsable.idDatosResponsable = donaciones.idDatosResponsable', 'inner');
        $donaciones = $builder->where('donaciones.estado !=', 'Eliminado')->get()->getResult();
        $dompdf->loadHTML(
                    view('reportes/donacionesReporte', ['donaciones' => $donaciones])
        );
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('ReporteDonaciones' . $fechaHoraActual . '.pdf');
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
        // Asignamos nombre al fichero a descargar
        $dompdf->stream(time()."Recibo.pdf");
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

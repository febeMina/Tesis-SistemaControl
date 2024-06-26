<?php
namespace App\Controllers;

use CodeIgniter\Controller;

class Donaciones extends Controller
{
    public function index()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('donaciones');
        $builder->select('donaciones.idDonaciones, donaciones.nombreDonante, donaciones.cantidad, donaciones.descripcion, donaciones.fechaDonacion, proyectos.nombreProyecto');
        $builder->join('proyectos', 'proyectos.idProyectos = donaciones.idProyectos', 'inner');
        $donaciones = $builder->get()->getResult();
        return view('donaciones/index', ['donaciones' => $donaciones]);
    
        //return view('donaciones/index');
    }

    public function create()
    {
        $projectBuilder = $db->table('proyectos');
        $proyectos = $projectBuilder->select('idProyectos, nombreProyecto')->get()->getResult();
        
        return view('donaciones/create', ['proyectos' => $proyectos]);  
    }

    public function store()
    {
        $request = \Config\Services::request();
        $donacionModel = new DonacionesModel();

        $data = [
            'nombre_completo' => $request->getVar('nombre_completo'),
            'cantidad' => $request->getVar('cantidad'),
            'cantidadLetras' => $request->getVar('cantidadLetras'), // Corregido aquí
            'descripcion' => $request->getVar('descripcion'),
            'fechaDonacion' => $request->getVar('fecha'),
            'estado' => "Activo",
            'idProyectos' => $request->getVar('idProyectos')
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
        
    }
}

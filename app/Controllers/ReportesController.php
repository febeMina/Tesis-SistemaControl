<?php

namespace App\Controllers;

class ReportesController extends BaseController
{
    public function permisos_magisteriales_reporte()
    {
        // Obtener datos del flashdata
        $session = session();
        $report_data = $session->getFlashdata('report_data');
    
        // Verificar si se encontraron datos en el flashdata
        if (!$report_data) {
            // Si no hay datos, redirigir o manejar el error según sea necesario
            return redirect()->back()->with('error', 'No se encontraron datos para el reporte.');
        }
    
        // Cargar la vista con los datos del reporte
        return view('Reportes/permisos_magisteriales_reporte', ['report_data' => $report_data]);
    }

}

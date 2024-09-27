<?php namespace App\Controllers;

use App\Models\ProductoModel;
use App\Models\ProductoLoteModel;
require_once APPPATH . 'Libraries/fpdf186/fpdf.php';

class ReportelotesProductos extends BaseController
{
    protected $productoModel;
    protected $productoLoteModel;

    public function __construct()
    {
        $this->productoModel = new ProductoModel();
        $this->productoLoteModel = new ProductoLoteModel();
    }

    // Método para cargar la vista de reportes de lotes por producto
    public function reportesLotes()
    {
        // Obtener todos los productos
        $productos = $this->productoModel->findAll();
        
        // Pasar los productos a la vista
        return view('Reportes/productos_lotes', [
            'productos' => $productos
        ]);
    }

    // Método para obtener los lotes del producto seleccionado
    public function getLotes($id)
    {
        // Obtener los lotes del producto seleccionado
        $productosLotes = $this->productoLoteModel
            ->select('
                productos_lotes.codigoLote,
                productos_lotes.fechaIngreso,
                productos_lotes.fechaVencimiento,
                productos_lotes.existenciaCaja,
                udm_caja.nombreCaja as udmCaja,
                productos_lotes.existenciaIndividual,
                udm_individual.nombreIndividual as udmIndividual,
                productos_lotes.existenciaTotal
            ')
            ->join('udm_caja', 'productos_lotes.idUdmCaja = udm_caja.idUdmCaja', 'left')
            ->join('udm_individual', 'productos_lotes.idUdmIndividual = udm_individual.idUdmIndividual', 'left')
            ->where('productos_lotes.idProducto', $id)
            ->findAll();

        // Retornar los lotes en formato JSON
        return $this->response->setJSON($productosLotes);
    }

    // Método para generar el reporte PDF de los lotes por producto
    public function generarReporte($idProducto)
    {
        // Obtener el producto
        $producto = $this->productoModel->find($idProducto);

        // Obtener los lotes del producto
        $productosLotes = $this->productoLoteModel
            ->select('
                productos_lotes.codigoLote,
                productos_lotes.fechaIngreso,
                productos_lotes.fechaVencimiento,
                productos_lotes.existenciaCaja,
                udm_caja.nombreCaja as udmCaja,
                productos_lotes.existenciaIndividual,
                udm_individual.nombreIndividual as udmIndividual,
                productos_lotes.existenciaTotal
            ')
            ->join('udm_caja', 'productos_lotes.idUdmCaja = udm_caja.idUdmCaja', 'left')
            ->join('udm_individual', 'productos_lotes.idUdmIndividual = udm_individual.idUdmIndividual', 'left')
            ->where('productos_lotes.idProducto', $idProducto)
            ->findAll();

        // Crear el PDF
        $pdf = new \FPDF('L', 'mm', 'Letter');
        $pdf->SetMargins(20, 20, 20);
        $pdf->SetAutoPageBreak(true, 20);

        // Agregar una página
        $pdf->AddPage();
         // Agregar la fecha de generación del reporte (esquina superior derecha)
         $pdf->SetFont('Arial', '', 10);
         $pdf->Cell(0, 10, 'Fecha de generacion: ' . date('d/m/Y'), 0, 1, 'R');
         
         // Agregar el encabezado "CENTRO ESCOLAR BARRIO LAS DELICIAS" (centro)
         $pdf->SetFont('Arial', 'B', 8);
         $pdf->Cell(0, 10, utf8_decode('CENTRO ESCOLAR BARRIO LAS DELICIAS'), 0, 1, 'L');
         $pdf->Ln(5); // Espacio adicional
        // Encabezado
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, utf8_decode('REPORTE DE LOTES DEL PRODUCTO'), 0, 1, 'C');
        $pdf->Ln(10);

        // Detalle del producto
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, utf8_decode('Producto: ' . $producto['descripcionProducto']), 0, 1, 'L');
        $pdf->Ln(5);

        // Encabezado de la tabla
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(30, 10, utf8_decode('Código lote'), 1, 0, 'C');
        $pdf->Cell(35, 10, utf8_decode('Fecha ingreso'), 1, 0, 'C');
        $pdf->Cell(40, 10, utf8_decode('Fecha vencimiento'), 1, 0, 'C');
        $pdf->Cell(50, 10, utf8_decode('Exist. Caja'), 1, 0, 'C');
        $pdf->Cell(45, 10, utf8_decode('Exist. Individ.'), 1, 0, 'C');
        $pdf->Cell(40, 10, utf8_decode('Exist. Total'), 1, 1, 'C');

        // Datos de los lotes
        foreach ($productosLotes as $lote) {
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(30, 10, utf8_decode($lote['codigoLote']), 1, 0, 'C');
            $pdf->Cell(35, 10, utf8_decode(date("d/m/Y", strtotime($lote['fechaIngreso']))), 1, 0, 'C');
            $pdf->Cell(40, 10, utf8_decode(date("d/m/Y", strtotime($lote['fechaVencimiento']))), 1, 0, 'C');
            $pdf->Cell(50, 10, utf8_decode($lote['existenciaCaja'] . ' ' . $lote['udmCaja']), 1, 0, 'C');
            $pdf->Cell(45, 10, utf8_decode($lote['existenciaIndividual'] . ' ' . $lote['udmIndividual']), 1, 0, 'C');
            $pdf->Cell(40, 10, utf8_decode($lote['existenciaTotal']), 1, 1, 'C');
        }
        

        // Si no hay lotes
        if (empty($productosLotes)) {
            $pdf->Cell(190, 10, utf8_decode('No se encontraron lotes para este producto.'), 1, 1, 'C');
        }

        // Salida del PDF
        $pdf->Output('D', 'reporte_lotes_' . $producto['descripcionProducto'] . '.pdf');
    }
}

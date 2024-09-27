<?php namespace App\Controllers;

require_once APPPATH . 'Libraries/fpdf186/fpdf.php';

class ReporteInventarioGeneral extends BaseController
{
    protected $productoModel;

    public function __construct()
    {
        $this->productoModel = new \App\Models\ProductoModel();
    }

    public function index()
    {
        // Obtener datos para la vista
        $productos = $this->productoModel
            ->select('productos.descripcionProducto, MIN(productos_lotes.fechaVencimiento) as fechaVencimientoProxima, SUM(productos_lotes.existenciaTotal) as totalExistencia')
            ->join('productos_lotes', 'productos.idProducto = productos_lotes.idProducto', 'left')
            ->groupBy('productos.idProducto')
            ->orderBy('fechaVencimientoProxima', 'ASC')
            ->get()
            ->getResult();

        return view('Reportes/inventario_general', ['productos' => $productos]);
    }

    public function generarReporte()
    {
        // Obtener productos para el reporte
        $productos = $this->productoModel
            ->select('productos.descripcionProducto, MIN(productos_lotes.fechaVencimiento) as fechaVencimientoProxima, SUM(productos_lotes.existenciaTotal) as totalExistencia')
            ->join('productos_lotes', 'productos.idProducto = productos_lotes.idProducto', 'left')
            ->groupBy('productos.idProducto')
            ->orderBy('fechaVencimientoProxima', 'ASC')
            ->get()
            ->getResult();

        // Crear el PDF
        $pdf = new \FPDF('P', 'mm', 'A4');
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
        $pdf->Cell(0, 10, utf8_decode('INVENTARIO GENERAL'), 0, 1, 'C');
        $pdf->Ln(10);

        // Tabla de inventario
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(10, 10, utf8_decode('N°'), 1, 0, 'C');
        $pdf->Cell(80, 10, utf8_decode('Producto'), 1, 0, 'C');
        $pdf->Cell(45, 10, utf8_decode('Próximo vencimiento'), 1, 0, 'C');
        $pdf->Cell(35, 10, utf8_decode('Existencia actual'), 1, 1, 'C');

        // Agregar datos de productos
        $n = 1;
        foreach ($productos as $producto) {
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(10, 10, $n++, 1, 0, 'C');
            $pdf->Cell(80, 10, utf8_decode($producto->descripcionProducto), 1, 0, 'C');
            $pdf->Cell(45, 10, utf8_decode($producto->fechaVencimientoProxima == "" ? "-" : date("d/m/Y", strtotime($producto->fechaVencimientoProxima))), 1, 0, 'C');
            $pdf->Cell(35, 10, utf8_decode(number_format($producto->totalExistencia, 2, ".", ",")) . ' u', 1, 1, 'C');
        }

        // Si no hay productos
        if ($n == 1) {
            $pdf->Cell(170, 10, utf8_decode('No se encontraron productos.'), 1, 1, 'C');
        }

        // Salida del PDF
        $pdf->Output('D', 'inventario_general.pdf');
    }
}

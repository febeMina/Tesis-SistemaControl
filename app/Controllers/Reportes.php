<?php
namespace App\Controllers;
namespace App\ThirdParty;

use CodeIgniter\Controller;
//use App\ThirdParty\FPDF;
use App\Libraries\LibReporte;

class Reportes extends Controllers 
{

    public function index()
    {
        $dompdf= new Dompdf();
        $dompdf->loadHTML('<!DOCTYPE html>
      <html>
        <head>
          <style>
            table {
              width: 100%%;
              text-align: center;
            } 
          </style>
        </head>
        <body>
          <img src="%s" alt="%s" style="width: 100px;"><br>
       
          <h1>Bienvenido de nuevo a %s</h1>
          <p>Versión <b>%s</b></p>
          <p>%s</p>
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>John Doe</td>
                <td>jhon@doe.com</td>
                <td>$2,532</td>
              </tr>
              <tr>
                <td>2</td>
                <td>John Doe</td>
                <td>jhon@doe.com</td>
                <td>$712</td>
              </tr>
              <tr>
                <td>3</td>
                <td>John Doe</td>
                <td>jhon@doe.com</td>
                <td>$6,250</td>
              </tr>
              <tr>
                <td>4</td>
                <td>John Doe</td>
                <td>jhon@doe.com</td>
                <td>$8,152</td>
              </tr>
              <tr>
                <td>5</td>
                <td>John Doe</td>
                <td>jhon@doe.com</td>
                <td>$596</td>
              </tr>
              <tr>
                <td>6</td>
                <td>John Doe</td>
                <td>jhon@doe.com</td>
                <td>$1,756</td>
              </tr>
            </tbody>
          </table>
        </body>
      </html>
        ');  
        $dompdf->setPaper('A1','portrait');
        $dompdf->render();
        $dompdf->stream();
    }
}






<html>
    <head>
        <style>
            
            @page {
                margin: 0cm 0cm;
            }


            body {
                margin-top: 3cm;
                margin-left: 2cm;
                margin-right: 2cm;
                margin-bottom: 2cm;
            }

          
            header {
                position: fixed;
                top: 0cm;
                left: 0cm;
                right: 0cm;
                height: 3cm;

            }

            footer {
                position: fixed; 
                bottom: 0cm; 
                left: 0cm; 
                right: 0cm;
                height: 1.5cm;

    
                background-color: #224986;
                color: white;
                text-align: center;
                line-height: 1.5cm;
                font-family: Arial, Helvetica, sans-serif;;
            }
            p{
                font-family:  Arial, Helvetica, sans-serif;;
                text-align: center;
            }
            table {
                width: 100%;
                text-align: center;
                border-collapse: collapse;
                margin: 0 0 1em 0;
                caption-side: top;
                font-family: Arial, Helvetica, sans-serif;;
                }
                caption, td, th {
                padding: 0.3em;
                }
                tbody {
                border-top: 1px solid #000;
                border-bottom: 1px solid #000;
                }
                tbody th, tfoot th {
                border: 0;
                }
                th.name {
                width: 25%;
                }
                th.location {
                width: 20%;
                }
                th.lasteruption {
                width: 30%;
                }
                th.eruptiontype {
                width: 25%;
                }
                tfoot {
                text-align: center;
                color: #555;
                font-size: 0.8em;
                }
        </style>
    </head>
 <body>
        <!-- Define header and footer blocks before your content -->
        <header>
            <center> 
            <img src="<?= base_url('public/assets/images/reportes/LOGOESCUELA.jpg') ?>" align="center" />            
            </center>
       
        </header>

        <footer>
            Copyright &copy; CEBD <?php echo date("Y");?> 
        </footer>

        <!-- contenido pdf-->
     <main>

                <h2 style="text-align: center;"><strong><span style="font-family: Arial, Helvetica, sans-serif;">Centro Escolar &quot;Barrio Las Delicias&quot;</span></strong></h2>
            <p style="text-align: center;"><span style="font-family: Arial, Helvetica, sans-serif;">Mejicanos, San Salvador Centro, El Salvador</span></p>
            <p style="text-align: center;"><br></p>
            <p style="text-align: right;"><span style="font-family: Arial, Helvetica, sans-serif; font-size: 12px;">Fecha de Generaci&oacute;n: <?php echo date("d-m-Y h:i:s");?></span></p>
            <p style="text-align: left;"><span style="font-family: Arial, Helvetica, sans-serif;"><strong><span style="font-size: 20px;">Reporte de Donaciones</span></strong></span></p>
            <p style="text-align: left;"><br></p>


        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                    <th>Nombre Donante</th>
                    <th>Cantidad</th>
                    <th>Proyecto</th>
                    <th>Fecha</th>
                    </tr>
                </thead>
                <tbody><?php foreach ($donaciones as $donacion) : ?>
                <tr>
                    <td><?= $donacion->nombreCompleto; ?></td>
                    <td><?= $donacion->cantidad; ?></td>
                    <td><?= $donacion->nombreProyecto; ?></td>
                    <td><?= $donacion->fechaDonacion ?></td>
                </tr>
                <?php endforeach ?>
                
                </tbody>
            </table>
        </div>

        </main>
    </body>
</html>
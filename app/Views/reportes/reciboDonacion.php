<html>
    <head>
        <style>
                        body {
                    font-family: Arial, sans-serif;
                    background-color: #f9f9f9;
                    margin: 0;
                    padding: 0;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                }

                .receipt {
                    background-color: #fff;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    padding: 20px;
                    width: 80%;
                    max-width: 600px;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                }

                h1 {
                    text-align: center;
                    color: #333;
                }

                .details p {
                    margin: 10px 0;
                }

                .details p strong {
                    display: inline-block;
                    width: 200px;
                }

                .footer {
                    margin-top: 20px;
                    text-align: center;
                    font-size: 0.9em;
                    color: #555;
                }  
           

          
        </style>
    </head>
 <body>
        <!-- Define header and footer blocks before your content -->
        <header>
           
        </header>

        <footer>
            Copyright &copy; CEBD <?php echo date("Y");?> 
        </footer>

        <!-- contenido pdf-->
     <main>
     <div class="receipt">
        <h1>Recibo de Donación</h1>
        <h3>Copia Centro Escolar</h3>
        <div class="details">
        <?php foreach ($donaciones as $donacion) : ?>
            <p><strong>Nombre del Donante:</strong> <?= $donacion->nombreCompleto; ?> </p>
            <p><strong>Concepto:</strong> <?= $donacion->descripcion; ?></p>
            <p><strong>Cantidad en Letras:</strong><?= $donacion->cantidadLetras; ?></p>
            <p><strong>Cantidad:</strong> <?= $donacion->cantidad; ?></p>
            <p><strong>Proyecto al que se Asignarán Fondos:</strong> <?= $donacion->nombreProyecto; ?></p>
            <?php endforeach ?>
        </div>
        <div class="footer">
        <p>Gracias por su generosa donación.</p>
        <?=
            date_default_timezone_set('UTC');
               // Configurar el idioma en Linux (UTF-8)
               setlocale(LC_TIME, 'es_ES.UTF-8');
            
               // O en Windows
               // setlocale(LC_TIME, 'spanish');
               
               $fechaActual = time(); // Obtener la fecha actual en formato Unix
               $fechaEnEspañol = strftime("%d de %B de %Y", $fechaActual);
               ?>
            <p>Fecha: <?= $fechaEnEspañol ?> </p>
        </div>
    </div>

    <div class="receipt">
        <h1>Recibo de Donación</h1>
        <h3>Copia Donante</h3>
        <div class="details">
        <?php foreach ($donaciones as $donacion) : ?>
            <p><strong>Nombre del Donante:</strong> <?= $donacion->nombreCompleto; ?> </p>
            <p><strong>Concepto:</strong> <?= $donacion->descripcion; ?></p>
            <p><strong>Cantidad en Letras:</strong><?= $donacion->cantidadLetras; ?></p>
            <p><strong>Cantidad:</strong> <?= $donacion->cantidad; ?></p>
            <p><strong>Proyecto al que se Asignarán Fondos:</strong> <?= $donacion->nombreProyecto; ?></p>
            <?php endforeach ?>
        </div>
        <div class="footer">
            <p>Gracias por su generosa donación.</p>
            <p>Fecha: <?= date('l, j F Y'); ?> </p>
        </div>
    </div>    


        </main>
    </body>
</html>
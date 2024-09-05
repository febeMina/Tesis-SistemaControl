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
        <div class="details">
            <p><strong>Nombre del Donante:</strong> Juan Pérez</p>
            <p><strong>Concepto:</strong> Donación para el proyecto de educación</p>
            <p><strong>Cantidad en Letras:</strong> Mil Doscientos</p>
            <p><strong>Cantidad:</strong> $1,200.00</p>
            <p><strong>Proyecto al que se Asignarán Fondos:</strong> Educación en comunidades rurales</p>
        </div>
        <div class="footer">
            <p>Gracias por su generosa donación.</p>
            <p>Fecha: 3 de septiembre de 2024</p>
        </div>
    </div>
           


        </main>
    </body>
</html>
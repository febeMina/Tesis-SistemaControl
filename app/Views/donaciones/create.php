<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="background-color: #f8f9fa; border-radius: 15px;">
                <div class="card-header bg-primary text-white">
                    <h3 class="text-center">Nueva donación</h3>
                </div>
                <div class="card-body">
                    
                <!-- Mensaje de éxito -->
                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success" role="alert">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>
                    
                    <form id="createForm" action="<?= site_url('donaciones/store') ?>" method="post">
                     <!-- nuevo donante -->
                      
                     <div class="form-row"> 
                              <div class="form-group col-md-9">
                              <label for="nombre_completo" style="color: #000;"><i class="fas fa-hands-holding-circle"></i> Tipo de Donante</label>
                            <select class="form-control" id="TipoDonante" name="TipoDonante"  required>
                                        <option value="">Seleccione...</option>
                                        <option value="INTERNO">INTERNO</option>
                                        <option value="EXTERNO">EXTERNO</option>
                             </select>
                                </div>
                                <div class="form-group col-md-3">
                                <a href="<?= site_url('padres/create') ?>" class="btn btn-edit">
                                 <i class="fa-square-plus"></i> + Donante <!-- Icono de Material Design Icons -->
                                </a>
                                </div>
                        </div>    
                    
                    
                    <!-- Datos de la personas  AQUI VAMOS-->
                      <div class="form-group">
                            <label for="nombre_completo" style="color: #000;"><i class="fas fa-user"></i> Nombre responsable</label>
                            <select class="form-control" id="NombreDonante" name="NombreDonante" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($responsables as $responsable) : ?>
                                   
                                   <option value="<?= $responsable->idDatosResponsable ?>"> <?= $responsable->nombreCompleto ?></option>
                                
                                   <?php endforeach; ?>
                             </select>

                             <!-- <input type="text" class="form-control" id="nombre_completo" name="nombre_responsable" required>-->
                        </div>
                         <div class="form-row"> 
                         <div class="form-group col-md-6">
                         <label for="nombre_completo" style="color: #000;"><i class="fas fa-dollar-sign"></i> Cantidad ($)</label>
                         <input type="number" class="form-control" id="cantidad" name="cantidad" oninput="actualizarTexto()" required>
                         </div>
                         <div class="form-group col-md-6">
                         <label for="nombre_completo" style="color: #000;"><i class="fas fa-hands-holding-circle"></i> Proyectos a asignar</label>
                            <select class="form-control" id="idProyecto" name="idProyecto" required>
                                <?php foreach ($proyectos as $proyecto) : ?>
                                    <option value="<?= $proyecto->idProyectos ?>"> <?= $proyecto->nombreProyecto ?></option>
                                <?php endforeach; ?>
                             </select>
                         </div>
                           
                        </div>
                        <div class="form-group">
                            <label for="nombre_completo" style="color: #000;"><i class="fa-solid fa-money-check-dollar"></i> Cantidad en letras</label>
                            <input type="text" class="form-control" id="cantidadLetras" name="cantidadLetras" readonly required>
                        </div>

                        <div class="form-group">
                            <label for="nombre_completo" style="color: #000;"><i class="fa-solid fa-pen-to-square"></i> Concepto</label>
                            <input type="text" class="form-control" id="concepto" name="concepto" required>
                        </div>
                        <div class="form-group">
                            <label for="nombre_completo" style="color: #000;"> <i class="fa-solid fa-calendar-days"></i> Fecha de donación</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" min="<?php echo date("Y-m-d");?>" required>
                        </div>
                        <div class="form-group">
                       
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary" style="background-color: #090066;">Guardar</button>
                            <a href="<?= site_url('donaciones') ?>" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cargar jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>

        function cargarDonadoresPorTipo() {

        const tipoDonante = document.querySelector('#TipoDonante').value;

        fetch('donaciones/tipoDonador')
        .then(response => response.json())
        .then(data => {
            // Aquí puedes trabajar con los datos recibidos (por ejemplo, mostrarlos en la consola)
            console.log(data);
        
        })
        .catch(error => {
            console.error('Error al obtener datos:', error);
        });

        }

</script>
<!-- numeros a letras -->
<script>
    // Función para convertir número a letras
        function numeroALetras(numero) {
            const unidad = ['cero', 'uno', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'siete', 'ocho', 'nueve'];
            const decenas = ['diez', 'veinte', 'treinta', 'cuarenta', 'cincuenta', 'sesenta', 'setenta', 'ochenta', 'noventa'];
            const centenas = ['cien', 'doscientos', 'trescientos', 'cuatrocientos', 'quinientos', 'seiscientos', 'setecientos', 'ochocientos', 'novecientos'];

            numero = parseInt(numero);
            if (isNaN(numero)) {
                return '';
            }

            if (numero < 0 || numero > 999) {
                return 'Número fuera de rango';
            }

            let resultado = '';
            
            if (numero >= 100) {
                let c = Math.floor(numero / 100) * 100;
                resultado += centenas[c / 100 - 1];
                numero -= c;
                if (numero > 0) {
                    resultado += ' ';
                }
            }

            if (numero >= 10) {
                let d = Math.floor(numero / 10) * 10;
                resultado += decenas[d / 10 - 1];
                numero -= d;
                if (numero > 0) {
                    resultado += ' ';
                }
            }

            if (numero > 0) {
                resultado += unidad[numero];
            }

            return resultado;
        }

        // Función para actualizar el campo de texto
        function actualizarTexto() {
            const numero = document.getElementById('cantidad').value;
            const letras = numeroALetras(numero);
            document.getElementById('cantidadLetras').value = letras;
        }
</script>

<!-- Tu script JavaScript -->
<script>

    // Esperar a que se cargue el documento
    $(document).ready(function() {
        // Escuchar el evento submit del formulario
        $('#createForm').submit(function(event) {
            // Evitar que el formulario se envíe automáticamente
            event.preventDefault();

            // Enviar la solicitud AJAX para guardar el maestro
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    console.log(response); // Verificar la respuesta en la consola del navegador
                    if (response.success) {
                        // Mostrar el alert de confirmación
                        var alert = '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">';
                        alert += 'La donación ha sido registrada exitosamente.';
                        alert += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                        alert += '<span aria-hidden="true">&times;</span>';
                        alert += '</button>';
                        alert += '</div>';
                        $(alert).insertBefore($('#createForm'));
                    }
                }
            });
        });
    });


    
</script>



<?= $this->endSection() ?>
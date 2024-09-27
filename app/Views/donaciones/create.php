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
                               <!-- Tipo de Donante -->
                                            
                                                <label style="color: #000;"><i class="fas fa-hands-holding-circle"></i> Tipo de Donante</label>
                                                <div>
                                                    <label style="color: #000;"> <input type="radio" name="TipoDonante" value="INTERNO" required>  INTERNO </label>
                                                    <label style="color: #000;"><input type="radio" name="TipoDonante" value="EXTERNO" required>  EXTERNO </label>
                                                </div>
                                    </div>
                                <div class="form-group col-md-3">
                                <a href="<?= site_url('padres/create') ?>" class="btn btn-edit">
                                 <i class="fa-square-plus"></i> + Donante <!-- Icono de Material Design Icons -->
                                </a>
                                </div>
                        </div>    
                    
                    
                    <!-- Datos de la personas  AQUI VAMOS-->
                    
                          <div class="form-group">
                            <label for="nombre_completo" style="color: #000;"><i class="fas fa-user"></i> Nombre Responsable</label>
                            <select class="form-control" id="NombreDonante" name="NombreDonante" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($responsables as $responsable) : ?>
                                   
                                   <option value="<?= $responsable->idDatosResponsable ?>" class="<?= $responsable->tipoAsociado ?>"> <?= $responsable->nombreCompleto ?></option>
                                
                                   <?php endforeach; ?>
                             </select>

                             <!-- <input type="text" class="form-control" id="nombre_completo" name="nombre_responsable" required>-->
                        </div>
                         <div class="form-row"> 
                         <div class="form-group col-md-6">
                         <label for="nombre_completo" style="color: #000;"><i class="fas fa-dollar-sign"></i> Cantidad ($)</label>
                         <input type="number" class="form-control" id="cantidad" name="cantidad" oninput="validarInput(event)"   required>
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
                            <input type="text" class="form-control" id="descripcion" name="descripcion" required>
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


<!-- numeros positivos -->

<script>
  function validarInput(event) {
            const input = event.target.value;
            // Verificar si es un número y no es negativo
            if (input < 0 || input.includes('e') || isNaN(input)) {
                alert("Por favor, introduce un número positivo sin la letra 'e'.");
                event.target.value = ''; // Limpiar el input si es inválido
            } else {
                // Si es válido, llamar a la función para convertir a letras
                const numeroEnLetras = numeroALetras(input);
            
                const numero = document.getElementById('cantidad').value;
                const letras = numeroALetras(numero);
                document.getElementById('cantidadLetras').value = letras;
            }
        }

        function numeroALetras(numero) {
            // Función simple para convertir números a letras (solo para números enteros)
            const unidades = [
                '', 'uno', 'dos', 'tres', 'cuatro', 'cinco', 
                'seis', 'siete', 'ocho', 'nueve', 'diez', 
                'once', 'doce', 'trece', 'catorce', 'quince', 
                'dieciséis', 'diecisiete', 'dieciocho', 'diecinueve'
            ];
            const decenas = [
                '', '', 'veinte', 'treinta', 'cuarenta', 'cincuenta', 
                'sesenta', 'setenta', 'ochenta', 'noventa'
            ];
            const centenas = [
                '', 'ciento', 'doscientos', 'trescientos', 
                'cuatrocientos', 'quinientos', 'seiscientos', 
                'setecientos', 'ochocientos', 'novecientos'
            ];
            
            let letras = '';
            const num = parseInt(numero);

            if (num < 20) {
                letras = unidades[num];
            } else if (num < 100) {
                letras = decenas[Math.floor(num / 10)] + (num % 10 !== 0 ? ' y ' + unidades[num % 10] : '');
            } else if (num < 1000) {
                letras = centenas[Math.floor(num / 100)] + (num % 100 !== 0 ? ' ' + numeroALetras(num % 100) : '');
            } else {
                letras = 'Número demasiado grande';
            }

            return letras;
        }

       
</script>

<!-- Filtrado de tipo de datos -->
<script>
    document.querySelectorAll('input[name="TipoDonante"]').forEach((radio) => {
        radio.addEventListener('change', function() {
            let selectedType = this.value;
            let options = document.querySelectorAll('#NombreDonante option');
            options.forEach(option => {
                if (option.value) {
                    option.style.display = option.classList.contains(selectedType) ? 'block' : 'none';
                }
            });
            // Reset selection
            document.getElementById('NombreDonante').value = '';
        });
    });
</script>


<!-- Tu script JavaScript -->
<script>
    // Esperar a que se cargue el documento
    $(document).ready(function() {
        // Escuchar el evento submit del formulario
        $('#createForm').submit(function(event) {
            // Evitar que el formulario se envíe automáticamente
            event.preventDefault();

            // Guardar la referencia al formulario
            var form = $(this);

            // Enviar la solicitud AJAX para  guardar el usuario
            // JavaScript
            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize(),
                success: function(response) {
                    console.log(response); // Verificar la respuesta en la consola del navegador
                    if (response && typeof response.success !== 'undefined' && response.success) {
                        // Mostrar el alert de confirmación
                        var successAlert = '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">';
                        successAlert += 'El usuario ha sido creado exitosamente.';
                        successAlert += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                        successAlert += '<span aria-hidden="true">&times;</span>';
                        successAlert += '</button>';
                        successAlert += '</div>';
                        $(successAlert).insertBefore(form);

                        // Redirigir al índice de usuarios después de 1 segundo
                        setTimeout(function() {
                            window.location.href = "<?= base_url('public/donaciones') ?>";
                        }, 1000);
                    } else {
                        // Mostrar el mensaje de error si existe
                        console.error('Error: ' + (response && response.error ? response.error : 'undefined'));
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error); // Imprimir cualquier error en la consola
                }
            });

        });
    });
</script>



<?= $this->endSection() ?>
<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="background-color: #f8f9fa; border-radius: 15px;">
                <div class="card-header bg-primary text-white">
                    <h3 class="text-center">Nuevo Personal magisterial</h3>
                </div>
                <div class="card-body">
                    <!-- Mensaje de éxito -->
                    <div id="successMessage"></div>
                    <form id="createForm" action="<?= site_url('maestros/store') ?>" method="post">
                        <div class="form-group">
                            <label for="nombre_completo" style="color: #000;"><i class="fas fa-user"></i> Nombre Completo</label>
                            <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" required>
                        </div>
                        <div class="form-group">
                            <label for="nip" style="color: #000;"><i class="fas fa-key"></i> NIP</label>
                            <input type="text" class="form-control" id="nip" name="nip" required>
                        </div>
                        <div class="form-group">
                            <label for="escalafon" style="color: #000;"><i class="fas fa-user-tie"></i> Escalafón</label>
                            <input type="text" class="form-control" id="escalafon" name="escalafon" required>
                        </div>
                        <div class="form-group">
                            <label for="fecha_ingreso" style="color: #000;"><i class="far fa-calendar-alt"></i> Fecha de Ingreso</label>
                            <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" required>
                        </div>
                        <div class="form-group">
                            <label for="estado" style="color: #000;"><i class="fas fa-check-circle"></i> Estado</label>
                            <select class="form-control" id="estado" name="estado" required>
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tipo" style="color: #000;"><i class="fas fa-user-tie"></i> Tipo</label>
                            <select class="form-control" id="tipo" name="tipo" required>
                                <option value="Docente">Docente</option>
                                <option value="Administrativo">Administrativo</option>
                            </select>
                        </div>
                        <div class="form-group" id="rol-group" style="display: none;">
                            <label for="rol" style="color: #000;"><i class="fas fa-briefcase"></i> Cargo</label>
                            <select class="form-control" id="rol" name="rol">
                                <option value="Director">Director</option>
                                <option value="Subdirector">Subdirector</option>
                                <option value="Secretaria">Secretaria</option>
                                <option value="Contador">Contador</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary" style="background-color: #090066;">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cargar jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Tu script JavaScript -->
<script>
    // Esperar a que se cargue el documento
    $(document).ready(function() {
        // Mostrar u ocultar el campo de rol según el tipo seleccionado
        $('#tipo').change(function() {
            var tipo = $(this).val();
            if (tipo === 'Administrativo') {
                $('#rol-group').show();
            } else {
                $('#rol-group').hide();
            }
        });

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
                        alert += response.message;
                        alert += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                        alert += '<span aria-hidden="true">&times;</span>';
                        alert += '</button>';
                        alert += '</div>';
                        $('#successMessage').html(alert);
                        // Redirigir a la vista de índice después de un breve retraso
                        setTimeout(function() {
                            window.location.href = response.redirect;
                        }, 1500); // Retraso de 1.5 segundos
                    }
                }
            });
        });
    });
</script>

<?= $this->endSection() ?>

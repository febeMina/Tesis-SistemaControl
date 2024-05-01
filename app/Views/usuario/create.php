<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="background-color: #f8f9fa; border-radius: 15px;">
                <div class="card-header bg-primary text-white">
                    <h3 class="text-center">Nuevo Usuario</h3>
                </div>
                <div class="card-body">
                    <form id="createForm" action="<?= site_url('usuario/store') ?>" method="post">
                        <div class="form-group">
                            <label for="usuario" style="color: #000;"><i class="fas fa-user"></i> Usuario</label>
                            <input type="text" class="form-control" id="usuario" name="usuario" required>
                        </div>
                        <div class="form-group">
                            <label for="clave" style="color: #000;"><i class="fas fa-lock"></i> Clave</label>
                            <input type="password" class="form-control" id="clave" name="clave" required>
                        </div>
                        <div class="form-group">
                            <label for="idDocente" style="color: #000;"><i class="fas fa-user"></i> Docente</label>
                            <select class="form-control" id="idDocente" name="idDocente" required>
                                <?php foreach ($docentes as $docente) : ?>
                                    <option value="<?= $docente->idDocente ?>"><?= $docente->nombre_completo ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="idRol" style="color: #000;"><i class="fas fa-user"></i> Rol</label>
                            <select class="form-control" id="idRol" name="idRol" required>
                                <?php foreach ($roles as $rol) : ?>
                                    <option value="<?= $rol->idRol ?>"><?= $rol->nombreRol ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="estado" style="color: #000;"><i class="fas fa-check-circle"></i> Estado</label>
                            <select class="form-control" id="estado" name="estado" required>
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
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
        // Escuchar el evento submit del formulario
        $('#createForm').submit(function(event) {
            // Evitar que el formulario se envíe automáticamente
            event.preventDefault();

            // Guardar la referencia al formulario
            var form = $(this);

            // Enviar la solicitud AJAX para guardar el usuario
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
                            window.location.href = "<?= base_url('public/usuario') ?>";
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
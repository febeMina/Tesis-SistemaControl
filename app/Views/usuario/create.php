<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="background-color: #f8f9fa; border-radius: 15px;">
                <div class="card-header bg-primary text-white">
                    <h3 class="text-center">Nuevo usuario</h3>
                </div>
                <div class="card-body">
                    <!-- Mostrar errores -->
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>
                    
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
                            <label for="idRol" style="color: #000;"><i class="fas fa-user"></i> Rol</label>
                            <select class="form-control" id="idRol" name="idRol" required>
                            <option value="" disabled selected>Selecciona</option>
                                <?php foreach ($roles as $rol) : ?>
                                    <option value="<?= $rol->idRol ?>"><?= $rol->nombreRol ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="estado" style="color: #000;"><i class="fas fa-check-circle"></i> Estado</label>
                            <select class="form-control" id="estado" name="estado" required>
                            <option value="" disabled selected>Selecciona</option>
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary" style="background-color: #090066;">Guardar</button>
                            <a href="<?= base_url('public/usuario') ?>" class="btn btn-secondary">Cancelar</a>
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
    $(document).ready(function() {
        // Escuchar el evento submit del formulario
        $('#createForm').submit(function(event) {
            event.preventDefault(); // Evitar que el formulario se envíe automáticamente

            var form = $(this);

            // Enviar la solicitud AJAX para guardar el usuario
            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize(),
                success: function(response) {
                    if (response && typeof response.success !== 'undefined' && response.success) {
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
                        // Mostrar mensaje de error si el usuario ya existe
                        var errorAlert = '<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">';
                        errorAlert += 'Error: ' + ('El usuario ya existe.');
                        errorAlert += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                        errorAlert += '<span aria-hidden="true">&times;</span>';
                        errorAlert += '</button>';
                        errorAlert += '</div>';
                        $(errorAlert).insertBefore(form);
                    }
                },
                error: function(xhr, status, error) {
                    var errorAlert = '<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">';
                    errorAlert += 'Error: ' + xhr.responseText;
                    errorAlert += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                    errorAlert += '<span aria-hidden="true">&times;</span>';
                    errorAlert += '</button>';
                    errorAlert += '</div>';
                    $(errorAlert).insertBefore(form);
                }
            });
        });
    });
</script>

<?= $this->endSection() ?>

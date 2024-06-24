<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="background-color: #f8f9fa; border-radius: 15px;">
                <div class="card-header bg-primary text-white" style="border-radius: 15px 15px 0 0; background-color: #090066 !important;">
                    <h3 class="text-center">Editar Usuario</h3>
                </div>
                <div class="card-body">
                    <form id="updateForm" action="<?= site_url('usuario/update/' . $usuario->idUsuarios) ?>" method="post">
                        <div class="form-group">
                            <label for="usuario" style="color: #000;"><i class="fas fa-user"></i> Usuario</label>
                            <input type="text" class="form-control" id="usuario" name="usuario" value="<?= $usuario->usuario ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="clave" style="color: #000;"><i class="fas fa-lock"></i> Clave</label>
                            <input type="password" class="form-control" id="clave" name="clave">
                            <small class="text-muted">Deje este campo en blanco para mantener la contraseña actual.</small>
                        </div>
                        <div class="form-group">
                            <label for="idDocente" style="color: #000;"><i class="fas fa-user"></i> Docente</label>
                            <select class="form-control" id="idDocente" name="idDocente" required>
                                <?php foreach ($docentes as $docente) : ?>
                                    <option value="<?= $docente->idDocente ?>" <?= ($docente->idDocente == $usuario->idDocente) ? 'selected' : '' ?>>
                                        <?= $docente->nombre_completo ?>
                                    </option>
                                <?php endforeach; ?> 
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="idRol" style="color: #000;"><i class="fas fa-user"></i> Rol</label>
                            <select class="form-control" id="idRol" name="idRol" required>
                                <?php foreach ($roles as $rol) : ?>
                                    <option value="<?= $rol->idRol ?>" <?= ($rol->idRol == $usuario->idRol) ? 'selected' : '' ?>>
                                        <?= $rol->nombreRol ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="estado" style="color: #000;"><i class="fas fa-check-circle"></i> Estado</label>
                            <select class="form-control" id="estado" name="estado" required>
                                <option value="Activo" <?= ($usuario->estado == 'Activo') ? 'selected' : '' ?>>Activo</option>
                                <option value="Inactivo" <?= ($usuario->estado == 'Inactivo') ? 'selected' : '' ?>>Inactivo</option>
                            </select>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary" style="background-color: #090066;">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Esperar a que se cargue el documento
    $(document).ready(function() {
        // Escuchar el evento submit del formulario
        $('#updateForm').submit(function(event) {
            // Evitar que el formulario se envíe automáticamente
            event.preventDefault();

            // Enviar la solicitud AJAX para actualizar el usuario
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    console.log(response); // Verificar la respuesta en la consola del navegador
                    if (response.success) {
                        // Redirigir al índice de usuario
                        window.location.href = "<?= site_url('usuario') ?>";
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
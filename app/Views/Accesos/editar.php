<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="background-color: #f8f9fa; border-radius: 15px;">
                <div class="card-header bg-primary text-white" style="border-radius: 15px 15px 0 0; background-color: #090066 !important;">
                    <h3 class="text-center">Cambiar Tipo de Acceso</h3>
                </div>
                <div class="card-body"> 
                    <form id="updateForm" action="<?= site_url('acceso/update/' . $usuario->idUsuarios) ?>" method="post">
                        <!-- Agrega un campo oculto para almacenar el ID del tipo de permiso -->
                        <input type="hidden" name="id" value="<?= $usuario->idUsuarios ?>">
                        <div class="form-group">
                            <label for="nombre" style="color: #000;"><i class="fas fa-user"></i> Nombre de Usuario</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $usuario->usuario ?>" required>
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
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary" style="background-color: #090066;">Actualizar</button>
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
        $('#updateForm').submit(function(event) {
            event.preventDefault();

            $.ajax({
                // Asegúrate de que la URL esté construida correctamente
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(), // Serializar el formulario
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        window.location.href = "<?= site_url('tipo_permiso') ?>";
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });
    });
</script>

<?= $this->endSection() ?>

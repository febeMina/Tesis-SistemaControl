<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="background-color: #f8f9fa; border-radius: 15px;">
                <div class="card-header bg-primary text-white" style="border-radius: 15px 15px 0 0; background-color: #090066 !important;">
                    <h3 class="text-center">Editar Tipo de Permiso</h3>
                </div>
                <div class="card-body">
                    <form id="updateForm" action="<?= site_url('tipo_permiso/update/'.$tipo_permiso->idTipoPermiso) ?>" method="post">
                        <div class="form-group">
                            <label for="nombre" style="color: #000;"><i class="fas fa-user"></i> Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $tipo_permiso->nombre ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="cantidad_dias" style="color: #000;"><i class="fas fa-key"></i> Cantidad de Días</label>
                            <input type="number" class="form-control" id="cantidad_dias" name="cantidad_dias" value="<?= $tipo_permiso->cantidad_dias ?>" required>
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
        // Escuchar el evento submit del formulario
        $('#updateForm').submit(function(event) {
            // Evitar que el formulario se envíe automáticamente
            event.preventDefault();

            // Enviar la solicitud AJAX para actualizar el tipo de permiso
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    console.log(response); // Verificar la respuesta en la consola del navegador
                    if (response.success) {
                        // Redirigir al índice de tipos de permiso
                        window.location.href = "<?= site_url('tipo_permiso') ?>";
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

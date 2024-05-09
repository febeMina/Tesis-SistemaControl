<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
        <div class="card" style="background-color: #f8f9fa; border-radius: 15px;">
                <div class="card-header bg-primary text-white" style="border-radius: 15px 15px 0 0; background-color: #090066 !important;">
                    <h3 class="text-center">Editar Maestro</h3>
                </div>
                <div class="card-body">
                <!-- Mensaje de éxito -->
                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success" role="alert">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>
                <form id="updateForm" action="<?= site_url('maestros/update/'. ($maestro['idDocente'] ?? '')) ?>" method="post">
                        <div class="form-group">
                            <label for="nombre_completo" style="color: #000;"><i class="fas fa-user"></i> Nombre Completo</label>
                            <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" value="<?= $maestro['nombre_completo'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="nip" style="color: #000;"><i class="fas fa-key"></i> NIP</label>
                            <input type="text" class="form-control" id="nip" name="nip" value="<?= $maestro['nip'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="escalafon" style="color: #000;"><i class="fas fa-user-tie"></i> Escalafón</label>
                            <input type="text" class="form-control" id="escalafon" name="escalafon" value="<?= $maestro['escalafon'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="fecha_ingreso" style="color: #000;"><i class="far fa-calendar-alt"></i> Fecha de Ingreso</label>
                            <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" value="<?= $maestro['fecha_ingreso'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="estado" style="color: #000;"><i class="fas fa-check-circle"></i> Estado</label>
                            <select class="form-control" id="estado" name="estado" required>
                                <option value="Activo" <?= ($maestro['estado'] == 'Activo') ? 'selected' : '' ?>>Activo</option>
                                <option value="Inactivo" <?= ($maestro['estado'] == 'Inactivo') ? 'selected' : '' ?>>Inactivo</option>
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
        // Escuchar el evento submit del formulario
        $('#updateForm').submit(function(event) {
            // Evitar que el formulario se envíe automáticamente
            event.preventDefault();

            // Enviar la solicitud AJAX para actualizar el maestro
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    console.log(response); // Verificar la respuesta en la consola del navegador
                    if (response.success) {
                        // Mostrar el alert de confirmación
                        var alert = '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">';
                        alert += 'El maestro ha sido actualizado exitosamente.';
                        alert += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                        alert += '<span aria-hidden="true">&times;</span>';
                        alert += '</button>';
                        alert += '</div>';
                        $(alert).insertBefore($('#updateForm'));
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

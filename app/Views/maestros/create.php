<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h1 class="mt-4">Agregar Nuevo Maestro</h1>
        <button href="<?= site_url('/') ?>" class="btn btn-primary">Volver</button>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <!-- Formulario para agregar un nuevo maestro -->
            <form action="<?= site_url('store') ?>" method="post">
                <div class="form-group">
                    <label for="nombre_completo">Nombre Completo</label>
                    <input type="text" class="form-control form-control-md" id="nombre_completo" name="nombre_completo" required>
                </div>
                <div class="form-group">
                    <label for="nip">NIP</label>
                    <input type="text" class="form-control form-control-md" id="nip" name="nip" required>
                </div>
                <div class="form-group">
                    <label for="escalafon">Escalafón</label>
                    <input type="text" class="form-control form-control-md" id="escalafon" name="escalafon" required>
                </div>
                <div class="form-group">
                    <label for="fecha_ingreso">Fecha de Ingreso</label>
                    <input type="date" class="form-control form-control-md" id="fecha_ingreso" name="fecha_ingreso" required>
                </div>
                <div class="form-group">
                    <label for="estado">Estado</label>
                    <select class="form-control form-control-md" id="estado" name="estado" required>
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Agregar Maestro</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de confirmación -->
<div class="modal fade" id="confirmacionModal" tabindex="-1" role="dialog" aria-labelledby="confirmacionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmacionModalLabel">Confirmación de Creación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                El maestro ha sido creado exitosamente.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
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
        $('form').submit(function(event) {
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
                        // Mostrar la modal de confirmación
                        $('#confirmacionModal').modal('show');
                    }
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
    <!-- Enlace a los estilos de la plantilla de Corona Admin -->
    <link href="<?= base_url('/assets/vendors/mdi/css/materialdesignicons.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('/assets/vendors/css/vendor.bundle.base.css') ?>" rel="stylesheet">
    <!-- Estilos personalizados -->
    <link href="<?= base_url('/assets/css/style.css') ?>" rel="stylesheet">
    <!-- Icono de la página -->
    <link rel="shortcut icon" href="<?= base_url('assets/images/favicon.png') ?>" />
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <!-- Enlace a los archivos JavaScript -->
    <script src="<?= base_url('/assets/vendors/js/vendor.bundle.base.js') ?>"></script>
    <!-- Scripts personalizados -->
    <script src="<?= base_url('/assets/js/off-canvas.js') ?>"></script>
    <script src="<?= base_url('/assets/js/hoverable-collapse.js') ?>"></script>
    <script src="<?= base_url('/assets/js/misc.js') ?>"></script>
    <script src="<?= base_url('/assets/js/settings.js') ?>"></script>
    <script src="<?= base_url('/assets/js/todolist.js') ?>"></script>
    <!-- Scripts adicionales para la plantilla -->
    <!-- Por ejemplo, si hay algún script específico de la plantilla Corona Admin -->
<?= $this->endSection() ?>

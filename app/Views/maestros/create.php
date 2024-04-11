<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<style>
    body {
        height: 100vh;
        overflow: hidden;
        margin: 0;
        padding: 0;
    }

    .form-control {
        background-color: #dee2e6;
        color: #000;
        border-radius: 10px;
        padding: 10px;
        border: 2px solid #000;
        position: relative;
    }

    .form-control:focus {
        background-color: #dee2e6;
        border-color: #ff7f0f;
        outline: none;
        box-shadow: 0 0 0 2px #ff7f0f;
    }

    .btn-primary {
        border-radius: 15px;
        padding: 12px 30px; /* Ajustar el tamaño del padding para hacer el botón más grande */
        font-size: 16px; /* Ajustar el tamaño de la fuente */
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="background-color: #f8f9fa; border-radius: 15px;">
            <div class="card-header bg-primary text-white" style="border-radius: 15px 15px 0 0; background-color: #090066 !important;">
                <h3 class="text-center">Nuevo Maestro</h3>
            </div>
                <div class="card-body">
                    <form action="<?= site_url('maestros/store') ?>" method="post">
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
                        // Mostrar el alert de confirmación
                        var alert = '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">';
                        alert += 'El maestro ha sido creado exitosamente.';
                        alert += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                        alert += '<span aria-hidden="true">&times;</span>';
                        alert += '</button>';
                        alert += '</div>';
                        $(alert).insertBefore($('form'));
                    }
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>

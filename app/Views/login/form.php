<?= $this->extend('layouts/loginUser') ?>

<?= $this->section('content') ?>

<div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="row w-100 mx-0 custom-container">
            <div class="col-lg-4 mx-auto custom-form">
                <div class="logo-container d-flex justify-content-center mb-3">
                    <img src="<?= base_url('public/assets/images/img_cebd/logo.cebd.jpg') ?>" class="rounded-logo">
                </div>
                <h2 class="custom-title">CENTRO ESCOLAR BARRIO LAS DELICIAS</h2>
                <h3 class="small-title">San Salvador, Mejicanos</h3>
                <!-- Formulario de inicio de sesión -->
                <form class="pt-3">
                    <div class="form-group">
                        <label for="username">Usuario</label>
                        <input type="text" class="form-control form-control-lg" name="username" id="username" placeholder="Usuario" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" class="form-control form-control-lg" name="password" id="password" placeholder="Contraseña" required>
                    </div>
                    <div class="mt-3">
                        <button id="loginBtn" type="button" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">INICIAR SESIÓN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de error -->
<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white" style="background-color: #225195 !important;">
                <h5 class="modal-title" id="errorModalLabel">ALERTA</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="errorModalBody" style="color: #333;"></p> <!-- Establecer color de texto adecuado -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Incluir CSS personalizado para el modal -->
<style>
    .modal-content {
        background-color: #f8f9fa; /* Color de fondo personalizado */
        border: none; /* Eliminar borde */
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1); /* Sombra personalizada */
    }

    .modal-footer {
        border-top: none; /* Eliminar borde superior del pie de página */
    }
</style>

<script src="<?= base_url('public/assets/js/login/form/form.js') ?>"></script>
<script>
    $(document).ready(function () {
        $('#loginBtn').click(function () {
            var username = $('#username').val();
            var password = $('#password').val();

            $.ajax({
                url: '<?= base_url('public/login/signIn') ?>',
                type: 'POST',
                dataType: 'json',
                data: {
                    username: username,
                    password: password
                },
                success: function (response) {
                    if (response.status) {
                        // Redirigir al usuario después de iniciar sesión correctamente
                        window.location.href = '<?= base_url('public/home') ?>';
                    } else {
                        // Mostrar modal de error con el mensaje correspondiente
                        $('#errorModalBody').text(response.message);
                        $('#errorModal').modal('show');
                    }
                },
                error: function () {
                    // Mostrar modal de error genérico en caso de fallo de la petición AJAX
                    $('#errorModalBody').text('Error de conexión. Inténtalo de nuevo más tarde.');
                    $('#errorModal').modal('show');
                }
            });
        });
    });
</script>

<?= $this->endSection() ?>

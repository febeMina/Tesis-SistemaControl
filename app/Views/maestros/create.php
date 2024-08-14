<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="background-color: #f8f9fa; border-radius: 15px;">
                <div class="card-header bg-primary text-white">
                    <h3 class="text-center">Nuevo Personal Magisterial</h3>
                </div>
                <div class="card-body">

                    <!-- Mensaje de éxito -->
                    <?php if(session()->has('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                            <?= session('success') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Mensaje de error -->
                    <?php if(session()->has('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                            <?= session('error') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>
                    
                    <div id="errorMessage"></div>
                    
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
                            <label for="cargo" style="color: #000;"><i class="fas fa-briefcase"></i> Cargo</label>
                            <select class="form-control" id="cargo" name="cargo">
                                <?php foreach ($cargos as $key => $value): ?>
                                    <option value="<?= esc($key); ?>">
                                        <?= esc($value); ?>
                                    </option>
                                <?php endforeach; ?>
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
$(document).ready(function() {
    $('#tipo').change(function() {
        var tipo = $(this).val();
        if (tipo === 'Administrativo') {
            $('#rol-group').show();
        } else {
            $('#rol-group').hide();
            $('#cargo').val(''); // Limpiar el valor de cargo si se oculta
        }
    });

    $('#createForm').submit(function(event) {
        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    var alert = '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">';
                    alert += response.message;
                    alert += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                    alert += '<span aria-hidden="true">&times;</span>';
                    alert += '</button>';
                    alert += '</div>';
                    $('#successMessage').html(alert);
                    
                    setTimeout(function() {
                        window.location.href = response.redirect;
                    }, 1500);
                } else {
                    var alert = '<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">';
                    $.each(response.error, function(key, value) {
                        alert += value + '<br>';
                    });
                    alert += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                    alert += '<span aria-hidden="true">&times;</span>';
                    alert += '</button>';
                    alert += '</div>';
                    $('#errorMessage').html(alert);
                }
            }
        });
    });
});
</script>

<?= $this->endSection() ?>

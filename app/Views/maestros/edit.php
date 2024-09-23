<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="background-color: #f8f9fa; border-radius: 15px;">
                <div class="card-header bg-primary text-white" style="border-radius: 15px 15px 0 0; background-color: #090066 !important;">
                    <h3 class="text-center">Editar maestro</h3>
                </div>
                <div class="card-body">

                    <!-- Mensaje de éxito -->
                    <?php if (session()->getFlashdata('message')): ?>
                        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                            <?= session()->getFlashdata('message') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <!-- Mensaje de error -->
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                            <?= session()->getFlashdata('error') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <form id="updateForm" action="<?= site_url('maestros/update/' . $docente['idDocente']) ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <label for="nombreCompleto" style="color: #000;"><i class="fas fa-user"></i> Nombre completo</label>
                            <input type="text" class="form-control" id="nombreCompleto" name="nombreCompleto" value="<?= esc($docente['nombreCompleto']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="nip" style="color: #000;"><i class="fas fa-key"></i> NIP</label>
                            <input type="text" class="form-control" id="nip" name="nip" value="<?= esc($docente['nip']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="escalafon" style="color: #000;"><i class="fas fa-user-tie"></i> Escalafón</label>
                            <input type="text" class="form-control" id="escalafon" name="escalafon" value="<?= esc($docente['escalafon']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="fechaIngreso" style="color: #000;"><i class="far fa-calendar-alt"></i> Fecha de ingreso</label>
                            <input type="date" class="form-control" id="fechaIngreso" name="fechaIngreso" value="<?= esc($docente['fechaIngreso']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="estado" style="color: #000;"><i class="fas fa-check-circle"></i> Estado</label>
                            <select class="form-control" id="estado" name="estado" required>
                                <option value="Activo" <?= $docente['estado'] == 'Activo' ? 'selected' : '' ?>>Activo</option>
                                <option value="Inactivo" <?= $docente['estado'] == 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tipo" style="color: #000;"><i class="fas fa-user-tie"></i> Tipo</label>
                            <select class="form-control" id="tipo" name="tipo" required>
                                <option value="" disabled>Selecciona</option>
                                <option value="Docente" <?= $docente['tipo'] == 'Docente' ? 'selected' : '' ?>>Docente</option>
                                <option value="Administrativo" <?= $docente['tipo'] == 'Administrativo' ? 'selected' : '' ?>>Administrativo</option>
                            </select>
                        </div>

                        <div class="form-group" id="rol-group" style="<?= $docente['tipo'] == 'Administrativo' ? 'display: block;' : 'display: none;' ?>">
                            <label for="cargo" style="color: #000;"><i class="fas fa-briefcase"></i> Cargo</label>
                            <select class="form-control" id="cargo" name="cargo">
                                <option value="" disabled>Selecciona</option>
                                <?php foreach ($cargos as $key => $value): ?>
                                    <option value="<?= esc($key); ?>" <?= $key == $docente['cargo'] ? 'selected' : '' ?>>
                                        <?= esc($value); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group" id="grado-group" style="<?= $docente['tipo'] == 'Docente' ? 'display: block;' : 'display: none;' ?>">
                            <label for="grado" style="color: #000;"><i class="fas fa-graduation-cap"></i> Grado</label>
                            <select class="form-control" id="grado" name="idGrado">
                                <option value="" disabled>Selecciona</option>
                                <?php foreach ($grados as $grado): ?>
                                    <?php if ($grado['estado'] === 'Activo'): ?>
                                        <option value="<?= $grado['idGrado'] ?>" <?= $grado['idGrado'] == $docente['idGrado'] ? 'selected' : '' ?>>
                                            <?= esc($grado['nombre']) ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary" style="background-color: #090066;">Actualizar</button>
                            <a href="<?= base_url('public/maestros') ?>" class="btn btn-secondary">Cancelar</a>
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
            $('#grado-group').hide();
            $('#grado').val('');
        } else if (tipo === 'Docente') {
            $('#rol-group').hide();
            $('#cargo').val('');
            $('#grado-group').show();
        } else {
            $('#rol-group').hide();
            $('#grado-group').hide();
            $('#cargo').val('');
            $('#grado').val('');
        }
    });

    // Preseleccionar el grado si el tipo es Docente
    if ($('#tipo').val() === 'Docente') {
        $('#grado-group').show();
    } else {
        $('#grado-group').hide();
    }

    $('#updateForm').submit(function(event) {
        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
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
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error(textStatus, errorThrown);
            }
        });
    });
});
</script>

<?= $this->endSection() ?>

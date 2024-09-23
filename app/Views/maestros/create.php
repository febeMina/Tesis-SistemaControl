<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="background-color: #f8f9fa; border-radius: 15px;">
                <div class="card-header bg-primary text-white" style="border-radius: 15px 15px 0 0; background-color: #090066 !important;">
                    <h3 class="text-center">Nuevo personal magisterial</h3>
                </div>
                <div class="card-body">
                    
                    <!-- Mensaje de éxito -->
                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success">
                            <?= session()->getFlashdata('success'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (session()->getFlashdata('error')) : ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error'); ?>
                        </div>
                    <?php endif; ?>
                    
                    
                    <div id="errorMessage"></div>
                    
                    <form id="createForm" action="<?= site_url('maestros/store') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <label for="nombreCompleto" style="color: #000;"><i class="fas fa-user"></i> Nombre completo</label>
                            <input type="text" class="form-control" id="nombreCompleto" name="nombreCompleto" required>
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
                            <label for="fechaIngreso" style="color: #000;"><i class="far fa-calendar-alt"></i> Fecha de ingreso</label>
                            <input type="date" class="form-control" id="fechaIngreso" name="fechaIngreso" required>
                        </div>
                        <div class="form-group">
                            <label for="estado" style="color: #000;"><i class="fas fa-check-circle"></i> Estado</label>
                            <select class="form-control" id="estado" name="estado" required>
                            <option value="" disabled selected>Selecciona</option>
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tipo" style="color: #000;"><i class="fas fa-user-tie"></i> Tipo</label>
                            <select class="form-control" id="tipo" name="tipo" required>
                                <option value="" disabled selected>Selecciona</option>
                                <option value="Docente">Docente</option>
                                <option value="Administrativo">Administrativo</option>
                            </select>
                        </div>
                        
                        <div class="form-group" id="rol-group" style="display: none;">
                            <label for="cargo" style="color: #000;"><i class="fas fa-briefcase"></i> Cargo</label>
                            <select class="form-control" id="cargo" name="cargo">
                                <option value="" disabled selected>Selecciona</option>
                                
                                <?php foreach ($cargos as $key => $value): ?>
                                    <option value="<?= esc($key); ?>">
                                        <?= esc($value); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group" id="gradosContainer" style="display:none;">
                            <label for="idGrados" style="color: #000;">Grado(s)</label>
                            <select name="idGrado" class="form-control">
                            <option value="" disabled selected>Selecciona</option>
                                <?php foreach ($grados as $grado): ?>
                                    <option value="<?= $grado['idGrado']; ?>"><?= $grado['nombre']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary" style="background-color: #090066;">Guardar</button>
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
            $('#gradosContainer').hide();
            $('#idGrados').val('');
        } else if (tipo === 'Docente') {
            $('#rol-group').hide();
            $('#gradosContainer').show();
        } else {
            $('#rol-group').hide();
            $('#gradosContainer').hide();
            $('#cargo').val('');
            $('#idGrados').val('');
        }
    });

    $('#createForm').submit(function(event) {
    event.preventDefault();
    $.ajax({
        url: $(this).attr('action'),
        type: $(this).attr('method'),
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                window.location.href = '<?= base_url('public/maestros') ?>'; // Redirige a la ruta correcta
 // Redirige en caso de éxito
            } else {
                // Mostrar mensaje de error
                $('#errorMessage').html('<div class="alert alert-danger">' + response.error + '</div>');
            }
        },
        error: function(xhr, status, error) {
            $('#errorMessage').html('<div class="alert alert-danger">Ocurrió un error ');
        }
    });
});
});

</script>

<?= $this->endSection() ?>

<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="background-color: #f8f9fa; border-radius: 15px;">
                <div class="card-header bg-primary text-white">
                    <h3 class="text-center">Crear permiso magisterial</h3>
                </div>

                <div class="card-body">
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                    <?php endif; ?>

                    <form action="<?= site_url('permiso_magisterial/store') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="form-group">
                            <label for="id_docente">Docente:</label>
                            <select name="id_docente" id="id_docente" class="form-control">
                                <?php foreach ($maestros as $maestro): ?>
                                    <option value="<?= $maestro['idDocente'] ?>"><?= $maestro['nombre_completo'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="id_tipo_permiso">Tipo de permiso:</label>
                            <select name="id_tipo_permiso" id="id_tipo_permiso" class="form-control">
                                <?php foreach ($tipos_permisos as $tipo): ?>
                                    <option value="<?= $tipo['idTipoPermiso'] ?>" data-cantidad-dias="<?= $tipo['cantidadDias'] ?>">
                                        <?= $tipo['nombre'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="cantidad_dias_disponibles">Días disponibles:</label>
                            <input type="text" id="cantidad_dias_disponibles" class="form-control" readonly>
                        </div>

                        <div class="form-group">
                            <label for="fecha_inicio">Fecha inicio:</label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="fecha_fin">Fecha fin:</label>
                            <input type="date" name="fecha_fin" id="fecha_fin" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="horas_ocupadas">Horas ocupadas:</label>
                            <input type="number" name="horas_ocupadas" id="horas_ocupadas" class="form-control" step="0.01">
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">Crear permiso</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var tipoPermisoSelect = document.getElementById('id_tipo_permiso');
    var cantidadDiasInput = document.getElementById('cantidad_dias_disponibles');

    tipoPermisoSelect.addEventListener('change', function() {
        var selectedOption = tipoPermisoSelect.options[tipoPermisoSelect.selectedIndex];
        var cantidadDias = selectedOption.getAttribute('data-cantidad-dias');
        cantidadDiasInput.value = cantidadDias || 'No disponible';
    });

    // Trigger change event on page load to show the initial selected value
    tipoPermisoSelect.dispatchEvent(new Event('change'));
});
</script>

<?= $this->endSection() ?>

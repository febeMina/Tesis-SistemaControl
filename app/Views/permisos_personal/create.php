<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="background-color: #f8f9fa; border-radius: 15px;">
                <div class="card-header bg-primary text-white">
                    <h3 class="text-center">Crear Permiso Personal</h3>
                </div>

                <div class="card-body">
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                    <?php endif; ?>

                    <form action="<?= site_url('permisos_personal/store') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="form-group">
                            <label for="idDocente" style="color: #000;">
                                <i class="fas fa-chalkboard-teacher"></i> Seleccionar Docente:
                            </label>
                            <select name="idDocente" id="idDocente" class="form-control">
                                <?php foreach ($docentes as $docente): ?>
                                    <option value="<?= $docente['idDocente'] ?>"><?= esc($docente['nombre_completo']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="idTipoPermiso" style="color: #000;">
                                <i class="fas fa-calendar-check"></i> Seleccionar Tipo de Permiso:
                            </label>
                            <select name="idTipoPermiso" id="idTipoPermiso" class="form-control">
                                <?php foreach ($tiposPermisos as $tipoPermiso): ?>
                                    <option value="<?= $tipoPermiso['idTipoPermiso'] ?>">
                                        <?= esc($tipoPermiso['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="tipoSolicitud" style="color: #000;">
                                <i class="fas fa-calendar-alt"></i> Tipo de Solicitud:
                            </label>
                            <select name="tipoSolicitud" id="tipoSolicitud" class="form-control">
                                <option value="Dias">Días</option>
                                <option value="Horas">Horas</option>
                            </select>
                        </div>

                        <!-- Campos de Días -->
                        <div id="diasDiv" class="form-group" style="display: none;">
                            <label for="fechaInicio" style="color: #000;">
                                <i class="fas fa-calendar-day"></i> Fecha de Inicio:
                            </label>
                            <input type="date" name="fechaInicio" id="fechaInicio" class="form-control">
                            
                            <label for="fechaFin" style="color: #000;">
                                <i class="fas fa-calendar-day"></i> Fecha de Fin:
                            </label>
                            <input type="date" name="fechaFin" id="fechaFin" class="form-control">
                        </div>

                        <!-- Campos de Horas -->
                        <div id="horasDiv" class="form-group" style="display: none;">
                            <label for="fechaUnica" style="color: #000;">
                                <i class="fas fa-calendar-day"></i> Fecha del Permiso:
                            </label>
                            <input type="date" name="fechaUnica" id="fechaUnica" class="form-control">
                        
                            <label for="horasSolicitadas" style="color: #000;">
                                <i class="fas fa-hourglass-start"></i> Horas Solicitadas:
                            </label>
                            <input type="number" name="horasSolicitadas" id="horasSolicitadas" class="form-control" min="1" max="6">
                        </div>


                        <button type="submit" class="btn btn-primary">Guardar Permiso</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('tipoSolicitud').addEventListener('change', function() {
    var tipoSolicitud = this.value;
    var diasDiv = document.getElementById('diasDiv');
    var horasDiv = document.getElementById('horasDiv');

    if (tipoSolicitud === 'Dias') {
        diasDiv.style.display = 'block';
        horasDiv.style.display = 'none';
    } else {
        diasDiv.style.display = 'none';
        horasDiv.style.display = 'block';
    }
});

// Trigger
document.getElementById('tipoSolicitud').dispatchEvent(new Event('change'));

</script>

<?= $this->endSection() ?>

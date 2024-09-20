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
                                <option value="" disabled selected>Selecciona</option>
                                <?php foreach ($docentes as $docente): ?>
                                    <option value="<?= $docente['idDocente'] ?>"><?= esc($docente['nombreCompleto']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="idTipoPermiso" style="color: #000;">
                                <i class="fas fa-calendar-check"></i> Seleccionar Tipo de Permiso:
                            </label>
                            <select name="idTipoPermiso" id="idTipoPermiso" class="form-control">
                            <option value="" disabled selected>Selecciona</option>
                                <?php foreach ($tiposPermisos as $tipoPermiso): ?>
                                    <option value="<?= $tipoPermiso['idTipoPermiso'] ?>">
                                        <?= esc($tipoPermiso['nombre']) ?>
                                            <small>(<?= esc($tipoPermiso['cantidadDias'] ?? '0') ?> días)</small>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Mostrar el saldo actual según el tipo de permiso -->
                        <div class="form-group">
                            <label for="saldoActual" style="color: #000;">
                                <i class="fas fa-balance-scale"></i> Saldo Actual:
                            </label>
                            <input type="text" id="saldoActual" class="form-control" value="Días: <?= esc($saldoActual['saldoActualDias']) ?>, Horas: <?= esc($saldoActual['saldoActualHoras']) ?>" disabled>
                        </div>

                        <div class="form-group">
                            <label for="tipoSolicitud" style="color: #000;">
                                <i class="fas fa-calendar-alt"></i> Tipo de Solicitud:
                            </label>
                            <select name="tipoSolicitud" id="tipoSolicitud" class="form-control">
                            <option value="" disabled selected>Selecciona</option>
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

// Función para actualizar el saldoActual dependiendo del tipo de permiso seleccionado
document.getElementById('idTipoPermiso').addEventListener('change', function() {
    var idDocente = document.getElementById('idDocente').value;
    var idTipoPermiso = this.value;

    // Asegúrate de que ambas variables están definidas antes de hacer la solicitud
    if (idDocente && idTipoPermiso) {
        // Hacer una petición AJAX para obtener el saldo actual
        fetch(`<?= site_url('permisos_personal/getSaldoActual') ?>/${idDocente}/${idTipoPermiso}`)

            .then(response => response.json())
            .then(data => {
                console.log(data); // Verificar los datos recibidos
                // Actualiza el saldo en el campo correspondiente
                document.getElementById('saldoActual').value = `Días: ${data.saldoActualDias}, Horas: ${data.saldoActualHoras}`;
            })
            .catch(error => console.error('Error al obtener el saldo actual:', error));
    }
});

// También actualiza el saldo cuando cambie el docente
document.getElementById('idDocente').addEventListener('change', function() {
    var idDocente = this.value;
    var idTipoPermiso = document.getElementById('idTipoPermiso').value;

    if (idDocente && idTipoPermiso) {
        // Hacer una petición AJAX para obtener el saldo actual
        fetch(`<?= site_url('permisos_personal/getSaldoActual') ?>/${idDocente}/${idTipoPermiso}`)
            .then(response => response.json())
            .then(data => {
                console.log(data); // Verificar los datos recibidos
                // Actualiza el saldo en el campo correspondiente
                document.getElementById('saldoActual').value = `Días: ${data.saldoActualDias}, Horas: ${data.saldoActualHoras}`;
            })
            .catch(error => console.error('Error al obtener el saldo actual:', error));
    }
});

// Trigger para cargar el saldo inicial al cargar la página si ya hay un permiso seleccionado
document.getElementById('idTipoPermiso').dispatchEvent(new Event('change'));
</script>

<?= $this->endSection() ?>

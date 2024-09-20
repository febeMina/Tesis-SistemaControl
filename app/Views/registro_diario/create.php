<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <?php if (session()->has('success')): ?>
        <div class="alert alert-success">
            <?= session('success') ?>
        </div>
    <?php endif; ?>
    <?php if (session()->has('error')): ?>
        <div class="alert alert-danger">
            <?= session('error') ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-white" style="background-color: #4CAF50; border-radius: 10px;">
                    <h4 class="header-title text-center">Agregar Registro Diario</h4>
                </div>
                <div class="card-body" style="background-color: #FFFFFF; padding: 20px;">
                    <form id="registro-form" action="<?= base_url('public/registro-diario/store') ?>" method="post">

                        <?= csrf_field() ?>
                        
                        <!-- Campo de fecha para seleccionar la fecha de requisición -->
                        <div class="mb-3">
                            <label for="fecha" class="form-label" style="color: #000;">Fecha de Requisición:</label>
                            <input type="date" name="fecha" id="fecha" class="form-control" value="<?= old('fecha') ?>" required>
                        </div>

                        <!-- Detalles de la requisición -->
                        <div id="requisicion-detalles" class="mb-3" style="color: #000;">
                            <!-- Los detalles se llenarán dinámicamente con JavaScript -->
                        </div>
                    </form>

                    <!-- Detalles de asistencia por grado -->
                    <h4 style="color: #000;">Detalles de Asistencia por Grado</h4>
                    <div id="detalles-asistencia" class="row">
                        <?php foreach ($grados as $index => $grado): ?>
                            <div class="col-md-4 mb-3">
                                <div class="h-100 grado-asistencia" style="border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); padding: 15px; transition: transform 0.2s;">
                                    <div class="card-header" style="background-color: #2196F3; border-radius: 6px; color: #fff;">
                                        <h5 class="mb-0"><?= esc($grado->nombre) ?></h5>
                                    </div>
                                    <div class="card-body" style="padding: 15px;">
                                    <input type="hidden" name="idGrado[]" value="<?= esc($grado->idGrado) ?>">

                                        <div class="mb-2">
                                            <label style="color: #000;" for="idDocente-<?= esc($grado->idGrado) ?>" class="form-label">Docente:</label>
                                            <select name="idDocente[]" class="form-select" required>
                                                <?php foreach ($docentes as $docente): ?>
                                                    <option value="<?= esc($docente['idDocente']) ?>" <?= $grado->idDocente == $docente['idDocente'] ? 'selected' : '' ?>>
                                                        <?= esc($docente['nombreCompleto']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="mb-2">
                                            <label style="color: #000;" for="cantidadNiños-<?= esc($grado->idGrado) ?>" class="form-label">Cantidad de Niños:</label>
                                            <input type="number" name="cantidadNiños[]" class="form-control" min="0" required>
                                        </div>

                                        <div class="mb-2">
                                            <label style="color: #000;" for="cantidadNiñas-<?= esc($grado->idGrado) ?>" class="form-label">Cantidad de Niñas:</label>
                                            <input type="number" name="cantidadNiñas[]" class="form-control" min="0" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="mt-3 text-center">
                        <button type="submit" form="registro-form" class="btn btn-success">Guardar Registro</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fechaInput = document.getElementById('fecha');
    const requisicionDetalles = document.getElementById('requisicion-detalles');

    fechaInput.addEventListener('change', function() {
        const fecha = fechaInput.value;

        if (fecha) {
            fetch(`<?= base_url('public/registro-diario/getRequisicionByFecha') ?>/${fecha}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta de la red: ' + response.statusText);
                    }
                    return response.text();
                })
                .then(text => {
                    try {
                        const data = JSON.parse(text);
                        console.log('Datos recibidos:', data);

                        let html = '';

                        if (Array.isArray(data) && data.length > 0) {
                            data.forEach(requisicion => {
                                html += `
                                    <div class="requisicion-item">
                                        <p><strong>Fecha de Requisición:</strong> ${requisicion.fechaRequisicion}</p>
                                        <p><strong>Comida a Preparar:</strong> ${requisicion.comidaPreparar}</p>
                                    </div>
                                `;
                            });
                        } else {
                            html = '<p>No hay requisiciones para la fecha seleccionada.</p>';
                        }

                        requisicionDetalles.innerHTML = html;
                    } catch (e) {
                        console.error('Error al parsear JSON:', e);
                        requisicionDetalles.innerHTML = '<p>Error al procesar los datos recibidos.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error en el fetch:', error);
                    requisicionDetalles.innerHTML = '<p>Error al cargar los datos.</p>';
                });
        } else {
            requisicionDetalles.innerHTML = '';
        }
    });
});
</script>

<?= $this->endSection() ?>

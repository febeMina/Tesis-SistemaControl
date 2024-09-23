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
                    <h4 class="header-title text-center">Agregar registro diario</h4>
                </div>
                <div class="card-body" style="background-color: #FFFFFF; padding: 20px;">
                    <form id="registro-form" action="<?= base_url('public/registro-diario/store') ?>" method="post">

                        <?= csrf_field() ?>
                        
                        <!-- Campo de fecha para seleccionar la fecha de requisición -->
                        <div class="mb-3">
                            <label for="fecha" class="form-label" style="color: #000;">Fecha de requisición:</label>
                            <input type="date" name="fecha" id="fecha" class="form-control" value="<?= old('fecha') ?>" required>
                        </div>

                        <!-- Detalles de la requisición -->
                        <div id="requisicion-detalles" class="mb-3" style="color: #000;">
                            <!-- Los detalles se llenarán dinámicamente con JavaScript -->
                        </div>

                        <!-- Detalles de asistencia por grado en tabla -->
                        <h4 style="color: #000;">Detalles de asistencia por grado</h4>
                        <table class="table" style="color: #000;">
                            <thead>
                                <tr>
                                    <th>Grado</th>
                                    <th>Docente</th>
                                    <th>Cantidad de niños</th>
                                    <th>Cantidad de niñas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($grados as $grado): ?>
                                    <tr>
                                        <td><?= esc($grado->nombre) ?></td>
                                        <td>
                                            <input type="hidden" name="idGrado[]" value="<?= esc($grado->idGrado) ?>">
                                            <select name="idDocente[]" class="form-select" required>
                                                <?php foreach ($docentes as $docente): ?>
                                                    <option value="<?= esc($docente['idDocente']) ?>" <?= isset($grado->idDocente) && $grado->idDocente == $docente['idDocente'] ? 'selected' : '' ?>>
                                                        <?= esc($docente['nombreCompleto']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td><input type="number" name="cantidadNinos[]" class="form-control" min="0"></td>
                                        <td><input type="number" name="cantidadNinas[]" class="form-control" min="0"></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <div class="mt-3 text-center">
                            <button type="submit" form="registro-form" class="btn btn-success">Guardar registro</button>
                        </div>
                    </form>
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
                .then(response => response.json())
                .then(data => {
                    let html = '';

                    if (data && data.comidaPreparar) {
                        // Si hay requisición, muestra la comida a preparar y el ID oculto.
                        html = `
                            <div class="requisicion-item">
                                <p><strong>Fecha de Requisición:</strong> ${data.fechaRequisicion}</p>
                                <p><strong>Comida a Preparar:</strong> ${data.comidaPreparar}</p>
                                <input type="hidden" name="idProductoRequisicion" value="${data.idProductoRequisicion}">
                            </div>
                        `;
                    } else {
                        // Si no hay requisición para esa fecha.
                        html = '<p>No hay requisiciones para la fecha seleccionada.</p>';
                    }

                    requisicionDetalles.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error:', error);
                    requisicionDetalles.innerHTML = '<p>Error al procesar los datos recibidos.</p>';
                });
        } else {
            requisicionDetalles.innerHTML = '';
        }
    });
});
</script>

<?= $this->endSection() ?>

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
                <div class="card-header text-white" style="background-color: #090066; border-radius: 10px;">
                    <h4 class="header-title text-center">Agregar Registro Diario</h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0; padding: 15px;">
                    <form action="<?= base_url('public/registro-diario/store') ?>" method="post">
                        <div class="mb-3">
                            <label for="fecha" class="form-label" style="color: #000;">Fecha:</label>
                            <input type="date" name="fecha" class="form-control" required>
                        </div>
                        
                        <h4 style="color: #000;">Detalles de Asistencia por Grado</h4>
                        <div id="detalles-asistencia" class="row">
                            <?php foreach ($grados as $index => $grado): ?>
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100 grado-asistencia" style="border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                                        <div class="card-header">
                                            <h5 class="mb-0"><?= esc($grado['nombre']) ?></h5>
                                        </div>
                                        <div class="card-body">
                                            <input type="hidden" name="idGrado[]" value="<?= esc($grado['idGrado']) ?>">

                                            <div class="mb-2">
                                                <label for="idDocente-<?= esc($grado['idGrado']) ?>" class="form-label">Docente:</label>
                                                <select name="idDocente[]" class="form-select" required>
                                                    <?php foreach ($docentes as $docente): ?>
                                                        <option value="<?= esc($docente['idDocente']) ?>"><?= esc($docente['nombreCompleto']) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="mb-2">
                                                <label for="cantidadNiños-<?= esc($grado['idGrado']) ?>" class="form-label">Cantidad de Niños:</label>
                                                <input type="number" name="cantidadNiños[]" class="form-control cantidad-ninos" min="0" required>
                                            </div>

                                            <div class="mb-2">
                                                <label for="cantidadNiñas-<?= esc($grado['idGrado']) ?>" class="form-label">Cantidad de Niñas:</label>
                                                <input type="number" name="cantidadNiñas[]" class="form-control cantidad-ninas" min="0" required>
                                            </div>

                                            <!-- El campo total ha sido eliminado del formulario -->
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <button type="submit" class="btn btn-success">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const grados = document.querySelectorAll('.grado-asistencia');

    grados.forEach(grado => {
        const inputNinos = grado.querySelector('.cantidad-ninos');
        const inputNinas = grado.querySelector('.cantidad-ninas');

        const updateTotal = () => {
            const ninos = parseInt(inputNinos.value) || 0;
            const ninas = parseInt(inputNinas.value) || 0;
            // Calcular el total en el cliente, pero no mostrarlo
        };

        inputNinos.addEventListener('input', updateTotal);
        inputNinas.addEventListener('input', updateTotal);
    });
});
</script>

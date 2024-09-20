<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Registrar Diario</h2>
    <form action="<?= site_url('registro-diario/store') ?>" method="post">
        <div class="form-group">
            <label for="fecha">Fecha:</label>
            <input type="date" class="form-control" id="fecha" name="fecha" required>
        </div>
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>Grado</th>
                    <th>Docente</th>
                    <th>Cantidad Niños</th>
                    <th>Cantidad Niñas</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($grados as $grado): ?>
                    <tr>
                        <td><?= esc($grado['nombre']) ?></td>
                        <td><?= esc($grado['docenteNombre']) ?></td>
                        <td><input type="number" name="cantidadNiños[]" class="form-control" min="0"></td>
                        <td><input type="number" name="cantidadNiñas[]" class="form-control" min="0"></td>
                        <input type="hidden" name="idGrado[]" value="<?= esc($grado['idGrado']) ?>">
                        <input type="hidden" name="idDocente[]" value="<?= esc($grado['idDocente']) ?>">
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
</div>

<?= $this->endSection() ?>

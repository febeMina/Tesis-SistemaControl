<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="text-center">Editar Padre</h3>
                </div>
                <div class="card-body">
                    <form action="<?= site_url('padres/update/'.$padre->idDatosResponsable) ?>" method="post">
                        <div class="form-group">
                            <label for="nombre_completo">Nombre Completo</label>
                            <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" value="<?= $padre->nombre_completo ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="dui">DUI</label>
                            <input type="text" class="form-control" id="dui" name="dui" value="<?= $padre->dui ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" value="<?= $padre->telefono ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="estado">Estado</label>
                            <select class="form-control" id="estado" name="estado" required>
                                <option value="Activo" <?= ($padre->estado == 'Activo') ? 'selected' : '' ?>>Activo</option>
                                <option value="Inactivo" <?= ($padre->estado == 'Inactivo') ? 'selected' : '' ?>>Inactivo</option>
                            </select>
                        </div>
<!-- Campos para editar los alumnos asociados al padre -->
<div class="mt-12">
    <h4 class="text-center">Alumnos Asociados</h4>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre Completo</th>
                    <th>Sexo</th>
                    <th>NIE</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alumnos as $alumno): ?>
                    <tr>
                        <td><input type="text" class="form-control" name="alumno_nombre_completo[]" value="<?= $alumno['nombreCompleto'] ?>" required></td>
                        <td>
                            <select class="form-control" name="alumno_sexo[]" required>
                                <option value="M" <?= ($alumno['Sexo'] == 'M') ? 'selected' : '' ?>>Masculino</option>
                                <option value="F" <?= ($alumno['Sexo'] == 'F') ? 'selected' : '' ?>>Femenino</option>
                            </select>
                        </td>
                        <td>
                            <input type="hidden" name="alumno_id[]" value="<?= $alumno['idAlumno'] ?>">
                            <input type="text" class="form-control" name="alumno_nie[]" value="<?= $alumno['NIE'] ?>" required>
                        </td>
                        <td>
                            <select class="form-control" name="alumno_estado[]" required>
                                <option value="Activo" <?= ($alumno['estado'] == 'Activo') ? 'selected' : '' ?>>Activo</option>
                                <option value="Inactivo" <?= ($alumno['estado'] == 'Inactivo') ? 'selected' : '' ?>>Inactivo</option>
                            </select>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<!-- Fin de campos para editar alumnos -->

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary">Actualizar Padre</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

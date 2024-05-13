<!-- edit.php -->
<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card" style="background-color: #f8f9fa; border-radius: 15px;">
                <div class="card-header bg-primary text-white" style="border-radius: 15px 15px 0 0; background-color: #090066 !important;">
                    <h3 class="text-center">Editar Padre</h3>
                </div>
                <div class="card-body">
                    <form action="<?= site_url('padres/update/'.$padre['idDatosResponsable']) ?>" method="post">
                        <div class="form-group">
                            <label for="nombre_completo" style="color: #000;"><i class="fas fa-user"></i> Nombre Completo</label>
                            <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" value="<?= $padre['nombreCompleto'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="dui" style="color: #000;"><i class="fas fa-id-card"></i> DUI</label>
                            <input type="text" class="form-control" id="dui" name="dui" value="<?= $padre['DUI'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="telefono" style="color: #000;"><i class="fas fa-phone"></i> Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" value="<?= $padre['telefono'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="sexo" style="color: #000;"><i class="fas fa-venus-mars"></i> Sexo</label>
                            <select class="form-control" name="padre_sexo" required>
                                <option value="Masculino" <?= ($padre['sexo_padre'] == 'Masculino') ? 'selected' : '' ?>>Masculino</option>
                                <option value="Femenino" <?= ($padre['sexo_padre'] == 'Femenino') ? 'selected' : '' ?>>Femenino</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="estado" style="color: #000;"><i class="fas fa-check-circle"></i> Estado</label>
                            <select class="form-control" id="estado" name="estado" required>
                                <option value="Activo" <?= ($padre['estado'] == 'Activo') ? 'selected' : '' ?>>Activo</option>
                                <option value="Inactivo" <?= ($padre['estado'] == 'Inactivo') ? 'selected' : '' ?>>Inactivo</option>
                            </select>
                        </div>
                        <!-- Campos para editar los alumnos asociados al padre -->
                        <div class="mt-12">
                            <h4 class="text-center" style="color: #000;">Alumnos Asociados</h4>
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
                                                <td><input type="text" class="form-control" name="alumno_nombre_completo[]" value="<?= $alumno['nombreAlumno'] ?>" required></td>
                                                <td>
                                                <select class="form-control" name="alumno_sexo[]" required>
                                                    <option value="Masculino" <?= ($alumno['sexo_alumno'] == 'Masculino') ? 'selected' : '' ?>>Masculino</option>
                                                    <option value="Femenino" <?= ($alumno['sexo_alumno'] == 'Femenino') ? 'selected' : '' ?>>Femenino</option>
                                                </select>
                                                </td>
                                                <td><input type="text" class="form-control" name="alumno_nie[]" value="<?= $alumno['NIE'] ?>" required></td>
                                                <td>
                                                    <select class="form-control" name="alumno_estado[]" required>
                                                        <option value="Activo" <?= ($alumno['estado'] == 'Activo') ? 'selected' : '' ?>>Activo</option>
                                                        <option value="Inactivo" <?= ($alumno['estado'] == 'Inactivo') ? 'selected' : '' ?>>Inactivo</option>
                                                    </select>
                                                </td>
                                                <!-- Agregar campo oculto para el ID del alumno -->
                                                <input type="hidden" name="alumno_id[]" value="<?= $alumno['idAlumno'] ?>">
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

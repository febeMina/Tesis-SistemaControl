<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<style>
    /* Estilo para los labels */
    label {
        color: #000; /* Color negro */
    }
    .enunciado {
        color: #000; /* Color negro */
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="border rounded">
                <div class="card-header">
                    <h3 class="text-center">Editar Padre</h3>
                </div>
                <div class="p-4">
                    <form action="<?= site_url('padres/update/'.$padre['idDatosResponsable']) ?>" method="post">
                        <div class="form-group">
                            <label for="nombre_completo"><i class="fas fa-user"></i> Nombre Completo</label>
                            <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" value="<?= $padre['nombreCompleto'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="dui"><i class="fas fa-id-card"></i> DUI</label>
                            <input type="text" class="form-control" id="dui" name="dui" value="<?= $padre['DUI'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="telefono"><i class="fas fa-phone"></i> Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" value="<?= $padre['telefono'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="genero"><i class="fas fa-venus-mars"></i> Género</label>
                            <select class="form-control" name="genero" required>
                                <option value="Masculino" <?= ($padre['Genero'] == 'Masculino') ? 'selected' : '' ?>>Masculino</option>
                                <option value="Femenino" <?= ($padre['Genero'] == 'Femenino') ? 'selected' : '' ?>>Femenino</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="estado"><i class="fas fa-check-circle"></i> Estado</label>
                            <select class="form-control" name="estado" required>
                                <option value="Activo" <?= ($padre['estado'] == 'Activo') ? 'selected' : '' ?>>Activo</option>
                                <option value="Inactivo" <?= ($padre['estado'] == 'Inactivo') ? 'selected' : '' ?>>Inactivo</option>
                            </select>
                        </div>

                        <h4 class="text-center mt-4 enunciado">Alumnos Asociados</h4>
                        <hr style="border-top: 2px dashed #ccc;">

                        <div class="row">
                            <?php foreach ($alumnos as $alumno) : ?>
                                <div class="col-md-6">
                                    <div class="border rounded mb-3 p-3">
                                        <div class="form-group">
                                            <label for="alumno_nombre_completo"><i class="fas fa-user"></i> Nombre Completo del Alumno</label>
                                            <input type="text" class="form-control" name="alumno_nombre_completo[]" value="<?= $alumno['nombreAlumno'] ?>" required>
                                            <input type="hidden" name="alumno_id[]" value="<?= $alumno['idAlumno'] ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="alumno_nie"><i class="fas fa-id-badge"></i> NIE</label>
                                            <input type="text" class="form-control" name="alumno_nie[]" value="<?= $alumno['NIE'] ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="alumno_sexo"><i class="fas fa-venus-mars"></i> Género</label>
                                            <select class="form-control" name="alumno_sexo[]" required>
                                                <option value="Masculino" <?= ($alumno['Genero_alumno'] == 'Masculino') ? 'selected' : '' ?>>Masculino</option>
                                                <option value="Femenino" <?= ($alumno['Genero_alumno'] == 'Femenino') ? 'selected' : '' ?>>Femenino</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="alumno_estado"><i class="fas fa-check-circle"></i> Estado</label>
                                            <select class="form-control" name="alumno_estado[]" required>
                                                <option value="Activo" <?= ($alumno['estado'] == 'Activo') ? 'selected' : '' ?>>Activo</option>
                                                <option value="Inactivo" <?= ($alumno['estado'] == 'Inactivo') ? 'selected' : '' ?>>Inactivo</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Campos para agregar nuevos alumnos -->
                        <div class="border rounded mb-3 p-3" id="new-alumnos-container" style="display: none;">
                            <h4 class="text-center enunciado">Agregar Nuevo Alumno</h4>
                            <hr style="border-top: 2px dashed #ccc;">
                            <div class="new-alumno-form">
                                <div class="form-group">
                                    <label for="nuevo_alumno_nombre_completo"><i class="fas fa-user"></i> Nombre Completo del Alumno</label>
                                    <input type="text" class="form-control" name="nuevo_alumno_nombre_completo[]">
                                </div>
                                <div class="form-group">
                                    <label for="nuevo_alumno_nie"><i class="fas fa-id-badge"></i> NIE</label>
                                    <input type="text" class="form-control" name="nuevo_alumno_nie[]">
                                </div>
                                <div class="form-group">
                                    <label for="nuevo_alumno_sexo"><i class="fas fa-venus-mars"></i> Género</label>
                                    <select class="form-control" name="nuevo_alumno_sexo[]">
                                        <option value="Masculino">Masculino</option>
                                        <option value="Femenino">Femenino</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="nuevo_alumno_estado"><i class="fas fa-check-circle"></i> Estado</label>
                                    <select class="form-control" name="nuevo_alumno_estado[]">
                                        <option value="Activo">Activo</option>
                                        <option value="Inactivo">Inactivo</option>
                                    </select>
                                </div>
                                <hr style="border-top: 2px dashed #ccc;">
                            </div>
                        </div>

                        <button type="button" class="btn btn-secondary btn-sm mb-3" id="add-new-alumno-btn">Agregar Otro Alumno</button>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Actualizar Padre</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('add-new-alumno-btn').addEventListener('click', function() {
        var container = document.getElementById('new-alumnos-container');
        container.style.display = 'block'; // Mostrar el contenedor de nuevos alumnos al hacer clic en el botón
    });
</script>

<?= $this->endSection() ?>

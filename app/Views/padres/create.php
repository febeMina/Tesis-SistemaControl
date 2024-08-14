<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card" style="background-color: #f8f9fa; border-radius: 15px;">
                <div class="card-header bg-primary text-white">
                    <h3 class="text-center">Agregar Nuevo Padre</h3>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>
                    <form action="<?= site_url('padres/store') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <label for="nombre_completo" style="color: #000;"><i class="fas fa-user"></i> Nombre Completo</label>
                            <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" value="<?= old('nombre_completo') ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="dui" style="color: #000;"><i class="fas fa-id-card"></i> DUI</label>
                            <input type="text" class="form-control" id="dui" name="dui" value="<?= old('dui') ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="telefono" style="color: #000;"><i class="fas fa-phone"></i> Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" value="<?= old('telefono') ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="genero" style="color: #000;"><i class="fas fa-venus-mars"></i> Género</label>
                            <select class="form-control" name="genero" required>
                                <option value="M" <?= old('genero') == 'M' ? 'selected' : '' ?>>Masculino</option>
                                <option value="F" <?= old('genero') == 'F' ? 'selected' : '' ?>>Femenino</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="estado" style="color: #000;"><i class="fas fa-check-circle"></i> Estado</label>
                            <select class="form-control" id="estado" name="estado" required>
                                <option value="Activo" <?= old('estado') == 'Activo' ? 'selected' : '' ?>>Activo</option>
                                <option value="Inactivo" <?= old('estado') == 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
                            </select>
                        </div>
                        <!-- Campos para agregar alumnos asociados al padre -->
                        <div class="mt-4">
                            <h4 class="text-center" style="color: #000;">Alumnos Asociados</h4>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Nombre Completo</th>
                                            <th>Género</th>
                                            <th>NIE</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="alumnosTableBody">
                                        <?php if (session()->getFlashdata('alumnos')): ?>
                                            <?php foreach (session()->getFlashdata('alumnos')['nombre_completo'] as $index => $nombre): ?>
                                                <tr>
                                                    <td><input type="text" class="form-control" name="alumno_nombre_completo[]" value="<?= esc($nombre) ?>" required></td>
                                                    <td>
                                                        <select class="form-control" name="alumno_sexo[]" required>
                                                            <option value="M" <?= session()->getFlashdata('alumnos')['genero'][$index] == 'M' ? 'selected' : '' ?>>Masculino</option>
                                                            <option value="F" <?= session()->getFlashdata('alumnos')['genero'][$index] == 'F' ? 'selected' : '' ?>>Femenino</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="alumno_nie[]" value="<?= esc(session()->getFlashdata('alumnos')['nie'][$index]) ?>" required>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="alumno_estado[]" required>
                                                            <option value="Activo" <?= session()->getFlashdata('alumnos')['estado'][$index] == 'Activo' ? 'selected' : '' ?>>Activo</option>
                                                            <option value="Inactivo" <?= session()->getFlashdata('alumnos')['estado'][$index] == 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Eliminar</button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td><input type="text" class="form-control" name="alumno_nombre_completo[]" required></td>
                                                <td>
                                                    <select class="form-control" name="alumno_sexo[]" required>
                                                        <option value="M">Masculino</option>
                                                        <option value="F">Femenino</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="alumno_nie[]" required>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <select class="form-control" name="alumno_estado[]" required>
                                                        <option value="Activo">Activo</option>
                                                        <option value="Inactivo">Inactivo</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Eliminar</button>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-primary" id="addAlumno">Agregar Alumno</button>
                        </div>
                        <div class="mt-4 text-center">
                            <button type="submit" class="btn btn-success">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('addAlumno').addEventListener('click', function() {
            const tableBody = document.getElementById('alumnosTableBody');
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><input type="text" class="form-control" name="alumno_nombre_completo[]" required></td>
                <td>
                    <select class="form-control" name="alumno_sexo[]" required>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                    </select>
                </td>
                <td>
                    <div class="input-group">
                        <input type="text" class="form-control" name="alumno_nie[]" required>
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                        </div>
                    </div>
                </td>
                <td>
                    <select class="form-control" name="alumno_estado[]" required>
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Eliminar</button>
                </td>
            `;
            tableBody.appendChild(row);
        });

        window.removeRow = function(button) {
            button.closest('tr').remove();
        };
    });
</script>

<?= $this->endSection() ?>

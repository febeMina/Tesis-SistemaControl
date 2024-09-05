<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card" style="background-color: #f8f9fa; border-radius: 15px;">
                <div class="card-header bg-primary text-white">
                    <h3 class="text-center">Editar asociados</h3>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>
                    <form action="<?= site_url('padres/update/'.$padre['idDatosResponsable']) ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <label for="nombre_completo" style="color: #000;">Nombre Completo</label>
                            <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" value="<?= esc($padre['nombreCompleto']) ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="idTipoDocumento" style="color: #000;"><i class="fas fa-id-card"></i> Tipo de Documento</label>
                            <select class="form-control" id="idTipoDocumento" name="idTipoDocumento" required>
                                <?php foreach ($tipos_documento as $tipo): ?>
                                    <option value="<?= $tipo['idTipoDocumento']; ?>" data-mascara="<?= $tipo['mascara']; ?>" <?= $padre['idTipoDocumento'] == $tipo['idTipoDocumento'] ? 'selected' : '' ?>>
                                        <?= $tipo['nombre']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="numeroDocumento" style="color: #000;"><i class="fas fa-id-card"></i> Documento</label>
                            <input type="text" class="form-control" id="numeroDocumento" name="numeroDocumento" value="<?= esc($padre['numeroDocumento']) ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="telefono" style="color: #000;"><i class="fas fa-phone"></i> Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" value="<?= esc($padre['telefono']) ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="genero" style="color: #000;">Género</label>
                            <select class="form-control" id="genero" name="genero" required>
                                <option value="M" <?= $padre['Genero'] == 'M' ? 'selected' : '' ?>>Masculino</option>
                                <option value="F" <?= $padre['Genero'] == 'F' ? 'selected' : '' ?>>Femenino</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="estado" style="color: #000;"><i class="fas fa-check-circle"></i> Estado</label>
                            <select class="form-control" id="estado" name="estado" required>
                                <option value="Activo" <?= $padre['estado'] == 'Activo' ? 'selected' : '' ?>>Activo</option>
                                <option value="Inactivo" <?= $padre['estado'] == 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="tipoAsociado" style="color: #000;"><i class="fas fa-users"></i> Tipo de Asociado</label>
                            <select class="form-control" id="tipoAsociado" name="tipoAsociado" required>
                                <option value="EXTERNO" <?= $padre['tipoAsociado'] == 'EXTERNO' ? 'selected' : '' ?>>EXTERNO</option>
                                <option value="INTERNO" <?= $padre['tipoAsociado'] == 'INTERNO' ? 'selected' : '' ?>>INTERNO</option>
                            </select>
                        </div>
                        
                        <div id="alumnosContainer" style="<?= $padre['tipoAsociado'] == 'INTERNO' ? '' : 'display: none;' ?>">
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
                                            <?php if (isset($alumnos) && is_array($alumnos)): ?>
                                                <?php foreach ($alumnos as $alumno): ?>
                                                    <tr>
                                                        <td>
                                                            <input type="text" class="form-control" name="alumno_nombre_completo[]" value="<?= esc($alumno['nombreAlumno']) ?>" required>
                                                        </td>
                                                        <td>
                                                            <select class="form-control" name="alumno_sexo[]" required>
                                                                <option value="M" <?= $alumno['generoAlumno'] == 'M' ? 'selected' : '' ?>>Masculino</option>
                                                                <option value="F" <?= $alumno['generoAlumno'] == 'F' ? 'selected' : '' ?>>Femenino</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" name="alumno_nie[]" value="<?= esc($alumno['NIE']) ?>" required>
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <select class="form-control" name="alumno_estado[]" required>
                                                                <option value="Activo" <?= $alumno['estado'] == 'Activo' ? 'selected' : '' ?>>Activo</option>
                                                                <option value="Inactivo" <?= $alumno['estado'] == 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Eliminar</button>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                    <button type="button" class="btn btn-success" id="addAlumnoBtn">Agregar Alumno</button>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="padre_tipo_asociado" id="padre_tipo_asociado" value="<?= esc($padre['tipoAsociado']) ?>">

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                            <a href="<?= site_url('padres') ?>" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tipoAsociadoSelect = document.getElementById('tipoAsociado');
        var alumnosContainer = document.getElementById('alumnosContainer');
        var padreTipoAsociadoInput = document.getElementById('padre_tipo_asociado');

        // Set initial state based on existing value
        if (padreTipoAsociadoInput.value === 'INTERNO') {
            alumnosContainer.style.display = 'block';
        } else {
            alumnosContainer.style.display = 'none';
        }

        tipoAsociadoSelect.addEventListener('change', function () {
            if (this.value === 'INTERNO') {
                alumnosContainer.style.display = 'block';
            } else {
                alumnosContainer.style.display = 'none';
            }
        });

        document.getElementById('addAlumnoBtn').addEventListener('click', function () {
            var tableBody = document.getElementById('alumnosTableBody');
            var rowCount = tableBody.rows.length;
            var row = tableBody.insertRow(rowCount);

            row.innerHTML = `
                <td>
                    <input type="text" class="form-control" name="alumno_nombre_completo[]" required>
                </td>
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
        });
    });

    function removeRow(button) {
        var row = button.closest('tr');
        row.parentNode.removeChild(row);
    }
</script>

<?= $this->endSection() ?>

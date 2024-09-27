<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card" style="background-color: #f8f9fa; border-radius: 15px;">
                <div class="card-header bg-primary text-white">
                    <h3 class="text-center">Agregar nuevo asociado</h3>
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
                            <label for="nombre_completo" style="color: #000;">Nombre completo</label>
                            <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" value="<?= old('nombre_completo') ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="idTipoDocumento" style="color: #000;"><i class="fas fa-id-card"></i> Tipo de documento</label>
                            <select class="form-control" id="idTipoDocumento" name="idTipoDocumento" required>
                                <option value="" disabled selected>Selecciona</option>
                                <?php foreach ($tipos_documento as $tipo): ?>
                                    <option value="<?= $tipo['idTipoDocumento']; ?>" data-mascara="<?= $tipo['mascara']; ?>">
                                        <?= $tipo['nombre']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="numeroDocumento" style="color: #000;"><i class="fas fa-id-card"></i> Documento</label>
                            <input type="text" class="form-control" id="numeroDocumento" name="numeroDocumento" value="<?= old('numeroDocumento') ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="telefono" style="color: #000;"><i class="fas fa-phone"></i> Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" value="<?= old('telefono') ?>" required maxlength="8" title="Ingrese un número de teléfono de 8 dígitos.">
                        </div>

                        <div class="form-group">
                            <label for="genero" style="color: #000;">Género</label>
                            <select class="form-control" id="genero" name="genero" required>
                                <option value="" disabled selected>Selecciona</option>
                                <option value="M" <?= old('genero') == 'M' ? 'selected' : '' ?>>Masculino</option>
                                <option value="F" <?= old('genero') == 'F' ? 'selected' : '' ?>>Femenino</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="estado" style="color: #000;"><i class="fas fa-check-circle"></i> Estado</label>
                            <select class="form-control" id="estado" name="estado" required>
                                <option value="" disabled selected>Selecciona</option>
                                <option value="Activo" <?= old('estado') == 'Activo' ? 'selected' : '' ?>>Activo</option>
                                <option value="Inactivo" <?= old('estado') == 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="tipoAsociado" style="color: #000;"><i class="fas fa-users"></i> Tipo de asociado</label>
                            <select class="form-control" id="tipoAsociado" name="tipoAsociado" required>
                                <option value="" disabled selected>Selecciona</option>
                                <option value="EXTERNO">EXTERNO</option>
                                <option value="INTERNO">INTERNO</option>
                            </select>
                        </div>
                        
                        <div id="alumnosContainer" style="display: none;">
                            <div class="mt-4">
                                <h4 class="text-center" style="color: #000;">Alumnos asociados</h4>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Nombre completo</th>
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
                                                                <option value="" disabled selected>Selecciona</option>
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
                                                    <td><input type="text" class="form-control" name="alumno_nombre_completo[]"></td>
                                                    <td>
                                                        <select class="form-control" name="alumno_sexo[]">
                                                            <option value="" disabled selected>Selecciona</option>
                                                            <option value="M">Masculino</option>
                                                            <option value="F">Femenino</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="alumno_nie[]">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <select class="form-control" name="alumno_estado[]">
                                                            <option value="" disabled selected>Selecciona</option>
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
                                <button type="button" class="btn btn-primary" id="addAlumno">Agregar alumno</button>
                            </div>
                        </div>
                        
                        <div class="mt-4 text-center">
                            <button type="submit" class="btn btn-primary" style="background-color: #090066;">Guardar</button>
                            <a href="<?= base_url('public/padres') ?>" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.8/inputmask.min.js"></script>
<script>
   $(document).ready(function() {
    const telefonoInput = document.getElementById('telefono');
    const documentoInput = document.getElementById('numeroDocumento');
    const idTipoDocumento = document.getElementById('idTipoDocumento');

    // Validación y limpieza del campo de teléfono
    telefonoInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');  // Solo permitir números
        if (this.value.length !== 8) {
            this.setCustomValidity('Ingrese un número de teléfono válido de 8 dígitos.');
        } else {
            this.setCustomValidity('');
        }
    });

    // Validar solo números en el campo de documento
    documentoInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');  // Solo permitir números
        if (this.value === '' || isNaN(this.value) || parseInt(this.value) <= 0) {
            this.setCustomValidity('Ingrese solo números positivos.');
        } else {
            this.setCustomValidity('');
        }
    });

    // Aplicar máscara según el tipo de documento seleccionado
    function applyInputMask(mask) {
        if (mask) {
            $("#numeroDocumento").inputmask(mask);
        } else {
            $("#numeroDocumento").inputmask('remove');  // Quitar máscara si no hay
        }
    }

    $("#idTipoDocumento").change(function() {
        var mask = $(this).find('option:selected').data('mascara');
        applyInputMask(mask);
    });

    // Añadir nuevo alumno a la tabla
    $("#addAlumno").click(function() {
        var newRow = `
            <tr>
                <td><input type="text" class="form-control" name="alumno_nombre_completo[]"></td>
                <td>
                    <select class="form-control" name="alumno_sexo[]">
                        <option value="" disabled selected>Selecciona</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                    </select>
                </td>
                <td>
                    <div class="input-group">
                        <input type="text" class="form-control" name="alumno_nie[]">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                        </div>
                    </div>
                </td>
                <td>
                    <select class="form-control" name="alumno_estado[]">
                        <option value="" disabled selected>Selecciona</option>
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Eliminar</button>
                </td>
            </tr>
        `;
        $("#alumnosTableBody").append(newRow);
    });

    // Eliminar fila de alumno
    window.removeRow = function(button) {
        $(button).closest('tr').remove();
    };

    // Mostrar u ocultar el contenedor de alumnos basado en el tipo de asociado
    $("#tipoAsociado").change(function() {
        if ($(this).val() === 'INTERNO') {
            $("#alumnosContainer").show();
        } else {
            $("#alumnosContainer").hide();
        }
    }).trigger('change'); // Disparar el evento para verificar el valor inicial
});
</script>


<?= $this->endSection() ?>

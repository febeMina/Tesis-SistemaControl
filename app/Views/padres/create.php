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
                            <label for="nombre_completo" style="color: #000;">Nombre Completo</label>
                            <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" value="<?= old('nombre_completo') ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="idTipoDocumento" style="color: #000;"><i class="fas fa-id-card"></i> Tipo de Documento</label>
                            <select class="form-control" id="idTipoDocumento" name="idTipoDocumento" required>
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
                            <input type="text" class="form-control" id="telefono" name="telefono" value="<?= old('telefono') ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="genero" style="color: #000;">Género</label>
                            <select class="form-control" id="genero" name="genero" required>
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

                        <div class="form-group">
                            <label for="tipoAsociado" style="color: #000;"><i class="fas fa-users"></i> Tipo de Asociado</label>
                            <select class="form-control" id="tipoAsociado" name="tipoAsociado" required>
                                <option value="EXTERNO">EXTERNO</option>
                                <option value="INTERNO">INTERNO</option>
                            </select>
                        </div>
                        
                        <div id="alumnosContainer" style="display: none;">
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
                                                    <td><input type="text" class="form-control" name="alumno_nombre_completo[]"></td>
                                                    <td>
                                                        <select class="form-control" name="alumno_sexo[]">
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
                                                        <select class="form-control" name="alumno_estado[]" >
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
        // Inicializar Inputmask
        const inputMask = (selector, mask) => {
            $(selector).inputmask(mask, {
                "clearIncomplete": true,
                "autoUnmask": true,
                "placeholder": mask.replace(/\d/g, '9').replace(/\D/g, '_')
            });
        };

        const documentoInput = document.getElementById('numeroDocumento');
        const idTipoDocumento = document.getElementById('idTipoDocumento');

        idTipoDocumento.addEventListener('change', function() {
            const selectedOption = idTipoDocumento.options[idTipoDocumento.selectedIndex];
            const mascara = selectedOption.getAttribute('data-mascara');
            inputMask('#numeroDocumento', mascara);
        });

        // Mostrar/ocultar campos según selección de tipo de asociado
        const tipoAsociado = document.querySelector('select[name="tipoAsociado"]');
    const alumnosFields = document.querySelectorAll('.alumnos-fields'); // Cambia por el selector que agrupa los campos de alumnos.

    function toggleAlumnoFields() {
        if (tipoAsociado.value === 'INTERNO') {
            alumnosFields.forEach(field => {
                field.style.display = ''; // Mostrar los campos
                field.querySelector('input').setAttribute('required', 'required'); // Añadir atributo required
            });
        } else {
            alumnosFields.forEach(field => {
                field.style.display = 'none'; // Ocultar los campos
                field.querySelector('input').removeAttribute('required'); // Quitar atributo required
            });
        }
    }

    tipoAsociado.addEventListener('change', toggleAlumnoFields);
    toggleAlumnoFields(); // Llamar para asegurar que los campos estén en el estado correcto al cargar la página

        // Clonar fila para agregar alumno
        const addAlumnoButton = document.getElementById('addAlumno');
        const alumnosTableBody = document.getElementById('alumnosTableBody');

        addAlumnoButton.addEventListener('click', function () {
            const firstRow = alumnosTableBody.querySelector('tr');
            const cloneRow = firstRow.cloneNode(true);

            // Limpiar los valores de los inputs en la nueva fila
            const inputs = cloneRow.querySelectorAll('input, select');
            inputs.forEach(input => input.value = '');

            alumnosTableBody.appendChild(cloneRow);
        });

        // Eliminar fila de alumno
        window.removeRow = function (button) {
            const row = button.closest('tr');
            if (alumnosTableBody.children.length > 1) {
                row.remove();
            } else {
                alert('Debe haber al menos un alumno.');
            }
        };

        // Inicializar Inputmask según el tipo de documento seleccionado inicialmente
        idTipoDocumento.dispatchEvent(new Event('change'));
    });
</script>

<?= $this->endSection() ?>

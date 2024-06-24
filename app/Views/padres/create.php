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
                    <form action="<?= site_url('padres/store') ?>" method="post">
                        <div class="form-group">
                            <label for="nombre_completo" style="color: #000;"><i class="fas fa-user"></i> Nombre Completo</label>
                            <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" required>
                        </div>
                        <div class="form-group">
                            <label for="dui" style="color: #000;"><i class="fas fa-id-card"></i> DUI</label>
                            <input type="text" class="form-control" id="dui" name="dui" required>
                        </div>
                        <div class="form-group">
                            <label for="telefono" style="color: #000;"><i class="fas fa-phone"></i> Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" required>
                        </div>
                        <div class="form-group">
                            <label for="genero" style="color: #000;"><i class="fas fa-venus-mars"></i> Género</label>
                            <select class="form-control" name="genero" required>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="estado" style="color: #000;"><i class="fas fa-check-circle"></i> Estado</label>
                            <select class="form-control" id="estado" name="estado" required>
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
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
                                        <!-- Aquí se agregarán dinámicamente los alumnos -->
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-primary" id="addAlumno"><i class="mdi mdi-plus"></i> Agregar Alumno</button>
                        </div>
                        <!-- Fin de campos para agregar alumnos -->
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-user-plus"></i> Agregar Padre</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Función para agregar un nuevo campo de alumno al formulario
    function addAlumno() {
        const alumnosTableBody = document.getElementById('alumnosTableBody');

        // Crear una nueva fila para el alumno
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
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
                <button type="button" class="btn btn-delete"><i class="fas fa-trash-alt"></i></button>
            </td>
        `;

        // Agregar la nueva fila a la tabla
        alumnosTableBody.appendChild(newRow);

        // Agregar evento click al botón de eliminar en la nueva fila
        const deleteButton = newRow.querySelector('.btn-delete');
        deleteButton.addEventListener('click', function() {
            // Eliminar la fila actual del alumno
            newRow.remove();
        });
    }

    // Agregar evento click al botón "Agregar Alumno"
    document.getElementById('addAlumno').addEventListener('click', addAlumno);
</script>

<?= $this->endSection() ?>

<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
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
                            <label for="estado" style="color: #000;"><i class="fas fa-check-circle"></i> Estado</label>
                            <select class="form-control" id="estado" name="estado" required>
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                        </div>
                        <!-- Campos para agregar alumnos asociados al padre -->
                        <div class="mt-4">
                            <h4 class="text-center">Alumnos Asociados</h4>
                            <div id="alumnosForm">
                                <div class="form-group">
                                <label for="alumno_nombre_completo" style="color: #000;"><i class="fas fa-user"></i>Nombre Completo</label>
                                    <input type="text" class="form-control" id="alumno_nombre_completo" name="alumno_nombre_completo[]" required>
                                </div>
                                <div class="form-group">
                                    <label for="alumno_sexo">Sexo</label>
                                    <select class="form-control" id="alumno_sexo" name="alumno_sexo[]" required>
                                        <option value="M">Masculino</option>
                                        <option value="F">Femenino</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="alumno_nie">NIE</label>
                                    <input type="text" class="form-control" id="alumno_nie" name="alumno_nie[]" required>
                                </div>
                                <div class="form-group">
                                    <label for="alumno_estado">Estado</label>
                                    <select class="form-control" id="alumno_estado" name="alumno_estado[]" required>
                                        <option value="Activo">Activo</option>
                                        <option value="Inactivo">Inactivo</option>
                                    </select>
                                </div>
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
        const alumnosForm = document.getElementById('alumnosForm');

        // Crear elementos de formulario para el nuevo alumno
        const newAlumno = document.createElement('div');
        newAlumno.classList.add('mt-4');
        newAlumno.innerHTML = `
            <div class="form-group">
                <label for="alumno_nombre_completo">Nombre Completo</label>
                <input type="text" class="form-control" name="alumno_nombre_completo[]" required>
            </div>
            <div class="form-group">
                <label for="alumno_sexo">Sexo</label>
                <select class="form-control" name="alumno_sexo[]" required>
                    <option value="M">Masculino</option>
                    <option value="F">Femenino</option>
                </select>
            </div>
            <div class="form-group">
                <label for="alumno_nie">NIE</label>
                <input type="text" class="form-control" name="alumno_nie[]" required>
            </div>
            <div class="form-group">
                <label for="alumno_estado">Estado</label>
                <select class="form-control" name="alumno_estado[]" required>
                    <option value="Activo">Activo</option>
                    <option value="Inactivo">Inactivo</option>
                </select>
            </div>
        `;

        // Agregar el nuevo campo de alumno al formulario
        alumnosForm.appendChild(newAlumno);
    }

    // Agregar evento click al botón "Agregar Alumno"
    document.getElementById('addAlumno').addEventListener('click', addAlumno);
</script>

<?= $this->endSection() ?>

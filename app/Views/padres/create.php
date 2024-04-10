<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="text-center">Agregar Nuevo Padre</h3>
                </div>
                <div class="card-body">
                    <form action="<?= site_url('padres/store') ?>" method="post">
                        <div class="form-group">
                            <label for="nombre_completo">Nombre Completo</label>
                            <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" required>
                        </div>
                        <div class="form-group">
                            <label for="dui">DUI</label>
                            <input type="text" class="form-control" id="dui" name="dui" required>
                        </div>
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" required>
                        </div>
                        <div class="form-group">
                            <label for="estado">Estado</label>
                            <select class="form-control" id="estado" name="estado" required>
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                        </div>
                        <!-- Campo oculto para capturar el idAlumno -->
                        <input type="hidden" id="idAlumno" name="idAlumno" value="">
                        <!-- Tabla para agregar, editar y eliminar alumnos -->
                        <div class="mt-4">
                            <h4 class="text-center">Alumnos Asociados</h4>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nombre Completo</th>
                                        <th>Sexo</th>
                                        <th>NIE</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Aquí se mostrarán los datos de los alumnos -->
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-success btn-sm" id="addAlumno">Agregar Alumno</button>
                        </div>
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary">Agregar Padre</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script para manejar la lógica de agregar, editar y eliminar alumnos -->
<script>
    // Arreglo para almacenar los datos de los alumnos
    let alumnos = [];

    // Función para agregar un nuevo alumno a la tabla
    function addAlumno() {
        const nombreCompleto = prompt("Ingrese el nombre completo del alumno:");
        const sexo = prompt("Ingrese el sexo del alumno (M/F):");
        const nie = prompt("Ingrese el NIE del alumno:");
        const estado = prompt("Ingrese el estado del alumno (Activo/Inactivo):");
        const idAlumno = prompt("Ingrese el ID del alumno:");

        if (nombreCompleto && sexo && nie && estado && idAlumno) {
            // Agregar el alumno al arreglo de alumnos
            alumnos.push({ nombreCompleto, sexo, nie, estado });
            // Actualizar el valor del campo oculto idAlumno
            document.getElementById("idAlumno").value = idAlumno;
            // Actualizar la tabla de alumnos
            renderAlumnos();
        } else {
            alert("Debe completar todos los campos para agregar un alumno.");
        }
    }

    // Función para renderizar los datos de los alumnos en la tabla
    function renderAlumnos() {
        const tbody = document.querySelector("tbody");
        tbody.innerHTML = "";

        alumnos.forEach((alumno, index) => {
            const row = `
                <tr>
                    <td>${alumno.nombreCompleto}</td>
                    <td>${alumno.sexo}</td>
                    <td>${alumno.nie}</td>
                    <td>${alumno.estado}</td>
                    <td>
                        <button type="button" class="btn btn-primary btn-sm" onclick="editAlumno(${index})">Editar</button>
                        <button type="button" class="btn btn-danger btn-sm" onclick="deleteAlumno(${index})">Eliminar</button>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });
    }

    // Función para editar los datos de un alumno
    function editAlumno(index) {
        const alumno = alumnos[index];
        const nombreCompleto = prompt("Ingrese el nuevo nombre completo del alumno:", alumno.nombreCompleto);
        const sexo = prompt("Ingrese el nuevo sexo del alumno (M/F):", alumno.sexo);
        const nie = prompt("Ingrese el nuevo NIE del alumno:", alumno.nie);
        const estado = prompt("Ingrese el nuevo estado del alumno (Activo/Inactivo):", alumno.estado);

        if (nombreCompleto && sexo && nie && estado) {
            // Actualizar los datos del alumno en el arreglo
            alumnos[index] = { nombreCompleto, sexo, nie, estado };
            // Actualizar la tabla de alumnos
            renderAlumnos();
        } else {
            alert("Debe completar todos los campos para editar un alumno.");
        }
    }

    // Función para eliminar un alumno del arreglo
    function deleteAlumno(index) {
        if (confirm("¿Está seguro que desea eliminar este alumno?")) {
            alumnos.splice(index, 1);
            // Actualizar la tabla de alumnos
            renderAlumnos();
        }
    }

    // Event listener para el botón de agregar alumno
    document.getElementById("addAlumno").addEventListener("click", addAlumno);
</script>
<?= $this->endSection() ?>

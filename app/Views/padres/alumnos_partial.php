<!-- Aquí muestras la información de los alumnos -->
<?php foreach ($alumnos as $alumno): ?>
    <div>
        <p>Nombre Completo: <?= $alumno['nombreCompleto'] ?></p>
        <p>Sexo: <?= $alumno['Sexo'] ?></p>
        <p>NIE: <?= $alumno['NIE'] ?></p>
        <p>Estado: <?= $alumno['estado'] ?></p>
        <!-- Agrega más información de los alumnos según sea necesario -->
    </div>
    <hr> <!-- Añade una línea horizontal para separar cada alumno -->
<?php endforeach; ?>

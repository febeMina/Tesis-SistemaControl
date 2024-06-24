<style>
    .alumnos-list {
        max-height: 400px;
        overflow-y: auto;
        padding: 10px;
    }

    .alumno-item {
        background-color: #f0f0f0;
        border-radius: 8px;
        margin-bottom: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 15px;
    }

    .alumno-info {
        margin-bottom: 10px;
        color: #000; /* Color negro para el texto */
    }

    .alumno-info p {
        margin: 5px 0;
        line-height: 1.5;
    }

    .alumno-info p strong {
        font-weight: bold;
    }
</style>

<?php if (!empty($alumnos)) : ?>
    <div class="alumnos-list">
        <?php foreach ($alumnos as $alumno) : ?>
            <div class="alumno-item">
                <div class="alumno-info">
                    <p><strong>Nombre Completo:</strong> <?= $alumno['nombreAlumno'] ?></p>
                    <p><strong>Género:</strong> <?= $alumno['Genero_alumno'] === 'M' ? 'Masculino' : 'Femenino' ?></p>
                    <p><strong>NIE:</strong> <?= $alumno['NIE'] ?></p>
                    <p><strong>Estado:</strong> <?= $alumno['estado'] ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else : ?>
    <p>No se encontraron alumnos asociados a este padre.</p>
<?php endif; ?>

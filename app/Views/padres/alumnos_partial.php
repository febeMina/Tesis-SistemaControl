<?php if (!empty($alumnos)) : ?>
    <ul>
        <?php foreach ($alumnos as $alumno) : ?>
            <li>
                <span style="color: black;">Nombre Completo:    <?= $alumno['nombreCompleto'] ?></span><br>
                <span style="color: black;">Sexo:               <?= $alumno['Sexo'] === 'M' ? 'Masculino' : 'Femenino' ?></span><br>
                <span style="color: black;">NIE:                <?= $alumno['NIE'] ?></span><br>
                <span style="color: black;">Estado:             <?= $alumno['estado'] ?></span><br>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else : ?>
    <p>No se encontraron alumnos asociados a este padre.</p>
<?php endif; ?>


<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<h1>Reporte de Saldos Docentes</h1>

<table>
    <thead>
        <tr>
            <th>Nombre Completo</th>
            <th>NIP</th>
            <th>Tipo de Permiso</th>
            <th>Cantidad Días</th>
            <th>Días Ocupados</th>
            <th>Horas Ocupadas</th>
            <th>Días Disponibles</th>
            <th>Horas Disponibles</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($saldos_docentes as $saldos_docente): ?>
            <?php foreach ($saldos_docente['detalle_saldos_permiso'] as $detalle): ?>
                <tr>
                    <td><?= $saldos_docente['nombre_completo'] ?></td>
                    <td><?= $saldos_docente['nip'] ?></td>
                    <td><?= $detalle['nombre_tipo_permiso'] ?></td>
                    <td><?= $detalle['cantidadDias'] ?></td>
                    <td><?= $detalle['dias_ocupados'] ?></td>
                    <td><?= $detalle['horas_ocupadas'] ?></td>
                    <td><?= $detalle['dias_disponibles'] ?></td>
                    <td><?= $detalle['horas_disponibles'] ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection() ?>

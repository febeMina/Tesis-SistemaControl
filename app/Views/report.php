<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<div class="container mt-3">
    <h4 class="header-title text-center">Reporte de Permisos Magisteriales</h4>

    <!-- Formulario para seleccionar el rango de fechas -->
    <form action="<?= site_url('usuario') ?>" method="get">
        <div class="row mb-3">
            <div class="col-md-3">
                <input type="date" name="fecha_inicio" class="form-control" placeholder="Fecha de inicio" required>
            </div>
            <div class="col-md-3">
                <input type="date" name="fecha_fin" class="form-control" placeholder="Fecha de fin" required>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">Generar Reporte</button>
            </div>
        </div>
    </form>

    <!-- Mostrar el reporte solo si se ha generado -->
    <?php if (isset($report) && !empty($report)): ?>
        <h4 class="header-title text-center">Reporte de Permisos Magisteriales - <?= $fecha_inicio ?> al <?= $fecha_fin ?></h4>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>NIP</th>
                        <th>Nombre del / a Docente</th>
                        <th>Fecha de Permiso</th>
                        <th>Tipo de Permiso</th>
                        <th>Días Ocupados</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($report as $row): ?>
                        <tr>
                            <td><?= $row['nip'] ?></td>
                            <td><?= $row['nombre_completo'] ?></td>
                            <td><?= $row['fecha_permiso'] ?></td>
                            <td><?= $row['tipo_permiso'] ?></td>
                            <td><?= $row['dias_ocupados'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php elseif (isset($fecha_inicio) && isset($fecha_fin)): ?>
        <div class="alert alert-warning text-center" role="alert">
            No se encontraron datos para el rango de fechas seleccionado.
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>

<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<div class="container mt-3">
    <h4 class="header-title text-center">Reporte de Permisos Magisteriales</h4>

    <!-- Formulario para seleccionar mes y año -->
    <form action="<?= site_url('permiso_magisterial/report') ?>" method="get">
        <div class="row mb-3">
            <div class="col-md-3">
                <input type="number" name="month" class="form-control" placeholder="Mes (1-12)" required>
            </div>
            <div class="col-md-3">
                <input type="number" name="year" class="form-control" placeholder="Año" required>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">Generar Reporte</button>
            </div>
        </div>
    </form>

    <!-- Mostrar el reporte solo si se ha generado -->
    <?php if (isset($report) && !empty($report)): ?>
        <h4 class="header-title text-center">Reporte de Permisos Magisteriales - <?= $month ?>/<?= $year ?></h4>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>NIP</th>
                        <th>Nombre del / a Docente</th>
                        <th>Fecha</th>
                        <th>Tipo de Permiso (Cantidad de Días)</th>
                        <th>Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($report as $row): ?>
                        <tr>
                            <td><?= $row['nip'] ?></td>
                            <td><?= $row['nombre_completo'] ?></td>
                            <td><?= $row['fecha'] ?></td>
                            <td><?= $row['tipo_permiso'] ?></td>
                            <td><?= $row['saldo'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php elseif (isset($month) && isset($year)): ?>
        <div class="alert alert-warning text-center" role="alert">
            No se encontraron datos para el mes y año seleccionados.
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>

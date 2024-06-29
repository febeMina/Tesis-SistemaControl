<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Reporte de Permisos Magisteriales</h3>
                    <h4 class="card-subtitle"><?= $report_data['nombre_maestro'] ?></h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Tipo de Permiso:</strong> <?= $report_data['tipo_permiso'] ?>
                    </div>
                    <div class="mb-3">
                        <strong>Fecha de Inicio:</strong> <?= $report_data['fecha_inicio'] ?>
                    </div>
                    <div class="mb-3">
                        <strong>Fecha de Fin:</strong> <?= $report_data['fecha_fin'] ?>
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Fecha Inicio</th>
                                <th>Fecha Fin</th>
                                <th>Días</th>
                                <th>Horas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($report_data['saldos_docentes'] as $saldo): ?>
                                <tr>
                                    <td><?= $saldo['fecha_inicio'] ?></td>
                                    <td><?= $saldo['fecha_fin'] ?></td>
                                    <td><?= $saldo['saldo_total_dias'] ?></td>
                                    <td><?= $saldo['saldo_total_horas'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

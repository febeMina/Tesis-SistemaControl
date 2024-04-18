<?= $this->extend('layouts/default') ?>

<?= $this->section('content') ?>
<div class="container mt-3">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-white" style="background-color: #090066; border-radius: 15px;">
                    <h4 class="header-title text-center">Saldo de Permiso Magisterial</h4>
                </div>
                <div class="card-body" style="background-color: #f0f0f0">
                    <!-- Tabla de saldos de permiso -->
                    <div class="table-responsive">
                        <table class="table" style="color: #000;">
                            <thead>
                                <tr>
                                    <th>Nombre del Docente</th>
                                    <th>NIP</th>
                                    <th>Escalafón</th>
                                    <th>Fecha de Ingreso</th>
                                    <th>Saldo de Días de Permiso</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($saldos_docentes as $saldo): ?>
                                    <tr>
                                        <td><?= $saldo->nombre_completo ?></td>
                                        <td><?= $saldo->nip ?></td>
                                        <td><?= $saldo->escalafon ?></td>
                                        <td><?= $saldo->fecha_ingreso ?></td>
                                        <td><?= $saldo->saldo_total_dias ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
